<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayoutMaterial extends Model
{
    protected $table = 'layout_materials';
    protected $primaryKey = 'layout_material_id';
    public $timestamps = false;

    protected $fillable =[
        'layout_id',
        'position_id',
        'count',
        'type'
    ];
}
