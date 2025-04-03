<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SotrController extends Controller
{
    protected function bd()
    {
        return DB::table('sotr')
            ->select(
                'sotr.id',
                'otdels.NameOtdel',
                'sotr.LastName',
                'sotr.FirstName',
                DB::raw('COALESCE(SUM(zarpl.Money), 0) as TotalSalary')
            )
            ->join('otdels', 'sotr.Otdel', '=', 'otdels.idOtdel')
            ->leftJoin('zarpl', function($join) {
                $join->on('sotr.id', '=', 'zarpl.idSotr')
                    ->where('zarpl.God', '=', 2018); // Простое сравнение числа, а не whereYear()
            })
            ->groupBy('sotr.id', 'otdels.NameOtdel', 'sotr.LastName', 'sotr.FirstName')
            ->orderBy('otdels.NameOtdel')
            ->orderBy('LastName')
            ->orderBy('FirstName')
            ->get();
    }
    public function getHtml()
    {
        $sotrs = $this->bd();
        $filePath = $this->createExcel($sotrs);
        return view('office_program.lab3.index', compact('sotrs', 'filePath'));
    }

    public function createExcel($sotrs)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Заголовки
        $sheet->setCellValue('D1', 'Отдел');
        $sheet->setCellValue('E1', 'Фамилия');
        $sheet->setCellValue('F1', 'Имя');
        $sheet->setCellValue('G1', 'Зарплата');
        $sheet->setCellValue('H1', 'Итого по отделу');

        // Устанавливаем числовой формат для колонок G (зарплата) и H (итого)
        $sheet->getStyle('G2:H' . (count($sotrs) + 1))
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $sum = 0;
        $lastOtdel = $sotrs->first()->NameOtdel;
        $sheet->setCellValue('D2', $lastOtdel);
        $count = count($sotrs)-1;

        foreach ($sotrs as $i => $sotr) {
            $row = $i + 2;

            // Основные данные
            $sheet->setCellValue('E'.$row, $sotr->LastName);
            $sheet->setCellValue('F'.$row, $sotr->FirstName);

            // Явно устанавливаем числовое значение для зарплаты
            $sheet->setCellValueExplicit(
                'G'.$row,
                $sotr->TotalSalary,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC
            );

            // Логика подсчета итого по отделу
            if($lastOtdel !== $sotr->NameOtdel || $row-2 === $count) {
                if($row-2 === $count) {
                    $sheet->setCellValue('H'.$row, $sum + $sotr->TotalSalary);
                    break;
                }
                $sheet->setCellValue('D'.$row, $sotr->NameOtdel);
                $sheet->setCellValue('H'.$row-1, $sum);
                $sheet->setCellValue('H'.$row, 0);
                $sum = 0;
            } else {
                $sheet->setCellValue('H'.$row, 0);
            }

            $sum += $sotr->TotalSalary;
            $lastOtdel = $sotr->NameOtdel;
        }

        // Автоподбор ширины колонок
        foreach(range('D','H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Стили таблицы
        $lastRow = count($sotrs) + 1;
        $table = new Table('D1:H'.$lastRow, 'Table1');

        $tableStyle = new TableStyle();
        $tableStyle->setTheme(TableStyle::TABLE_STYLE_MEDIUM9);
        $tableStyle->setShowRowStripes(true);
        $table->setStyle($tableStyle);
        $sheet->addTable($table);

        // Сохраняем в публичную директорию
        $fileName = 'salaries_2018.xlsx';
        $publicPath = public_path($fileName);

        $writer = new Xlsx($spreadsheet);
        $writer->save($publicPath);

        return $fileName;
    }
}
