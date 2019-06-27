<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('product_name');
            $table->string('product_type')->nullable();
            $table->string('product_model')->nullable();
            $table->string('product_quantity');
            $table->string('unit_price');
            $table->string('total_price');
            $table->string('first_photo');
            $table->string('other_photos');
            $table->string('description', 5000);
            $table->integer('status');
            $table->string('pstatus')->nullable();
            $table->integer('category_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('location_id')->unsigned();
            //$table->integer('type_id')->unsigned();
            $table->timestamps();

        //----Foreign Key---
             $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('restrict')->onUpdate('cascade');

             $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

             $table->foreign('location_id')->references('id')->on('locations')->onDelete('restrict')->onUpdate('cascade');
            // $table->foreign('type_id')->references('id')->on('product_types')->onDelete('restrict')->onUpdate('cascade');

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
