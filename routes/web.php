<?php

use App\Http\Controllers\Lab5Controller;
use App\Http\Controllers\SotrController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\Lab9_2Controller;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\ImageGenerateController;
use App\Http\Controllers\VegetableController;

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

Route::get('/office_program/lab4', [SotrController::class, 'getHtml']);
Route::get('/office_program/lab5', [VegetableController::class, 'exportDeliveries']);

Route::get('/office_program/lab6', [\App\Http\Controllers\TovaryReportController::class, 'generateExcelReport']);
Route::get('/office_program/lab7', [\App\Http\Controllers\TovaryReportController::class, 'index']);
Route::get('/office_program/lab8', [\App\Http\Controllers\ReportController::class, 'index'])->name('report.index');
Route::get('/office_program/lab8/diagram', [\App\Http\Controllers\ReportController::class, 'drawDiagram'])->name('report.diagram');
Route::get('/office_program/lab8//show', [\App\Http\Controllers\ReportController::class, 'show'])->name('report.show');
Route::prefix('data_base')->group(function () {
    Route::resource('/lab2', 'App\Http\Controllers\WorkerController')->except(['show']);

    Route::get('/lab3', [UniversityController::class, 'index'])->name('lab3');
    Route::get('/lab4', [UniversityController::class, 'index']);
    Route::get('/lab3/departments/{facultyId}', [UniversityController::class, 'getDepartments'])->name('faculty'); // Получить кафедры для факультета
    Route::get('/lab3/employees/{departmentId}', [UniversityController::class, 'getEmployees'])->name('employees'); // Получить сотрудников для кафедры
    Route::get('/lab3/employees/show/{employeesId}', [UniversityController::class, 'showEmployees'])->name('showEmployees');
    Route::get('/lab5', [Lab5Controller::class, 'index']);
    Route::post('/lab5/store-selection', [UniversityController::class, 'storeSelection'])
        ->name('university.storeSelection');
    Route::prefix('lab6')->group(function() {
        Route::get('/', [Lab9_2Controller::class, 'index'])->name('lab9_2.index');
        Route::get('/generate-image', [Lab9_2Controller::class, 'generateImage'])->name('lab9_2.generateImage');
        Route::post('/save-generated', [Lab9_2Controller::class, 'saveGeneratedImage'])->name('lab9_2.saveGeneratedImage');
        Route::get('/get-images', [Lab9_2Controller::class, 'getImages'])->name('lab9_2.getImages');
    });
    Route::get('/lab7', function () {
        return redirect()->route('lab9_2.index');
    });
    Route::get('/lab8', [\App\Http\Controllers\CurrencyController::class, 'index'])->name('currency.index');
    Route::get('/lab8/get-rates', [\App\Http\Controllers\CurrencyController::class, 'getRates'])->name('currency.rates');
});