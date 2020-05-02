<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layout extends Model
{
    protected $table = 'layouts';
    protected $primaryKey = 'layout_id';
    protected $fillable =[
        'title'
    ];
}
