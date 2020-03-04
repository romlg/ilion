<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterUnit extends Model
{
    protected $table = 'filter_unit';
    protected $primaryKey = 'funit_id';
    protected $fillable = [
        'filter_id',
        'material_id',
        'count'
    ];
}
