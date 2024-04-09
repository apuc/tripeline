<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller {
    /**
     * Validate and update the given user's profile information.
     *
     * @param mixed $user
     * @param array $input
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
//    public function update( $user, array $input ) {
    public function update( \Illuminate\Http\Request $request ) {
        $data = $request->all();

        try {
            Validator::make( $data, [
                'first_name'   => [ 'required', 'string', 'max:255' ],
                'last_name'    => [ 'nullable', 'string', 'max:32' ],
                'day_of_birth' => [ 'nullable', 'string', 'max:32' ],
                'full_number'  => [ 'nullable', 'string', 'max:32' ],
            ] )->validate();
        } catch ( \Exception $e ) {
            print_r( $e->getMessage() );
        }
        $user = Auth::user();

        $user->forceFill( [
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'day_of_birth' => date( 'Y-m-d', strtotime( $data['day_of_birth'] ) ),
            'full_number'  => $data['full_number'],
        ] )->save();

        return view( 'pages/cabinet',
            [
                'user'   => Auth::user(),
                'orders' => \App\Models\RouteOrder::select()->where( 'user_id', Auth::user()->id )->get()
            ]
        );
    }

    public function cabinet( \Illuminate\Http\Request $request ) {
        if ( Auth::check() ) {
            return view( 'pages/cabinet',
                [
                    'user'   => Auth::user(),
                    'orders' => \App\Models\RouteOrder::select()->where( 'user_id', Auth::user()->id )->get()
                ]
            );
        } else {
            return redirect()->guest(route('home', '#login', app()->getLocale()));
        }
    }

    public function cabinet_api( \Illuminate\Http\Request $request ) {

        if ( Auth::check() ) {
            return
                [
                    'user'   => [
                        "email" => Auth::user()->email,
                        "first_name" => Auth::user()->first_name,
                        "last_name" => Auth::user()->last_name,
                        "day_of_birth" => Auth::user()->day_of_birth,
                    ],
                    'orders' => \App\Models\RouteOrder::select()->where( 'user_id', Auth::user()->id )->get()
                ];
        } else {
            return redirect()->guest(route('home', '#login', app()->getLocale()));
        }


    }
}
