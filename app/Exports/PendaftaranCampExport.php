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

    public function __construct()
    {
        // Load pendaftar lengkap dengan relasi
        $this->pendaftar = PendaftaranProgramCamp::with(['programCamp', 'period'])->get();
    }

    public function collection()
    {
        // Mapping data utama untuk Excel
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
                'bukti_pembayaran' => '', 
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

                // Apply style (border + align)
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

                // Set height for all rows
                for ($row = 1; $row <= $highestRow; $row++) {
                    $sheet->getDelegate()->getRowDimension($row)->setRowHeight(60);
                }

                // Auto width for all columns
                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }

                // Bold header
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
        $row_height = 60; // Sesuai row height di AfterSheet
        $columnWidthInPixels = 80; // Estimasi lebar kolom Excel dalam pixel (sesuaikan jika perlu)
    
        foreach ($this->pendaftar as $index => $item) {
            if ($item->bukti_pembayaran && Storage::disk('public')->exists($item->bukti_pembayaran)) {
                $pathToFile = storage_path('app/public/' . $item->bukti_pembayaran);
    
                // Pastikan file adalah gambar
                if (@getimagesize($pathToFile)) {
                    list($originalWidth, $originalHeight) = getimagesize($pathToFile);
    
                    $newHeight = $row_height - 10;
                    $newWidth = ($originalWidth / $originalHeight) * $newHeight;
    
                    $drawing = new Drawing();
                    $drawing->setName('Bukti');
                    $drawing->setDescription('Bukti Pembayaran');
                    $drawing->setPath($pathToFile);
                    $drawing->setHeight($newHeight);
                    $drawing->setOffsetX(($columnWidthInPixels - $newWidth) / 1);
                    $drawing->setOffsetY(($row_height - $newHeight) / 1);
    
                    // Kolom I (ke-9), dan baris dimulai dari 2 (karena header di baris 1)
                    $drawing->setCoordinates('I' . ($index + 2));
    
                    $drawings[] = $drawing;
                }
            }
        }
    
        return $drawings;
    }
    
}
