<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'branch_type',
        'is_headquarter',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function operationalTime()
    {
        return $this->hasOne(OperationalTime::class);
    }

    public function consumerPickups()
    {
        return $this->hasMany(ConsumerPickup::class);
    }

    public function reqBusinessPickups()
    {
        return $this->hasMany(BusinessPickup::class, 'requestor_branch_id');
    }

    public function colBusinessPickups()
    {
        return $this->hasMany(BusinessPickup::class, 'collector_branch_id');
    }
}
