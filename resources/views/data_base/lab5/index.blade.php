<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Карточка сотрудника</title>
    <!-- Подключаем Bootstrap из CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .chart-container {
            text-align: center;
            margin: 20px auto;
            width: 600px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
    </style>
</head>
<body>
<h1>Распределение сотрудников по отделам</h1>
<div class="chart-container">
    <img src="{{ $img }}" alt="Круговая диаграмма распределения сотрудников">
</div>

<table>
    <thead class="table">
    <td class="table-column">123</td>
    </thead>
</table>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>