<?php

namespace App\Exports;

use App\Models\PendaftaranProgramCamp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\Storage;

class PendaftaranCampExport implements FromCollection, WithHeadings, WithEvents, WithDrawings
{
    protected $pendaftar;

    public function __construct($startDate, $endDate)
    {
        // Filter by tanggal
        $this->pendaftar = PendaftaranProgramCamp::with(['programCamp', 'period'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();
    }

    public function collection()
    {
        return $this->pendaftar->map(function ($item) {
            return [
                'nama_lengkap'     => $item->nama_lengkap,
                'email'            => $item->email,
                'no_hp'            => $item->no_hp,
                'asal_kota'        => $item->asal_kota,
                'program_camp'     => $item->programCamp->nama ?? '-',
                'periode'          => $item->period && $item->period->date
                    ? $item->period->date->format('d-m-Y')
                    : 'Tidak ada',
                'durasi_paket'     => $item->durasi_paket,
                'nama_kamar'       => $item->nama_kamar,
                'bukti_pembayaran' => '', // Kosong karena gambar
                'status'           => $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Email',
            'No HP',
            'Asal Kota',
            'Program Camp',
            'Periode',
            'Durasi Paket',
            'Nama Kamar',
            'Bukti Pembayaran',
            'Status',
        ];
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
                    $sheet->getDelegate()->getRowDimension($row)->setRowHeight(60);
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

    public function drawings()
    {
        $drawings = [];
        $rowHeight = 60;
        $columnWidthInPixels = 80;

        foreach ($this->pendaftar as $index => $item) {
            if ($item->bukti_pembayaran && Storage::disk('public')->exists($item->bukti_pembayaran)) {
                $pathToFile = storage_path('app/public/' . $item->bukti_pembayaran);

                if (@getimagesize($pathToFile)) {
                    list($originalWidth, $originalHeight) = getimagesize($pathToFile);

                    $newHeight = $rowHeight - 10;
                    $newWidth = ($originalWidth / $originalHeight) * $newHeight;

                    $drawing = new Drawing();
                    $drawing->setName('Bukti');
                    $drawing->setDescription('Bukti Pembayaran');
                    $drawing->setPath($pathToFile);
                    $drawing->setHeight($newHeight);
                    $drawing->setOffsetX(($columnWidthInPixels - $newWidth));
                    $drawing->setOffsetY(($rowHeight - $newHeight));

                    $drawing->setCoordinates('I' . ($index + 2)); // Kolom I, mulai dari baris 2

                    $drawings[] = $drawing;
                }
            }
        }

        return $drawings;
    }

}
