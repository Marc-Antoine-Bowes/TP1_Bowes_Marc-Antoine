<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = [
        'name', 'description', 'daily_price', 'category_Id'
    ];

    public function categories(){
        return $this->belongsTo('App/Models/Categorie');
    }

    public function sports()
    {
        return $this->hasMany('App/Models/Sport');
    }
}
