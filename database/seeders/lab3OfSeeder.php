<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Lab3OfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the `otdels` table
        DB::statement("
            CREATE TABLE IF NOT EXISTS `otdels` (
                `idOtdel` int(4) NOT NULL,
                `NameOtdel` varchar(25) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // Insert data into the `otdels` table
        DB::table('otdels')->insert([
            ['idOtdel' => 1, 'NameOtdel' => 'Общий'],
            ['idOtdel' => 2, 'NameOtdel' => 'Управления'],
            ['idOtdel' => 3, 'NameOtdel' => 'Технического обеспечения'],
            ['idOtdel' => 4, 'NameOtdel' => 'Безопасности труда'],
        ]);

        // Create the `sotr` table
        DB::statement("
            CREATE TABLE IF NOT EXISTS `sotr` (
                `id` int(5) NOT NULL,
                `LastName` varchar(20) NOT NULL,
                `FirstName` varchar(20) NOT NULL,
                `Otdel` int(3) NOT NULL,
                `Date_R` date NOT NULL,
                `Dolzn` varchar(25) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // Insert data into the `sotr` table
        DB::table('sotr')->insert([
            ['id' => 101, 'LastName' => 'Арбузов', 'FirstName' => 'Николай', 'Otdel' => 2, 'Date_R' => '1996-11-21', 'Dolzn' => 'Начальник отдела'],
            ['id' => 102, 'LastName' => 'Титова', 'FirstName' => 'Валентина', 'Otdel' => 2, 'Date_R' => '1997-10-21', 'Dolzn' => 'Менеджер'],
            ['id' => 103, 'LastName' => 'Розов', 'FirstName' => 'Илья', 'Otdel' => 2, 'Date_R' => '1993-10-20', 'Dolzn' => 'Менеджер'],
            ['id' => 104, 'LastName' => 'Рябова', 'FirstName' => 'Анна', 'Otdel' => 2, 'Date_R' => '1994-05-20', 'Dolzn' => 'Инженер'],
            ['id' => 105, 'LastName' => 'Мухин', 'FirstName' => 'Антон', 'Otdel' => 2, 'Date_R' => '1992-12-04', 'Dolzn' => 'Старший менеджер'],
            ['id' => 106, 'LastName' => 'Дубов', 'FirstName' => 'Сергей', 'Otdel' => 3, 'Date_R' => '1991-02-22', 'Dolzn' => 'Начальник отдела'],
            ['id' => 107, 'LastName' => 'Прохоров', 'FirstName' => 'Олег', 'Otdel' => 3, 'Date_R' => '1994-10-26', 'Dolzn' => 'Водитель'],
            ['id' => 108, 'LastName' => 'Дубов', 'FirstName' => 'Василий', 'Otdel' => 3, 'Date_R' => '1990-10-07', 'Dolzn' => 'Экспедитор'],
            ['id' => 109, 'LastName' => 'Смирнов', 'FirstName' => 'Иван', 'Otdel' => 3, 'Date_R' => '1996-02-10', 'Dolzn' => 'Экспедитор'],
            ['id' => 110, 'LastName' => 'Ефимов', 'FirstName' => 'Анатолий', 'Otdel' => 3, 'Date_R' => '1990-02-09', 'Dolzn' => 'Охранник'],
            ['id' => 111, 'LastName' => 'Егорова', 'FirstName' => 'Антонина', 'Otdel' => 4, 'Date_R' => '1992-11-02', 'Dolzn' => 'Начальник отдела'],
            ['id' => 112, 'LastName' => 'Панин', 'FirstName' => 'Андрей', 'Otdel' => 4, 'Date_R' => '1989-04-07', 'Dolzn' => 'Электрик'],
            ['id' => 113, 'LastName' => 'Новосёлов', 'FirstName' => 'Ефим', 'Otdel' => 4, 'Date_R' => '1989-10-22', 'Dolzn' => 'Электрик'],
            ['id' => 114, 'LastName' => 'Краснов', 'FirstName' => 'Михаил', 'Otdel' => 4, 'Date_R' => '1986-06-09', 'Dolzn' => 'Старший техник'],
            ['id' => 115, 'LastName' => 'Смирнова', 'FirstName' => 'Екатерина', 'Otdel' => 4, 'Date_R' => '1994-07-08', 'Dolzn' => 'Инженер по ТБ'],
            ['id' => 116, 'LastName' => 'Песков', 'FirstName' => 'Роман', 'Otdel' => 4, 'Date_R' => '1995-01-06', 'Dolzn' => 'Инженер по ТБ'],
            ['id' => 117, 'LastName' => 'Камнев', 'FirstName' => 'Егор', 'Otdel' => 4, 'Date_R' => '1994-07-08', 'Dolzn' => 'Инженер по ТБ'],
            ['id' => 118, 'LastName' => 'Кружкин', 'FirstName' => 'Владимир', 'Otdel' => 4, 'Date_R' => '1995-01-06', 'Dolzn' => 'Инженер по ТБ'],
        ]);

        // Create the `zarpl` table
        DB::statement("
            CREATE TABLE IF NOT EXISTS `zarpl` (
                `idSotr` int(5) NOT NULL,
                `God` int(3) NOT NULL,
                `Month` int(2) NOT NULL,
                `Money` decimal(8,2) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // Insert data into the `zarpl` table
        DB::table('zarpl')->insert([
            ['idSotr' => 101, 'God' => 2018, 'Month' => 2, 'Money' => '19000.00'],
            ['idSotr' => 101, 'God' => 2018, 'Month' => 3, 'Money' => '20500.00'],
            ['idSotr' => 101, 'God' => 2018, 'Month' => 5, 'Money' => '21000.00'],
            ['idSotr' => 101, 'God' => 2018, 'Month' => 7, 'Money' => '20700.00'],
            ['idSotr' => 102, 'God' => 2018, 'Month' => 4, 'Money' => '17500.00'],
            ['idSotr' => 102, 'God' => 2018, 'Month' => 6, 'Money' => '20400.00'],
            ['idSotr' => 102, 'God' => 2018, 'Month' => 9, 'Money' => '19500.00'],
            ['idSotr' => 102, 'God' => 2018, 'Month' => 12, 'Money' => '22000.00'],
            ['idSotr' => 103, 'God' => 2018, 'Month' => 3, 'Money' => '17500.00'],
            ['idSotr' => 103, 'God' => 2018, 'Month' => 5, 'Money' => '19500.00'],
            ['idSotr' => 103, 'God' => 2018, 'Month' => 7, 'Money' => '22000.00'],
            ['idSotr' => 103, 'God' => 2018, 'Month' => 9, 'Money' => '21700.00'],
            ['idSotr' => 103, 'God' => 2018, 'Month' => 11, 'Money' => '19800.00'],
            ['idSotr' => 104, 'God' => 2018, 'Month' => 2, 'Money' => '18500.00'],
            ['idSotr' => 104, 'God' => 2018, 'Month' => 4, 'Money' => '19300.00'],
            ['idSotr' => 104, 'God' => 2018, 'Month' => 6, 'Money' => '21400.00'],
            ['idSotr' => 104, 'God' => 2018, 'Month' => 8, 'Money' => '20500.00'],
            ['idSotr' => 104, 'God' => 2018, 'Month' => 11, 'Money' => '21200.00'],
            ['idSotr' => 105, 'God' => 2018, 'Month' => 1, 'Money' => '23000.00'],
            ['idSotr' => 105, 'God' => 2018, 'Month' => 4, 'Money' => '24000.00'],
            ['idSotr' => 105, 'God' => 2018, 'Month' => 6, 'Money' => '24500.00'],
            ['idSotr' => 105, 'God' => 2018, 'Month' => 8, 'Money' => '23700.00'],
            ['idSotr' => 105, 'God' => 2018, 'Month' => 10, 'Money' => '23800.00'],
            ['idSotr' => 105, 'God' => 2018, 'Month' => 12, 'Money' => '25300.00'],
            ['idSotr' => 106, 'God' => 2018, 'Month' => 1, 'Money' => '18900.00'],
            ['idSotr' => 106, 'God' => 2018, 'Month' => 3, 'Money' => '21100.00'],
            ['idSotr' => 106, 'God' => 2018, 'Month' => 4, 'Money' => '21400.00'],
            ['idSotr' => 106, 'God' => 2018, 'Month' => 5, 'Money' => '22300.00'],
            ['idSotr' => 106, 'God' => 2018, 'Month' => 6, 'Money' => '21800.00'],
            ['idSotr' => 106, 'God' => 2018, 'Month' => 7, 'Money' => '26000.00'],
            ['idSotr' => 106, 'God' => 2018, 'Month' => 9, 'Money' => '23600.00'],
            ['idSotr' => 106, 'God' => 2018, 'Month' => 11, 'Money' => '22100.00'],
            ['idSotr' => 107, 'God' => 2018, 'Month' => 3, 'Money' => '24300.00'],
            ['idSotr' => 107, 'God' => 2018, 'Month' => 4, 'Money' => '19700.00'],
            ['idSotr' => 108, 'God' => 2018, 'Month' => 1, 'Money' => '22100.00'],
            ['idSotr' => 108, 'God' => 2018, 'Month' => 7, 'Money' => '28600.00'],
            ['idSotr' => 109, 'God' => 2018, 'Month' => 1, 'Money' => '28100.00'],
            ['idSotr' => 109, 'God' => 2018, 'Month' => 2, 'Money' => '25300.00'],
            ['idSotr' => 109, 'God' => 2018, 'Month' => 4, 'Money' => '25000.00'],
            ['idSotr' => 109, 'God' => 2018, 'Month' => 5, 'Money' => '26000.00'],
            ['idSotr' => 109, 'God' => 2018, 'Month' => 8, 'Money' => '22800.00'],
            ['idSotr' => 110, 'God' => 2018, 'Month' => 1, 'Money' => '29000.00'],
            ['idSotr' => 110, 'God' => 2018, 'Month' => 3, 'Money' => '27800.00'],
            ['idSotr' => 110, 'God' => 2018, 'Month' => 4, 'Money' => '26400.00'],
            ['idSotr' => 110, 'God' => 2018, 'Month' => 6, 'Money' => '29100.00'],
            ['idSotr' => 110, 'God' => 2018, 'Month' => 7, 'Money' => '28200.00'],
            ['idSotr' => 111, 'God' => 2018, 'Month' => 5, 'Money' => '28600.00'],
            ['idSotr' => 111, 'God' => 2018, 'Month' => 7, 'Money' => '25800.00'],
            ['idSotr' => 111, 'God' => 2018, 'Month' => 10, 'Money' => '31200.00'],
            ['idSotr' => 111, 'God' => 2018, 'Month' => 12, 'Money' => '21400.00'],
            ['idSotr' => 112, 'God' => 2018, 'Month' => 2, 'Money' => '25800.00'],
            ['idSotr' => 112, 'God' => 2018, 'Month' => 3, 'Money' => '31000.00'],
            ['idSotr' => 112, 'God' => 2018, 'Month' => 4, 'Money' => '22300.00'],
            ['idSotr' => 112, 'God' => 2018, 'Month' => 6, 'Money' => '19300.00'],
            ['idSotr' => 112, 'God' => 2018, 'Month' => 8, 'Money' => '23850.00'],
            ['idSotr' => 112, 'God' => 2018, 'Month' => 9, 'Money' => '28600.00'],
            ['idSotr' => 113, 'God' => 2018, 'Month' => 7, 'Money' => '29800.00'],
            ['idSotr' => 113, 'God' => 2018, 'Month' => 8, 'Money' => '31300.00'],
            ['idSotr' => 113, 'God' => 2018, 'Month' => 10, 'Money' => '28700.00'],
            ['idSotr' => 113, 'God' => 2018, 'Month' => 11, 'Money' => '29150.00'],
            ['idSotr' => 114, 'God' => 2018, 'Month' => 1, 'Money' => '19400.00'],
            ['idSotr' => 114, 'God' => 2018, 'Month' => 2, 'Money' => '22350.00'],
            ['idSotr' => 114, 'God' => 2018, 'Month' => 4, 'Money' => '31740.00'],
            ['idSotr' => 114, 'God' => 2018, 'Month' => 6, 'Money' => '30140.00'],
            ['idSotr' => 114, 'God' => 2018, 'Month' => 8, 'Money' => '22900.00'],
            ['idSotr' => 114, 'God' => 2018, 'Month' => 9, 'Money' => '28960.00'],
            ['idSotr' => 114, 'God' => 2018, 'Month' => 10, 'Money' => '23400.00'],
            ['idSotr' => 114, 'God' => 2018, 'Month' => 12, 'Money' => '25000.00'],
            ['idSotr' => 115, 'God' => 2018, 'Month' => 3, 'Money' => '19800.00'],
            ['idSotr' => 115, 'God' => 2018, 'Month' => 5, 'Money' => '17800.00'],
            ['idSotr' => 116, 'God' => 2018, 'Month' => 5, 'Money' => '23800.00'],
            ['idSotr' => 116, 'God' => 2018, 'Month' => 7, 'Money' => '27000.00'],
            ['idSotr' => 116, 'God' => 2018, 'Month' => 8, 'Money' => '29800.00'],
            ['idSotr' => 116, 'God' => 2018, 'Month' => 9, 'Money' => '31200.00'],
            ['idSotr' => 103, 'God' => 2019, 'Month' => 2, 'Money' => '34800.00'],
            ['idSotr' => 103, 'God' => 2019, 'Month' => 3, 'Money' => '21700.00'],
            ['idSotr' => 103, 'God' => 2019, 'Month' => 5, 'Money' => '23450.00'],
            ['idSotr' => 103, 'God' => 2019, 'Month' => 7, 'Money' => '28700.00'],
            ['idSotr' => 105, 'God' => 2019, 'Month' => 1, 'Money' => '19700.00'],
            ['idSotr' => 105, 'God' => 2019, 'Month' => 2, 'Money' => '21300.00'],
            ['idSotr' => 105, 'God' => 2019, 'Month' => 3, 'Money' => '28150.00'],
            ['idSotr' => 105, 'God' => 2019, 'Month' => 5, 'Money' => '21430.00'],
        ]);
    }
}