<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatternNomenclatures extends Model
{
    protected $table = 'pattern_nomenclatures';
    protected $primaryKey = 'pattern_id';
    protected $fillable = [
        'pattern_id',
        'n_id'
    ];
}
