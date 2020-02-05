<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    protected $table = 'groups';
    protected $primaryKey = 'group_id';
    protected $fillable = [
        'title'
    ];
}
