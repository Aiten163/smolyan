<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Eltovary; // Предполагаем, что у вас есть модель Eltovary
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class TovaryReportController extends Controller
{
    public function index()
    {
        // Получаем данные из базы, сортируем по id
        $tovary = Eltovary::orderBy('id')->get();

        // Начинаем формировать HTML
        $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>Электротовары</title>
        <meta charset="utf-8">
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }
            .table-container {
                width: 100%;
                overflow-x: auto;
            }
            table {
                border-collapse: collapse;
                width: 100%;
                margin-bottom: 20px;
            }
            th {
                background-color: #FFFF00;
                color: #000;
                font-weight: bold;
                text-align: center;
                padding: 10px;
                border: 1px solid #000;
            }
            td {
                padding: 8px;
                text-align: center;
                border: 1px solid #00FF00;
            }
            tr:nth-child(even) {
                background-color: #CCFFCC;
            }
            tr:nth-child(odd) {
                background-color: #99CC99;
            }
            .total-row {
                background-color: #FFCC99 !important;
                font-weight: bold;
            }
            .photo-cell {
                border: 1px solid #0000FF !important;
            }
            .photo-img {
                max-width: 80px;
                max-height: 60px;
            }
        </style>
    </head>
    <body>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Код товара</th>
                        <th>Наименование</th>
                        <th>Дата прихода</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Итого по товару</th>
                        <th>Фото товара</th>
                    </tr>
                </thead>
                <tbody>';

        // Заполняем таблицу данными
        $totalSum = 0;
        foreach ($tovary as $index => $tovar) {
            $total = $tovar->price * $tovar->kol_vo;
            $totalSum += $total;

            // Формируем строку с товаром
            $html .= '<tr>
            <td>' . htmlspecialchars($tovar->kod) . '</td>
            <td>' . htmlspecialchars($tovar->tname) . '</td>
            <td>' . $tovar->date_p . '</td>
            <td>' . number_format($tovar->price, 2, '.', '') . '</td>
            <td>' . $tovar->kol_vo . '</td>
            <td>' . number_format($total, 2, '.', '') . '</td>
            <td class="photo-cell">';

            // Добавляем фото, если оно есть
            if ($tovar->photo) {
                $imageData = base64_encode($tovar->photo);
                $src = 'data:image/jpeg;base64,' . $imageData;
                $html .= '<img src="' . $src . '" class="photo-img" alt="Фото товара">';
            }

            $html .= '</td></tr>';
        }

        // Добавляем итоговую строку
        $html .= '<tr class="total-row">
        <td colspan="4"></td>
        <td>Итого:</td>
        <td>' . number_format($totalSum, 2, '.', '') . '</td>
        <td></td>
    </tr>';

        // Завершаем HTML
        $html .= '</tbody>
            </table>
        </div>
    </body>
    </html>';

        return $html;
    }

    public function generateExcelReport()
    {
        // Получаем данные из базы, сортируем по id
        $tovary = Eltovary::orderBy('id')->get();

        // Создаем новый Spreadsheet объект
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Устанавливаем альбомную ориентацию
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

        // Заголовки столбцов
        $headers = [
            'Код товара',       // Ширина: 12
            'Наименование',     // Ширина: 25
            'Дата прихода',     // Ширина: 12
            'Цена',             // Ширина: 10
            'Количество',       // Ширина: 12
            'Итого по товару', // Ширина: 15
            'Фото товара'       // Ширина: 20
        ];

        // Устанавливаем заголовки
        $sheet->fromArray($headers, null, 'A1');

        // Устанавливаем фиксированные ширины столбцов
        $sheet->getColumnDimension('A')->setWidth(12);  // Код товара
        $sheet->getColumnDimension('B')->setWidth(25);  // Наименование
        $sheet->getColumnDimension('C')->setWidth(12);  // Дата прихода
        $sheet->getColumnDimension('D')->setWidth(10);  // Цена
        $sheet->getColumnDimension('E')->setWidth(12);  // Количество
        $sheet->getColumnDimension('F')->setWidth(15);  // Итого
        $sheet->getColumnDimension('G')->setWidth(20);  // Фото

        // Стиль для заголовков
        $headerStyle = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'] // Жёлтый
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        // Цвета для чередования строк
        $color1 = 'CCFFCC'; // Светло-зелёный
        $color2 = '99CC99'; // Зелёный

        // Заполняем данные
        $row = 2;
        $totalSum = 0;

        // Временная директория для изображений
        $tempDir = sys_get_temp_dir() . '/tovary_images/';
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        foreach ($tovary as $index => $tovar) {
            $total = $tovar->price * $tovar->kol_vo;
            $totalSum += $total;

            // Устанавливаем значения ячеек
            $sheet->setCellValue('A' . $row, $tovar->kod);
            $sheet->setCellValue('B' . $row, $tovar->tname);
            $sheet->setCellValue('C' . $row, $tovar->date_p);

            // Форматируем цену и итого как числа
            $sheet->setCellValue('D' . $row, $tovar->price);
            $sheet->getStyle('D' . $row)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');

            $sheet->setCellValue('E' . $row, $tovar->kol_vo);
            $sheet->setCellValue('F' . $row, $total);
            $sheet->getStyle('F' . $row)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');

            // Определяем цвет для строки (чередуем)
            $rowColor = ($index % 2 == 0) ? $color1 : $color2;

            // Стиль для строки
            $rowStyle = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $rowColor]
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '00FF00'] // Зелёные границы
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ];
            $sheet->getStyle('A'.$row.':F'.$row)->applyFromArray($rowStyle);

            // Обработка изображения (уменьшенный размер)
            if ($tovar->photo) {
                try {
                    $imagePath = $tempDir . 'photo_' . $tovar->id . '.jpg';
                    file_put_contents($imagePath, $tovar->photo);

                    if (file_exists($imagePath)) {
                        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                        $drawing->setName('Photo_' . $tovar->id);
                        $drawing->setDescription('Photo');
                        $drawing->setPath($imagePath);
                        $drawing->setCoordinates('G' . $row);
                        $drawing->setOffsetX(5);  // Центрирование
                        $drawing->setOffsetY(5);  // Центрирование
                        $drawing->setWidth(80);   // Уменьшенная ширина
                        $drawing->setHeight(60);  // Уменьшенная высота
                        $drawing->setWorksheet($sheet);

                        // Устанавливаем высоту строки
                        $sheet->getRowDimension($row)->setRowHeight(50);
                    }
                } catch (\Exception $e) {
                    \Log::error('Ошибка при обработке изображения товара ID: ' . $tovar->id, ['error' => $e->getMessage()]);
                }
            }

            // Стиль для ячейки с изображением (синие границы)
            $sheet->getStyle('G'.$row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '0000FF'] // Синий
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ]);

            $row++;
        }

        // Добавляем итоговую сумму
        $sheet->setCellValue('E' . $row, 'Итого:');
        $sheet->setCellValue('F' . $row, $totalSum);
        $sheet->getStyle('F' . $row)
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');

        // Стиль для строки итогов
        $sheet->getStyle('A'.$row.':F'.$row)->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFCC99'] // Оранжевый
            ],
            'font' => [
                'bold' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '00FF00']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);

        // Создаем writer и сохраняем в временный файл
        $writer = new Xlsx($spreadsheet);
        $fileName = 'tovary_report_' . date('Y-m-d') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);

        // Очищаем временные изображения
        array_map('unlink', glob($tempDir . '*.jpg'));
        @rmdir($tempDir);

        // Отправляем файл пользователю
        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }
}