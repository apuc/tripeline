<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BecomeAPartner extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $with = [
        'profile',
        'company'
    ];

    public function profile() : HasOne
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function company() : HasOne
    {
        return $this->hasOne(Company::class, 'user_id');
    }
}
