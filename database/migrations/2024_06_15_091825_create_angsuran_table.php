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
        Schema::create('angsuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->nullable();
            $table->foreignId('id_pinjaman')->nullable();
            $table->date('tgl_angsuran')->nullable();
            $table->integer('angsur_ke')->nullable();
            $table->integer('sisa_angsur')->nullable();
            $table->integer('sisa_pinjam')->nullable();
            $table->string('bukti_angsur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsuran');
    }
};
