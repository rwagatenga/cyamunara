<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = 'products';

    public function product_types()
    {
    	return $this->belongToMany('App\Product_type');
    }
}
