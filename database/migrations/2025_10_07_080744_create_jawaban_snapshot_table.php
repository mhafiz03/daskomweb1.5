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
        Schema::create('jawaban_snapshot', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('praktikan_id');
            $table->unsignedBigInteger('soal_id');
            $table->unsignedBigInteger('modul_id');
            $table->json('jawaban');
            $table->string('tipe_soal', 16);

            $table->foreign('praktikan_id')->references('id')->on('praktikans')->onDelete('cascade');
            $table->foreign('modul_id')->references('id')->on('moduls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_snapshot');
    }
};
