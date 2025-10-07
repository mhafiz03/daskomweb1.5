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
        Schema::create('soal_comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('soal_id');
            $table->string('soal_type'); // e.g. 'mandiri', 'tp', 'tk', 'ta', 'jurnal', 'fitb'
            $table->unsignedBigInteger('praktikan_id');
            $table->string('comment', 2000);

            $table->foreign('praktikan_id')->references('id')->on('praktikans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_comments');
    }
};
