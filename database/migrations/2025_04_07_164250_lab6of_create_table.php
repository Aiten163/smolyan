<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEltovaryTable extends Migration
{
    public function up()
    {
        Schema::create('eltovary', function (Blueprint $table) {
            $table->id();
            $table->string('tname');
            $table->string('kod')->unique();
            $table->date('date_p');
            $table->text('opisanie')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('kol_vo');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eltovary');
    }
}
