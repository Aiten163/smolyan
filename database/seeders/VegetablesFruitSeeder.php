<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VegetablesFruitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Очищаем таблицы в правильном порядке из-за foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_deliveries')->truncate();
        DB::table('product_names')->truncate();
        DB::table('subcategory')->truncate();
        DB::table('category')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Заполняем таблицу категорий
        DB::table('category')->insert([
            ['CategId' => 1, 'CategName' => 'Овощи'],
            ['CategId' => 2, 'CategName' => 'Фрукты'],
        ]);

        // Заполняем таблицу подкатегорий
        DB::table('subcategory')->insert([
            // Овощи
            ['CategId' => 1, 'SubCategId' => 1, 'SubCategName' => 'Клубнеплоды'],
            ['CategId' => 1, 'SubCategId' => 2, 'SubCategName' => 'Корнеплоды'],
            ['CategId' => 1, 'SubCategId' => 3, 'SubCategName' => 'Корневищные'],
            ['CategId' => 1, 'SubCategId' => 4, 'SubCategName' => 'Капустные'],
            ['CategId' => 1, 'SubCategId' => 5, 'SubCategName' => 'Листовые'],
            ['CategId' => 1, 'SubCategId' => 6, 'SubCategName' => 'Пряные'],
            ['CategId' => 1, 'SubCategId' => 7, 'SubCategName' => 'Луковичные'],
            ['CategId' => 1, 'SubCategId' => 8, 'SubCategName' => 'Томатные'],
            ['CategId' => 1, 'SubCategId' => 9, 'SubCategName' => 'Тыквенные'],
            ['CategId' => 1, 'SubCategId' => 10, 'SubCategName' => 'Бобовые'],
            ['CategId' => 1, 'SubCategId' => 11, 'SubCategName' => 'Злаковые'],
            ['CategId' => 1, 'SubCategId' => 12, 'SubCategName' => 'Десертные'],

            // Фрукты
            ['CategId' => 2, 'SubCategId' => 1, 'SubCategName' => 'Фрукты'],
            ['CategId' => 2, 'SubCategId' => 2, 'SubCategName' => 'Ягоды'],
            ['CategId' => 2, 'SubCategId' => 3, 'SubCategName' => 'Экзотические фрукты'],
            ['CategId' => 2, 'SubCategId' => 4, 'SubCategName' => 'Сухофрукты'],
        ]);

        // Заполняем таблицу наименований продуктов
        DB::table('product_names')->insert([
            // Овощи - Клубнеплоды
            ['CategId' => 1, 'SubCategId' => 1, 'ProductId' => 1, 'ProductName' => 'Картофель'],
            ['CategId' => 1, 'SubCategId' => 1, 'ProductId' => 2, 'ProductName' => 'Топинамбур'],
            ['CategId' => 1, 'SubCategId' => 1, 'ProductId' => 3, 'ProductName' => 'Батат'],
            ['CategId' => 1, 'SubCategId' => 1, 'ProductId' => 4, 'ProductName' => 'Китайский артишок'],

            // Овощи - Корнеплоды
            ['CategId' => 1, 'SubCategId' => 2, 'ProductId' => 1, 'ProductName' => 'Свекла'],
            ['CategId' => 1, 'SubCategId' => 2, 'ProductId' => 2, 'ProductName' => 'Морковь'],
            ['CategId' => 1, 'SubCategId' => 2, 'ProductId' => 3, 'ProductName' => 'Редис'],
            ['CategId' => 1, 'SubCategId' => 2, 'ProductId' => 4, 'ProductName' => 'Редька'],

            // Фрукты
            ['CategId' => 2, 'SubCategId' => 1, 'ProductId' => 1, 'ProductName' => 'Абрикос'],
            ['CategId' => 2, 'SubCategId' => 1, 'ProductId' => 2, 'ProductName' => 'Айва'],
            ['CategId' => 2, 'SubCategId' => 1, 'ProductId' => 3, 'ProductName' => 'Апельсины'],
            ['CategId' => 2, 'SubCategId' => 1, 'ProductId' => 4, 'ProductName' => 'Арбуз'],

            // Ягоды
            ['CategId' => 2, 'SubCategId' => 2, 'ProductId' => 1, 'ProductName' => 'Виноград (красный, зеленый)'],
            ['CategId' => 2, 'SubCategId' => 2, 'ProductId' => 2, 'ProductName' => 'Виноград (мускатный)'],
            ['CategId' => 2, 'SubCategId' => 2, 'ProductId' => 3, 'ProductName' => 'Вишня'],
            ['CategId' => 2, 'SubCategId' => 2, 'ProductId' => 4, 'ProductName' => 'Клубника'],

            // Экзотические фрукты
            ['CategId' => 2, 'SubCategId' => 3, 'ProductId' => 1, 'ProductName' => 'Авокадо'],
            ['CategId' => 2, 'SubCategId' => 3, 'ProductId' => 2, 'ProductName' => 'Ананас'],
            ['CategId' => 2, 'SubCategId' => 3, 'ProductId' => 3, 'ProductName' => 'Антильский абрикос'],
            ['CategId' => 2, 'SubCategId' => 3, 'ProductId' => 4, 'ProductName' => 'Барбадосская вишня'],

            // Сухофрукты
            ['CategId' => 2, 'SubCategId' => 4, 'ProductId' => 1, 'ProductName' => 'Бананы'],
            ['CategId' => 2, 'SubCategId' => 4, 'ProductId' => 2, 'ProductName' => 'Изюм'],
            ['CategId' => 2, 'SubCategId' => 4, 'ProductId' => 3, 'ProductName' => 'Курага'],
            ['CategId' => 2, 'SubCategId' => 4, 'ProductId' => 4, 'ProductName' => 'Финики'],
        ]);

        // Заполняем таблицу поставок продуктов
        $deliveries = [
                ['CategId' =>1,'SubCategId' => 1,'ProductId' => 1,'Price' => '70.00', 'Quantity' =>1000, 'DeliveryDate' => '2021-09-05'],
                ['CategId' => 1,'SubCategId' => 1,'ProductId' =>1,'Price' => '70.00', 'Quantity' =>2000, 'DeliveryDate' => '2021-09-15'],
                ['CategId' => 1,'SubCategId' => 1,'ProductId' =>1,'Price' => '75.00', 'Quantity' =>1000, 'DeliveryDate' => '2021-10-05'],
                ['CategId' => 1,'SubCategId' => 1,'ProductId' =>1,'Price' => '75.00', 'Quantity' =>780, 'DeliveryDate' => '2021-10-15'],
                ['CategId' => 1,'SubCategId' => 1,'ProductId' =>3,'Price' => '200.00','Quantity' => 100, 'DeliveryDate' => '2021-09-10'],
                ['CategId' => 1,'SubCategId' => 1,'ProductId' =>3,'Price' => '200.00','Quantity' => 200, 'DeliveryDate' => '2021-09-20'],
                ['CategId' => 1,'SubCategId' => 1,'ProductId' =>3,'Price' => '100.00','Quantity' => 100, 'DeliveryDate' => '2021-10-10'],
                ['CategId' => 1,'SubCategId' => 1,'ProductId' =>3,'Price' => '122.00','Quantity' => 100, 'DeliveryDate' => '2021-10-20'],
                ['CategId' => 1,'SubCategId' => 2,'ProductId' =>1,'Price' => '80.00', 'Quantity' =>1100, 'DeliveryDate' => '2021-09-05'],
                ['CategId' => 1,'SubCategId' => 2,'ProductId' =>1,'Price' => '80.00', 'Quantity' =>1200, 'DeliveryDate' => '2021-09-15'],
                ['CategId' => 1,'SubCategId' => 2,'ProductId' =>1,'Price' => '90.00', 'Quantity' =>1300, 'DeliveryDate' => '2021-10-05'],
                ['CategId' => 1,'SubCategId' => 2,'ProductId' =>1,'Price' => '90.00', 'Quantity' =>1400, 'DeliveryDate' => '2021-10-25'],
                ['CategId' => 1,'SubCategId' => 2,'ProductId' =>2,'Price' => '75.00', 'Quantity' =>1300, 'DeliveryDate' => '2021-10-10'],
                ['CategId' => 1,'SubCategId' => 2,'ProductId' =>2,'Price' => '85.00', 'Quantity' =>1400, 'DeliveryDate' => '2021-11-05'],
                ['CategId' => 1,'SubCategId' => 2,'ProductId' =>3,'Price' => '105.00','Quantity' => 200, 'DeliveryDate' => '2021-08-05'],
                ['CategId' => 1,'SubCategId' => 2,'ProductId' =>3,'Price' => '105.00','Quantity' => 300, 'DeliveryDate' => '2021-08-20'],
                ['CategId' => 1,'SubCategId' => 2,'ProductId' =>3,'Price' => '110.00','Quantity' => 150, 'DeliveryDate' => '2021-09-03'],
                ['CategId' => 1,'SubCategId' => 2,'ProductId' =>3,'Price' => '110.00','Quantity' => 120, 'DeliveryDate' => '2021-09-13'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>1,'Price' => '250.00','Quantity' => 1000, 'DeliveryDate' => '2021-08-10'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>1,'Price' => '300.00','Quantity' => 1100, 'DeliveryDate' => '2021-08-15'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>1,'Price' => '300.00','Quantity' => 850, 'DeliveryDate' => '2021-08-20'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>1,'Price' => '320.00','Quantity' => 800, 'DeliveryDate' => '2021-08-23'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>3,'Price' => '90.00', 'Quantity' =>1100, 'DeliveryDate' => '2021-09-20'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>3,'Price' => '95.00', 'Quantity' =>1200, 'DeliveryDate' => '2021-09-26'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>3,'Price' => '95.00', 'Quantity' =>880, 'DeliveryDate' => '2021-10-11'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>3,'Price' => '95.00', 'Quantity' =>660, 'DeliveryDate' => '2021-10-18'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>3,'Price' => '100.00','Quantity' => 620, 'DeliveryDate' => '2021-11-10'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>3,'Price' => '100.00','Quantity' => 710, 'DeliveryDate' => '2021-11-18'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>4,'Price' => '80.00', 'Quantity' =>1400, 'DeliveryDate' => '2021-08-05'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>4,'Price' => '80.00', 'Quantity' =>1500, 'DeliveryDate' => '2021-08-15'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>4,'Price' => '70.00', 'Quantity' =>1050, 'DeliveryDate' => '2021-09-02'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>4,'Price' => '70.00', 'Quantity' =>1090, 'DeliveryDate' => '2021-09-15'],
                ['CategId' => 2,'SubCategId' => 2,'ProductId' =>1,'Price' => '210.00','Quantity' => 750, 'DeliveryDate' => '2021-09-05'],
                ['CategId' => 2,'SubCategId' => 2,'ProductId' =>1,'Price' => '210.00','Quantity' => 600, 'DeliveryDate' => '2021-09-15'],
                ['CategId' => 2,'SubCategId' => 2,'ProductId' =>1,'Price' => '230.00','Quantity' => 570, 'DeliveryDate' => '2021-10-03'],
                ['CategId' => 2,'SubCategId' => 2,'ProductId' =>1,'Price' => '230.00','Quantity' => 430, 'DeliveryDate' => '2021-10-22'],
                ['CategId' => 2,'SubCategId' => 4,'ProductId' =>1,'Price' => '70.00', 'Quantity' =>920, 'DeliveryDate' => '2021-10-10'],
                ['CategId' => 2,'SubCategId' => 4,'ProductId' =>1,'Price' => '75.00', 'Quantity' =>640, 'DeliveryDate' => '2021-10-24'],
                ['CategId' => 2,'SubCategId' => 4,'ProductId' =>1,'Price' => '80.00', 'Quantity' =>410, 'DeliveryDate' => '2021-11-10'],
                ['CategId' => 2,'SubCategId' => 4,'ProductId' =>1,'Price' => '80.00', 'Quantity' =>320, 'DeliveryDate' => '2021-11-21'],
                ['CategId' => 2,'SubCategId' => 4,'ProductId' =>4,'Price' => '180.00','Quantity' => 280, 'DeliveryDate' => '2021-11-10'],
                ['CategId' => 2,'SubCategId' => 4,'ProductId' =>4,'Price' => '190.00','Quantity' => 225, 'DeliveryDate' => '2021-11-15'],
                ['CategId' => 2,'SubCategId' => 4,'ProductId' =>4,'Price' => '205.00','Quantity' => 185, 'DeliveryDate' => '2021-12-10'],
                ['CategId' => 2,'SubCategId' => 4,'ProductId' =>4,'Price' => '215.00','Quantity' => 195, 'DeliveryDate' => '2021-12-20'],
                ['CategId' => 2,'SubCategId' => 3,'ProductId' =>1,'Price' => '420.00','Quantity' => 230, 'DeliveryDate' => '2022-01-06'],
                ['CategId' => 2,'SubCategId' => 3,'ProductId' =>2,'Price' => '650.00','Quantity' => 310, 'DeliveryDate' => '2022-01-09'],
                ['CategId' => 2,'SubCategId' => 3,'ProductId' =>1,'Price' => '460.00','Quantity' => 120, 'DeliveryDate' => '2022-02-10'],
                ['CategId' => 2,'SubCategId' => 3,'ProductId' =>2,'Price' => '670.00','Quantity' => 270, 'DeliveryDate' => '2022-02-14'],
                ['CategId' => 1,'SubCategId' => 1,'ProductId' =>1,'Price' => '80.00', 'Quantity' =>720, 'DeliveryDate' => '2022-01-16'],
                ['CategId' => 1,'SubCategId' => 1,'ProductId' =>1,'Price' => '82.00', 'Quantity' =>490, 'DeliveryDate' => '2022-01-19'],
                ['CategId' => 2,'SubCategId' => 1,'ProductId' =>3,'Price' => '100.00','Quantity' => 5987, 'DeliveryDate' => '2021-11-18'],
                ['CategId' => 1,'SubCategId' => 1,'ProductId' =>3,'Price' => '135.00','Quantity' => 210, 'DeliveryDate' => '2022-01-23'],
                ['CategId' => 1,'SubCategId' => 1,'ProductId' =>3,'Price' => '135.00','Quantity' => 320, 'DeliveryDate' => '2022-01-30'],
                ['CategId' => 1,'SubCategId' => 2,'ProductId' =>2,'Price' => '85.00', 'Quantity' =>230, 'DeliveryDate' => '2022-01-25'],
                ['CategId' => 1,'SubCategId' => 2,'ProductId' =>2,'Price' => '75.00', 'Quantity' =>340, 'DeliveryDate' => '2022-01-28']

        ];

        // Вставляем данные порциями для оптимизации
        foreach (array_chunk($deliveries, 50) as $chunk) {
            DB::table('product_deliveries')->insert($chunk);
        }
    }
}