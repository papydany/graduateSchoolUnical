<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StudentTemplateExport implements FromArray, WithColumnWidths, WithCustomStartCell, WithEvents, WithHeadings
{
    public function array(): array
    {
        return [];
    }

    public function headings(): array
    {
        return ['sn', 'surname', 'firstname', 'othername', 'registrationNumber'];
    }

    // Rows 1-3 hold faculty, department and programme; headings start on row 4
    public function startCell(): string
    {
        return 'A4';
    }

    public function columnWidths(): array
    {
        return ['A' => 16, 'B' => 25, 'C' => 25, 'D' => 25, 'E' => 25];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                foreach ([1 => 'Faculty:', 2 => 'Department:', 3 => 'Programme:'] as $row => $label) {
                    $sheet->setCellValue("A{$row}", $label);
                    $sheet->mergeCells("B{$row}:E{$row}");
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                    $sheet->getStyle("B{$row}:E{$row}")->applyFromArray([
                        'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN]],
                    ]);
                }

                $sheet->getStyle('A4:E4')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9E1F2'],
                    ],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);

                $sheet->freezePane('A5');
            },
        ];
    }
}
