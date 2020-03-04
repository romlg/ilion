<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pattern extends Model
{
    protected $table = 'patterns';
    protected $primaryKey = 'pattern_id';
    protected $fillable = [
        'title'
    ];

    public function materials()
    {
        return $this->hasMany('App\Models\PatternAdditionalMaterials', 'pattern_id', 'pattern_id');
    }

    public function nomenclatures()
    {
        return $this->hasMany('App\Models\PatternNomenclatures', 'pattern_id', 'pattern_id');
    }

    public function works()
    {
        return $this->hasMany('App\Models\PatternWorks', 'pattern_id', 'pattern_id');
    }

}
