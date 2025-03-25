<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Карточка сотрудника</title>
    <!-- Подключаем Bootstrap из CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .staff-card {
            max-width: 500px;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .staff-header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .staff-body {
            padding: 20px;
        }
        .staff-info {
            margin-bottom: 15px;
        }
        .staff-info h5 {
            color: #0d6efd;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <form action="{{route('lab3')}}">
        @csrf
        <input type="hidden" name="id_fak" value="{{$employ['faculty_id']}}">
        <input type="hidden" name="id_caf" value="{{$employ['department_id']}}">
        <input type="submit" value="Назад">
    </form>
    <div class="staff-card">
        <div class="staff-header">
            <h3>{{$employ['full_name']}}</h3>
        </div>
        <div class="staff-body">
            <div class="staff-info">
                <h5>Должность:</h5>
                <p>{{$employ['position']}}</p>
            </div>

            <div class="staff-info">
                <h5>Ученая степень:</h5>
                <p>{{$employ["academic_degree"]}}</p>
            </div>

            <div class="staff-info">
                <h5>Ученое звание:</h5>
                <p>{{$employ['academic_title']}}</p>
            </div>

            <div class="staff-info">
                <h5>Карьера:</h5>
                <p>{{$employ['career']}}</p>
            </div>

            <div class="staff-info">
                <h5>Научные интересы:</h5>
                <p>{{$employ['research_interests']}}</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>