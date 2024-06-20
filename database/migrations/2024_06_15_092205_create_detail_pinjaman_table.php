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
          Schema::create('detail_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_id')->nullable();
            $table->string('date_submission_loan')->nullable();
            $table->string('date_acc_loan')->nullable();
            $table->string('remaining_loan')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pinjaman');
    }
};
