<?php

namespace Database\Seeders;

use Database\Factories\WorkerFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkerFactory::new()->createMany(15)->each(function ($worker) {});
    }
}
