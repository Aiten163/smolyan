<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class VegetableController extends Controller
{
    public function exportDeliveries()
    {
        $spreadsheet = new Spreadsheet();
        $this->createDeliveriesSheet($spreadsheet);
        $this->createSummarySheet($spreadsheet);

        $fileName = 'vegetables_fruits_delivery_2021.xlsx';
        $tempPath = storage_path('app/public/' . $fileName);

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    private function createDeliveriesSheet(Spreadsheet $spreadsheet): void
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Поставка_овощи_фрукты_2021_год');
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)->setFitToWidth(1);

        // Заголовок
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'Поставка овощей и фруктов за 2021 год');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Заголовки
        $headers = ['Категория', 'Подкатегория', 'Наименование', 'Сумма, руб', 'Итого подкатегории'];
        $sheet->fromArray($headers, null, 'A2');
        $sheet->getStyle('A2:E2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '0000FF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCFFCC']],
        ]);

        $deliveries = DB::table('product_deliveries as pd')
            ->select([
                'c.CategName as category',
                'sc.SubCategName as subcategory',
                'pn.ProductName as product_name',
                DB::raw('SUM(pd.Price * pd.Quantity) as total_sum'),
            ])
            ->join('product_names as pn', function ($join) {
                $join->on('pd.CategId', '=', 'pn.CategId')
                    ->on('pd.SubCategId', '=', 'pn.SubCategId')
                    ->on('pd.ProductId', '=', 'pn.ProductId');
            })
            ->join('subcategory as sc', function ($join) {
                $join->on('pn.CategId', '=', 'sc.CategId')
                    ->on('pn.SubCategId', '=', 'sc.SubCategId');
            })
            ->join('category as c', 'pn.CategId', '=', 'c.CategId')
            ->whereYear('pd.DeliveryDate', 2021)
            ->groupBy('c.CategName', 'sc.SubCategName', 'pn.ProductName')
            ->orderBy('c.CategName')
            ->orderBy('sc.SubCategName')
            ->orderBy('pn.ProductName')
            ->get();

        $row = 3;
        $currentSubcategory = null;
        $subcategoryTotal = 0;

        foreach ($deliveries as $index => $delivery) {
            $isNewSubcategory = $delivery->subcategory !== $currentSubcategory;

            if ($currentSubcategory !== null && $isNewSubcategory) {
                // Вставляем итог по предыдущей подкатегории
                $sheet->setCellValue("D{$row}", 'ИТОГО по подкатегории');
                $sheet->setCellValueExplicit("E{$row}", $subcategoryTotal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle("D{$row}:E{$row}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFEB99']],
                ]);
                $row++;
                $subcategoryTotal = 0;
            }

            // Строка с продуктом
            $sheet->setCellValue("A{$row}", $isNewSubcategory ? $delivery->category : '');
            $sheet->setCellValue("B{$row}", $isNewSubcategory ? $delivery->subcategory : '');
            $sheet->setCellValue("C{$row}", $delivery->product_name);
            $sheet->setCellValueExplicit("D{$row}", $delivery->total_sum, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->getStyle("D{$row}")->getNumberFormat()->setFormatCode('#,##0.00');

            // Окраска строк
            $color = ($row % 2 == 1) ? 'FFFFFF' : 'E6FFE6';
            $sheet->getStyle("A{$row}:E{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($color);

            $subcategoryTotal += $delivery->total_sum;
            $currentSubcategory = $delivery->subcategory;
            $row++;
        }

        // Итог последней подкатегории
        if ($subcategoryTotal > 0) {
            $sheet->setCellValue("D{$row}", 'ИТОГО по подкатегории');
            $sheet->setCellValueExplicit("E{$row}", $subcategoryTotal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("D{$row}:E{$row}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFEB99']],
            ]);
            $row++;
        }
        $totalSum = $deliveries->sum('total_sum');
        $sheet->setCellValue("D{$row}", 'ОБЩИЙ ИТОГ');
        $sheet->setCellValueExplicit("E{$row}", $totalSum, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle("D{$row}:E{$row}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4CAF50']],
        ]);
        $row++;
        $this->applyCommonStyles($sheet, 'A', 'E', $row - 1);
    }

    private function createSummarySheet(Spreadsheet $spreadsheet): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Сводка за 2021 год');

        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'Сводка поставок за 2021 год');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $headers = ['Категория', 'Подкатегория', 'Сумма, руб'];
        $sheet->fromArray($headers, null, 'A2');
        $sheet->getStyle('A2:C2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '0000FF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCFFCC']],
        ]);

        $summaryData = DB::table('product_deliveries as pd')
            ->select([
                'c.CategName as category',
                'sc.SubCategName as subcategory',
                DB::raw('SUM(pd.Price * pd.Quantity) as total_sum'),
            ])
            ->join('product_names as pn', function ($join) {
                $join->on('pd.CategId', '=', 'pn.CategId')
                    ->on('pd.SubCategId', '=', 'pn.SubCategId')
                    ->on('pd.ProductId', '=', 'pn.ProductId');
            })
            ->join('subcategory as sc', function ($join) {
                $join->on('pn.CategId', '=', 'sc.CategId')
                    ->on('pn.SubCategId', '=', 'sc.SubCategId');
            })
            ->join('category as c', 'sc.CategId', '=', 'c.CategId')
            ->whereYear('pd.DeliveryDate', 2021)
            ->groupBy('c.CategName', 'sc.SubCategName')
            ->orderBy('c.CategName')
            ->orderBy('sc.SubCategName')
            ->get();

        $row = 3;
        $totalSum = 0;

        foreach ($summaryData as $data) {
            $sheet->setCellValue("A{$row}", $data->category);
            $sheet->setCellValue("B{$row}", $data->subcategory);
            $sheet->setCellValueExplicit("C{$row}", $data->total_sum, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode('#,##0.00');

            $color = ($row % 2 == 1) ? 'FFFFFF' : 'E6FFE6';
            $sheet->getStyle("A{$row}:C{$row}")
                ->getFill()->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB($color);

            $totalSum += $data->total_sum;
            $row++;
        }

        // Итоговая строка
        $sheet->setCellValue("A{$row}", 'ИТОГО');
        $sheet->mergeCells("A{$row}:B{$row}");
        $sheet->setCellValueExplicit("C{$row}", $totalSum, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle("A{$row}:C{$row}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFCC99']],
        ]);

        $this->applyCommonStyles($sheet, 'A', 'C', $row);
    }

    private function applyCommonStyles($sheet, string $firstCol, string $lastCol, int $lastRow): void
    {
        foreach (range($firstCol, $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle("{$firstCol}2:{$lastCol}{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle("{$lastCol}3:{$lastCol}{$lastRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    }
}
