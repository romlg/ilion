<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'contracts';
    protected $primaryKey = 'contract_id';
    protected $fillable =[
        'contract_date',
        'title',
        'text',
        'is_signed'
    ];
}
