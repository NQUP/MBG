<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->foreignId('permintaan_pembelian_id')
                  ->nullable()
                  ->constrained('permintaan_pembelian')
                  ->nullOnDelete()
                  ->after('supplier_id');
        });
    }

    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropForeign(['permintaan_pembelian_id']);
            $table->dropColumn('permintaan_pembelian_id');
        });
    }
};