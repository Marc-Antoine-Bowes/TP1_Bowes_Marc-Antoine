<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
   use HasFactory;
   protected $fillable = [
    'rating',
    'comment',
    'user_id',
    'rental_id'
   ];

   public function users(){
    return $this->belongsTo('App/Models/User');
   }

    public function rentals(){
    return $this->belongsTo('App/Models/Rental');
   }
}
