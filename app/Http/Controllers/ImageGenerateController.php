<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ImageGenerateController extends Controller
{
    public function generateImage($contrast)
    {
        $contrast = (int) $contrast;
        $filename = public_path('lab2.jpg');

        if (!file_exists($filename)) {
            abort(404, "Файл изображения не найден");
        }

        $info = getimagesize($filename);
        $type = $info[2];

        switch ($type) {
            case IMAGETYPE_GIF:
                $img = imagecreatefromgif($filename);
                imageSaveAlpha($img, true);
                break;
            case IMAGETYPE_JPEG:
                $img = imagecreatefromjpeg($filename);
                break;
            case IMAGETYPE_PNG:
                $img = imagecreatefrompng($filename);
                imageSaveAlpha($img, true);
                break;
            default:
                abort(400, "Неподдерживаемый формат изображения");
        }

        // Добавляем текст с текущим значением контрастности
        $fontFile = public_path('arial.ttf');
        if (file_exists($fontFile)) {
            $colorText = imagecolorallocate($img, 20, 10, 10);
            imagettftext($img, 20, 0, 20, 40, $colorText, $fontFile, "Контраст: $contrast");
        }

        // Применяем фильтр контрастности
        imagefilter($img, IMG_FILTER_CONTRAST, -$contrast);

        ob_start();
        imagejpeg($img, null, 100);
        $imageContent = ob_get_clean();
        imagedestroy($img);

        return Response::make($imageContent, 200, ['Content-Type' => 'image/jpeg']);
    }
}
