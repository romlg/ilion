<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatternPrices extends Model
{
    protected $table = 'pattern_prices';
    protected $primaryKey = 'pattern_price_id';
    protected $fillable = [
        'title'
    ];

    public function nomenclatures()
    {
        return $this->hasOne('App\Models\PatternNomenclatures', 'pattern_id', 'pattern_price_id');
    }

    public function works()
    {
        return $this->hasMany('App\Models\PatternWorks', 'pattern_id', 'pattern_price_id');
    }

    public function patternMaterials()
    {
        return $this->hasMany('App\Models\PatternAdditionalMaterials', 'pattern_id', 'pattern_price_id');
    }

    public function expendableMaterials()
    {
        return $this->hasMany('App\Models\PatternExpendableMaterials', 'pattern_id', 'pattern_price_id');
    }

    public function worksForCommercialOffer()
    {
        return $this->hasManyThrough(
            'App\Models\Work',
            'App\Models\PatternWorks',
            'pattern_id', // Foreign key on PatternWorks table...
            'work_id', // Foreign key on Works table...
            'pattern_price_id', // Local key on PatternPrices table...
            'pworks_id' // Local key on PatternWorks table...
        );
    }

    public function patternMaterialsForCommercialOffer()
    {
        return $this->hasManyThrough(
            'App\Models\PatternMaterials',
            'App\Models\PatternAdditionalMaterials',
            'pattern_id', // Foreign key on PatternAdditionalMaterials table...
            'pattern_material_id', // Foreign key on PatternMaterials table...
            'pattern_price_id', // Local key on PatternPrices table...
            'material_id' // Local key on PatternAdditionalMaterials table...
        );
    }

    public function expendableMaterialsForCommercialOffer()
    {
        return $this->hasManyThrough(
            'App\Models\Material',
            'App\Models\PatternExpendableMaterials',
            'pattern_id', // Foreign key on PatternExpendableMaterials table...
            'material_id', // Foreign key on Material table...
            'pattern_price_id', // Local key on PatternPrices table...
            'material_id' // Local key on PatternExpendableMaterials table...
        );
    }

}
