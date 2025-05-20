<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Rates Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .chart-container {
            position: relative;
            height: 400px;
            margin-top: 30px;
        }
        .currency-checkbox {
            margin-right: 15px;
        }
        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Курсы валют<span id="last-update"></span></h1>

    <div class="filter-section">
        <div class="row mb-4">
            <div class="col-md-2">
                <label for="year" class="form-label">Год:</label>
                <select id="year" class="form-select">
                    <option value="">Все годы</option>
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="date_from" class="form-label">Дата от:</label>
                <input type="date" id="date_from" class="form-control" value="{{ $minDate }}">
            </div>
            <div class="col-md-3">
                <label for="date_to" class="form-label">Дата до:</label>
                <input type="date" id="date_to" class="form-control" value="{{ $maxDate }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button id="apply_filters" class="btn btn-primary w-100">Применить</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button id="toggle_update" class="btn btn-secondary w-100">Автообновление</button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>Дата</th>
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

    <div class="mt-4">
        <h3>График курсов валют</h3>
        <div class="mb-3">
            <label class="form-label">Показывать валюты:</label>
            <div class="d-flex">
                <div class="form-check currency-checkbox">
                    <input class="form-check-input" type="checkbox" id="show_usd" checked>
                    <label class="form-check-label" for="show_usd">USD</label>
                </div>
                <div class="form-check currency-checkbox">
                    <input class="form-check-input" type="checkbox" id="show_eur" checked>
                    <label class="form-check-label" for="show_eur">EUR</label>
                </div>
                <div class="form-check currency-checkbox">
                    <input class="form-check-input" type="checkbox" id="show_gbp" checked>
                    <label class="form-check-label" for="show_gbp">GBP</label>
                </div>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="currencyChart"></canvas>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let autoUpdateInterval;
        let isAutoUpdateActive = false;
        let currentIndex = 0;
        let allRecords = [];
        let currencyChart = null;

        // Функция для форматирования времени
        function formatTime(date) {
            return date.toLocaleTimeString();
        }

        // Инициализация графика
        function initChart() {
            const ctx = document.getElementById('currencyChart').getContext('2d');
            currencyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'USD',
                            data: [],
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.1)',
                            tension: 0.1,
                            hidden: false
                        },
                        {
                            label: 'EUR',
                            data: [],
                            borderColor: 'rgb(54, 162, 235)',
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            tension: 0.1,
                            hidden: false
                        },
                        {
                            label: 'GBP',
                            data: [],
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.1)',
                            tension: 0.1,
                            hidden: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    },
                    animation: {
                        duration: 1000
                    }
                }
            });

            // Обработчики чекбоксов
            $('#show_usd').change(function() {
                const meta = currencyChart.getDatasetMeta(0);
                meta.hidden = !this.checked;
                currencyChart.update();
            });

            $('#show_eur').change(function() {
                const meta = currencyChart.getDatasetMeta(1);
                meta.hidden = !this.checked;
                currencyChart.update();
            });

            $('#show_gbp').change(function() {
                const meta = currencyChart.getDatasetMeta(2);
                meta.hidden = !this.checked;
                currencyChart.update();
            });
        }

        // Обновление графика
        function updateChart(rate) {
            if (!currencyChart) initChart();

            // Добавляем новую точку данных
            currencyChart.data.labels.push(rate.CDate);
            currencyChart.data.datasets[0].data.push(rate.dollar);
            currencyChart.data.datasets[1].data.push(rate.euro);
            currencyChart.data.datasets[2].data.push(rate.GBP);

            // Ограничиваем количество точек на графике (например, последние 50)
            const maxPoints = 50;
            if (currencyChart.data.labels.length > maxPoints) {
                currencyChart.data.labels.shift();
                currencyChart.data.datasets.forEach(dataset => {
                    dataset.data.shift();
                });
            }

            currencyChart.update();
        }

        // Функция загрузки всех данных
        function loadAllRecords(callback) {
            const dateFrom = $('#date_from').val();
            const dateTo = $('#date_to').val();
            const year = $('#year').val();

            $.ajax({
                url: 'lab8/get-rates',
                type: 'GET',
                data: {
                    date_from: dateFrom,
                    date_to: dateTo,
                    year: year
                },
                success: function(data) {
                    allRecords = data;
                    currentIndex = 0;
                    $('#rates_body').empty();

                    // Сбрасываем график
                    if (currencyChart) {
                        currencyChart.destroy();
                        currencyChart = null;
                    }

                    // Инициализируем график с новыми данными
                    if (data.length > 0) {
                        initChart();
                        // Добавляем все данные в график
                        data.forEach(rate => {
                            updateChart(rate);
                        });
                    }

                    if (callback) callback(true);
                },
                error: function() {
                    alert('Error loading data');
                    if (callback) callback(false);
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

                $('#rates_body').prepend(newRow);
                $('#last-update').text('Last added: ' + rate.CDate + ' at ' + formatTime(new Date()));

                // Обновляем график
                updateChart(rate);

                currentIndex++;

                // Удаляем класс анимации через 2 секунды
                setTimeout(() => {
                    newRow.removeClass('highlight-new');
                }, 2000);
            } else {
                // Все записи загружены, останавливаем интервал
                clearInterval(autoUpdateInterval);
                $('#toggle_update').text('Все записи загружены')
                    .removeClass('btn-danger')
                    .addClass('btn-success')
                    .prop('disabled', true);
                isAutoUpdateActive = false;
            }
        }

        // Обработчик кнопки применения фильтров
        $('#apply_filters').click(function() {
            // Останавливаем автообновление, если оно активно
            if (isAutoUpdateActive) {
                clearInterval(autoUpdateInterval);
                isAutoUpdateActive = false;
                $('#toggle_update').text('Автообновление')
                    .removeClass('btn-danger')
                    .addClass('btn-secondary');
            }

            // Загружаем данные с новыми фильтрами
            loadAllRecords();
        });

        // Обработчик кнопки автообновления
        $('#toggle_update').click(function() {
            if (!isAutoUpdateActive) {
                // Загружаем все данные один раз
                loadAllRecords(function(success) {
                    if (success) {
                        // Запускаем пошаговую загрузку
                        isAutoUpdateActive = true;
                        $('#toggle_update').text('Остановить')
                            .removeClass('btn-secondary')
                            .addClass('btn-danger');

                        // Сразу добавляем первую запись
                        addSingleRecord();

                        // Устанавливаем интервал на каждые 2 секунды
                        autoUpdateInterval = setInterval(addSingleRecord, 2000);
                    }
                });
            } else {
                // Останавливаем загрузку
                isAutoUpdateActive = false;
                $('#toggle_update').text('Автообновление')
                    .removeClass('btn-danger')
                    .addClass('btn-secondary');
                clearInterval(autoUpdateInterval);
            }
        });
    });
</script>
</body>
</html>