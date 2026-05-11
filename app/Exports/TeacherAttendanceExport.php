<?php

namespace App\Exports;

use App\Models\TeacherAttendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class TeacherAttendanceExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $filter;
    protected $startDate;
    protected $endDate;

    public function __construct($filter = 'daily', $startDate = null, $endDate = null)
    {
        $this->filter    = $filter ?? 'daily';
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function query()
    {
        $query = TeacherAttendance::with('teacher');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        } else {
            $date = now();
            switch ($this->filter) {
                case 'weekly':
                    $query->whereBetween('date', [
                        $date->startOfWeek()->format('Y-m-d'),
                        $date->endOfWeek()->format('Y-m-d')
                    ]);
                    break;
                case 'monthly':
                    $query->whereMonth('date', $date->month)->whereYear('date', $date->year);
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

    public function map($a): array
    {
        return [
            $a->teacher->nip ?? '-',
            $a->teacher->name,
            $a->teacher->jabatan ?? '-',
            $a->teacher->gender == 'L' ? 'Laki-laki' : 'Perempuan',
            $a->date,
            $a->check_in  ?? '-',
            $a->check_out ?? '-',
            $a->status     ?? '-',
            $a->keterangan ?? '-',
            $a->catatan_keterangan ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'NIP', 'Nama Guru', 'Jabatan', 'Jenis Kelamin',
            'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status', 'Keterangan', 'Catatan',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                Carbon::setLocale('id');
                $exportDate = Carbon::now()->translatedFormat('d F Y');
                $date       = now();

                if ($this->startDate && $this->endDate) {
                    $periode = "Periode: " . Carbon::parse($this->startDate)->translatedFormat('d F Y')
                             . " s/d " . Carbon::parse($this->endDate)->translatedFormat('d F Y');
                } else {
                    switch ($this->filter) {
                        case 'weekly':
                            $periode = "Periode: Minggu Ini (" . $date->startOfWeek()->translatedFormat('d F Y')
                                     . " s/d " . $date->endOfWeek()->translatedFormat('d F Y') . ")";
                            break;
                        case 'monthly':
                            $periode = "Periode: Bulan " . $date->translatedFormat('F Y');
                            break;
                        case 'yearly':
                            $periode = "Periode: Tahun " . $date->format('Y');
                            break;
                        default:
                            $periode = "Periode: " . today()->translatedFormat('d F Y');
                            break;
                    }
                }

                $event->sheet->insertNewRowBefore(1, 3);

                $event->sheet->mergeCells('A1:J1');
                $event->sheet->setCellValue('A1', 'REKAPITULASI ABSENSI GURU SDIT UMMATAN WAHIDAH');

                $event->sheet->mergeCells('A2:J2');
                $event->sheet->setCellValue('A2', 'Tanggal Ekspor: ' . $exportDate);

                $event->sheet->mergeCells('A3:J3');
                $event->sheet->setCellValue('A3', $periode);

                $styleCenter = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => ['bold' => true],
                ];

                $event->sheet->getStyle('A1:J3')->applyFromArray($styleCenter);
                $event->sheet->getStyle('A1')->getFont()->setSize(14);
            },
        ];
    }
}
