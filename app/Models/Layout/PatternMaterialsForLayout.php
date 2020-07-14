<?php

namespace App\Models\Layout;

use App\Models\Material;
use App\Models\PatternMaterials;

class PatternMaterialsForLayout extends PatternMaterials
{
    protected $with = [
        'materials'
    ];

    /**
     * Все доступные материалы для паттерна
     *
     * @return type
     */
    public function materials()
    {
        return $this->hasMany(Material::class, 'pattern_material_id', 'pattern_material_id');
    }
}
