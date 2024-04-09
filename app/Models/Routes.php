<?php

namespace App\Models;

use App\Models\Cities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;
use Illuminate\Database\Eloquent\SoftDeletes;
use SleepingOwl\Admin\Form\Element\Image;

class Routes extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    //protected $appends = ['invert'];

    public function cars()
    {
        //return $this->belongsToMany(Car::class);
        //return $this->hasManyThrough(Car::class, RouteCar::class, 'route_id', 'id', 'id', 'car_id');
        return $this->belongsToMany(Car::class, RouteCar::class, 'route_id', 'car_id', 'id', 'id')
        ->select([
            'cars.id',
            'title',
            'places_min',
            'places_max',
            'vehicle_body_type',
            'brand',
            'image',
            'luggage',
            'route_cars.price',
            'priority',
        ]);
    }

    public function points()
    {
        return $this->morphToMany(Cities::class, 'cities_route')->orderBy('id');;
    }


    public function places()
    {
        return $this->morphToMany(Place::class, 'places_route')->orderBy('id');
    }

    public function pointsName(): \Illuminate\Database\Eloquent\Collection {
        return $this->morphToMany(Cities::class, 'cities_route')->select(['name'])->get();
    }

    public function fromCity() {
        return
            $this->hasOne(Cities::class,'id','route_from_city_id' )->select(['name']);
    }

    public function getFromCity(): \Illuminate\Database\Eloquent\Collection {
        return
            $this->hasOne(Cities::class,'id','route_from_city_id' )->select(['name'])->get();
    }

    public function fromCountry(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return
            $this->hasOne(Country::class,'id','route_from_country_id' )->select(['name','price_increase']);
    }


    public function getFromCountry(): \Illuminate\Database\Eloquent\Collection {
        return
            $this->hasOne(Country::class,'id','route_from_country_id' )->select(['name', 'visible_routes'])->get();
    }

    public function getToCity(): \Illuminate\Database\Eloquent\Collection {
        return
            $this->hasOne(Cities::class,'id','route_to_city_id' )->select(['name'])->get();
    }


    public function toCity() {
        return
            $this->hasOne(Cities::class,'id','route_to_city_id' )->select(['name']);
    }

    public function getToCountry(): \Illuminate\Database\Eloquent\Collection {
        return
            $this->hasOne(Country::class,'id','route_to_country_id' )->select(['name'])->get();
    }

//    public function getInvertAttribute(): int
//    {
//        return 0;
//    }

    public function setInvertAttribute($value): void
    {
        $this->attributes['invert'] = (int)$value;
    }

}
