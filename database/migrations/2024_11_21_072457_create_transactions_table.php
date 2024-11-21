<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID pengguna
            $table->string('address'); // Alamat pengiriman
            $table->string('payment_method'); // Metode pembayaran
            $table->decimal('total_price', 10, 2); // Total harga sebelum diskon/ongkir
            $table->decimal('final_price', 10, 2); // Harga akhir setelah diskon/ongkir
            $table->decimal('shipping_fee', 10, 2)->default(0); // Biaya pengiriman
            $table->decimal('discount', 10, 2)->default(0); // Diskon
            $table->timestamps();

            // Relasi ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
