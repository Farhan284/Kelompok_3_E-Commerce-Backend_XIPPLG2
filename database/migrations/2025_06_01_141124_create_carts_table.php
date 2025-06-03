<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);

            // ✅ BENAR - Referensi ke kolom 'id' di tabel users
            $table->foreignId('user_id')
                ->constrained('users') // Tidak perlu specify 'id'
                ->onDelete('cascade');

            // ✅ BENAR - Referensi ke kolom 'id' di tabel produk  
            $table->foreignId('produk_id')
                ->constrained('produk') // Sesuai nama tabel Anda
                ->onDelete('cascade');
                
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
