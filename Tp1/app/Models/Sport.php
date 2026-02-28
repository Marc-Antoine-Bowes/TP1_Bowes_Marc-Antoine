<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $fillable = [
        'name'
    ];

    public function equipment(){
        return $this->hasMany('App/Models/Equipment');
   }
}
