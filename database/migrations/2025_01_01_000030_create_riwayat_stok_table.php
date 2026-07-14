<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->restrictOnDelete();
            $table->date('tanggal');
            $table->enum('jenis_transaksi', ['masuk', 'keluar_produksi', 'keluar_opname', 'penyesuaian']);
            $table->unsignedBigInteger('referensi_id');
            $table->string('referensi_tabel', 50);
            $table->decimal('jumlah', 12, 3);
            $table->decimal('stok_sebelum', 12, 2);
            $table->decimal('stok_sesudah', 12, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_stok');
    }
};
