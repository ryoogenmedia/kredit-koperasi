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
         Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->nullable();
            $table->integer('jumlah_pinjaman')->nullable();
            $table->integer('bunga')->nullable();
            $table->date('tgl_pinjaman')->nullable();
            $table->integer('jumlah_angsur')->nullable();
            $table->integer('total_angsur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
