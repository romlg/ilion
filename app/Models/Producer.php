<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producer extends Model
{
    //
    protected $table = 'producers';
    protected $primaryKey = 'producer_id';
    protected $fillable = [
        'title',
        'is_active'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
