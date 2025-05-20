<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        // Получаем минимальную и максимальную даты для установки в форме
        $minDate = Currency::min('CDate');
        $maxDate = Currency::max('CDate');

        return view('data_base.lab7.index', compact('minDate', 'maxDate'));
    }

    public function getRates(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        return Currency::whereBetween('CDate', [$dateFrom, $dateTo])
            ->orderBy('CDate', 'asc') // или 'desc' в зависимости от нужного порядка
            ->get();
    }
}