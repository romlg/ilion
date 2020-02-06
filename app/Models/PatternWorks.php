<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatternWorks extends Model
{
    protected $table = 'pattern_works';
    protected $primaryKey = 'pworks_id';
    protected $fillable = [
        'pattern_id',
        'work_id',
        'count'
    ];
}
