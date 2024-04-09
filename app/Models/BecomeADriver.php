<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BecomeADriver extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $with = [
        'profile'
    ];

    public function profile() : HasOne
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
}
