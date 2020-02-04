<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materials2object extends Model
{
    protected $table = 'materials2objects';
    protected $primaryKey = 'id';
    protected $fillable =[
        'material_id',
        'stage_id',
        'ver',
        'purchase_price',
        'count',
        'units'
    ];

    public $timestamps = false;

    public function material()
    {
        return $this->belongsTo('App\Models\Material', 'material_id');
    }

    public function object()
    {
        return $this->belongsTo('App\Models\Objct', 'object_id ');
    }

}
