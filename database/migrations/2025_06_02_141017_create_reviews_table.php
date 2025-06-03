<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // ✅ BENAR - Referensi ke kolom 'id' di tabel users
            $table->foreignId('user_id')
                ->constrained('users') // Default referensi ke 'id'
                ->onDelete('cascade');

            // ✅ BENAR - Referensi ke kolom 'id' di tabel produk
            $table->foreignId('produk_id')
                ->constrained('produk') // Sesuai nama tabel Anda
                ->onDelete('cascade');

            $table->integer('rating');
            $table->text('review');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
