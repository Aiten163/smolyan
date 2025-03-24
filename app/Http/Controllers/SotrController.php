<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SotrController extends Controller
{
    protected function bd()
    {
         return DB::table('sotr')
            ->join('otdels', 'sotr.Otdel', '=', 'otdels.idOtdel')
            ->join('zarpl', 'sotr.id', '=', 'zarpl.idSotr')
            ->get();
    }
    public function getHtml()
    {
        $sotrs = $this->bd();
        return view('office_program.lab3.index', compact('sotrs'));
    }
    public function createExcel()
    {
        $sotrs = $this->bd();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('C1', 'id');
        $sheet->setCellValue('D1', 'Отдел');
        $sheet->setCellValue('E1', 'Фамилия');
        $sheet->setCellValue('F1', 'Имя');
        $sheet->setCellValue('G1', 'Зарплата');
        print_r($sotrs);
        $count = count($sotrs);
        for ($i=0; $i<$count; $i++) {
            $sheet->setCellValue('C'.$i+2, $sotrs[$i]["id"]);
            $sheet->setCellValue('D'. $i+2, $sotrs[$i]["NameOtdel"]);
            $sheet->setCellValue('E'. $i+2, $sotrs[$i]["LastName"]);
            $sheet->setCellValue('F'. $i+2, $sotrs[$i]["FirstName"]);
            $sheet->setCellValue('G'.$i+2, $sotrs[$i]["TotalSalary"]);
        };

        $writer = new Xlsx($spreadsheet);

        $table = new Table('A1:L50', 'Table1');

        $tableStyle = new TableStyle();
        $tableStyle->setTheme(TableStyle::TABLE_STYLE_MEDIUM9);
        $tableStyle->setShowRowStripes(true);
        $table->setStyle($tableStyle);

        $sheet->addTable($table);

        $writer->save('CreateExcelTable.xlsx');
    }
}
