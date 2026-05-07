<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Carbon\Carbon;

class AttendanceExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithEvents
{
    protected $filter;
    protected $classId;
    protected $startDate;
    protected $endDate;

    public function __construct($filter, $classId = null, $startDate = null, $endDate = null)
    {
        // Pastikan filter memiliki default jika null agar teks periode tidak kosong
        $this->filter = $filter ?? 'daily';
        $this->classId = $classId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = Attendance::with(['student.schoolClass']);

        if ($this->classId) {
            $query->whereHas('student', fn($q) => $q->where('school_class_id', $this->classId));
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        } else {
            $date = now();
            switch ($this->filter) {
                case 'weekly': $query->whereBetween('date', [$date->startOfWeek()->format('Y-m-d'), $date->endOfWeek()->format('Y-m-d')]); break;
                case 'monthly': $query->whereMonth('date', $date->month)->whereYear('date', $date->year); break;
                case 'yearly': $query->whereYear('date', $date->year); break;
                default: $query->whereDate('date', today()); break;
            }
        }

        return $query;
    }

    public function map($attendance): array
    {
        return [
            $attendance->student->nisn,
            $attendance->student->nik,
            $attendance->student->name,
            $attendance->student->schoolClass->name,
            $attendance->student->gender == 'L' ? 'Laki-laki' : 'Perempuan',
            $attendance->date,
            $attendance->check_in ?? '-',
            $attendance->check_out ?? '-',
            $attendance->status,
        ];
    }

    public function headings(): array
    {
        return [
            'NISN', 'NIK', 'Nama Siswa', 'Kelas', 'Jenis Kelamin', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Set locale Indonesia agar nama bulan muncul
                config(['app.locale' => 'id']);
                Carbon::setLocale('id');

                $exportDate = Carbon::now()->translatedFormat('d F Y');

                // Logika Teks Periode agar tidak kosong
                if ($this->startDate && $this->endDate) {
                    $dateRange = "Periode Data: " . Carbon::parse($this->startDate)->translatedFormat('d F Y') . " s/d " . Carbon::parse($this->endDate)->translatedFormat('d F Y');
                } else {
                    $filterLabel = [
                        'daily' => 'Harian (Hari Ini)',
                        'weekly' => 'Mingguan (Minggu Ini)',
                        'monthly' => 'Bulanan (Bulan Ini)',
                        'yearly' => 'Tahunan (Tahun Ini)'
                    ];
                    $dateRange = "Periode Data: " . ($filterLabel[$this->filter] ?? ucfirst($this->filter));
                }

                $event->sheet->insertNewRowBefore(1, 3);

                // Baris 1: Judul Utama
                $event->sheet->mergeCells('A1:I1');
                $event->sheet->setCellValue('A1', 'REKAPITULASI ABSENSI SISWA SDIT UMMATAN WAHIDAH');

                // Baris 2: Tanggal Ekspor
                $event->sheet->mergeCells('A2:I2');
                $event->sheet->setCellValue('A2', 'Tanggal Ekspor: ' . $exportDate);

                // Baris 3: Periode Data
                $event->sheet->mergeCells('A3:I3');
                $event->sheet->setCellValue('A3', $dateRange);

                // Styling Judul agar benar-benar ke tengah
                $styleCenter = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ];

                $event->sheet->getStyle('A1:I3')->applyFromArray($styleCenter);
                $event->sheet->getStyle('A1')->getFont()->setSize(14);

                // Paksa NISN & NIK jadi String (Menghilangkan +15 dan Kutip)
                $highestRow = $event->sheet->getHighestRow();
                for ($row = 4; $row <= $highestRow; $row++) {
                    $event->sheet->getCell('A' . $row)->setDataType(DataType::TYPE_STRING);
                    $event->sheet->getCell('B' . $row)->setDataType(DataType::TYPE_STRING);
                }

                // Warna Header Tabel
                $event->sheet->getStyle('A4:I4')->applyFromArray($styleCenter);
                $event->sheet->getStyle('A4:I4')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('F3E8FF');
            },
        ];
    }
}
