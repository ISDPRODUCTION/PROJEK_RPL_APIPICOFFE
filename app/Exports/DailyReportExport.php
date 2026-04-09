<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyReportExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    public function __construct(private array $report) {}

    public function array(): array
    {
        $rows = [];
        foreach ($this->report['data'] as $item) {
            $rows[] = [
                $item['date'],
                $item['transaction_count'],
                'Rp ' . number_format($item['revenue'], 0, ',', '.'),
            ];
        }

        // Total row
        $rows[] = [];
        $rows[] = [
            'TOTAL',
            $this->report['count'],
            'Rp ' . number_format($this->report['total'], 0, ',', '.'),
        ];

        return $rows;
    }

    public function headings(): array
    {
        return ['Tanggal', 'Jumlah Transaksi', 'Pendapatan'];
    }

    public function title(): string
    {
        return 'Laporan Harian ' . $this->report['period'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}