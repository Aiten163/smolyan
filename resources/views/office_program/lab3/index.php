<?php
$mysqli = mysqli_connect("127.0.0.1", "root", "rootpassword", "sotrzarpl");

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
        <link href="../../../../css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>

        <h1>Изменение контрастности изображения</h1>

        <form method="POST" action="">
            <label for="contrast">Контрастность:</label>
            <input type="range" id="contrast" name="contrast" min="-100" max="100" value="<?php echo $contrast; ?>">
            <div id="contrast-value">Текущее значение: <?php echo $contrast; ?></div>
            <button class="btn-info" type="submit" name="run">Run</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <img class="img-thumbnail border-4 border-danger" style="width: 40%"  src="Graph2.php?contrast=<?php echo $contrast; ?>" alt="Изображение с измененной контрастностью">
        <?php endif; ?>

        <script>
            const contrastSlider = document.getElementById('contrast');
            const contrastValue = document.getElementById('contrast-value');

            contrastSlider.addEventListener('input', function() {
                contrastValue.textContent = `Текущее значение: ${this.value}`;
            });
        </script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>