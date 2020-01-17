<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecUnit extends Model
{
    //
    protected $table = 'spec_units';
    protected $primaryKey = 'sunit_id';
    protected $fillable =[
        'sunit_id',
        'spec_id',
        'n_id',
        'count',
        'ver',
        'is_active'
    ];
}
