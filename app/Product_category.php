<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_category extends Model
{
    //
    protected $fillable = ['category_name'];

    protected $table = 'Product_categories';

     public function brands()
    {
    	return $this->hasMany('App\Brand');
    }
}
