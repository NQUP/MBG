<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permintaan_pembelian_detail', function (Blueprint $table) {
            $table->foreignId('menu_id')->nullable()->after('bahan_baku_id')
                  ->constrained('menu')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('permintaan_pembelian_detail', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);
            $table->dropColumn('menu_id');
        });
    }
};