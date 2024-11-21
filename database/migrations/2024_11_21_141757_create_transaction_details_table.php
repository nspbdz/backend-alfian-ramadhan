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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id'); // ID transaksi
            $table->unsignedBigInteger('product_id'); // ID produk
            $table->integer('quantity'); // Jumlah barang
            $table->decimal('price', 10, 2); // Harga produk
            $table->timestamps();

            // Relasi ke tabel transactions
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            // Relasi ke tabel products
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_details');
    }
};