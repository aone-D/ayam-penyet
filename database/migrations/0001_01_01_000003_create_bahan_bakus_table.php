<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('satuan_beli');
            $table->string('satuan_pakai');
            $table->decimal('konversi', 10, 2);
            $table->decimal('harga_beli', 12, 2);
            $table->decimal('harga_per_satuan_pakai', 12, 4);
            $table->decimal('stok_saat_ini', 12, 2);
            $table->decimal('stok_minimum', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_bakus');
    }
};
