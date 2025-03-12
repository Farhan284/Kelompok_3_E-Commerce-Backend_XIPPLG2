<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('alamat');
            $table->string('nomor_telepon');
            $table->timestamp('tanggal_daftar')->useCurrent();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pelanggan');
    }
};
