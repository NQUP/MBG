<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->date('tanggal_masuk');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('detail_barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_masuk_id')->constrained('barang_masuk')->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->restrictOnDelete();
            $table->decimal('jumlah', 12, 2);
            $table->foreignId('satuan_id')->constrained('satuan')->restrictOnDelete();
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_barang_masuk');
        Schema::dropIfExists('barang_masuk');
    }
};
