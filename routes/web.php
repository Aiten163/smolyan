<?php

use App\Http\Controllers\Lab5Controller;
use App\Http\Controllers\SotrController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\ImageGenerateController;

Route::get('/', function () {
    return view('main');
})->name('main');

Route::get('/generate-image/{contrast}', [ImageGenerateController::class, 'generateImage'])
    ->where('contrast', '-?[0-9]+')
    ->name('generate.image');

Route::get('/office_program/lab1', function () {
    return view('office_program.lab1.index');
});
Route::get('/office_program/lab2', function () {
    return view('office_program.lab2.index');
});
Route::get('/office_program/lab3', [SotrController::class, 'getHtml']);

Route::get('/office_program/lab4', function () {
    return view('office_program.lab1.index');
});
Route::get('/office_program/lab5', function () {
    return view('office_program.lab1.index');
});
Route::get('/office_program/lab6', function () {
    return view('office_program.lab1.index');
});
Route::get('/office_program/lab7', function () {
    return view('office_program.lab1.index');
});
Route::prefix('data_base')->group(function () {
    Route::resource('/lab2', 'App\Http\Controllers\WorkerController')->except(['show']);

    Route::get('/lab3', [UniversityController::class, 'index']);
    Route::get('/lab4', [UniversityController::class, 'index']);
    Route::get('/lab3/departments/{facultyId}', [UniversityController::class, 'getDepartments'])->name('faculty'); // Получить кафедры для факультета
    Route::get('/lab3/employees/{departmentId}', [UniversityController::class, 'getEmployees'])->name('employees'); // Получить сотрудников для кафедры
    Route::get('/lab3/employees/show/{employeesId}', [UniversityController::class, 'showEmployees'])->name('showEmployees');
    Route::get('/lab5', [Lab5Controller::class, 'index']);
});