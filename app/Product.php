<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
        'name', 'price', 'slug','product_status','image'
     ];

     public  function user()
         {
             return $this->belongsTo(User::class);
         }

}
