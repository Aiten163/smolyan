<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Maatwebsite\Excel\Facades\Excel;

class SotrController extends Controller
{
    public function getHtml()
    {
        $sotrs = DB::table('sotr')
            ->join('otdels', 'sotr.Otdel', '=', 'otdels.idOtdel')
            ->join('zarpl', 'sotr.id', '=', 'zarpl.idSotr')
            ->get();
        return view('office_program.lab3.index', compact('sotrs'));
    }

    public function createExcel()
    {
        Excel::
    }
}
