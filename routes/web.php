<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\UniversityController;
Route::get('/', function () {
    return view('main');
})->name('main');

Route::get('/generate-image', [\App\Http\Controllers\ImageGenerateController::class, 'generateImage'])->name('generate.image');

Route::get('/office_program/lab1', function () {
    return view('office_program.lab1.index');
});
Route::get('/office_program/lab2', function () {
    return view('office_program.lab2.index');
});
Route::get('/office_program/lab3', function () {
    return view('office_program.lab1.index');
});
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
    Route::get('/lab3/departments/{facultyId}', [UniversityController::class, 'getDepartments'])->name('faculty'); // Получить кафедры для факультета
    Route::get('/lab3/employees/{departmentId}', [UniversityController::class, 'getEmployees'])->name('employees'); // Получить сотрудников для кафедры

});