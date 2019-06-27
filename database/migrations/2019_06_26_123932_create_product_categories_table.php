<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Product_category;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_name');
            $table->timestamps();

        });

        $category = ['Car', 'House', 'Electronics', 'Furnitures'];
        // foreach ($roles as $role) {
        //     Role::create(['role_name' => $role]);
        // }
        for ($i=0; $i < 4; $i++) { 
            Product_category::create(['category_name' => $category[$i]]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
}
