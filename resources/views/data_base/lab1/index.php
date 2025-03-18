<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $red = isset($_GET['red']) ? (int)$_GET['red'] : 0;
    $green = isset($_GET['green']) ? (int)$_GET['green'] : 0;
    $blue = isset($_GET['blue']) ? (int)$_GET['blue'] : 0;
    $width = isset($_GET['width']) ? (int)$_GET['width'] : 200;
    $height = isset($_GET['height']) ? (int)$_GET['height'] : 200;
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../../../../css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
    <style>
        #size-value {
            font-size: 18px;
            width: 100%
        }
    </style>
</head>
<body>
<div class="container ">
    <table>
        <form action="" method="get">
        <tr>
            <td>
                Палитра RGB: RGB() или
            </td>
        </tr>
        <tr>
            <td>
                Красный: <input name="red" id="redR" type="range" min="0" max="255" value="<?php echo $red ?>" onchange="changeValue(this.value, this.id)">
                Зеленый: <input name="green" id="greenR" type="range" min="0" max="255" value="<?php echo $green ?>" onchange="changeValue(this.value, this.id)">
                Синий: <input name="blue" id="blueR" type="range" min="0" max="255" value="<?php echo $blue ?>" onchange="changeValue(this.value, this.id)">
            </td>
        </tr>
        <tr>
            <td>
                Красный: <input id="redN" type="number" min="0" max="255" value="<?php echo $red ?>" onchange="changeValue(this.value, this.id)">
                Зеленый: <input id="greenN" type="number" min="0" max="255" value="<?php echo $green ?>" onchange="changeValue(this.value, this.id)">
                Синий: <input id="blueN" type="number" min="0" max="255" value="<?php echo $blue ?>" onchange="changeValue(this.value, this.id)">
            </td>
        </tr>
        <tr>
            <td>
                Ширина: <input name="width" type="range" min="0" max="1000" value="<?php echo $width ?>">
                Длина: <input name="height" type="range" min="0" max="1000" value="<?php echo $height?>">
                <input type="submit">
            </td>
        </tr>
        </form>
    </table>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'GET'): ?>
        <img class="img-thumbnail border-4 border-danger"
             src="image_generate.php<?php echo '?red=' . $red . '&green=' . $green . '&blue=' . $blue . '&width=' . $width . '&height=' . $height?>" alt="Изображение">
    <?php endif; ?>
</div>
<script>
    function changeValue(value, id)
    {
        let last_index = id.charAt(id.length - 1);
        value = value?value:0;
        if (last_index === 'R') {
            document.querySelector('#' + id.slice(0, -1) + 'N').value=value;
        } else {
            document.querySelector('#' + id.slice(0, -1) + 'R').value=value;
        }
    }
</script>
<script src="../../js/bootstrap.min.js"></script>
</body>
</html>