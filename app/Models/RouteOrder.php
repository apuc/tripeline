<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RouteOrder extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'status'
    ];

    protected $with = [
        'driver',
        'vehicle',
    ];

    protected $dates = ['deleted_at'];

    public function places()
    {
        return $this->morphToMany(Place::class, 'places_route_order')->withPivot(['price','durations']);
    }

//    public function cars()
//    {
//        return $this->hasManyThrough(Car::class, RouteOrderCar::class, 'route_id', 'id', 'id', 'car_id');
//    }

    public function getRoute()
    {
        return Routes::find($this->route_id);
    }

    public function getCars()
    {
        //return $this->morphToMany(Car::class, 'cars_route_order')->withPivot(['car_id','count']);
        return $this->hasManyThrough(Car::class, RouteOrderCar::class, 'route_id', 'id', 'id', 'car_id');
    }

    public function getCity($id)
    {
        return Cities::find($id);
    }

    public function driver() : HasOne
    {
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }

    public function vehicle() : HasOne
    {
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }

}
