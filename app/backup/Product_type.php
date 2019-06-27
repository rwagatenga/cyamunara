<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_type extends Model
{
    //
    protected $fillable = ['model_name', ];

    protected $table = 'product_types';

    public function product_class()
    {
    	return $this->belongTo('App\Product_class');
    }

    public function products()
    {
    	return $this->hasMany('App\Product');
    }
}
