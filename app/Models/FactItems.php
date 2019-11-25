<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactItems extends Model
{
    protected $table = 'fact_items';
    protected $primaryKey = 'id';
    protected $fillable =[
        'fact_id',
        'material_id',
        'count'
    ];

    public $timestamps = false;
}
