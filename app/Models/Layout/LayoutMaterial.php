<?php

namespace App\Models\Layout;

use App\Models\Material;
use App\Models\Work;
use Illuminate\Database\Eloquent\Model;

class LayoutMaterial extends Model
{
    const
        TYPE_WORK = 'work',
        TYPE_MATERIAL = 'material',
        TYPE_PATTERN = 'pattern_material'
    ;

    public $timestamps = false;

    protected $fillable =[
        'layout_id',
        'position_id',
        'count',
        'type'
    ];
    
    const MORPH_TYPES = [
        self::TYPE_WORK => Work::class,
        self::TYPE_MATERIAL => Material::class,
        self::TYPE_PATTERN => PatternMaterialsForLayout::class,
    ];
    
    public function getTypeAttribute($valueFromObject)
    {
        return self::MORPH_TYPES[$valueFromObject] ?? $valueFromObject;
    }
    
    public function position()
    {
        return $this->morphTo(null, 'type', 'position_id');
    }
    
    public function selectedMaterial()
    {
        return $this->hasOneThrough(
            Material::class,
            MaterialToLayoutPatternMaterial::class,
            'layout_material_id',
            'material_id',
            'id',
            'material_id'
        );
    }
    
    public function isPattern(): bool
    {
        return $this->getOriginal('type') === self::TYPE_PATTERN; 
    }
    
    public function getPositionPrice(): int
    {
        $position = $this->position;
        if ($this->isPattern()) {
            $sm = $this->selectedMaterial;
            return $sm ? (int)$sm->price : 0;
        }
        
        if ($position instanceof Work) {
            return $position->wprice;
        }
        
        if ($position instanceof Material) {
            return $position->price ?? 0;
        }
    }
    
    public function getPositionSum(): int
    {
        return $this->getPositionPrice() * $this->count; 
    }
}
