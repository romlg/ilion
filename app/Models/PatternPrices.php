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

    public function patternMaterials()
    {
        return $this->hasMany('App\Models\PatternMaterials', 'pattern_material_id', 'pattern_price_id');
    }

    public function nomenclatures()
    {
        return $this->hasMany('App\Models\PatternNomenclatures', 'pattern_id', 'pattern_price_id');
    }

    public function works()
    {
        return $this->hasMany('App\Models\PatternWorks', 'pattern_id', 'pattern_price_id');
    }

}
