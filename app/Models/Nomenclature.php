<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nomenclature extends Model
{
    //
    protected $table = 'nomenclatures';
    protected $primaryKey = 'n_id';
    protected $fillable =[
        'n_id',
        'title',
        'group_id',
        'is_active'
    ];

    public function group()
    {
        return $this->hasOne('App\Models\Group', 'group_id', 'group_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
