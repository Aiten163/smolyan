<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
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
        $filePath2 = $this->createExcel2();
        return view('office_program.lab3.index', compact('sotrs', 'filePath','filePath2'));
    }

    public function createExcel($sotrs)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Заголовки с цветом фона
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD']
            ]
        ];

        $sheet->setCellValue('D1', 'Отдел')->getStyle('D1')->applyFromArray($headerStyle);
        $sheet->setCellValue('E1', 'Фамилия')->getStyle('E1')->applyFromArray($headerStyle);
        $sheet->setCellValue('F1', 'Имя')->getStyle('F1')->applyFromArray($headerStyle);
        $sheet->setCellValue('G1', 'Зарплата')->getStyle('G1')->applyFromArray($headerStyle);
        $sheet->setCellValue('H1', 'Итого по отделу')->getStyle('H1')->applyFromArray($headerStyle);

        // Устанавливаем числовой формат и размер шрифта 12 для колонок G (зарплата) и H (итого)
        $sheet->getStyle('G2:H' . (count($sotrs) + 1))
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $numberStyle = [
            'font' => [
                'size' => 10
            ]
        ];
        $sheet->getStyle('G2:H' . (count($sotrs) + 1))->applyFromArray($numberStyle);

        // Цвета для колонок
        $columnColors = [
            'D' => 'DCE6F1', // Светло-голубой для отдела
            'E' => 'F2DCDB', // Светло-розовый для фамилии
            'F' => 'EBF1DE', // Светло-зеленый для имени
            'G' => 'E5E0EC', // Светло-фиолетовый для зарплаты
            'H' => 'FDEADA'  // Светло-оранжевый для итого
        ];

        foreach ($columnColors as $column => $color) {
            $sheet->getStyle($column . '2:' . $column . (count($sotrs) + 1))
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($color);
        }

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
                //$sheet->setCellValue('H'.$row, 0);
                $sum = 0;
            } else {
                //$sheet->setCellValue('H'.$row, 0);
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
    public function createExcel2()
    {
        // 1. Получаем данные из базы с сортировкой
        $employees = DB::table('sotr')
            ->select(
                'otdels.NameOtdel as department',
                'sotr.LastName',
                'sotr.FirstName',
                'sotr.Date_R',
                'sotr.Dolzn'
            )
            ->join('otdels', 'sotr.Otdel', '=', 'otdels.idOtdel')
            ->orderBy('NameOtdel')
            ->orderBy('LastName')
            ->orderBy('FirstName')
            ->get();

        // 2. Создаем Excel документ
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Устанавливаем альбомную ориентацию
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

        // Добавляем заголовок "Список сотрудников отделов"
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'Список сотрудников отделов');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '0000FF'] // Синий цвет
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        // Заголовки столбцов (синие)
        $sheet->setCellValue('A2', 'Отдел');
        $sheet->setCellValue('B2', 'Фамилия');
        $sheet->setCellValue('C2', 'Имя');
        $sheet->setCellValue('D2', 'Дата рождения');
        $sheet->setCellValue('E2', 'Должность');

        $sheet->getStyle('A2:E2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '0000FF'] // Синий цвет
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFFF'] // Белый фон
            ]
        ]);

        // Заполняем данные с чередованием цветов
        foreach ($employees as $index => $employee) {
            $row = $index + 3; // Начинаем с 3 строки (после заголовков)
            $sheet->setCellValue('A'.$row, $employee->department);
            $sheet->setCellValue('B'.$row, $employee->LastName);
            $sheet->setCellValue('C'.$row, $employee->FirstName);
            $sheet->setCellValue('D'.$row, $employee->Date_R);
            $sheet->setCellValue('E'.$row, $employee->Dolzn);

            // Чередуем цвета строк (белый/зеленый)
            $color = ($index % 2 == 0) ? 'FFFFFF' : 'CCFFCC'; // Белый или светло-зеленый
            $sheet->getStyle('A'.$row.':E'.$row)->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $color]
                ]
            ]);
        }

        // Автоподбор ширины колонок
        foreach(range('A','E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Границы для всей таблицы
        $lastRow = count($employees) + 2;
        $sheet->getStyle('A2:E'.$lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);
        $fileName = 'salaries_2018_2.xlsx';
        $publicPath = public_path($fileName);
        $sheet->getStyle('D3:D30')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $writer = new Xlsx($spreadsheet);
        $writer->save($publicPath);

        return $fileName;
    }
}
