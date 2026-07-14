<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opname', function (Blueprint $table) {
            $table->id();
            $table->string('kode_opname')->unique();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->date('tanggal_opname');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['draft', 'selesai'])->default('draft');
            $table->timestamps();
        });

        Schema::create('detail_stock_opname', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained('stock_opname')->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->restrictOnDelete();
            $table->decimal('stok_sistem', 12, 2);
            $table->decimal('stok_fisik', 12, 2)->nullable();
            $table->decimal('selisih', 12, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_stock_opname');
        Schema::dropIfExists('stock_opname');
    }
};
