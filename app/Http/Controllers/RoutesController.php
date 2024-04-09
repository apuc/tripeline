<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Cities;
use App\Models\CityRoute;
use App\Models\Place;
use App\Models\Routes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Database\Eloquent\Builder;

// the image will be replaced with an optimized version which should be smaller

class RoutesController extends Controller {

    /**
     * Show products list.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function index( \Illuminate\Http\Request $request ) {
        try {
            $routes = Routes::select( [ 'countries.name', 'countries.id' ] )->where( 'status', '=', 'open' )
                            ->join( 'countries', 'countries.id', '=', 'routes.route_to_country_id' )
                            ->groupBy( 'countries.id' )
                            ->get();

            return view( 'pages/routes', [ 'routes' => $routes ] );

        } catch ( \Throwable $t ) {
            return view( 'pages/routes', [ 'routes' => [], 'error' => $t->getMessage() ] );
        }
    }


    public function getRoute( \Illuminate\Http\Request $request ) {
        try {
            $data  = $request->all();
            $route = Routes::find( $data['route'] );

            $places = $route->places;

            $placesResponse = [];

            foreach ( $places as $item ) {
                if ( $item['status'] === 'enabled' ) {
                    $placesResponse[] = [
                        'id'              => $item['id'],
                        'title'           => $this->getTranslateContent( $item, 'title' ),
                        'body'            => strip_tags( $this->getTranslateContent( $item, 'body' ) ),

                        //                        'body' => substr(strip_tags($this->getTranslateContent($item, 'body')),0, 320) . ((strlen(strip_tags($this->getTranslateContent($item, 'body'))) > 320) ? '...' : ''),
                        'image'           => $item['image'],
                        'price'           => $item['price'],
                        'durations'       => $item['durations'],
                        'extra_durations' => $item['extra_durations'],
                        'price_per_hour'  => $item['price_per_hour'],
                        'status'          => $item['status']
                    ];
                }
            }

            $cars = $route->cars;

            $from_city    = $route->fromCity;
            $from_country = $route->fromCountry;
            $to_city      = $route->toCity;

            return [
                'current_route'        => $route,
                'current_route_places' => $placesResponse
            ];

        } catch ( \Throwable $t ) {
            return [
                'current_route'        => [],
                'current_route_places' => []
            ];
        }
    }

    /**
     * routes list.
     *
     * @return array
     */
    public function routeList(): array {
        try {
            $routes = Routes::select(
                [
                    'id',
                    'title',
                    'route_from_city_id',
                    'route_from_country_id',
                    'route_to_city_id',
                    'route_to_country_id',
                    'route_start',
                    'route_end',
                    'price',
                ]
            )->where( 'status', '=', 'open' )
                            ->get();

            $result = [];
            foreach ( $routes as $route ) {
                $from_city    = $route->getFromCity();
                $from_country = $route->getFromCountry();
                $to_city      = $route->getToCity();
                $to_country   = $route->getToCountry();

                if ($from_country[0]->name == $to_country[0]->name && !$from_country[0]->visible_routes) {
                    continue;
                }

                $points = [];
                foreach ( $route->pointsName() as $point ) {
                    $points[] = $point->name;
                }

                $result[] =
                    [
                        'id'                    => $route->id,
                        'title'                 => $route->title,
                        'from_city'             => $from_city[0]->name ?? '',
                        'route_from_city_id'    => $route->route_from_city_id,
                        'route_from_country_id' => $route->route_from_country_id,
                        'route_to_city_id'      => $route->route_to_city_id,
                        'route_to_country_id'   => $route->route_to_country_id,
                        'from_country'          => $from_country[0]->name ?? '',
                        'to_city'               => $to_city[0]->name ?? '',
                        'to_country'            => $to_country[0]->name ?? '',
                        'points'                => $points,
                        'route_start'           => $route->route_start,
                        'route_end'             => $route->route_end,
                        'visible_routes'        => $from_country[0]->visible_routes,
//                        'visible_routes_check'  => $from_country[0]->name == $to_country[0]->name && !$route->visible_routes
                    ];
            }

            return $result;
        } catch ( \Throwable $t ) {
            return [];
        }
    }

    /**
     * Show products list.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function router( \Illuminate\Http\Request $request ) {
        try {
            $routes = Routes::select( [ 'cities.name', 'cities.id' ] )->where( 'status', '=', 'open' )
                            ->join( 'cities_routes', 'cities_routes.cities_route_id', '=', 'routes.id' )
                            ->join( 'cities', 'cities.id', '=', 'cities_routes.cities_id' )
                            ->get();

//            $routes = Routes::select()->get();
            return view( 'pages/routes', [ 'routes' => $routes ] );

        } catch ( \Throwable $t ) {
            return view( 'pages/routes', [ 'routes' => [ 's' ] ] );
        }
    }


    /**
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function get( $lang, $id ) {
        try {
            $routes = Routes::select(
                [
                    'routes.*',
                ]
            )->where( 'status', '=', 'open' )
                            ->where( 'routes.route_to_country_id', $id )
                            ->get();

            foreach ( $routes as $route ) {
                $route->image = $this->getImageBySize('404x309',$route->image);
            }


            return view( 'pages/routes2', [ 'routes' => $routes, 'dd' => [ $id, $lang ] ] );

        } catch ( \Throwable $t ) {
            return view( 'pages/routes2', [ 'routes' => [], 'dd' => [ $id, $lang ] ] );
        }
    }


    public function getCarsList(\Illuminate\Http\Request $request) {
        try {
            $cars = Car::select()->get();

            return view( 'pages/request', [ 'cars' => $cars ] );

        } catch ( \Throwable $t ) {
            return view( 'pages/request', [ 'cars' => [] ] );
        }
    }


    public function getCities(\Illuminate\Http\Request $request)
    {
        $cities = Cities::where('name', 'LIKE', $request->cityPart.'%')->with('country:id,name')->get();

//        if ($direction = $request->direction) {
//            $cities = $cities->filter(function ($city) use($direction) {
//                if (!$route = Routes::where('route_'. $direction . '_city_id', $city->id)->first()) {
//                    return false;
//                }
//
//                $method = 'get' . ucfirst($direction) . 'Country';
//                $city->country = $route->$method()->toArray()[0]['name'];
//
//                return true;
//            });
//        }
        
//        $cities = $cities->filter(function ($city) {
//            $route = Routes::where('route_'. $direction . '_city_id', $city->id)->first();
//
//            $method = 'get' . ucfirst($direction) . 'Country';
//            $city->country = $route->$method()->toArray()[0]['name'];
//
//            return true;
//        });
        

        return $cities;
    }


    public function getByPlace(\Illuminate\Http\Request $request)
    {
        $result = [];

        //return Routes::whereIn('route_from_city_id', [136115, 148565])->whereIn('route_to_city_id', [22970])->get();

        $routes = new Routes;
        $routes2 = new Routes;

        if (count($request->from) && !count($request->to)) {
            $routes = $routes->where('status', '!=', 'closed')->whereIn('route_from_city_id', $request->from);
            $routes = $routes->get();
            $routes2 = $routes2->where('status', '!=', 'closed')->whereIn('route_to_city_id', $request->from);
            $routes2 = $routes2->get();

            foreach ($routes2 as $route2) {
                $route2->setInvertAttribute(1);
            }

            $routes = $routes->merge($routes2);
        }

        if (count($request->to) && !count($request->from)) {
            $routes = $routes->where('status', '!=', 'closed')->whereIn('route_to_city_id', $request->to);
            $routes = $routes->get();
            $routes2 = $routes2->where('status', '!=', 'closed')->whereIn('route_from_city_id', $request->to);
            $routes2 = $routes2->get();

            foreach ($routes2 as $route2) {
                $route2->setInvertAttribute(1);
            }

            $routes = $routes->merge($routes2);
        }

        if (count($request->to) && count($request->from)) {
            $routes = $routes->where('status', '!=', 'closed')->whereIn('route_from_city_id', $request->from)->whereIn('route_to_city_id', $request->to)->get();

            $routesInvert = $routes2->where('status', '!=', 'closed')->whereIn('route_from_city_id', $request->to)->whereIn('route_to_city_id', $request->from)->get();

            foreach ($routesInvert as $routeInvert) {
                $routeInvert->setInvertAttribute(1);
            }

            $routes = $routes->merge($routesInvert);
            
            

//            if (count($request->to) == 1 && count($request->from) > 1) {
//                $routes = $routes->where('route_to_city_id', $request->to)->whereIn('route_from_city_id', $request->from)->get();
//            } elseif(count($request->from) == 1 && count($request->to) > 1) {
//                $routes = $routes->where('route_from_city_id', $request->from)->whereIn('route_to_city_id', $request->to)->get();
//            } else {
//                $routes = $routes->where([['route_from_city_id', $request->from], ['route_to_city_id', $request->to]])->get();
//            }
        }

        foreach ( $routes as $route ) {
            $from_city    = $route->getFromCity();
            $from_country = $route->getFromCountry();
            $to_city      = $route->getToCity();
            $to_country   = $route->getToCountry();

            if ($from_country[0]->name == $to_country[0]->name && !$from_country[0]->visible_routes) {
                continue;
            }

            $points = [];
            foreach ( $route->pointsName() as $point ) {
                $points[] = $point->name;
            }

            $result[] =
                [
                    'id'                    => $route->id,
                    'title'                 => $route->title,
                    'from_city'             => $from_city[0]->name ?? '',
                    'route_from_city_id'    => $route->route_from_city_id,
                    'route_from_country_id' => $route->route_from_country_id,
                    'route_to_city_id'      => $route->route_to_city_id,
                    'route_to_country_id'   => $route->route_to_country_id,
                    'from_country'          => $from_country[0]->name ?? '',
                    'to_city'               => $to_city[0]->name ?? '',
                    'to_country'            => $to_country[0]->name ?? '',
                    'points'                => $points,
                    'route_start'           => $route->route_start,
                    'route_end'             => $route->route_end,
                    'visible_routes'        => $from_country[0]->visible_routes,
                    'invert'                => $route->invert,
                ];
        }

        return $result;

        //$routes = $routes->whereIn('route_from_city_id', [136115, 148565])->whereIn('route_to_city_id', [22970]);

        //$routes = $routes->whereIn('route_to_city_id', [22970]);

        //return $routes->get();
//        return Routes::all()->pluck('id');

//        $fromCitiesIDS = Cities::where('name', 'LIKE', $request->fromCity.'%')->get('id');
//        $toCitiesIDS = Cities::where('name', 'LIKE', $request->toCity.'%')->pluck('id'); //[148684]
//
//        //$fromRoutes = Routes::whereIn('route_from_city_id', $fromCitiesIDS)->get();
//        $fromRoutes = Routes::select('route_from_city_id','route_to_city_id', 'route_from_country_id', 'route_to_country_id')->whereIn('route_from_city_id', $fromCitiesIDS)->get();
//
//        //return $toCitiesIDS;
//
//
//        $routes = [];
//
//        foreach ($fromRoutes as $fromRoute) {
//            if (in_array($fromRoute->route_to_city_id, $toCitiesIDS->toArray())) {
//                $routes[] = $fromRoute;
//            }
//        }

        //$routeIDS = Routes::all()->pluck('id');

        if (count($request->to)) {
            if (count($request->to) < 2) {
                $routes = Routes::where('route_to_city_id', $request->to[1]);
            } else {
                $routes = Routes::whereIn('route_to_city_id', $request->to);
            }
        }

        $routes = Routes::where([['route_to_city_id', $request->toCityId]])->get(['route_from_city_id','route_to_city_id', 'route_from_country_id', 'route_to_country_id']);

        foreach ( $routes as $route ) {
            $from_city    = $route->getFromCity();
            $from_country = $route->getFromCountry();
            $to_city      = $route->getToCity();
            $to_country   = $route->getToCountry();

            if ($from_country[0]->name == $to_country[0]->name && !$from_country[0]->visible_routes) {
                continue;
            }

            $points = [];
            foreach ( $route->pointsName() as $point ) {
                $points[] = $point->name;
            }

            $result[] =
                [
                    'id'                    => $route->id,
                    'title'                 => $route->title,
                    'from_city'             => $from_city[0]->name ?? '',
                    'route_from_city_id'    => $route->route_from_city_id,
                    'route_from_country_id' => $route->route_from_country_id,
                    'route_to_city_id'      => $route->route_to_city_id,
                    'route_to_country_id'   => $route->route_to_country_id,
                    'from_country'          => $from_country[0]->name ?? '',
                    'to_city'               => $to_city[0]->name ?? '',
                    'to_country'            => $to_country[0]->name ?? '',
                    'points'                => $points,
                    'route_start'           => $route->route_start,
                    'route_end'             => $route->route_end,
                    'visible_routes'        => $from_country[0]->visible_routes,
                ];
        }

        return $result;
    }


    /**
     * Show products list.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function details( $lang, $id ) {

        $routes = Routes::select(
            [
                'id',
                'title',
                'route_from_city_id',
                'route_from_country_id',
                'route_to_city_id',
                'route_to_country_id',
                'price'
            ]
        )->where( 'status', '=', 'open' )
                        ->get();

        $result = [];
        foreach ( $routes as $route ) {
            $from_city    = $route->getFromCity();
            $from_country = $route->getFromCountry();
            $to_city      = $route->getToCity();
            $to_country   = $route->getToCountry();

            $points = [];
            foreach ( $route->pointsName() as $point ) {
                $points[] = $point->name;
            }

            $result[] =
                [
                    'id'                    => $route->id,
                    'title'                 => $route->title,
                    'from_city'             => $from_city[0]->name ?? '',
                    'route_from_city_id'    => $route->route_from_city_id,
                    'route_from_country_id' => $route->route_from_country_id,
                    'route_to_city_id'      => $route->route_to_city_id,
                    'route_to_country_id'   => $route->route_to_country_id,
                    'from_country'          => $from_country[0]->name ?? '',
                    'to_city'               => $to_city[0]->name ?? '',
                    'to_country'            => $to_country[0]->name ?? '',
                    'points'                => $points,
                ];
        }

        try {
            $route = Routes::select(
                [
                    'routes.*',
                ]
            )->where( 'status', '=', 'open' )
                           ->where( 'routes.id', $id )
                           ->first();
//            ImageOptimizer::optimize($route->image);

            $places = $route->places;

            $placesResponse = [];
            foreach ( $places as $item ) {
                $placesResponse[] = [
                    'id'              => $item['id'],
                    'title'           => $this->getTranslateContent( $item, 'title' ),
                    'body'            => strip_tags( $this->getTranslateContent( $item, 'body' ) ),
                    'image'           => $this->getImageBySize( '360x230', $item['image'] ),
                    'durations'       => $item['durations'],
                    'extra_durations' => $item['extra_durations'],
                    'price_per_hour'  => $item['price_per_hour'],
                ];
            }

            return view( 'pages/routes3', [
                'routes'          => json_encode( $result ),
                'route'           => $route,
                'places_response' => $placesResponse,
                'dd'              => [ $id, $lang ]
            ] );

        } catch ( \Throwable $t ) {
            return view( 'pages/routes3', [ 'route' => [], 'dd' => [ $id, $lang ], 'error' => $t->getMessage() ] );
        }
    }

    /**
     * getAllRoutes list.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function getAllRoutes( \Illuminate\Http\Request $request ) {
        try {
            $routes = Routes::select(
                [
                    'id',
                    'title',
                    'route_from_city_id',
                    'route_from_country_id',
                    'route_to_city_id',
                    'route_to_country_id',
                    'price'
                ]
            )->where( 'status', '=', 'open' )
                            ->get();

            $result = [];
            foreach ( $routes as $route ) {
                $from_city    = $route->getFromCity();
                $from_country = $route->getFromCountry();
                $to_city      = $route->getToCity();
                $to_country   = $route->getToCountry();

                $points = [];
                foreach ( $route->pointsName() as $point ) {
                    if ( $point->status === 'enabled' ) {
                        $points[] = $point->name;
                    }
                }

                $result[] =
                    [
                        'id'                    => $route->id,
                        'title'                 => $route->title,
                        'from_city'             => $from_city[0]->name ?? '',
                        'route_from_city_id'    => $route->route_from_city_id,
                        'route_from_country_id' => $route->route_from_country_id,
                        'route_to_city_id'      => $route->route_to_city_id,
                        'route_to_country_id'   => $route->route_to_country_id,
                        'from_country'          => $from_country[0]->name ?? '',
                        'to_city'               => $to_city[0]->name ?? '',
                        'to_country'            => $to_country[0]->name ?? '',
                        'points'                => $points,
                    ];
            }

            return [ 'routes' => ( $result ) ];

        } catch ( \Throwable $t ) {
            return [ 'error' => $t->getMessage() ];
        }
    }

    public function getTranslateContent( $content, $key ) {
        return ( isset( $content[ $key . '_' . app()->getLocale() ] ) && strlen( $content[ $key . '_' . app()->getLocale() ] ) > 0 ) ? $content[ $key . '_' . app()->getLocale() ] : $content[ $key . '_en' ];
    }


    public function getImageBySize($size, $image): string {
        try {
            $pathinfo = pathinfo($image);
            $pathinfo['basename'] = $size . '_' . $pathinfo['basename'];
            $resized_image = $pathinfo['dirname'] . '/' . $pathinfo['basename'];
        }catch (\Exception $e){
            return $image;
        }
        return $resized_image;
    }

    public function checkRedis(\Illuminate\Http\Request $request)
    {
        return Routes::all();
        //return Cache::put('routes', Routes::all(), 180);
        //return Cache::get('routes');

//        return Cache::get('routes', function () use ($request) {
//            return $this->getByPlace($request);
//        });
        //return Cache::put('names2', 'Ilya', 30);
    }

}
