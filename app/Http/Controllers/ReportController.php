<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        try {
            $otdels = DB::table('otdels')->get();
        } catch (\Exception $e) {
            return response('Ошибка подключения к базе данных: ' . $e->getMessage(), 500);
        }

        return view('office_program.lab8.index', ['otdels' => $otdels, 'selectedOtdel' => null]);
    }

    public function show(Request $request)
    {
        $idOtdel = $request->input('otdel');

        try {
            $otdels = DB::table('otdels')->get();
        } catch (\Exception $e) {
            return response('Ошибка подключения к базе данных: ' . $e->getMessage(), 500);
        }

        return view('office_program.lab8.index', [
            'otdels' => $otdels,
            'selectedOtdel' => $idOtdel
        ]);
    }


    public function drawDiagram(Request $request)
    {
        $idOtdel = $request->query('otdel');
        $fontPath = public_path('arial.ttf'); // путь к TTF-шрифту

        if (!file_exists($fontPath)) {
            die('Файл шрифта не найден: ' . $fontPath);
        }

        $width = 800;
        $height = 600;
        $padding = 50;

        try {
            // Получение данных за 2018 год по указанному отделу
            $monthlySums = DB::table('zarpl')
                ->join('sotr', 'zarpl.idSotr', '=', 'sotr.id')
                ->where('sotr.Otdel', $idOtdel)
                ->where('zarpl.God', 2018)
                ->select(DB::raw('Month, SUM(Money) as total'))
                ->groupBy('Month')
                ->pluck('total', 'Month')
                ->toArray();
        } catch (\Exception $e) {
            die('Ошибка запроса к базе данных: ' . $e->getMessage());
        }

        if (empty($monthlySums)) {
            return $this->drawNoDataImage();
        }

        $image = imagecreate($width, $height);
        $white = imageColorAllocate($image, 255, 255, 255);
        $black = imageColorAllocate($image, 0, 0, 0);
        $gray = imageColorAllocate($image, 200, 200, 200);

        // Массив разных цветов для столбцов
        $colors = [
            imageColorAllocate($image, 255, 0, 0),     // красный
            imageColorAllocate($image, 0, 255, 0),     // зеленый
            imageColorAllocate($image, 0, 0, 255),     // синий
            imageColorAllocate($image, 255, 255, 0),   // желтый
            imageColorAllocate($image, 255, 0, 255),   // пурпурный
            imageColorAllocate($image, 0, 255, 255),   // голубой
            imageColorAllocate($image, 128, 0, 0),     // темно-красный
            imageColorAllocate($image, 0, 128, 0),     // темно-зеленый
            imageColorAllocate($image, 0, 0, 128),     // темно-синий
            imageColorAllocate($image, 128, 128, 0),  // оливковый
            imageColorAllocate($image, 128, 0, 128),   // фиолетовый
            imageColorAllocate($image, 0, 128, 128)   // темно-голубой
        ];

        // Фон и рамка
        imagefilledrectangle($image, 0, 0, $width, $height, $white);
        imagerectangle($image, 0, 0, $width - 1, $height -1, $black);

        // Заголовок
        imagettftext($image, 16, 0, $padding, 30, $black, $fontPath, "Зарплата по отделу №$idOtdel за 2018 год");
        imagettftext($image, 12, 0, 500, 50, $black, $fontPath, "Дата создания " . now());

        // Подписи месяцев и график
        $months = ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'];
        $barWidth = 30;
        $maxValue = max($monthlySums);
        $scale = ($height - 150) / $maxValue;

        for ($i = 1; $i <= 12; $i++) {
            $x = $padding + ($i - 1) * 60;
            $y = $height - 65; // Понижено на 15 пикселей (было 50)
            $value = $monthlySums[$i] ?? 0;
            $barHeight = $value * $scale;
            $colorIndex = ($i - 1) % count($colors); // циклический выбор цвета

            // Столбик
            imagefilledrectangle($image, $x, $y - $barHeight, $x + $barWidth, $y, $colors[$colorIndex]);

            // Подпись месяца
            imagettftext($image, 10, 90, $x + 17, $y - 10, $black, $fontPath, $months[$i - 1]);

            // Цифра (сумма) с форматированием с запятой
            if ($value > 0) {
                imagettftext($image, 7, 45, $x, 575, $black, $fontPath, number_format($value, 2, ',', ' '));          }
        }

        header('Content-Type: image/jpeg');
        imagejpeg($image, null, 100);
        imagedestroy($image);
        exit;
    }


    private function drawChart($labels, $values, $width, $height, $padX, $padY, $title, $subtitle)
    {
        $img = imagecreate($width, $height);

        $white = imageColorAllocate($img, 255, 255, 255);
        $black = imageColorAllocate($img, 0, 0, 0);
        $blue = imageColorAllocate($img, 30, 144, 255);
        $gray = imageColorAllocate($img, 211, 211, 211);

        imagefilledrectangle($img, 0, 0, $width, $height, $white);
        imagerectangle($img, 0, 0, $width-1, $height-1, $black);

        $maxValue = max($values);
        $barWidth = ($width - 2 * $padX) / count($values);
        $scale = ($height - 2 * $padY) / ($maxValue ?: 1);

        for ($i = 0; $i < count($values); $i++) {
            $x1 = $padX + $i * $barWidth;
            $x2 = $x1 + $barWidth - 10;
            $y2 = $height - $padY;
            $y1 = $y2 - $values[$i] * $scale;

            imagefilledrectangle($img, $x1, $y1, $x2, $y2, $blue);
            imagerectangle($img, $x1, $y1, $x2, $y2, $black);

            imagestring($img, 3, $x1 + 3, $y2 + 5, $labels[$i], $black);
        }

        imagestring($img, 5, $width / 2 - strlen($title) * 4, 10, $title, $black);
        imagestring($img, 4, $width / 2 - strlen($subtitle) * 4, 30, $subtitle, $gray);

        header('Content-Type: image/jpeg');
        imagejpeg($img, null, 100);
        imagedestroy($img);
    }
    private function drawNoDataImage()
    {
        $width = 600;
        $height = 200;
        $img = imagecreate($width, $height);

        $white = imageColorAllocate($img, 255, 255, 255);
        $black = imageColorAllocate($img, 0, 0, 0);

        // Фон
        imagefilledrectangle($img, 0, 0, $width, $height, $white);

        // Путь к TTF-шрифту
        $fontPath = public_path('arial.ttf');
        if (!file_exists($fontPath)) {
            die('Файл шрифта не найден: ' . $fontPath);
        }

        $message = 'Нет данных за 2018 год';

        // Выводим текст по центру
        $fontSize = 16;
        $bbox = imagettfbbox($fontSize, 0, $fontPath, $message);
        $textWidth = $bbox[2] - $bbox[0];
        $x = ($width - $textWidth) / 2;
        $y = ($height / 2) + ($fontSize / 2);

        imagettftext($img, $fontSize, 0, $x, $y, $black, $fontPath, $message);

        header('Content-Type: image/jpeg');
        imagejpeg($img, null, 100);
        imagedestroy($img);
        exit;
    }


}
