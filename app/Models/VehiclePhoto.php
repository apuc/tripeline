<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiclePhoto extends Model
{
    protected $fillable = [
        'vehicle_id',
        'photo',
    ];

    public function getPhotoAttribute($value)
    {
        return 'https://api.drivermytripline.com/storage/vehicles/' . $this->vehicle_id . '/' . $value;
    }
}
