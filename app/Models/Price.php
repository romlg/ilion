<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $table = 'prices';
    protected $primaryKey = 'pworks_id';
    protected $fillable = [
        'material_id',
        'sprice',
        'oprice',
        'price',
        'user_id'
    ];
}
