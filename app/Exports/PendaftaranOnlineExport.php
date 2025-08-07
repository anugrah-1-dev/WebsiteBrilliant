<?php

namespace App\Exports;

use App\Models\PendaftaranProgramOnline; // 1. Ganti Model
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PendaftaranOnlineExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithDrawings
{
    protected $startDate;
    protected $endDate;
    protected $pendaftarans;
    // --- PENGATURAN TAMPILAN ---
    protected $row_height = 80;
    protected $column_H_width = 20;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;

    }

    public function collection()
    {
        return PendaftaranProgramOnline::select([
            'trx_id',
            'nama_lengkap',
            'email',
            'no_hp',
            'asal_kota',
            'program_id',
            'period_id',
            'bukti_pembayaran',
            'status',
        ])
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->get();
    }

    public function headings(): array
    {
        // 3. Headings disesuaikan untuk versi Online
        return [
            'ID Transaksi',
            'Nama Lengkap',
            'Email',
            'No HP',
            'Asal Kota',
            'Nama Program',
            'Tanggal Periode',
            'Bukti Pembayaran',
            'Status',
        ];
    }

    public function map($pendaftaran): array
    {
        // 4. Mapping disesuaikan untuk versi Online
        return [
            $pendaftaran->trx_id,
            $pendaftaran->nama_lengkap,
            $pendaftaran->email,
            $pendaftaran->no_hp,
            $pendaftaran->asal_kota,
            $pendaftaran->program ? $pendaftaran->program->nama : 'N/A',
            $pendaftaran->period ? $pendaftaran->period->date->format('d F Y') : 'N/A',
            '', // Kolom Bukti Pembayaran dikosongkan
            $pendaftaran->status,
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $columnWidthInPixels = $this->column_H_width * 7.5;

        foreach ($this->pendaftarans as $key => $pendaftaran) {
            if ($pendaftaran->bukti_pembayaran) {
                $pathToFile = public_path('storage/' . $pendaftaran->bukti_pembayaran);
                if (!file_exists($pathToFile)) {
                    continue;
                }

                $drawing = new Drawing();
                $drawing->setName('Bukti Pembayaran');
                $drawing->setDescription($pendaftaran->nama_lengkap);
                $drawing->setPath($pathToFile);
                // 5. Koordinat diubah ke kolom 'H'
                $drawing->setCoordinates('H' . ($key + 2));

                // Logika untuk posisi tengah
                list($originalWidth, $originalHeight) = getimagesize($pathToFile);
                $newHeight = $this->row_height - 10;
                $drawing->setHeight($newHeight);
                $newWidth = ($originalWidth / $originalHeight) * $newHeight;
                $drawing->setOffsetX(($columnWidthInPixels - $newWidth) / 2);
                $drawing->setOffsetY(($this->row_height - $newHeight) / 2);

                $drawings[] = $drawing;
            }
        }
        return $drawings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getDelegate()->getHighestRow();
                $highestColumn = $sheet->getDelegate()->getHighestColumn();
                $cellRange = 'A1:' . $highestColumn . $highestRow;

                $sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                for ($row = 1; $row <= $highestRow; $row++) {
                    $sheet->getDelegate()->getRowDimension($row)->setRowHeight(25);
                }

                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }

                $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
                    'font' => ['bold' => true],
                ]);
                $sheet->getDelegate()->getRowDimension(1)->setRowHeight(30);
            },
        ];
    }
}
