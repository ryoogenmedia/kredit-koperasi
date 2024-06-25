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
            $table->foreignId('pinjaman_id')->nullable();
            $table->foreignId('nasabah_id')->nullable();
            $table->string('date_installments')->nullable();
            $table->string('installments_to')->nullable();
            $table->string('remaining_installments')->nullable();
            $table->string('remaining_loan')->nullable();
            $table->string('proof')->nullable();
            $table->boolean('confirmation_repayment')->default(false);
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
