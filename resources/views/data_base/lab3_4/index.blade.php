<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выбор факультета и кафедры</title>
    <!-- Подключаем Bootstrap из CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
<div class="row row-cols-2" id="employee-list"></div>

<x-modal
        idModal="addModal"
        title="Добавить работника"
        link="{{ route('lab2.store') }}"
        method="POST"
        button="Добавить">
    <label class="form-label" for="second_name">Фамилия</label>
    <input name="second_name" class="form-control" type="text">

    <label class="form-label" for="first_name">Имя</label>
    <input name="first_name" class="form-control" type="text">

    <label class="form-label" for="middle_name">Отчество</label>
    <input name="middle_name" class="form-control" type="text">

    <label class="form-label" for="birthday">Дата рождения</label>
    <input name="birthday" class="form-control" type="date">

    <label class="form-label" for="position">Должность</label>
    <input name="position" class="form-control" type="text">
</x-modal>

<!-- Подключаем скрипты Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Получаем кафедры по выбранному факультету
    document.getElementById('select-faculty').addEventListener('click', function () {
        const facultyId = document.getElementById('faculty-select').value;
        const departmentSelect = document.getElementById('department-select');

        fetch(`/data_base/lab3/departments/${facultyId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ошибка получения списка кафедр.');
                }
                return response.json();
            })
            .then(departments => {
                departmentSelect.innerHTML = ''; // Очищаем список кафедр
                if (departments.length === 0) {
                    departmentSelect.innerHTML = '<option disabled>Нет кафедр для этого факультета</option>';
                } else {
                    departments.forEach(department => {
                        const option = document.createElement('option');
                        option.value = department.id;
                        option.textContent = department.name;
                        departmentSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                alert(error.message);
            });
    });

    // Получаем сотрудников по выбранной кафедре
    document.getElementById('select-department').addEventListener('click', function () {
        const departmentId = document.getElementById('department-select').value;
        const employeeList = document.getElementById('employee-list');

        fetch(`/data_base/lab3/employees/${departmentId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ошибка получения списка сотрудников.');
                }
                return response.json();
            })
            .then(employees => {
                employeeList.innerHTML = ''; // Очищаем список сотрудников
                if (employees.length === 0) {
                    employeeList.innerHTML = '<p class="text-warning">Нет сотрудников на этой кафедре.</p>';
                } else {
                    employees.forEach(employee => {
                        const employeeCard = document.createElement('div');
                        employeeCard.className = 'card mb-3 col-3 col';
                        employeeCard.innerHTML = `
                            <div class="card-body">
                                <h5 class="card-title">${employee.full_name}</h5>
                                <p class="card-text ">
                                    <strong>ID:</strong> ${employee.id}<br>
                                    <strong>Должность:</strong> ${employee.position}<br>
                                    <strong>Ставка:</strong> ${employee.salary_rate}<br>
                                    <button class='btn-info'><a href='lab3/employees/show/${employee.id}'>Подробнее</a></button>
                                </p>
                            </div>
                        `;
                        employeeList.appendChild(employeeCard);
                    });
                }
            })
            .catch(error => {
                alert(error.message);
            });
    });
</script>

</body>
</html>