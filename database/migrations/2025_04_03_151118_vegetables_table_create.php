<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Таблица категорий
        Schema::create('category', function (Blueprint $table) {
            $table->integer('CategId', false, true)->default(0);
            $table->char('CategName', 30);
            $table->primary('CategId');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';  // Changed to utf8mb4
            $table->collation = 'utf8mb4_unicode_ci';  // Matching collation
        });

        // Таблица подкатегорий
        Schema::create('subcategory', function (Blueprint $table) {
            $table->integer('CategId', false, true)->default(0);
            $table->integer('SubCategId', false, true)->default(0);
            $table->char('SubCategName', 30);
            $table->primary(['CategId', 'SubCategId']);
            $table->foreign('CategId')->references('CategId')->on('category');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';  // Changed to utf8mb4
            $table->collation = 'utf8mb4_unicode_ci';  // Matching collation
        });

        // Таблица наименований продуктов
        Schema::create('product_names', function (Blueprint $table) {
            $table->integer('CategId', false, true)->default(0);
            $table->integer('SubCategId', false, true)->default(0);
            $table->integer('ProductId', false, true)->default(0);
            $table->char('ProductName', 30);
            $table->primary(['CategId', 'SubCategId', 'ProductId']);
            $table->foreign(['CategId', 'SubCategId'])
                ->references(['CategId', 'SubCategId'])
                ->on('subcategory');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';  // Changed to utf8mb4
            $table->collation = 'utf8mb4_unicode_ci';  // Matching collation
        });

        // Таблица поставок продуктов
        Schema::create('product_deliveries', function (Blueprint $table) {
            $table->integer('CategId', false, true)->default(0);
            $table->integer('SubCategId', false, true)->default(0);
            $table->integer('ProductId', false, true)->default(0);
            $table->decimal('Price', 6, 2)->default(0.00);
            $table->integer('Quantity', false, true)->default(0);
            $table->date('DeliveryDate');

            $table->index(['CategId', 'SubCategId', 'ProductId', 'DeliveryDate'], 'CategId');

            $table->foreign(['CategId', 'SubCategId', 'ProductId'])
                ->references(['CategId', 'SubCategId', 'ProductId'])
                ->on('product_names');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';  // Changed to utf8mb4
            $table->collation = 'utf8mb4_unicode_ci';  // Matching collation
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_deliveries');
        Schema::dropIfExists('product_names');
        Schema::dropIfExists('subcategory');
        Schema::dropIfExists('category');
    }
};