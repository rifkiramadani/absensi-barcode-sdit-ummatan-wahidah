<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AttendanceExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    protected $filter;
    protected $classId;
    protected $startDate;
    protected $endDate;

    public function __construct($filter, $classId = null, $startDate = null, $endDate = null)
    {
        $this->filter = $filter;
        $this->classId = $classId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = Attendance::with(['student.schoolClass']);

        // Filter Kelas
        if ($this->classId) {
            $query->whereHas('student', fn($q) => $q->where('school_class_id', $this->classId));
        }

        // Filter Rentang Tanggal Spesifik
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        } else {
            // Filter Berdasarkan Periode (Harian, Mingguan, dll)
            $date = now();
            switch ($this->filter) {
                case 'weekly':
                    $query->whereBetween('date', [$date->startOfWeek()->format('Y-m-d'), $date->endOfWeek()->format('Y-m-d')]);
                    break;
                case 'monthly':
                    $query->whereMonth('date', $date->month)->whereYear('date', $date->year);
                    break;
                case 'quarterly':
                    $query->whereBetween('date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);
                    break;
                case 'yearly':
                    $query->whereYear('date', $date->year);
                    break;
                default:
                    $query->whereDate('date', today());
                    break;
            }
        }

        return $query;
    }

    // Mapping kolom yang akan muncul di Excel
    public function map($attendance): array
    {
        return [
            $attendance->student->nisn,
            $attendance->student->name,
            $attendance->student->schoolClass->name,
            $attendance->student->gender,
            $attendance->date,
            $attendance->check_in ?? '-',
            $attendance->check_out ?? '-',
            $attendance->status,
        ];
    }

    // Header untuk file Excel
    public function headings(): array
    {
        return [
            'NISN',
            'Nama Siswa',
            'Kelas',
            'Jenis Kelamin',
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Status',
        ];
    }
}
