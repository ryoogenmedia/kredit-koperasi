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
            $table->foreignId('angsuran_id');
            $table->foreignId('detail_pinjaman_id');
            $table->string('amount_installments');
            $table->text('note');
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
