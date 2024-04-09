<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'english_lvl',
        'whatsapp',
        'country',
        'city',
        'passport',
        'driver_licence',
        'criminal_check',
        'photo',
    ];

    public function getPhotoAttribute($value)
    {
        return 'https://api.drivermytripline.com/storage/profiles/' . $this->user_id . '/' . $value;
    }

}
