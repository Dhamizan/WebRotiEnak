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
        Schema::create('cutis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengguna')->constrained('penggunas')->onDelete('cascade');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->tinyInteger('status');
            $table->string('alasan');
            $table->string('dokumen_pendukung')->nullable();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cutis');
    }
};
