<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use LaravelApi\Facade as Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Api::get( 'login', 'Controller@action' )
   ->setSummary( 'My operation summary' )
   ->setDescription( 'My operation description' )
   ->addTag( 'api' )
   ->setConsumes( [ 'application/json' ] )
   ->setProduces( [ 'application/json' ] );
Api::basicAuthSecurity( 'basic_auth' );

Route::get( '/login', function () {
    return true;
}
)->middleware( 'auth.basic.once' );


Api::post( 'signup', 'RegisterController@register' )
   ->addBodyParameter( 'Object', '{
    "email": "",
    "password": "",
    "password_confirmation": ""
}', true )
   ->setDescription( 'Create new customer' )
   ->addTag( 'signup' )
   ->setConsumes( [ 'application/json' ] )
   ->setProduces( [ 'application/json' ] );
Route::post( '/signup', [ \App\Http\Controllers\Auth\RegisterController::class, 'register' ] )->name( 'register' );
//Route::post( '/signup', [ \App\Http\Controllers\Auth\RegisterController::class, 'register_api' ] )->name( 'register_api' );

//'Auth\RegisterController@register'


Api::post( 'get_route_places', 'SearchController@getRoutePlaces' )
   ->addQueryParameter( 'route', function ( $param ) {
       $param->setDescription( 'Route ID' )
             ->setType( 'integer' )
             ->setFormat( 'int32' );
   } )
   ->setSummary( 'My operation summary' )
   ->setDescription( 'My operation description' )
   ->addTag( 'api' )
   ->setConsumes( [ 'application/json' ] )
   ->setProduces( [ 'application/json' ] );
Route::post( '/get_route_places', [ \App\Http\Controllers\SearchController::class, 'getRoutePlaces' ] );

Api::post( 'get_all_routes', 'RoutesController@getAllRoutes' )
   ->setSummary( 'My operation summary' )
   ->setDescription( 'My operation description' )
   ->addTag( 'api' )
   ->setConsumes( [ 'application/json' ] )
   ->setProduces( [ 'application/json' ] );
Route::post( '/get_all_routes', [ \App\Http\Controllers\RoutesController::class, 'getAllRoutes' ] );

Api::post( 'get_route', 'RoutesController@getRoute' )
   ->setSummary( 'My operation summary' )
   ->setDescription( 'My operation description' )
   ->addTag( 'api' )
   ->setConsumes( [ 'application/json' ] )
   ->setProduces( [ 'application/json' ] );
Route::post( '/get_route', [ \App\Http\Controllers\RoutesController::class, 'getRoute' ] );


Api::post( 'set_request', 'SearchController@setRequest' )
   ->setSummary( 'My operation summary' )
   ->setDescription( 'My operation description' )
   ->addTag( 'api' )
   ->setConsumes( [ 'application/json' ] )
   ->setProduces( [ 'application/json' ] );
Route::post( '/set_request', [ \App\Http\Controllers\SearchController::class, 'setRequest' ] );


Api::post( 'set_order', 'RouteOrder@save' )
   ->setSummary( 'My operation summary' )
   ->setDescription( 'My operation description' )
   ->addTag( 'api' )
   ->setConsumes( [ 'application/json' ] )
   ->setProduces( [ 'application/json' ] );
Api::basicAuthSecurity( 'basic_auth' );
Route::post( '/set_order', [ \App\Http\Controllers\RouteOrder::class, 'save' ] )
     ->name( 'api_save_order' )
     ->middleware( 'auth.basic.once' );


Api::post( 'user' )
   ->setSummary( 'My operation summary' )
   ->setDescription( 'My operation description' )
   ->addTag( 'api' )
   ->setConsumes( [ 'application/json' ] )
   ->setProduces( [ 'application/json' ] );
Api::basicAuthSecurity( 'basic_auth' );
Route::get( '/user',
    function () {
        return [
            'user' => [
                "email"        => Auth::user()->email,
                "first_name"   => Auth::user()->first_name,
                "last_name"    => Auth::user()->last_name,
                "day_of_birth" => Auth::user()->day_of_birth,
            ],
        ];
    }
)->middleware( 'auth.basic.once' );


Api::get( 'cabinet', '\App\Http\Controllers\UserController@cabinet_api' )
   ->setSummary( 'My operation summary' )
   ->setDescription( 'My operation description' )
   ->addTag( 'api' )
   ->setConsumes( [ 'application/json' ] )
   ->setProduces( [ 'application/json' ] );
Api::basicAuthSecurity( 'basic_auth' );
Route::get( '/cabinet', [ \App\Http\Controllers\UserController::class, 'cabinet_api' ] )
     ->name( 'cabinet_api' )
     ->middleware( 'auth.basic.once' );


Route::post( '/partners/add', [ \App\Http\Controllers\PartnerController::class, 'add' ] );
Route::get( '/partners/list', [ \App\Http\Controllers\PartnerController::class, 'list' ] );


Route::post( '/message/add', [ \App\Http\Controllers\MessageController::class, 'add' ] );

Route::post( '/routes', [ \App\Http\Controllers\RoutesController::class, 'getByPlace' ] );
Route::post( '/cities', [ \App\Http\Controllers\RoutesController::class, 'getCities' ] );


