@extends('layouts.app')

@section('title', 'lab2')
@section('content')
    <a href="{{ url($filePath) }}" class="btn bi-cloud-download" download>
        Скачать Excel
    </a>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th class="col-auto">№</th>
            <th class="col-3">Отдел</th>
            <th class="col-2">Фамилия</th>
            <th class="col-3">Имя</th>
            <th class="col-2">Зарплата</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sotrs as $worker)
            <tr>
                <td>{{$worker->id}}</td>
                <td>{{$worker->NameOtdel}}</td>
                <td>{{$worker->LastName}}</td>
                <td>{{$worker->FirstName}}</td>
                <td>{{$worker->TotalSalary}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection