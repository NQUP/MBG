<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_permintaan')->unique();
            $table->date('tanggal');
            $table->enum('status', ['Draft', 'Diajukan', 'Disetujui', 'Selesai', 'Ditolak'])->default('Draft');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('permintaan_pembelian_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_pembelian_id')->constrained('permintaan_pembelian')->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->restrictOnDelete();
            $table->decimal('jumlah', 12, 2);
            $table->foreignId('satuan_id')->constrained('satuan')->restrictOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_pembelian_detail');
        Schema::dropIfExists('permintaan_pembelian');
    }
};