<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produksi_harian', function (Blueprint $table) {
            $table->dropForeign(['menu_minuman_id']);
            $table->dropForeign(['menu_buah_id']);
        });

        Schema::table('produksi_harian', function (Blueprint $table) {
            $table->dropColumn(['menu_minuman_id', 'menu_buah_id']);
        });

        Schema::table('produksi_harian', function (Blueprint $table) {
            $table->foreignId('menu_minuman_id')->nullable()->after('menu_makanan_id')
                  ->constrained('bahan_baku')->restrictOnDelete();
            $table->foreignId('menu_buah_id')->nullable()->after('menu_minuman_id')
                  ->constrained('bahan_baku')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('produksi_harian', function (Blueprint $table) {
            $table->dropForeign(['menu_minuman_id']);
            $table->dropForeign(['menu_buah_id']);
        });

        Schema::table('produksi_harian', function (Blueprint $table) {
            $table->dropColumn(['menu_minuman_id', 'menu_buah_id']);
        });

        Schema::table('produksi_harian', function (Blueprint $table) {
            $table->foreignId('menu_minuman_id')->after('menu_makanan_id')
                  ->constrained('menu')->restrictOnDelete();
            $table->foreignId('menu_buah_id')->after('menu_minuman_id')
                  ->constrained('menu')->restrictOnDelete();
        });
    }
};