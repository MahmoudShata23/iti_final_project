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
            $table->string('brand')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('sub_category_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('short_desc');
            $table->string('product_size')->nullable();
            $table->string('color');
            $table->string('product_qty');
            $table->string('selling_price');
            $table->string('discount_price')->nullable();
            $table->string('tags')->nullable();
            $table->string('image')->nullable();
            $table->integer('status')->default(0);
            $table->float('avgRate')->default(0);
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
