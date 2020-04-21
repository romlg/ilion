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
        return $this->hasMany('App\Models\PatternAdditionalMaterials', 'pattern_id', 'pattern_price_id');
    }

    public function expendableMaterials()
    {
        return $this->hasMany('App\Models\PatternExpendableMaterials', 'pattern_id', 'pattern_price_id');
    }

    public function nomenclatures()
    {
        return $this->hasOne('App\Models\PatternNomenclatures', 'pattern_id', 'pattern_price_id');
    }

    public function works()
    {
        return $this->hasMany('App\Models\PatternWorks', 'pattern_id', 'pattern_price_id');
    }

}
