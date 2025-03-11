@extends('layouts.app')

@section('title', 'lab2')
@section('content')
    <table class="table table-bordered">
        <thead>
        <tr>
            <th class="col-auto">№</th>
            <th class="col-3">Фамилия</th>
            <th class="col-2">Имя</th>
            <th class="col-3">Отчество</th>
            <th class="col-2">Дата рождения</th>
            <th class="col-2">Должность</th>
            <th class="col-1">Изменить</th>
            <th class="col-1">Удалить</th>
        </tr>
        </thead>
        <tbody>
        @foreach($workers as $worker)
            <tr>
                <td>{{$worker->id}}</td>
                <td>{{$worker->second_name}}</td>
                <td>{{$worker->first_name}}</td>
                <td>{{$worker->middle_name}}</td>
                <td>{{$worker->birthday}}</td>
                <td>{{$worker->position}}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#editModal{{$worker->id}}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <x-modal
                            idModal="editModal{{$worker->id}}"
                            title="Редактировать работника"
                            link="{{ route('lab2.update', $worker->id) }}"
                            method="PUT"
                            button="Сохранить">
                        <label class="form-label d-block" for="second_name">Фамилия</label>
                        <input name="second_name" class="form-control" type="text" value="{{ $worker->second_name }}">

                        <label class="form-label" for="first_name">Имя</label>
                        <input name="first_name" class="form-control" type="text" value="{{ $worker->first_name }}">

                        <label class="form-label" for="middle_name">Отчество</label>
                        <input name="middle_name" class="form-control" type="text" value="{{ $worker->middle_name }}">

                        <label class="form-label" for="birthday">Дата рождения</label>
                        <input name="birthday" class="form-control" type="date" value="{{ $worker->birthday }}">

                        <label class="form-label" for="position">Должность</label>
                        <input name="position" class="form-control" type="text" value="{{ $worker->position }}">
                    </x-modal>
                </td>
                <td class="text-center">
                    <form action="{{ route('lab2.destroy', $worker->id) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
    {{$workers->links('pagination::bootstrap-5')}}

    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
        Добавить работника
    </button>
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

@endsection