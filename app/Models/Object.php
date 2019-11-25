<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Object extends Model
{
    protected $table = 'objects';
    protected $primaryKey = 'object_id';
    protected $fillable =[
        'title'
    ];
}
