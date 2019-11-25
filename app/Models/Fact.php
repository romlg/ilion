<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fact extends Model
{
    protected $table = 'facts';
    protected $primaryKey = 'fact_id';
    protected $fillable =[
        'customer_id',
        'object_id',
        'notes'
    ];
}
