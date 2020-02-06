<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatternAdditionalMaterials extends Model
{
    protected $table = 'pattern_additional_materials';
    protected $primaryKey = 'pam_id';
    protected $fillable = [
        'pattern_id',
        'material_id',
        'count'
    ];
}
