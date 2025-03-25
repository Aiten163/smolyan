<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Lab5Controller extends Controller
{
    public function generateImage()
    {
        // Получаем данные из базы
        $data = DB::table('sotr')
            ->join('otdels', 'sotr.Otdel', '=', 'otdels.idOtdel')
            ->select('otdels.NameOtdel', DB::raw('COUNT(sotr.id) as count'))
            ->groupBy('sotr.Otdel', 'otdels.NameOtdel')
            ->get();

        // Подсчет общего количества сотрудников
        $total = $data->sum('count');

        // Добавляем проценты
        foreach ($data as $item) {
            $item->percent = round(($item->count / $total) * 100, 2);
        }

        // Создаем изображение
        $width = 800;
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
            imagecolorallocate($image, 255, 105, 180)  // Темно-розовый (безопасности труда)
        ];
//        $colors_down = [
//            imagecolorallocate($image, 82, 151, 82), // Светло-зеленый (управления)
//            imagecolorallocate($image, 185, 127, 135), // Светло-розовый (тех. обеспечения)
//            imagecolorallocate($image, 193, 95, 144)  // Темно-розовый (безопасности труда)
//        ];
        $colors_down = [
            imagecolorallocate($image, 144-$color, 238-$color, 144-$color), // Светло-зеленый (управления)
            imagecolorallocate($image, 255-$color, 182-$color, 193-$color), // Светло-розовый (тех. обеспечения)
            imagecolorallocate($image, 255-$color, 105-$color, 180-$color)  // Темно-розовый (безопасности труда)
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
            imagettftext($image, 12, 0, $legendX + $squareSize + 10, $legendY + 15, $black, $font, $item->NameOtdel . " - " . $item->percent . "%");
            $legendY += $step;
        }

        // Сохраняем изображение
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return 'data:image/png;base64,' . base64_encode($imageData);
    }
    public function index()
    {
        $img = self::generateImage();
        return(view('data_base.lab5.index', ['img' => $img]));
    }
}
