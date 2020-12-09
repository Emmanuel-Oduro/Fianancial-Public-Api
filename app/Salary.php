<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{

    protected $fillable = [
        'user_id', 'basic_salary_amount', 'paid_amount','paid_month','balance','paid_date'
     ];

    public  function user()
         {
             return $this->belongsTo(User::class);
         }
}
