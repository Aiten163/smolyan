<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Lab5Controller extends Controller
{
    // Функция генерации изображения
    public function generateImage()
    {
        // Получаем данные из базы
        $data = DB::table('sotr')
            ->join('otdels', 'sotr.Otdel', '=', 'otdels.idOtdel')
            ->join('zarpl', 'sotr.id', '=', 'zarpl.idSotr')
            ->select(
                'otdels.NameOtdel',
                'otdels.idOtdel',
                DB::raw('COUNT(DISTINCT sotr.id) as employee_count'),
                DB::raw('SUM(zarpl.Money) as total_salary'),
                'zarpl.God as year'
            )
            ->groupBy('zarpl.God', 'otdels.idOtdel')
            ->orderBy('year', 'asc')
            ->orderBy('otdels.idOtdel', 'asc')
            ->get();

        $total = $data->sum('employee_count');

        // Добавляем проценты
        foreach ($data as $item) {
            $item->percent = round(($item->employee_count / $total) * 100, 2);
            $item->sumMoney = round($item->total_salary / $item->employee_count);
        }

        // Создаем изображение
        $width = 1000;
        $height = 400;
        $image = imagecreatetruecolor($width, $height);
        $color = 40;

        // Цвета
        $background = imagecolorallocate($image, 255, 235, 205);
        $black = imagecolorallocate($image, 0, 0, 0);
        $shadow = imagecolorallocate($image, 200, 200, 200);

        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        $r1 = rand(0, 255);
        $g1 = rand(0, 255);
        $b1 = rand(0, 255);
        $r2 = rand(0, 255);
        $g2 = rand(0, 255);
        $b2 = rand(0, 255);
        $r3 = rand(0, 255);
        $g3 = rand(0, 255);
        $b3 = rand(0, 255);

        // Основные цвета
        $colors = [
            imagecolorallocate($image, $r, $g, $b),
            imagecolorallocate($image, $r1, $g1, $r1),
            imagecolorallocate($image, $r2, $g2, $r2),
            imagecolorallocate($image, $r3, $g3, $r3),
        ];
        $colors_down = [
            imagecolorallocate($image, abs($r -$color), abs($g -$color), abs($b -$color)),
            imagecolorallocate($image, abs($r1-$color), abs($g1-$color), abs($r1-$color)),
            imagecolorallocate($image, abs($r2-$color), abs($g2-$color), abs($r2-$color)),
            imagecolorallocate($image, abs($r3-$color), abs($g3-$color), abs($r3-$color)),
        ];

        // Заполняем фон
        imagefill($image, 0, 0, $background);

        // Параметры диаграммы
        $centerX = 180;
        $centerY = 200;
        $radiusX = 100;
        $radiusY = 60;
        $up = 80;
        $depth = 100;
        $startAngle = 0;

        // Рисуем "основание" цилиндра (тень)
        imagefilledellipse($image, $centerX, $centerY + $depth, $radiusX * 2, $radiusY * 2, $shadow);

        // Рисуем 3D-эффект (боковые части цилиндра)
        foreach ($data as $key => $item) {
            $endAngle = $startAngle + (3.6 * $item->percent);
            imagefilledarc($image, $centerX, $centerY + $depth, $radiusX * 2, $radiusY * 2, $startAngle, $endAngle, $shadow, IMG_ARC_PIE);
            $startAngle = $endAngle;
        }

        for ($i = 0; $i < $up; $i++) {
            $startAngle = 0;
            foreach ($data as $key => $item) {
                $endAngle = $startAngle + (3.6 * $item->percent);
                imagefilledarc($image, $centerX, $centerY - $i, $radiusX * 2, $radiusY * 2, $startAngle, $endAngle, $colors_down[$key], IMG_ARC_PIE);
                $startAngle = $endAngle;
            }
        }

        for ($i = $up; $i < $depth; $i++) {
            $startAngle = 0;
            foreach ($data as $key => $item) {
                $endAngle = $startAngle + (3.6 * $item->percent);
                imagefilledarc($image, $centerX, $centerY - $i, $radiusX * 2, $radiusY * 2, $startAngle, $endAngle, $colors[$key], IMG_ARC_PIE);
                $startAngle = $endAngle;
            }
        }

        // Заголовок
        $font = '/arial.ttf';
        imagettftext($image, 14, 0, 300, 40, $black, $font, "Диаграмма численности сотрудников отделов");

        // Легенда
        $legendX = 300;
        $legendY = 100;
        $squareSize = 20;
        $step = 40;

        foreach ($data as $key => $item) {
            imagefilledrectangle($image, $legendX, $legendY, $legendX + $squareSize, $legendY + $squareSize, $colors[$key]);
            imagerectangle($image, $legendX, $legendY, $legendX + $squareSize, $legendY + $squareSize, $black);
            imagettftext($image, 12, 0, $legendX + $squareSize + 10, $legendY + 15, $black, $font, $item->year . ' ' . $item->NameOtdel . " - " . $item->percent . "%" . ' Средняя з/п ' . $item->sumMoney . ' руб');
            $legendY += $step;
        }

        ob_start(); // Буферизация вывода
        imagepng($image); // Отправляем PNG в буфер
        $imageData = ob_get_clean(); // Получаем данные из буфера
        imagedestroy($image); // Очищаем ресурс изображения

        $base = 'data:image/png;base64,' . base64_encode($imageData);
        return $base;
    }
}
