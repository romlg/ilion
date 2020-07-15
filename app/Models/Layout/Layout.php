<?php

namespace App\Models\Layout;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layout extends Model
{
    protected $table = 'layouts';
    protected $primaryKey = 'layout_id';
    protected $fillable =[
        'title'
    ];
    
    /**
     * 
     * @return HasMany|LayoutMaterial
     */
    public function layoutMaterial()
    {
        return $this->hasMany(LayoutMaterial::class, 'layout_id', 'layout_id');
    }
    
    /**
     * 
     * @return type
     */
    public function getPatterns()
    {
        return $this->layoutMaterial->where('type', PatternMaterialsForLayout::class);
    }
    
    /**
     * 
     * @return int
     */
    public function getTotalAmount(): int
    {
        return $this->layoutMaterial->sum(
            function (LayoutMaterial $item) {
                return $item->getPositionSum();
            }
        );
    }
}
