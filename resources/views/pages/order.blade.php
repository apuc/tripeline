@extends('layouts.app')

@section('content')
    <div class="header-block --border" style="background-image: url({{asset('img/order-bg.jpg')}});">
        <div class="container">
            <div class="header-block__wrap">
                <h1>Complete your booking</h1>
            </div>
        </div>
    </div>
    <v-order-route :payment_methods="{{@\GuzzleHttp\json_encode($payments_methods)}}"
                   csrf="{{csrf_token()}}"
                   @if(Auth::check())
                       :user="{{Auth::user()}}"
                   @endif
    ></v-order-route>
@endsection
