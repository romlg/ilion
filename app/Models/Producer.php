<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producer extends Model
{
    //
    protected $table = 'producers';
    protected $primaryKey = 'producer_id';
    protected $fillable = [
        'title'
    ];
}
