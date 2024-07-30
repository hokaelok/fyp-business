<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPickup extends Model
{
    use HasFactory;

    protected $fillable = [
        'requestor_branch_id',
        'collector_branch_id',
        'waste_payload',
        'status',
        'remark',
        'requested_at',
        'request_pickup_time',
        'accepted_rejected_at',
        'completed_at',
        'contact_number',
        'street',
        'city',
        'state',
        'zip',
        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'waste_payload' => 'array',
        'requested_at' => 'datetime',
        'request_pickup_time' => 'datetime',
        'accepted_rejected_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function requestorBranch()
    {
        return $this->belongsTo(Branch::class, 'requestor_branch_id');
    }

    public function collectorBranch()
    {
        return $this->belongsTo(Branch::class, 'collector_branch_id');
    }
}
