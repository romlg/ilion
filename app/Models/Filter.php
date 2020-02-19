<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    protected $table = 'filters';
    protected $primaryKey = 'filter_id';
    protected $fillable = [
        'title'
    ];
}
