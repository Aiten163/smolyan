<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>

<nav class="navbar navbar-dark bg-dark p-2">
    <a class="navbar-brand " href="{{route('main')}}">Главная страница</a>
</nav>

<div class="container mt-4">
    @yield('content')
</div>

</body>
</html>
