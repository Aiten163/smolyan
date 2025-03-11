<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ImageGenerateController extends Controller
{
    public function generateImage(Request $request)
    {
        // Получаем значение контрастности из GET-запроса
        $contrast = $request->query('contrast', 0);

        // Загружаем изображение
        $filename = public_path('lab2.jpg'); // Убедитесь, что изображение находится в папке public
        $info = getimagesize($filename);
        $width = $info[0];
        $height = $info[1];
        $type = $info[2];

        switch ($type) {
            case 1:
                $img = imageCreateFromGif($filename);
                imageSaveAlpha($img, true);
                break;
            case 2:
                $img = imageCreateFromJpeg($filename);
                break;
            case 3:
                $img = imageCreateFromPng($filename);
                imageSaveAlpha($img, true);
                break;
            default:
                abort(400, "Неподдерживаемый тип изображения");
        }

        // Добавляем текст с текущим значением контрастности
        $font_file = public_path('arial.ttf'); // Убедитесь, что шрифт находится в папке public
        $ColorLines = imagecolorallocate($img, 0, 0, 0);
        $ColorFill = imagecolorallocate($img, 250, 250, 160);
        $color_text = imagecolorallocate($img, 20, 10, 10);

        imagefilledrectangle($img, 10, 10, 150, 82, $ColorFill);
        imagerectangle($img, 9, 9, 154, 86, $ColorLines);
        imagettftext($img, 36, 0, 35, 66, $color_text, $font_file, $contrast);

        // Применяем фильтр контрастности
        imagefilter($img, IMG_FILTER_CONTRAST, $contrast);

        // Начинаем буферизацию вывода
        ob_start();

        // Генерируем изображение и выводим его в буфер
        imagejpeg($img, null, 100);

        // Получаем содержимое буфера
        $imageContent = ob_get_clean();

        // Освобождаем память
        imagedestroy($img);

        // Возвращаем ответ с изображением
        return Response::make($imageContent, 200, ['Content-Type' => 'image/jpeg']);
    }
}