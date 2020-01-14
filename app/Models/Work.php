<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    //
    protected $table = 'works';
    protected $primaryKey = 'work_id';
    protected $fillable =[
        'work_id',
        'title',
        'units',
        'wtime',
        'wprice',
        'group_id',
        'is_active'
    ];
}
