<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    
    // database/migrations/xxxx_xx_xx_create_pengguna_table.php
    {

Schema::create('pengguna', function (Blueprint $table) {
    $table->id();
    $table->string('nama_Pengguna');
    $table->string('email')->unique();
    $table->string('password');
    $table->string('alamat')->nullable();
    $table->string('nomor_telepon')->nullable();
    $table->timestamp('tanggal_daftar')->useCurrent();
    $table->enum('role', ['pembeli', 'penjual'])->default('pembeli');
    $table->timestamps();
});
    }

    
    public function down()
    {
        Schema::dropIfExists('Pengguna');
    }
};
