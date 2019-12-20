<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $table = 'stages';
    protected $primaryKey = 'stage_id';
    protected $fillable =[
        'title',
        'object_id',
    ];
}
