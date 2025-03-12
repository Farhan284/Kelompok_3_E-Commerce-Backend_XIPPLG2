<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_penjualan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pesanan');
            $table->unsignedBigInteger('id_pembayaran');
            $table->string('periode');
            $table->decimal('total_penjualan', 10, 2);
            $table->decimal('total_pendapatan', 10, 2);
            $table->date('tanggal_laporan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_penjualan');
    }
};

