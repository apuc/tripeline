<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helper\EmailHelper;

class Country extends Model
{
    use HasFactory;

    function index(){
        return $this->where('region' ,'=', 'Europe');
    }

    public function setVisibleRoutesAttribute($value)
    {
        $visibleRoutes = $this->attributes['visible_routes'] ? 0 : 1;

        $statusRoutes = $visibleRoutes ? 'open' : 'closed';

        $this->changeStatusRoutesForCountry($this->attributes['id'], $statusRoutes);

        $this->attributes['visible_routes'] = $visibleRoutes;
    }

    private function changeStatusRoutesForCountry($countryID, $status)
    {
        $routes = Routes::where([
            ['route_from_country_id', $countryID],
            ['route_to_country_id', $countryID],
        ])->get();

        foreach ($routes as $route) {
            Routes::whereId($route->id)->update(['status' => $status]);
        }

        return true;
    }
}
