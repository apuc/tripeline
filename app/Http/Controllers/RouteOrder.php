<?php

namespace App\Http\Controllers;

use App\Models\PlacesRouteOrders;
use App\Models\UserDevice;
use App\Models\RouteOrderCar;
use Illuminate\Http\Request;
use TheSeer\Tokenizer\Exception;
use App\Helper\NotificationHelper;

class RouteOrder extends Controller {
    //

    /**
     * Save order.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string[]
     */
    public function save( \Illuminate\Http\Request $request ) {

        try {
            $data = $request->all();
            $data['user_id'] = \Auth::user()->id ?? 1;
            $userDevices = UserDevice::all();
            $routeOrder = new \App\Models\RouteOrder();


            $routeOrder->user_id          = $data['user_id'] ?? 0;
            $routeOrder->email            = $data['order_details']['email'] ?? '';
            $routeOrder->first_name       = $data['order_details']['first_name'] ?? '';
            $routeOrder->last_name        = $data['order_details']['last_name'] ?? '';
            $routeOrder->phone            = $data['order_details']['phone'] ?? '';
            $routeOrder->comment          = $data['order_details']['comment'] ?? '';
            $routeOrder->pickup_address   = $data['order_details']['pickup_address'] ?? '';
            $routeOrder->drop_off_address = $data['order_details']['drop_off_address'] ?? '';
            $routeOrder->currency         = $data['currency'] ?? 'eur';
            $routeOrder->route_id         = $data['route_id'];
            $routeOrder->route_date       = $data['route_date'];
            $routeOrder->amount           = $data['total'];
            $routeOrder->adults           = $data['adults'];
            $routeOrder->childrens        = $data['childrens'];
            $routeOrder->luggage          = $data['luggage'];
            $routeOrder->payment_type     = $data['payment_type'];
            $routeOrder->phone            = $data['phone'];
            $routeOrder->currency         = $data['currency'] ?? 'EUR';
            $routeOrder->save();

            foreach ( $data['cars'] as $c ) {
                $car = new RouteOrderCar();

                $car->route_id = $routeOrder->id;
                $car->car_id = $c['id'];

                $car->save();
            }

            foreach ( $data['places'] as $p ) {
                $place = new PlacesRouteOrders();

                $place->places_route_order_id = $routeOrder->id;
                $place->place_id              = $p['id'];
                $place->durations             = $p['durations'];
                $place->price                 = $p['price'];

                $place->save();

            }


            if ( isset( $data['payment_type'] ) && $data['payment_type'] === 1 ) {
                $stripe = new \Stripe\StripeClient( env( 'STRIPE_SECRET_API' ) );

                $token = $data['stripe_token'];

                $charge = $stripe->charges->create( [
                    'amount'      => (int) $routeOrder->amount * 100,
                    'currency'    => 'usd',
                    'description' => 'Example charge',
                    'source'      => $token,
                    'metadata'    => [ 'order_id' => $routeOrder->id ]
                ]);


                if ( $charge['status'] && $charge['status'] === 'succeeded' ) {
                    $routeOrder->status = 'complete';
                    $routeOrder->save();
                } else {
                    $routeOrder->status = 'fail';
                    $routeOrder->save();
                }

                foreach ($userDevices as $userDevice) {
                    NotificationHelper::send($userDevice->token, 'Mytripline Driver', 'New trip!');
                }
                return [ 'status' => 'success', 'path' => 'order-success' ];
            }

            foreach ($userDevices as $userDevice) {
                NotificationHelper::send($userDevice->token, 'Mytripline Driver', 'New trip!');
            }

            return [ 'status' => 'success', 'path' => 'order-success-manual' ];
        } catch ( \Throwable $e ) {
            return [ 'status' => 'success', 'path' => 'error', 'message' => $e->getMessage() ];
        }
    }

    public function get_payment_token( \Illuminate\Http\Request $request ) {

        try {
            $data = $request->all();
            if ( isset( $data['payment_type'] ) && $data['payment_type'] === 1 ) {
                \Stripe\Stripe::setApiKey( env( 'STRIPE_SECRET_API' ) );
                $paymentIntent = \Stripe\PaymentIntent::create( [
                    'amount'                    => (int) $data['total'] * 100,
                    'currency'                  => 'eur',
                    'automatic_payment_methods' => [
                        'enabled' => true,
                    ],
                ] );
            }

            return [ 'status' => 'success', 'clientSecret' => $paymentIntent->client_secret ];


        } catch ( \Throwable $e ) {
            return [ 'status' => 'success', 'path' => 'error', 'message' => $e->getMessage() ];
        }
//        return $data;
    }

    function generateResponse( $intent ) {
        # Note that if your API version is before 2019-02-11, 'requires_action'
        # appears as 'requires_source_action'.
        if ( $intent->status == 'requires_action' &&
             $intent->next_action->type == 'use_stripe_sdk' ) {
            # Tell the client to handle the action
            echo json_encode( [
                'requires_action'              => true,
                'payment_intent_client_secret' => $intent->client_secret
            ] );
        } else if ( $intent->status == 'succeeded' ) {
            # The payment didn’t need any additional actions and completed!
            # Handle post-payment fulfillment
            echo json_encode( [
                "success" => true
            ] );
        } else {
            # Invalid status
            http_response_code( 500 );
            echo json_encode( [ 'error' => 'Invalid PaymentIntent status' ] );
        }
    }

    public function testNotification()
    {
        return NotificationHelper::send('eAR3kQt1ln0:APA91bFFtVEuIcwNzTQ-XqvCKNGAbooSlyd2Kto91kTiytO6qsWRIFtaE2gxJ8iCGgn8CrHil8wvbOOiBPnrc0448A61vxNLxEiyOKGChG5RQy-7gnKSu_EzVHIfz4mLTTvRsz_GWhuT', 'Mytripline Driver', 'New trip!');
    }
}
