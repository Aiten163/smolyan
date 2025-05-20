<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->date('CDate')->primary();
            $table->decimal('dollar', 6, 2);
            $table->decimal('euro', 6, 2);
            $table->decimal('GBP', 6, 2);
        });

        // Заполнение таблицы тестовыми данными
        DB::table('currencies')->insert([
            ['CDate' => '2016-01-04', 'dollar' => 65.24, 'euro' => 71.79, 'GBP' => 95.01],
            ['CDate' => '2016-01-07', 'dollar' => 64.34, 'euro' => 71.98, 'GBP' => 94.92],
            ['CDate' => '2016-01-09', 'dollar' => 64.80, 'euro' => 72.04, 'GBP' => 95.00],
            ['CDate' => '2016-02-05', 'dollar' => 65.07, 'euro' => 70.90, 'GBP' => 95.07],
            ['CDate' => '2016-02-09', 'dollar' => 65.02, 'euro' => 71.63, 'GBP' => 95.17],
            ['CDate' => '2016-02-15', 'dollar' => 65.17, 'euro' => 71.93, 'GBP' => 94.98],
            ['CDate' => '2016-03-05', 'dollar' => 63.97, 'euro' => 71.72, 'GBP' => 94.24],
            ['CDate' => '2016-03-09', 'dollar' => 64.16, 'euro' => 71.75, 'GBP' => 95.01],
            ['CDate' => '2016-03-15', 'dollar' => 64.72, 'euro' => 71.87, 'GBP' => 94.02],
            ['CDate' => '2016-04-02', 'dollar' => 63.95, 'euro' => 72.04, 'GBP' => 95.13],
            ['CDate' => '2016-04-05', 'dollar' => 64.77, 'euro' => 70.44, 'GBP' => 94.98],
            ['CDate' => '2016-04-10', 'dollar' => 65.24, 'euro' => 70.72, 'GBP' => 94.08],
            ['CDate' => '2017-01-04', 'dollar' => 65.17, 'euro' => 70.46, 'GBP' => 94.97],
            ['CDate' => '2017-01-07', 'dollar' => 64.03, 'euro' => 71.66, 'GBP' => 95.20],
            ['CDate' => '2017-01-13', 'dollar' => 64.06, 'euro' => 71.97, 'GBP' => 94.91],
            ['CDate' => '2017-02-03', 'dollar' => 64.24, 'euro' => 71.89, 'GBP' => 94.88],
            ['CDate' => '2017-02-09', 'dollar' => 64.99, 'euro' => 71.69, 'GBP' => 94.94],
            ['CDate' => '2017-02-14', 'dollar' => 64.82, 'euro' => 70.51, 'GBP' => 94.04],
            ['CDate' => '2017-03-04', 'dollar' => 64.11, 'euro' => 71.70, 'GBP' => 93.97],
            ['CDate' => '2017-03-09', 'dollar' => 63.88, 'euro' => 71.87, 'GBP' => 94.82],
            ['CDate' => '2017-03-12', 'dollar' => 65.05, 'euro' => 70.62, 'GBP' => 93.92],
            ['CDate' => '2017-04-04', 'dollar' => 64.13, 'euro' => 70.89, 'GBP' => 94.19],
            ['CDate' => '2017-04-07', 'dollar' => 64.90, 'euro' => 70.44, 'GBP' => 94.14],
            ['CDate' => '2017-04-09', 'dollar' => 64.39, 'euro' => 71.72, 'GBP' => 95.23],
            ['CDate' => '2018-01-04', 'dollar' => 65.25, 'euro' => 70.56, 'GBP' => 94.27],
            ['CDate' => '2018-01-06', 'dollar' => 64.01, 'euro' => 70.61, 'GBP' => 94.95],
            ['CDate' => '2018-01-11', 'dollar' => 64.90, 'euro' => 70.89, 'GBP' => 93.91],
            ['CDate' => '2018-02-05', 'dollar' => 64.88, 'euro' => 71.89, 'GBP' => 95.04],
            ['CDate' => '2018-02-11', 'dollar' => 65.29, 'euro' => 71.97, 'GBP' => 95.17],
            ['CDate' => '2018-02-13', 'dollar' => 64.03, 'euro' => 70.88, 'GBP' => 93.98],
            ['CDate' => '2018-03-05', 'dollar' => 65.14, 'euro' => 71.84, 'GBP' => 94.16],
            ['CDate' => '2018-03-11', 'dollar' => 63.88, 'euro' => 71.58, 'GBP' => 95.03],
            ['CDate' => '2018-03-15', 'dollar' => 64.20, 'euro' => 71.79, 'GBP' => 93.90],
            ['CDate' => '2018-04-03', 'dollar' => 64.06, 'euro' => 72.06, 'GBP' => 94.85],
            ['CDate' => '2018-04-05', 'dollar' => 65.12, 'euro' => 71.84, 'GBP' => 95.08],
            ['CDate' => '2018-04-09', 'dollar' => 65.13, 'euro' => 71.55, 'GBP' => 95.22],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
