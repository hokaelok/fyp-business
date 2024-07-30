<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'image',
        'description',
        'start_time',
        'end_time',
        'street',
        'city',
        'state',
        'zip',
        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
