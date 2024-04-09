@extends('layouts.app')

@section('content')
    <section class="routes">
        <div class="routes-header">
            <div class="container">
                <h2>{!! __("routes.routes") !!}</h2><span>{!! __("routes.select_a_country") !!}</span>
            </div>
        </div>
        <div class="routes-content container">
            @php($routesData = ceil($routes->count() / 4))
            @foreach($routes->chunk($routesData) as $three)
                <ul>
                    @foreach($three as $country)
                        <script>
                            let p = {{$country['name']}}.replace(/\(.+\)./,'')
                            console.log(p)
                        </script>
                        <li><a href="{{ route('routes', app()->getLocale()) . '/' . $country['id'] }}">{{ $country['name'] }}</a></li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </section>
@endsection
