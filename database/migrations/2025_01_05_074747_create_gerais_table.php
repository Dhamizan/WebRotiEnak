<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('gerais', function (Blueprint $table) {
            $table->id();
            $table->string('nama_gerai');
            $table->string('alamat');
            $table->unsignedBigInteger('pegawai_id')->nullable(); // nanti jadi kepala toko
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('gerais');
    }
};
