<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'material_id';
    protected $fillable =[
        'title',
        'vendor_code',
        'unit',
        'notes',
        'producer_id',
        'pattern_material_id',
        'category_id'
    ];
}
