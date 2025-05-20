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

        // Получаем список доступных годов
        $availableYears = Currency::selectRaw('YEAR(CDate) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('data_base.lab7.index', compact('minDate', 'maxDate', 'availableYears'));
    }

    public function getRates(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $year = $request->input('year');

        $query = Currency::query();

        if ($year) {
            $query->whereYear('CDate', $year);
        }

        if ($dateFrom && $dateTo) {
            $query->whereBetween('CDate', [$dateFrom, $dateTo]);
        }

        return $query->orderBy('CDate', 'asc')->get();
    }
}