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
}
