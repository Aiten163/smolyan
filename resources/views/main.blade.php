@extends('layouts.app')

@section('title', 'Главная страница')

@section('content')
    <h3>Офис. прогр</h3>
    <div class="list-group list-group-flush">
        <?php for ($i = 1; $i < 9 ;$i++) {
            echo
            "<div class='list-group-item'>
                    <a class='accordion-item col-md-8 mx-auto' href='office_program/lab$i'>Лабораторная  № $i </a>
                </div>";
        }?>
    </div>
    <h3>Базы данных</h3>
    <div class="list-group list-group-flush">
        <?php for ($i = 1; $i < 9 ;$i++) {
            echo
            "<div class='list-group-item'>
                    <a class='accordion-item col-md-8 mx-auto' href='data_base/lab$i'>Лабораторная  № $i </a>
                </div>";
        }?>
    </div>
@endsection