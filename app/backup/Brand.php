<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    //
    protected $fillable = ['brand_name'];

    protected $table = 'brands';

    public function product_categories()
    {
    	return $this->belongTo('App\Product_category');
    }

    public function product_class()
    {
    	return $this->hasMany('App\Product_class');
    }
}
