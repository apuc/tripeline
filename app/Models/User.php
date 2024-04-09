<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'first_name',
        'last_name',
        'status',
        'role_id',
        'phone',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = [
        'profile',
        'device'
    ];

    public function isAdmin(){
        return $this->role_id === 1;
    }

    public function isOperator(){
        return $this->role_id === 2;
    }

    public function isCustomer(){
        return $this->role_id === 3;
    }

    public function isPartner(){
        return $this->role_id === 4 || $this->role_id === 5;
    }

    public function profile() : HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function company() : HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function device() : HasOne
    {
        return $this->hasOne(UserDevice::class);
    }
}
