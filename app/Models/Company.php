<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
        'logo',
        'website',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function headquaterBranch()
    {
        return $this->hasOne(Branch::class)->where('is_headquarter', true);
    }

    public function normalBranches()
    {
        return $this->hasMany(Branch::class)->where('is_headquarter', false);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
