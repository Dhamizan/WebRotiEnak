<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('penggunas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('notelp');
            $table->string('alamat');
            $table->unsignedBigInteger('gerai')->nullable();
            $table->foreign('gerai')->references('id')->on('gerais')->onDelete('cascade');
            $table->string('kata_sandi');
            $table->integer('peran');
            $table->string('jenis_kelamin');
            $table->timestamp('verifikasi_email')->nullable();
            $table->integer('id_sidik_jari')->nullable();
            $table->string('gambar_profil')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('penggunas');
    }
};
