<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = [
        'name'
    ];

    public function equipements()
    {
        return $this->hasMany('App/Models/Equipment');
    }
}
