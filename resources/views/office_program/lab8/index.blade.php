<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отчет по зарплате</title>
</head>
<body>
<h1>Формирование отчета по зарплате сотрудников за 2018 год</h1>

<form method="GET" action="{{ route('report.show') }}">
    <label for="otdel">Выберите отдел:</label>
    <select name="otdel" id="otdel" required>
        @foreach ($otdels as $otdel)
            <option value="{{ $otdel->idOtdel }}" {{ $selectedOtdel == $otdel->idOtdel ? 'selected' : '' }}>
                {{ $otdel->NameOtdel }} (№{{ $otdel->idOtdel }})
            </option>
        @endforeach
    </select>
    <button type="submit">Рисовать диаграмму</button>
</form>

@if ($selectedOtdel)
    <h2>Диаграмма отдела №{{ $selectedOtdel }}</h2>
    <img src="{{ route('report.diagram', ['otdel' => $selectedOtdel]) }}" alt="Диаграмма зарплаты">
@endif
</body>
</html>
