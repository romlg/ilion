<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materials2object extends Model
{
    protected $table = 'Materials2objects';
    protected $primaryKey = 'id';
    protected $fillable =[
        'material_id',
        'object_id',
        'purchase_price',
        'sale_price',
        'count',
        'units'
    ];

    public $timestamps = false;
}
