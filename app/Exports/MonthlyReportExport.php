<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyReportExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    private array $monthNames = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
        4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
        10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    public function __construct(private array $report) {}

    public function array(): array
    {
        $rows = [];
        foreach ($this->report['data'] as $item) {
            $rows[] = [
                $this->monthNames[$item['month']] ?? $item['month'],
                $item['year'],
                $item['transaction_count'],
                'Rp ' . number_format($item['revenue'], 0, ',', '.'),
            ];
        }

        $rows[] = [];
        $rows[] = [
            'TOTAL', '',
            $this->report['count'],
            'Rp ' . number_format($this->report['total'], 0, ',', '.'),
        ];

        return $rows;
    }

    public function headings(): array
    {
        return ['Bulan', 'Tahun', 'Jumlah Transaksi', 'Pendapatan'];
    }

    public function title(): string
    {
        return 'Laporan Bulanan ' . $this->report['period'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}