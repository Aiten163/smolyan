<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
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
    <div class="row m-3" style="width: 400px">
        <div id="size-value">Размер: 10</div>
        <label>
            <input id="range" type="range" value="10" max="20" min="1" size="20"  class="form-range mx-auto">
        </label>
    </div>
    <div >
        <img id="image" style="width: 100%" src="{{asset('storage/lab1.jpg')}}" alt="photo">
    </div>
</div>

<script src="{{asset('js/changeImage.js')}}"> </script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>