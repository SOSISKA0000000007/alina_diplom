<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Обновляем существующие записи, где quantity = null, на 1
        DB::table('rentals')->whereNull('quantity')->update(['quantity' => 1]);

        // Изменяем схему: делаем quantity not null с default 1
        Schema::table('rentals', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->integer('quantity')->nullable()->default(null)->change();
        });
    }
};
