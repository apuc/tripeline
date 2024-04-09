<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

//Prepare table
//UPDATE `cities` SET `label`= CONCAT(`name`,',',`country_code`) WHERE id >=0

class Cities extends Model
{
    use HasFactory;

    public static function boot() {

        parent::boot();

        static::created(function($item) {
            \Log::info('Item Created Event:'.$item);
        });

        static::creating(function($item) {

            $state = State::find($item['state_id'])->first();
            $country = Country::find($item['country_id'])->first();

//            \Log::info('Item State Event:'.$state);
//            \Log::info('Item Country Event:'.$country);
//            `label``name``state_id``state_code``country_id``country_code``latitude``longitude``flag``wikiDataId`

            $item['state_code'] = $state['iso2'];
            $item['country_code'] = $country['iso2'];
            $item['latitude'] = 0;
            $item['longitude'] = 0;
            $item['flag'] = 0;
            $item['wikiDataId'] = 'Q';
//            \Log::info('Item Created Event:'.$item);
        });

    }

    function index(){
        return $this->where('country_id' ,'=', 144)->get();
//        return $this->where('country_id' ,'IN', (Country::select('id')->where('region' ,'=', 'Europe')->get()))->get();
    }

    function euList(){
        return $this->where('country_id' ,'IN', (Country::select('id')->where('region' ,'=', 'Europe')->get()))->get();
    }

    public function citiesable()
    {
        return $this->morphTo();
    }

    public function toCities()
    {
        return $this->belongsTo(Routes::class, 'id', 'route_to_city_id');
    }

    public function fromCities()
    {
        return $this->belongsTo(Routes::class, 'id', 'route_from_city_id');
    }

    public function country() : HasOne
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}
