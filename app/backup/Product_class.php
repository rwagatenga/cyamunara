<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_class extends Model
{
    //
    protected $fillable = ['class_name'];

    protected $table = 'Product_classes';

    public function brands()
    {
    	return $this->belongTo('App\Brand');
    }

    public function product_type()
    {
    	return $this->hasMany('App\Product_type');
    }
}
