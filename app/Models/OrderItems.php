<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    protected $fillable =[
        'order_id',
        'material_id',
        'count'
    ];

    public $timestamps = false;
}
