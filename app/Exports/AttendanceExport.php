<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Carbon\Carbon;

class AttendanceExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithStyles, WithTitle, WithEvents
{
    protected $filter;
    protected $classId;
    protected $startDate;
    protected $endDate;

    // Warna baris per status/keterangan (ARGB)
    const WARNA_STATUS = [
        'Hadir'  => ['bg' => 'FFD1FAE5', 'font' => 'FF065F46'], // hijau muda
        'Telat'  => ['bg' => 'FFFEF3C7', 'font' => 'FF92400E'], // kuning muda
        'Izin'   => ['bg' => 'FFDBEAFE', 'font' => 'FF1E40AF'], // biru muda
        'Sakit'  => ['bg' => 'FFFFEDD5', 'font' => 'FF9A3412'], // oranye muda
        'Alpa'   => ['bg' => 'FFFEE2E2', 'font' => 'FF991B1B'], // merah muda
    ];

    public function __construct($filter, $classId = null, $startDate = null, $endDate = null)
    {
        $this->filter    = $filter ?? 'daily';
        $this->classId   = $classId;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
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

        return $query->orderBy('date', 'desc');
    }

    public function map($attendance): array
    {
        if ($attendance->keterangan) {
            $statusTampil = $attendance->keterangan;
        } elseif ($attendance->status) {
            $statusTampil = $attendance->status;
        } else {
            $statusTampil = '-';
        }

        return [
            $attendance->student->nisn,
            $attendance->student->nik,
            $attendance->student->name,
            $attendance->student->schoolClass->name,
            $attendance->student->gender == 'L' ? 'Laki-laki' : 'Perempuan',
            $attendance->date,
            $attendance->check_in  ?? '-',
            $attendance->check_out ?? '-',
            $statusTampil,
            $attendance->catatan_keterangan ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'NISN', 'NIK', 'Nama Siswa', 'Kelas', 'Jenis Kelamin',
            'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status / Keterangan', 'Catatan',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        // Header akan di-style via AfterSheet (karena ada insert row di atas)
        // Styling baris data dilakukan di registerEvents setelah data diisi
        return [];
    }

    public function title(): string
    {
        return 'Rekap Absensi Siswa';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                Carbon::setLocale('id');
                $sheet      = $event->sheet->getDelegate();
                $exportDate = Carbon::now()->translatedFormat('d F Y');
                $date       = now();
                $totalCols  = 10;
                $lastCol    = Coordinate::stringFromColumnIndex($totalCols);

                // ===== PERIODE =====
                if ($this->startDate && $this->endDate) {
                    $periode = "Periode: " . Carbon::parse($this->startDate)->translatedFormat('d F Y')
                             . " s/d " . Carbon::parse($this->endDate)->translatedFormat('d F Y');
                } else {
                    switch ($this->filter) {
                        case 'weekly':
                            $periode = "Periode: Minggu Ini ("
                                     . $date->startOfWeek()->translatedFormat('d F Y')
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

                // ===== INSERT 3 BARIS HEADER DI ATAS =====
                $sheet->insertNewRowBefore(1, 3);

                // Baris 1: Judul
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->setCellValue('A1', 'REKAPITULASI ABSENSI SISWA SDIT UMMATAN WAHIDAH');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF773DCE']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Baris 2: Tanggal ekspor
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->setCellValue('A2', 'Tanggal Ekspor: ' . $exportDate);
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['argb' => 'FF5B21B6']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF5F3FF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Baris 3: Periode
                $sheet->mergeCells("A3:{$lastCol}3");
                $sheet->setCellValue('A3', $periode);
                $sheet->getStyle('A3')->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['argb' => 'FF5B21B6']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEDE9FE']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // ===== STYLE BARIS HEADER KOLOM (baris 4 setelah insert) =====
                $sheet->getStyle("A4:{$lastCol}4")->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                        'size'  => 10,
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF773DCE'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                ]);
                $sheet->getRowDimension(4)->setRowHeight(25);

                // ===== WARNA BARIS DATA PER STATUS =====
                $highestRow = $sheet->getHighestRow();
                for ($row = 5; $row <= $highestRow; $row++) {
                    $statusVal = $sheet->getCell("I{$row}")->getValue();
                    $warna     = self::WARNA_STATUS[$statusVal]
                              ?? ['bg' => 'FFFFFFFF', 'font' => 'FF374151'];

                    $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                        'fill' => [
                            'fillType'   => Fill::FILL_SOLID,
                            'startColor' => ['argb' => $warna['bg']],
                        ],
                        'font' => [
                            'color' => ['argb' => $warna['font']],
                            'size'  => 9,
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_TOP,
                        ],
                    ]);
                }

                // ===== BORDER SEMUA SEL =====
                if ($highestRow >= 4) {
                    $sheet->getStyle("A1:{$lastCol}{$highestRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color'       => ['argb' => 'FFE5E7EB'],
                            ],
                        ],
                    ]);
                }

                // Freeze setelah header kolom
                $sheet->freezePane('A5');
            },
        ];
    }
}
