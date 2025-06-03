<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
             $table->unsignedBigInteger('cart_id')->nullable();
            $table->dateTime('tanggal_pesanan');
            $table->string('status_pesanan');
            $table->decimal('total_harga', 10, 2);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pesanan');
    }
};

