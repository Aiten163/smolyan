<?php
// Graph2.php

// Получаем значение контрастности из GET-запроса
$contrast = isset($_GET['contrast']) ? (int)$_GET['contrast'] : 0;

// Загружаем изображение
$filename = 'lab1.jpg';
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
        die("Неподдерживаемый тип изображения");
}

// Добавляем текст с текущим значением контрастности
$font_file = 'arial.ttf'; // Убедитесь, что шрифт доступен
$ColorLines = imagecolorallocate($img, 0, 0, 0);
$ColorFill = imagecolorallocate($img, 250, 250, 160);
$color_text = imagecolorallocate($img, 20, 10, 10);

imagefilledrectangle($img, 10, 10, 150, 82, $ColorFill);
imagerectangle($img, 9, 9, 154, 86, $ColorLines);
imagettftext($img, 36, 0, 35, 66, $color_text, $font_file, $contrast);

// Применяем фильтр контрастности
imagefilter($img, IMG_FILTER_CONTRAST, $contrast);

// Выводим изображение в браузер
header('Content-type: image/jpeg');
imagejpeg($img, quality: 100);

// Освобождаем память
imagedestroy($img);