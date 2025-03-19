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
        img {
            width: 40%;
            border: 4px solid red;
        }
    </style>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>

<h1>Изменение контрастности изображения</h1>

<form onsubmit="event.preventDefault(); generateImage();">
    <label for="contrast">Контрастность:</label>
    <input type="range" id="contrast" name="contrast" min="-100" max="100" value="0">
    <div id="contrast-value">Текущее значение: 0</div>
    <button class="btn-info" type="submit">Применить</button>
</form>

<img id="image-preview" class="img-thumbnail" src="/lab2.jpg" alt="Изображение с измененной контрастностью">

<script>
    const contrastSlider = document.getElementById('contrast');
    const contrastValue = document.getElementById('contrast-value');
    const imagePreview = document.getElementById('image-preview');

    contrastSlider.addEventListener('input', function () {
        contrastValue.textContent = `Текущее значение: ${this.value}`;
    });

    function generateImage() {
        const contrast = contrastSlider.value;
        imagePreview.src = `/generate-image/${contrast}?t=${new Date().getTime()}`;
    }
</script>

</body>
</html>
