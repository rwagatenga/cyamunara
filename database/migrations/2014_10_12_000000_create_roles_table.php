<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

//---Call Role Model---
use App\Role;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role_name');
            $table->timestamps();
        });

        //----Insert Data----
        $roles = ['Admin', 'Seller', 'Buyer'];
        // foreach ($roles as $role) {
        //     Role::create(['role_name' => $role]);
        // }
        for ($i=0; $i < 3; $i++) { 
            Role::create(['role_name' => $roles[$i]]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
