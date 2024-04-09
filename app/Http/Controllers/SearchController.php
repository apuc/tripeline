<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Page;
use App\Models\Place;
use App\Models\Routes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;

class SearchController extends Controller {

    /**
     * Show route by ID.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function index( \Illuminate\Http\Request $request ) {
        try {
            $content = Page::query()->where([['slug', '=', 'search']])->first() ?? false;
            $data  = $request->all();
            $route = Routes::find( $data['route'] );

            $places = $route->places;

            $placesResponse = [];

            foreach ( $places as $item ) {
                if ( $item['status'] === 'enabled' ) {
                    $placesResponse[] = [
                        'id'              => $item['id'],
                        'title'           => $this->getTranslateContent( $item, 'title' ),
                        'body' => strip_tags($this->getTranslateContent($item, 'body')),

//                        'body' => substr(strip_tags($this->getTranslateContent($item, 'body')),0, 320) . ((strlen(strip_tags($this->getTranslateContent($item, 'body'))) > 320) ? '...' : ''),
                        'image'           => $item['image'],
                        'price'           => $item['price'],
                        'durations'       => $item['durations'],
                        'extra_durations' => $item['extra_durations'],
                        'price_per_hour' => $item['price_per_hour'],
                        'status'          => $item['status']
                    ];
                }
            }

            $cars = $route->cars;

            $from_city    = $route->fromCity;
            $from_country = $route->fromCountry;
            $to_city      = $route->toCity;

            return view( 'pages/search', [
                'currentRoute'       => $route,
                'currentRoutePlaces' => $placesResponse,
                'debug'              => [ 'data' => $data, 'route' => $route, 'places' => $placesResponse ],
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

        } catch ( \Throwable $t ) {
            return redirect($request->lang . '/request')->with('route', $request->all());  //view( 'pages/search', [ 'currentRoute' => [], 'debug' => $t->getMessage() ] )
        }
    }

    /**
     * getRoutePlaces list.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function getRoutePlaces( \Illuminate\Http\Request $request ) {
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
                        'body' => substr(strip_tags($this->getTranslateContent($item, 'body')),0, 150) . ((strlen(strip_tags($this->getTranslateContent($item, 'body'))) > 150) ? '...' : ''),
                        'image'           => $this->getImageBySize( '360x230', $item['image'] ),
                        'durations'       => $item['durations'],
                        'extra_durations' => $item['extra_durations'],
                        'price_per_hour' => $item['price_per_hour'],
                        'status'          => $item['status']
                    ];
                }
            }

            return $placesResponse;

        } catch ( \Throwable $t ) {
            return [ 'dd' => $t->getMessage() ];
        }
    }


    public function setRequest( \Illuminate\Http\Request $request ) {
//        $to_email = 'bogbuk@gmail.com';
        $to_email = 'route@mytripline.com';

        $data_send = $request->all();

        unset( $data_send['route'] );
        unset( $data_send['pm'] );
        unset( $data_send['am'] );

        try {
            Mail::send( 'emails.request', [ 'data_send' => $data_send ], function ( $message ) use (
                $to_email
            ) {
                $message->to( $to_email )
                        ->subject( 'TripLine Request' );
            } );

            return true;
        } catch ( Exception $e ) {
            return $e->getMessage();
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
}
