<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produksi_harian', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_produksi');
            $table->foreignId('menu_makanan_id')->constrained('menu')->restrictOnDelete();
            $table->foreignId('menu_minuman_id')->constrained('menu')->restrictOnDelete();
            $table->foreignId('menu_buah_id')->constrained('menu')->restrictOnDelete();
            $table->integer('jumlah_siswa');
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->enum('status', ['draft', 'dikonfirmasi'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('detail_produksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_harian_id')->constrained('produksi_harian')->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->restrictOnDelete();
            $table->decimal('jumlah_dibutuhkan', 12, 3);
            $table->foreignId('satuan_id')->constrained('satuan')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_produksi');
        Schema::dropIfExists('produksi_harian');
    }
};
