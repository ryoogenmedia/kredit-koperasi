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
            $table->foreignId('nasabah_id')->nullable();
            $table->string('amount')->nullable();
            $table->string('interest')->nullable();
            $table->string('date')->nullable();
            $table->string('installments')->nullable();
            $table->string('amount_installments')->nullable();
            $table->string('status_akad')->nullable()->default('belum di berikan');
            $table->string('installments_type')->nullable()->default('bulan');
            $table->boolean('confirmation_nasabah')->nullable();
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
