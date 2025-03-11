<?php
$r = isset($_GET['red']) ? (int)$_GET['red'] : 0;
$g = isset($_GET['green']) ? (int)$_GET['green'] : 0;
$b = isset($_GET['blue']) ? (int)$_GET['blue'] : 0;
$width = isset($_GET['width']) ? (int)$_GET['width'] : 200;
$height = isset($_GET['height']) ? (int)$_GET['height'] : 200;

$r = max(0, min(255, $r));
$g = max(0, min(255, $g));
$b = max(0, min(255, $b));

$image = imagecreatetruecolor(310, 310);

$background = imagecolorallocate($image, 144, 238, 144);
$panel = imagecolorallocate($image, 220, 220, 220);
$textColor = imagecolorallocate($image, 0, 0, 0);
$selectedColor = imagecolorallocate($image, $r, $g, $b);

imagefilledrectangle($image, 0, 0, 400, 400, $background);

imagefilledrectangle($image, 20, 50, 280, 250, $panel);

$fontPath = __DIR__ . '/arial.ttf';
if (file_exists($fontPath)) {
    imagettftext($image, 14, 0, 100, 40, $textColor, $fontPath, 'Палитра RGB');
} else {
    imagestring($image, 5, 100, 30, 'Палитра RGB', $textColor);
}

imagefilledrectangle($image, 40, 60, 60, 220, imagecolorallocate($image, $r, 0, 0));
imagefilledrectangle($image, 80, 60, 100, 220, imagecolorallocate($image, 0, $g, 0));
imagefilledrectangle($image, 120, 60, 140, 220, imagecolorallocate($image, 0, 0, $b));

imagefilledrectangle($image, 180, 100, 260, 180, $selectedColor);

$scaledImage = imagescale($image, $width, $height);

header('Content-Type: image/jpeg');
imagejpeg($scaledImage);

imagedestroy($image);
imagedestroy($scaledImage);