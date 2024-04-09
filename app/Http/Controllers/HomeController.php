<?php

namespace App\Http\Controllers;

use App\Models\OurTeam;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Place;
use App\Models\Routes;
use Illuminate\Http\Request;

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth');
    }

    /**
     * Show the application home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {

        $content = Page::query()->where([['slug', '=', 'home']])->first() ?? false;

//        $routes = Routes::select(
//            [
//                'id',
//                'title',
//                'route_from_city_id',
//                'route_from_country_id',
//                'route_to_city_id',
//                'route_to_country_id',
//                'price'
//            ]
//        )->where( 'status', '=', 'open' )
//            ->get();

        $result = [];
//        foreach ( $routes as $route ) {
//            $from_city    = $route->getFromCity();
//            $from_country = $route->getFromCountry();
//            $to_city      = $route->getToCity();
//            $to_country   = $route->getToCountry();
//
//            $points = [];
//            foreach ( $route->pointsName() as $point ) {
//                $points[] = $point->name;
//            }
//
//            $result[] =
//                [
//                    'id'                    => $route->id,
//                    'title'                 => $route->title,
//                    'from_city'             => $from_city[0]->name ?? '',
//                    'route_from_city_id'    => $route->route_from_city_id,
//                    'route_from_country_id' => $route->route_from_country_id,
//                    'route_to_city_id'      => $route->route_to_city_id,
//                    'route_to_country_id'   => $route->route_to_country_id,
//                    'from_country'          => $from_country[0]->name ?? '',
//                    'to_city'               => $to_city[0]->name ?? '',
//                    'to_country'            => $to_country[0]->name ?? '',
//                    'points'                => $points,
//                ];
//        }

//        $partners =  Partner::query()->where('status', true)->get();
        $partners = Partner::select()
            ->where( 'status', '=', 'enabled' )
            ->get();

        foreach ($partners as $partner){
            $partner->image = $this->getImageBySize('274x118', $partner->image);
        }


//        $places = Place::query()->where('status', true)->limit(10)->get();
        $places = Place::query()->where('status', true)->get()->random(10);
//        Images::all()->random(4);

        $placesResponse = [];

        foreach ($places as $item){
            $placesResponse[] = [
                'id' => $item['id'],
                'title' => $this->getTranslateContent($item, 'title'),
                'body' => strip_tags($this->getTranslateContent($item, 'body')),
//                'body' => substr(strip_tags($this->getTranslateContent($item, 'body')),0, 320) . ((strlen(strip_tags($this->getTranslateContent($item, 'body'))) > 320) ? '...' : ''),
                'image' => $this->getImageBySize('360x230', $item['image']),
                'durations' => $item['durations'],
                'extra_durations' => $item['extra_durations'],
                'price_per_hour' => $item['price_per_hour'],
            ];
        }

        return view( 'home', [
            'routes'   => json_encode($result),
            'partners' => $partners,
            'places' => $placesResponse,
            'content' => [
                'title' => $this->getTranslateContent($content, 'title'),
                'body'              => strip_tags($this->getTranslateContent( $content, 'body' )),

//                'body' => $this->getTranslateContent($content, 'body'),
                'meta_title' => $this->getTranslateContent($content, 'meta_title'),
                'meta_keywords' => $this->getTranslateContent($content, 'meta_keywords'),
                'meta_descriptions' => $this->getTranslateContent($content, 'meta_descriptions'),
                'embed_video' => $content['embed_video'],
                'video_block_title' => $content['video_block_title'],
                'block_image' => $content['block_image'],
            ]
        ] );
    }

    public function getTranslateContent($content, $key){
        return (isset($content[$key.'_'.app()->getLocale()]) && strlen($content[$key.'_'.app()->getLocale()]) > 0 ) ?$content[$key.'_'.app()->getLocale()] : $content[$key.'_en'];
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
}
