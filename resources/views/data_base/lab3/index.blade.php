<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выбор факультета и кафедры</title>
    <!-- Подключаем Bootstrap из CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="container mt-5">

<h1 class="mb-4">Выберите факультет и кафедру</h1>

<!-- Список факультетов -->
<div class="mb-3">
    <label for="faculty-select" class="form-label">Факультет/Институт</label>
    <select id="faculty-select" class="form-select">
        @foreach ($faculties as $faculty)
            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
        @endforeach
    </select>
</div>
<button id="select-faculty" class="btn btn-primary mb-3">Выбрать факультет</button>

<!-- Список кафедр (пока пуст) -->
<div class="mb-3">
    <label for="department-select" class="form-label">Кафедра</label>
    <select id="department-select" class="form-select"></select>
</div>
<button id="select-department" class="btn btn-primary mb-3">Выбрать кафедру</button>

<!-- Список сотрудников (пока пуст) -->
<div id="employee-list"></div>

<!-- Подключаем скрипты Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Получаем кафедры по выбранному факультету
    $('#select-faculty').click(function () {
        const facultyId = $('#faculty-select').val();

        $.get('/data_base/lab3/departments/' + facultyId, function (departments) {
            $('#department-select').empty(); // Очищаем список кафедр
            if (departments.length === 0) {
                $('#department-select').append('<option disabled>Нет кафедр для этого факультета</option>');
            } else {
                departments.forEach(function (department) {
                    $('#department-select').append('<option value="' + department.id + '">' + department.name + '</option>');
                });
            }
        }).fail(function () {
            alert('Ошибка получения списка кафедр.');
        });
    });

    // Получаем сотрудников по выбранной кафедре
    $('#select-department').click(function () {
        const departmentId = $('#department-select').val();

        $.get('/data_base/lab3/employees/' + departmentId, function (employees) {
            $('#employee-list').empty(); // Очищаем список сотрудников
            if (employees.length === 0) {
                $('#employee-list').append('<p class="text-warning">Нет сотрудников на этой кафедре.</p>');
            } else {
                employees.forEach(function (employee) {
                    $('#employee-list').append('<p>' + employee.full_name + ' (' + employee.position + ')</p>');
                });
            }
        }).fail(function () {
            alert('Ошибка получения списка сотрудников.');
        });
    });
</script>

</body>
</html>
