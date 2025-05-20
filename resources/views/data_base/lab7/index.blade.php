<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Rates Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .auto-update-active {
            background-color: #28a745 !important;
            color: white !important;
        }
        #last-update {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .highlight-new {
            animation: highlight 2s;
        }
        @keyframes highlight {
            from { background-color: #ffff99; }
            to { background-color: transparent; }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Currency Rates Monitor <span id="last-update"></span></h1>

    <div class="row mb-4">
        <div class="col-md-3">
            <label for="date_from" class="form-label">Date From:</label>
            <input type="date" id="date_from" class="form-control" value="{{ $minDate }}">
        </div>
        <div class="col-md-3">
            <label for="date_to" class="form-label">Date To:</label>
            <input type="date" id="date_to" class="form-control" value="{{ $maxDate }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button id="toggle_update" class="btn btn-primary w-100">Start Loading Records</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>USD</th>
                <th>EUR</th>
                <th>GBP</th>
            </tr>
            </thead>
            <tbody id="rates_body">
            <!-- Данные будут загружаться по одной записи -->
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        let autoUpdateInterval;
        let isAutoUpdateActive = false;
        let currentIndex = 0;
        let allRecords = [];

        // Функция для форматирования времени
        function formatTime(date) {
            return date.toLocaleTimeString();
        }

        // Функция загрузки всех данных один раз
        function loadAllRecords() {
            const dateFrom = $('#date_from').val();
            const dateTo = $('#date_to').val();

            if (!dateFrom || !dateTo) {
                alert('Please select both dates');
                return false;
            }

            $.ajax({
                url: '/data_base/lab8/get-rates',
                type: 'GET',
                data: {
                    date_from: dateFrom,
                    date_to: dateTo
                },
                success: function(data) {
                    allRecords = data;
                    currentIndex = 0;
                    $('#rates_body').empty(); // Очищаем таблицу перед началом
                    return true;
                },
                error: function() {
                    alert('Error loading data');
                    return false;
                }
            });
        }

        // Функция добавления одной записи
        function addSingleRecord() {
            if (currentIndex < allRecords.length) {
                const rate = allRecords[currentIndex];
                const newRow = $(`
                    <tr class="highlight-new">
                        <td>${rate.CDate}</td>
                        <td>${rate.dollar}</td>
                        <td>${rate.euro}</td>
                        <td>${rate.GBP}</td>
                    </tr>
                `);

                $('#rates_body').prepend(newRow); // Добавляем в начало таблицы
                $('#last-update').text('Last added: ' + rate.CDate + ' at ' + formatTime(new Date()));
                currentIndex++;

                // Удаляем класс анимации через 2 секунды
                setTimeout(() => {
                    newRow.removeClass('highlight-new');
                }, 2000);
            } else {
                // Все записи загружены, останавливаем интервал
                clearInterval(autoUpdateInterval);
                $('#toggle_update').text('All records loaded')
                    .removeClass('btn-danger')
                    .addClass('btn-success')
                    .prop('disabled', true);
                isAutoUpdateActive = false;
            }
        }

        // Обработчик кнопки
        $('#toggle_update').click(function() {
            if (!isAutoUpdateActive) {
                // Загружаем все данные один раз
                if (loadAllRecords()) {
                    // Запускаем пошаговую загрузку
                    isAutoUpdateActive = true;
                    $(this).text('Stop Loading')
                        .removeClass('btn-primary')
                        .addClass('btn-danger');

                    // Сразу добавляем первую запись
                    addSingleRecord();

                    // Устанавливаем интервал на каждые 2 секунды
                    autoUpdateInterval = setInterval(addSingleRecord, 2000);
                }
            } else {
                // Останавливаем загрузку
                isAutoUpdateActive = false;
                $(this).text('Start Loading Records')
                    .removeClass('btn-danger')
                    .addClass('btn-primary');
                clearInterval(autoUpdateInterval);
            }
        });
    });
</script>
</body>
</html>