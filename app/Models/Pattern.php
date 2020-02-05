<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pattern extends Model
{
    protected $table = 'patterns';
    protected $primaryKey = 'pattern_id';
    protected $fillable = [
        'title'
    ];
}
