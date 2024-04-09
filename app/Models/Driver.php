<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Helper\EmailHelper;
use Illuminate\Support\Facades\Hash;

class Driver extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'country_id',
        'city_id',
        'personal',
        'licence',
        'criminal_check',
        'photo',
        'state',
    ];

    protected $with = [
        'city',
        'country'
    ];

    public function city() : HasOne
    {
        return $this->hasOne(Cities::class, 'id', 'city_id');
    }

    public function country() : HasOne
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function getPhotoAttribute($value)
    {
        return 'https://api.drivermytripline.com/storage/drivers/' . $this->id . '/' . $value;
    }

    public function user() : HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public static function boot()
    {
        parent::boot();

        self::updated(function($model){
            if ($model->state === 'Approved') {
                $pass = 'Pass!'. rand(100, 900);
                if ($user = User::where('email', $model->email)->first()) {
                    $user->update([
                        'email'    => $model->email,
                        'phone'    => $model->phone,
                    ]);
                } else {
                    $user = User::create([
                        'email'    => $model->email,
                        'phone'    => $model->phone,
                        'first_name' => $model->first_name,
                        'last_name' => $model->last_name,
                        'password' => Hash::make($pass),
                        'is_admin' => 0,
                        'role_id'  => 5, //Partner - 4, driver - 5, travel agency - 6
                        'email_verified_at' => date('Y-m-d H:i:s')
                    ]);

                    $model->update([
                        'user_id' => $user->id
                    ]);

                    EmailHelper::sendEmailFromRegDriver(['name' => $user->first_name, 'password' => $pass], $model->email);
                }
            }
        });
    }
}
