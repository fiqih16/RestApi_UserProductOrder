<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_product');
            $table->integer('harga_product');
            $table->string('des_product');
            $table->integer('stock_product')->unsigned()->default(0);
            $table->text('gambar_product')->unique();
            $table->foreignId('category_id')->constrained();
            $table->enum('status', ['public', 'private'])->default('public');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
