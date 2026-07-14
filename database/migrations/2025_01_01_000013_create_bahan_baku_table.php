<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bahan')->unique();
            $table->string('nama_bahan');
            $table->foreignId('kategori_id')->constrained('kategori_bahan')->restrictOnDelete();
            $table->foreignId('satuan_id')->constrained('satuan')->restrictOnDelete();
            $table->decimal('stok_saat_ini', 12, 2)->default(0);
            $table->decimal('stok_minimum', 12, 2)->default(0);
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_baku');
    }
};
