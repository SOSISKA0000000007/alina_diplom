<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rental_products', function (Blueprint $table) {
            $table->json('images')->nullable()->after('price');
            // Удаляем старое поле image, если оно больше не нужно
            $table->dropColumn('image');
        });
    }

    public function down(): void
    {
        Schema::table('rental_products', function (Blueprint $table) {
            $table->dropColumn('images');
            // Восстанавливаем поле image, если нужно откатить изменения
            $table->string('image')->nullable()->after('price');
        });
    }
};
