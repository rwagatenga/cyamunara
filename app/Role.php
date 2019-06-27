<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    //
    protected $table = 'roles';
    
    protected $fillable = [
        'role_name',
    ];
    
    public function users()
    {
    	return $this->hasMany('App\User');
    }

}
