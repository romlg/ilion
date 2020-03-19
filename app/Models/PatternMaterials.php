<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatternMaterials extends Model
{
    protected $table = 'pattern_materials';
    protected $primaryKey = 'pattern_material_id';
    protected $fillable = [
        'title',
        'unit'
    ];
}
