<?php

namespace App\Exports;

use App\Models\StudentCase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class StudentCaseExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected ?string $search;
    protected ?string $kategori;
    protected ?string $classId;
    protected ?string $startDate;
    protected ?string $endDate;

    // Warna baris per kategori (ARGB)
    const WARNA_KATEGORI = [
        'Pelanggaran'           => ['bg' => 'FFFEE2E2', 'font' => 'FF991B1B'], // merah muda
        'Prestasi Akademik'     => ['bg' => 'FFF0FDF4', 'font' => 'FF166534'], // hijau muda
        'Prestasi Non-Akademik' => ['bg' => 'FFEFF6FF', 'font' => 'FF1E40AF'], // biru muda
        'Perilaku Baik'         => ['bg' => 'FFFAF5FF', 'font' => 'FF6B21A8'], // ungu muda
        'Catatan Umum'          => ['bg' => 'FFF9FAFB', 'font' => 'FF374151'], // abu
    ];

    public function __construct(
        ?string $search    = null,
        ?string $kategori  = null,
        ?string $classId   = null,
        ?string $startDate = null,
        ?string $endDate   = null
    ) {
        $this->search    = $search;
        $this->kategori  = $kategori;
        $this->classId   = $classId;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection()
    {
        $query = StudentCase::with('student.schoolClass')
                    ->orderBy('tanggal_kejadian', 'desc');

        if ($this->search) {
            $query->whereHas('student', function ($q) {
                $q->where('name', 'LIKE', "%{$this->search}%")
                  ->orWhere('nisn', 'LIKE', "%{$this->search}%");
            });
        }

        if ($this->kategori) {
            $query->where('kategori', $this->kategori);
        }

        if ($this->classId) {
            $query->whereHas('student', fn($q) => $q->where('school_class_id', $this->classId));
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_kejadian', [$this->startDate, $this->endDate]);
        } elseif ($this->startDate) {
            $query->where('tanggal_kejadian', '>=', $this->startDate);
        } elseif ($this->endDate) {
            $query->where('tanggal_kejadian', '<=', $this->endDate);
        }

        return $query->get()->map(function ($case) {
            return [
                'no'               => '',  // diisi di styles
                'nama_siswa'       => $case->student->name,
                'nisn'             => $case->student->nisn,
                'kelas'            => $case->student->schoolClass->name,
                'tanggal'          => \Carbon\Carbon::parse($case->tanggal_kejadian)->format('d/m/Y'),
                'kategori'         => $case->kategori,
                'judul'            => $case->judul,
                'deskripsi'        => $case->deskripsi,
                'tindak_lanjut'    => $case->tindak_lanjut ?? '-',
                'dicatat_oleh'     => $case->dicatat_oleh,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'NISN',
            'Kelas',
            'Tanggal Kejadian',
            'Kategori',
            'Judul Catatan',
            'Deskripsi',
            'Tindak Lanjut',
            'Dicatat Oleh',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $totalCols = 10;
        $lastCol   = Coordinate::stringFromColumnIndex($totalCols);

        // ===== HEADER =====
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 10,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF773DCE'], // ungu SDIT
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // ===== WARNA BARIS PER KATEGORI + NOMOR URUT =====
        $query = StudentCase::with('student.schoolClass')
                    ->orderBy('tanggal_kejadian', 'desc');

        if ($this->search) {
            $query->whereHas('student', function ($q) {
                $q->where('name', 'LIKE', "%{$this->search}%")
                  ->orWhere('nisn', 'LIKE', "%{$this->search}%");
            });
        }
        if ($this->kategori) $query->where('kategori', $this->kategori);
        if ($this->classId)  $query->whereHas('student', fn($q) => $q->where('school_class_id', $this->classId));
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_kejadian', [$this->startDate, $this->endDate]);
        } elseif ($this->startDate) {
            $query->where('tanggal_kejadian', '>=', $this->startDate);
        } elseif ($this->endDate) {
            $query->where('tanggal_kejadian', '<=', $this->endDate);
        }

        $cases = $query->get();
        $row   = 2;

        foreach ($cases as $i => $case) {
            // Nomor urut
            $sheet->setCellValue("A{$row}", $i + 1);

            // Warna per kategori
            $warna = self::WARNA_KATEGORI[$case->kategori]
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
                    'wrapText' => true,
                ],
            ]);

            // Kolom A (No) center
            $sheet->getStyle("A{$row}")->getAlignment()
                  ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        // ===== BORDER SEMUA SEL =====
        $lastRow = max(2, $row - 1);
        $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FFE5E7EB'],
                ],
            ],
        ]);

        // ===== LEBAR KOLOM MANUAL untuk kolom teks panjang =====
        $sheet->getColumnDimension('H')->setWidth(50); // Deskripsi
        $sheet->getColumnDimension('I')->setWidth(35); // Tindak Lanjut

        // Freeze header
        $sheet->freezePane('A2');

        return [];
    }

    public function title(): string
    {
        return 'Buku Catatan Siswa';
    }
}
