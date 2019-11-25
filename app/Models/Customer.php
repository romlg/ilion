<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $fillable =[
        'customer_id',
        'first_name',
        'last_name',
        'middle_name',
        'bod',
        'post',
        'email',
        'password',
        'is_active',
        'phone'
    ];
}
