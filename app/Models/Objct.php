<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Objct extends Model
{
    protected $table = 'objects';
    protected $primaryKey = 'object_id';
    protected $fillable =[
        'title'
    ];

    public function materials()
    {
        return $this->hasMany('App\Models\Materials2object', 'object_id', 'object_id');
    }
}
