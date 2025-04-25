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
use Amenadiel\JpGraph\Graph\PieGraph;
use Amenadiel\JpGraph\Plot\PiePlot;

use CpChart\Chart\Pie;
use CpChart\Data;
use CpChart\Image;



class Lab5Controller extends Controller
{
    public function generateImage()
    {
        // Получаем данные из базы
        $data = DB::table('sotr')
            ->join('otdels', 'sotr.Otdel', '=', 'otdels.idOtdel')
            ->join('zarpl', 'sotr.id', '=', 'zarpl.idSotr')
            ->select(
                'otdels.NameOtdel',
                'otdels.idOtdel',
                DB::raw('COUNT(DISTINCT sotr.id) as employee_count'), // Уникальные сотрудники
                DB::raw('SUM(zarpl.Money) as total_salary'), // Сумма всех выплат
                'zarpl.God as year'
            )
            ->groupBy('zarpl.God', 'otdels.idOtdel')
            ->orderBy('year', 'asc')
            ->orderBy('otdels.idOtdel', 'asc')
            ->get();
        //dd($data);
        // Подсчет общего количества сотрудников
        $total = $data->sum('employee_count');

        // Добавляем процентыx`
        foreach ($data as $item) {
            $item->percent = round(($item->employee_count / $total) * 100, 2);
            echo  '       '.$item->total_salary . ' ' . $item->employee_count;
            $item->sumMoney = round($item->total_salary / $item->employee_count);
        }
        // Создаем изображение
        $width = 1000;
        $height = 400;
        $image = imagecreatetruecolor($width, $height);
        $color = 40;
        // Цвета
        $background = imagecolorallocate($image, 255, 235, 205); // Бежевый фон
        $black = imagecolorallocate($image, 0, 0, 0);
        $shadow = imagecolorallocate($image, 200, 200, 200); // Серый (тень)

        // Основные цвета
        $colors = [
            imagecolorallocate($image, 144, 238, 144), // Светло-зеленый (управления)
            imagecolorallocate($image, 255, 182, 193), // Светло-розовый (тех. обеспечения)
            imagecolorallocate($image, 255, 105, 180),  // Темно-розовый (безопасности труда)
            imagecolorallocate($image, 101-$color, 214-$color, 145-$color)
        ];
        $colors_down = [
            imagecolorallocate($image, 144-$color, 238-$color, 144-$color), // Светло-зеленый (управления)
            imagecolorallocate($image, 255-$color, 182-$color, 193-$color), // Светло-розовый (тех. обеспечения)
            imagecolorallocate($image, 255-$color, 105-$color, 180-$color),  // Темно-розовый (безопасности труда)
            imagecolorallocate($image, 101-$color, 214-$color, 145-$color),  // Темно-розовый (безопасности труда)
        ];

        // Заполняем фон
        imagefill($image, 0, 0, $background);

        // Параметры диаграммы
        $centerX = 180;
        $centerY = 200;
        $radiusX = 100;
        $radiusY = 60;
        $up = 80; // вверх
        $depth = 100; // Толщина цилиндра
        $startAngle = 0;
        $color = 40;

        // Рисуем "основание" цилиндра (тень)
        imagefilledellipse($image, $centerX, $centerY + $depth, $radiusX * 2, $radiusY * 2, $shadow);

        // Рисуем 3D-эффект (боковые части цилиндра)
        foreach ($data as $key => $item) {
            $endAngle = $startAngle + (3.6 * $item->percent);
            imagefilledarc($image, $centerX, $centerY + $depth, $radiusX * 2, $radiusY * 2,
                $startAngle, $endAngle, $shadow, IMG_ARC_PIE);
            $startAngle = $endAngle;
        }

        for($i = 0; $i < $up; $i++) {
            $startAngle = 0;
            foreach ($data as $key => $item) {
                $endAngle = $startAngle + (3.6 * $item->percent);
                imagefilledarc($image, $centerX, $centerY-$i, $radiusX * 2, $radiusY * 2,
                    $startAngle, $endAngle, $colors_down[$key], IMG_ARC_PIE);
                $startAngle = $endAngle;
            }
        }
        for($i = $up; $i < $depth; $i++) {
            $startAngle = 0;
            foreach ($data as $key => $item) {
                $endAngle = $startAngle + (3.6 * $item->percent);
                imagefilledarc($image, $centerX, $centerY-$i, $radiusX * 2, $radiusY * 2,
                    $startAngle, $endAngle, $colors[$key], IMG_ARC_PIE);
                $startAngle = $endAngle;
            }
        }

        // Заголовок
        $font = '/arial.ttf'; // Убедитесь, что шрифт доступен
        imagettftext($image, 14, 0, 300, 40, $black, $font, "Диаграмма численности сотрудников отделов");

        // Легенда
        $legendX = 300;
        $legendY = 100;
        $squareSize = 20;
        $step = 40;

        foreach ($data as $key => $item) {
            imagefilledrectangle($image, $legendX, $legendY, $legendX + $squareSize, $legendY + $squareSize, $colors[$key]);
            imagerectangle($image, $legendX, $legendY, $legendX + $squareSize, $legendY + $squareSize, $black);
            imagettftext($image, 12, 0, $legendX + $squareSize + 10, $legendY + 15, $black, $font,   $item->year. ' ' .$item->NameOtdel . " - " . $item->percent . "%" . ' Средняя з/п ' . $item->sumMoney . ' руб');
            $legendY += $step;
        }

        // Сохраняем изображение
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return 'data:image/png;base64,' . base64_encode($imageData);
    }
    public function generateImage2()
    {
        // Получаем данные из базы
        $dbData = DB::table('sotr')
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

        // Подготавливаем данные для графика
        $chartData = new pData();
        $chartData->addPoints([40, 30, 20], "ScoreA");
        $chartData->setSerieDescription("ScoreA", "Application A");
        $chartData->addPoints(["A", "B", "C"], "Labels");
        $chartData->setAbscissa("Labels");

        // Создаем изображение
        $image = new pImage(700, 230, $chartData, true);

        // Настройка фона
        $image->drawFilledRectangle(0, 0, 700, 230, [
            "R" => 173, "G" => 152, "B" => 217,
            "Dash" => 1, "DashR" => 193, "DashG" => 172, "DashB" => 237
        ]);

        // Градиент
        $image->drawGradientArea(0, 0, 700, 230, DIRECTION_VERTICAL, [
            "StartR" => 209, "StartG" => 150, "StartB" => 231,
            "EndR" => 111, "EndG" => 3, "EndB" => 138,
            "Alpha" => 50
        ]);

        // Заголовок
        $image->drawGradientArea(0, 0, 700, 20, DIRECTION_VERTICAL, [
            "StartR" => 0, "StartG" => 0, "StartB" => 0,
            "EndR" => 50, "EndG" => 50, "EndB" => 50,
            "Alpha" => 100
        ]);

        // Рамка
        $image->drawRectangle(0, 0, 699, 229, ["R" => 0, "G" => 0, "B" => 0]);

        // Текст заголовка
        $image->setFontProperties([
            "FontName" => resource_path('fonts/Silkscreen.ttf'),
            "FontSize" => 6
        ]);
        $image->drawText(10, 13, "pPie - Draw 3D pie charts", [
            "R" => 255, "G" => 255, "B" => 255
        ]);

        // Основные настройки шрифта
        $image->setFontProperties([
            "FontName" => resource_path('fonts/Forgotte.ttf'),
            "FontSize" => 10,
            "R" => 80, "G" => 80, "B" => 80
        ]);

        // Создаем круговую диаграмму
        $pieChart = new pPie($image, $chartData);

        // Цвета секторов
        $pieChart->setSliceColor(0, ["R" => 143, "G" => 197, "B" => 0]);
        $pieChart->setSliceColor(1, ["R" => 97, "G" => 77, "B" => 63]);
        $pieChart->setSliceColor(2, ["R" => 97, "G" => 113, "B" => 63]);

        // Рисуем диаграмму
        $pieChart->draw3DPie(340, 125, [
            "DrawLabels" => true,
            "Border" => true
        ]);

        // Включаем тень
        $image->setShadow(true, [
            "X" => 3, "Y" => 3,
            "R" => 0, "G" => 0, "B" => 0,
            "Alpha" => 10
        ]);

        // Сохраняем изображение в буфер
        ob_start();
        $image->render(null); // Не выводим сразу
        $imageData = ob_get_clean();

        // Возвращаем base64
        return response()->json([
            'image' => 'data:image/png;base64,' . base64_encode($imageData)
        ]);
    }

public function index()
{
    $img = self::generateImage(); // Ваш оригинальный график
    $imagePath = public_path('/img2.png');
    $imageData = file_get_contents($imagePath);
    $base64 = base64_encode($imageData);
    $img2 = 'data:image/jpeg;base64,' . $base64; // Готовый base64-код для вставки в HTML


    return view('data_base.lab5.index', [
        'img' => '',
        'img2' => $img2
    ]);
}
}



