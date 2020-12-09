<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $fillable = [
        'salary_id', 'user_id', 'bonus_amount','bonus_month'
     ];

    public  function user()
         {
             return $this->belongsTo(User::class);
         }
}
