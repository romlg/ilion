<?php

namespace App\Models\Layout;

use Illuminate\Database\Eloquent\Model;

/**
 * Junction table
 */
class MaterialToLayoutPatternMaterial extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'layout_material_id',
        'material_id'
    ];

    public $timestamps = false;

    public function layoutMaterial()
    {
        return $this->belongsTo('App\Models\Layout\LayoutMaterial');
    }

    public function material()
    {
        return $this->belongsTo('App\Models\Material', 'material_id ');
    }
}
