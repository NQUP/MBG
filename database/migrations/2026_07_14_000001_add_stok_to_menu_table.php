<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->decimal('stok_saat_ini', 12, 2)->default(0)->after('deskripsi');
            $table->decimal('stok_minimum', 12, 2)->default(0)->after('stok_saat_ini');
            $table->foreignId('satuan_id')->nullable()->after('stok_minimum')->constrained('satuan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->dropForeign(['satuan_id']);
            $table->dropColumn(['stok_saat_ini', 'stok_minimum', 'satuan_id']);
        });
    }
};
