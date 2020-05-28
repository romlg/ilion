<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatternExpendableMaterials extends Model
{
    protected $table = 'pattern_expendable_materials';
    protected $primaryKey = 'pem_id';
    protected $fillable = [
        'pattern_id',
        'material_id',
        'count'
    ];
}
