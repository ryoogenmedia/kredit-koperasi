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
        Schema::create('detail_angsuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_detail_pinjaman');
            $table->integer('jumlah_angsur');
            $table->string('keterangan_angsur');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_angsuran');
    }
};
