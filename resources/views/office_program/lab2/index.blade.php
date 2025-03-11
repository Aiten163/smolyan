<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $contrast = isset($_GET['contrast'])? (int)$_GET['contrast'] : 0;
} else {
    $contrast = 0;
}
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Изменение контрастности изображения</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100vh;
                margin: 0;
            }
            form {
                margin-bottom: 20px;
            }
            #contrast-value {
                margin-top: 10px;
                font-size: 18px;
            }
        </style>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body>

        <h1>Изменение контрастности изображения</h1>

        <form method="GET" action="">
            <label for="contrast">Контрастность:</label>
            <input type="range" id="contrast" name="contrast" min="-100" max="100" value="<?php echo $contrast; ?>">
            <div id="contrast-value">Текущее значение: <?php echo $contrast; ?></div>
            <button class="btn-info" type="submit" name="run">Run</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'GET'): ?>
            <!--<img class="img-thumbnail border-4 border-danger" style="width: 40%" src="" alt="Изображение с измененной контрастностью">-->
        <?php endif; ?>

        <script>
            const contrastSlider = document.getElementById('contrast');
            const contrastValue = document.getElementById('contrast-value');

            contrastSlider.addEventListener('input', function() {
                contrastValue.textContent = `Текущее значение: ${this.value}`;
            });
        </script>
    </body>
</html>