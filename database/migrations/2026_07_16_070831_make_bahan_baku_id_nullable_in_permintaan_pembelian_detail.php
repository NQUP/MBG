<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permintaan_pembelian_detail', function (Blueprint $table) {
            $table->dropForeign(['bahan_baku_id']);
            $table->unsignedBigInteger('bahan_baku_id')->nullable()->change();
            $table->foreign('bahan_baku_id')->references('id')->on('bahan_baku')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('permintaan_pembelian_detail', function (Blueprint $table) {
            $table->dropForeign(['bahan_baku_id']);
            $table->unsignedBigInteger('bahan_baku_id')->nullable(false)->change();
            $table->foreign('bahan_baku_id')->references('id')->on('bahan_baku')->restrictOnDelete();
        });
    }
};