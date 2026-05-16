<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnWidths
{
    /**
     * Ambil data transaksi yang sudah lunas
     */
    public function collection()
    {
        return Transaksi::with(['tiket.konser', 'user'])
            ->where('status_pembayaran', 'paid')
            ->latest()
            ->get();
    }

    /**
     * Header kolom (baris pertama)
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode Transaksi',
            'Nama Pembeli',
            'Konser',
            'Kategori Tiket',
            'Jumlah',
            'Total Bayar',
            'Metode',
            'Tanggal',
        ];
    }

    /**
     * Format setiap baris data
     */
    public function map($transaksi): array
    {
        static $no = 0;
        $no++;

        $metode = match ($transaksi->metode_pembayaran) {
            'cash'        => 'Cash',
            'bank_bca'    => 'BCA',
            'bank_bri'    => 'BRI',
            'bank_mandiri'=> 'Mandiri',
            'qris'        => 'QRIS',
            default       => $transaksi->metode_pembayaran,
        };

        return [
            $no,
            $transaksi->kode_unik ?? '-',
            $transaksi->nama_pembeli,
            $transaksi->tiket->konser->nama_konser ?? '-',
            $transaksi->tiket->kategori ?? '-',
            $transaksi->jumlah,
            $transaksi->total,
            $metode,
            $transaksi->tanggal
                ? $transaksi->tanggal->setTimezone('Asia/Jakarta')->format('d/m/Y H:i')
                : '-',
        ];
    }

    /**
     * Styling sheet
     */
    public function styles(Worksheet $sheet)
    {
        $highestRow    = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // ===== STYLE HEADER =====
        $headerRange = 'A1:' . $highestColumn . '1';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold'      => true,
                'color'     => ['argb' => Color::COLOR_WHITE],
                'size'      => 12,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF7C3AED'], // Ungu TicketWave
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // ===== STYLE DATA (baris 2 sampai akhir) =====
        $dataRange = 'A2:' . $highestColumn . $highestRow;
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FFD1D5DB'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // ===== ALIGNMENT PER KOLOM =====
        // Kolom A (No) → tengah
        $sheet->getStyle('A2:A' . $highestRow)
              ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Kolom F (Jumlah) → tengah
        $sheet->getStyle('F2:F' . $highestRow)
              ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Kolom G (Total) → kanan + format currency
        $sheet->getStyle('G2:G' . $highestRow)
              ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        foreach (range(2, $highestRow) as $row) {
            $cell = $sheet->getCell('G' . $row);
            $value = $cell->getValue();
            if (is_numeric($value)) {
                $cell->setValue($value);
                $sheet->getStyle('G' . $row)
                      ->getNumberFormat()
                      ->setFormatCode('"Rp " #,##0');
            }
        }

        // ===== STRIPE (warna selang-seling) =====
        for ($row = 2; $row <= $highestRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':' . $highestColumn . $row)
                      ->getFill()
                      ->setFillType(Fill::FILL_SOLID)
                      ->setStartColor(new Color('FFF5F3FF')); // ungu sangat muda
            }
        }

        // ===== TINGGI BARIS =====
        $sheet->getRowDimension(1)->setRowHeight(30);
        foreach (range(2, $highestRow) as $row) {
            $sheet->getRowDimension($row)->setRowHeight(22);
        }

        return [];
    }

    /**
     * Lebar kolom (opsional, auto-size sudah aktif)
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,   // No
            'B' => 22,  // Kode Transaksi
            'C' => 22,  // Nama Pembeli
            'D' => 25,  // Konser
            'E' => 16,  // Kategori
            'F' => 10,  // Jumlah
            'G' => 18,  // Total
            'H' => 14,  // Metode
            'I' => 18,  // Tanggal
        ];
    }
}