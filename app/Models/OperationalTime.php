<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationalTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'open_time',
        'close_time',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
