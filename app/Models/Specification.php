<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    //
    protected $table = 'specifications';
    protected $primaryKey = 'spec_id';
    protected $fillable =[
        'spec_id',
        'title',
        'object_id'
    ];

    public function object()
    {
        return $this->hasOne('App\Models\Objct', 'object_id', 'object_id');
    }

    public function units()
    {
        return $this->hasMany('App\Models\SpecUnit', 'spec_id', 'spec_id');
    }


    public function nomenclatures()
    {
        return $this->hasManyThrough(
            'App\Models\Nomenclature',
            'App\Models\SpecUnit',
            'spec_id', // Foreign key on SpecUnit table...
            'n_id', // Foreign key on Nomenclature table...
            'spec_id', // Local key on Specification table...
            'n_id' // Local key on SpecUnit table...
        );
    }
}
