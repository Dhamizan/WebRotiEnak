<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('gerais', function (Blueprint $table) {
            $table->id();
            $table->string('gerai');
            $table->string('alamat');
            $table->decimal('lat',10,8)->nullable();
            $table->decimal('long',11,8)->nullable();
            $table->integer('jumlah_pegawai')->nullable();
            $table->string('jenis_gerai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('gerais');
    }
};
