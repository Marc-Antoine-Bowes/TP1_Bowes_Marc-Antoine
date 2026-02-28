<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Rental extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_date', 'end_date', 'total_price',
        'user_id', 'equipement_id'
    ];

    public function users(){
        return $this->belongsTo('App/Models/User');
    }

    public function equipements(){
        return $this->belongsTo('App/Models/Equipment');
    }

    public function reviews(){
        return $this->hasMany('reviews');
    }
}
