<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $content['meta_title'] ?? config('app.name', 'TripLine')  }}</title>
    <meta name="robots" content="noindex" />
    <!-- CSRF Token -->
    <meta name="description"
          content="{{ $content['meta_description'] ?? ''  }}">
    <meta name="keywords"
          content="{{ $content['meta_keywords'] ?? ''  }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#fff">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/favicons/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" sizes="16x16" href="{{ asset('img/favicons/favicon-16x16.png') }}" type="image/png">
    <link rel="icon" sizes="32x32" href="{{ asset('img/favicons/favicon-32x32.png') }}" type="image/png">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('img/favicons/apple-touch-icon-precomposed.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/favicons/apple-touch-icon.png') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('img/favicons/apple-touch-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('img/favicons/apple-touch-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('img/favicons/apple-touch-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/favicons/apple-touch-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('img/favicons/apple-touch-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('img/favicons/apple-touch-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('img/favicons/apple-touch-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('img/favicons/apple-touch-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('img/favicons/apple-touch-icon-167x167.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicons/apple-touch-icon-180x180.png') }}">
    <link rel="apple-touch-icon" sizes="1024x1024" href="{{ asset('img/favicons/apple-touch-icon-1024x1024.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&amp;display=swap" rel="stylesheet">


    <link rel="stylesheet" href="{{ asset('styles/main.min.css') }}">

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-MDMWS9J7');</script>
    <!-- End Google Tag Manager -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" defer></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/css/intlTelInput.css" rel="stylesheet">

    <script async src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyDT5h2sEd76op2hB_4ML6yiWJ6aka-oFRU"></script>

    <script src="https://js.stripe.com/v3/" defer></script>


    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery-inputmask.min.js') }}" defer></script>
    <script>
        window.App = {!! json_encode([
        'user' => Auth::user(),
        'signedIn' => Auth::check(),
        'language' => app()->getLocale()
    ]) !!};
    </script>

    <style>

        .header__logout{
            padding-left: 5px;
        }

        .header__logout a {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 100%
        }

        .header__logout a:hover svg {
            fill: #ec824a
        }

        .header__logout a svg {
            width: 14px;
            height: 14px;
            -webkit-transition: .2s;
            -o-transition: .2s;
            transition: .2s;
            fill: rgba(255,255,255,.7)
        }
    </style>
    <link rel="stylesheet" href="{{ asset('styles/extend.css') }}">
</head>
<body>
<style>
    .swiper-wrapper a:hover, .swiper-wrapper a:active, .swiper-wrapper a:link {
        outline: 0 !important;
        text-decoration: none !important;
    }

</style>
<div id="app">
    <v-init auth="{{(Auth::check())}}"></v-init>
    <svg style="width: 0; height: 0; overflow: hidden; position: absolute; left: -100%;" width="120" height="240" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <lineargradient id="gradient">
                <stop offset="0%" stop-color="#ec824a"></stop>
                <stop offset="100%" stop-color="#F6B22E"></stop>
            </lineargradient>
        </defs>
    </svg>
    <header class="header">
        <div class="container">
            <div class="header__wrap">
                <a class="header__logo"
                   href="{{ route('home', app()->getLocale()) }}">
                    <img src="{{asset('img/logo.png')}}" alt="logo">
                </a>
                <div class="header__menu">
                    <nav>
                        <ul>
                            <li><a href="{{ route('company', app()->getLocale()) }}">{!! __("menu.company") !!}</a></li>
                            <li><a href="#">{!! __("menu.discover") !!}</a></li>
                            <li><a href="https://mytripline.com/sightseeing?en">Sightseeing tour</a></li>
                            <li><a href="{{ route('routes', app()->getLocale()) }}">{!! __("menu.routes") !!}</a></li>
                            <li><a href="{{ route('partner', app()->getLocale()) }}">{!! __("menu.become_a_partner") !!}</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="header__control">
                    <div class="header__currency">
                        <div class="custom-select">
                            <v-currency-select
                                {{--                                currencylist="{{json_encode(Config::get('app.currency_list'))}}"--}}
                                currencylist="{{json_encode(\App\Helper\Helper::getCurrencyList())}}"
                                currentcurrency="{{\App\Helper\Helper::getCurrency()}}"
                                currentcurrencyexchanges="{{json_encode(\App\Helper\Helper::getCurrencyExchanges())}}"
                            ></v-currency-select>
                        </div>
                    </div>
                    <div class="header__lang">
                        <div class="custom-select --right-desktop">
                            <v-language-select
                                currentlang="{{App::getLocale()}}"
                                languages="{{json_encode(Config::get('languages'))}}"
                            ></v-language-select>
                        </div>
                    </div>
                    @auth
{{--                        <div class="header__logout">--}}
{{--                            <a href="{{ route('logout', app()->getLocale()) }}" alt="close">--}}
{{--                                <svg class="icon">--}}
{{--                                    <use xlink:href="/img/sprites/sprite.svg#close"></use>--}}
{{--                                </svg>--}}
{{--                            </a>--}}
{{--                        </div>--}}
                    @endauth

                    @auth
                        <div class="header__user">
                            <a href="{{ route('cabinet', app()->getLocale()) }}" alt="user">
                                <svg class="icon">
                                    <use xlink:href="/img/sprites/sprite.svg#user"></use>
                                </svg>
                            </a>
                        </div>
                    @else
                        <div class="header__user">
                            <a id="auth_button" href="#login">
                                <svg class="icon">
                                    <use xlink:href="/img/sprites/sprite.svg#user"></use>
                                </svg>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
            <div class="header__mobile">
                <div class="header__burger js-menu-burger"><span><i></i></span></div>
                <a class="header__mobile-logo" href="{{ route('home', app()->getLocale()) }}">
                    <img src="/img/svg/logo.svg" alt="logo">
                </a>
                <div class="header__user">
                    @auth
                        <a href="{{ route('cabinet', app()->getLocale()) }}" alt="user">
                            <svg class="icon">
                                <use xlink:href="/img/sprites/sprite.svg#user"></use>
                            </svg>
                        </a>
                    @else
                        <a id="auth_button" href="#login">
{{--                        <a data-fancybox data-src="#login" alt="user">--}}
                            <svg class="icon">
                                <use xlink:href="/img/sprites/sprite.svg#user"></use>
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    @yield('content')
    <x-footer/>
    @auth

    @else

        <v-popups
            csrf="{{csrf_token()}}"
        ></v-popups>
        {{--        <div class="popup --sm popup-forgotpass" id="forgotpass">--}}
        {{--            <div class="popup__wrap">--}}
        {{--                <h3 class="--center">{{ __('Forgot password') }}</h3>--}}
        {{--                <form class="js-form-validator">--}}
        {{--                    <div class="input-block">--}}
        {{--                        <input type="email" placeholder="Email" name="email" required>--}}
        {{--                    </div>--}}
        {{--                    <button class="btn-submit --simple --no-opacity"><span>{{ __('Save') }}</span></button>--}}
        {{--                </form>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div class="popup --sm popup-registration" id="registration">--}}
        {{--            <div class="popup__wrap">--}}
        {{--                <h3 class="--center">{{ __('Register') }}</h3>--}}
        {{--                <form method="POST" class="js-form-validator" action="{{ route('register', app()->getLocale()) }}">--}}
        {{--                    @csrf--}}
        {{--                    <div class="input-block">--}}
        {{--                        <input type="email" placeholder="Email" name="email" required>--}}
        {{--                    </div>--}}
        {{--                    <div class="input-block">--}}
        {{--                        <input type="password" placeholder="Password" name="password" required>--}}
        {{--                    </div>--}}
        {{--                    <div class="input-block">--}}
        {{--                        <input type="password" placeholder="Password confirm" name="re_password" required>--}}
        {{--                    </div>--}}
        {{--                    <button class="btn-submit --simple --no-opacity"><span>{{ __('Register') }}</span></button>--}}
        {{--                </form>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div class="popup --sm popup-login" id="login">--}}
        {{--            <div class="popup__wrap">--}}
        {{--                <h3 class="--center">{{ __('Login in') }}</h3>--}}
        {{--                <form method="POST" class="js-form-validator" action="{{ route('login', app()->getLocale()) }}">--}}
        {{--                    @csrf--}}
        {{--                    <div class="input-block">--}}
        {{--                        <input type="email" placeholder="Email" name="email" required>--}}
        {{--                    </div>--}}
        {{--                    <div class="input-block">--}}
        {{--                        <input type="password" placeholder="Password" name="password" required>--}}
        {{--                    </div>--}}
        {{--                    <button class="btn-submit --simple --no-opacity"><span>{{ __('Save') }}</span></button>--}}
        {{--                    <a data-fancybox data-src="#forgotpass">Forgot password</a>--}}
        {{--                    <a data-fancybox data-src="#registration">Registration</a>--}}
        {{--                </form>--}}
        {{--            </div>--}}
        {{--        </div>--}}
    @endauth
</div>
<!-- Messenger Plugin chatu Code -->
<div id="fb-root"></div>

<!-- Your Plugin chatu code -->
<div id="fb-customer-chat" class="fb-customerchat">
</div>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MDMWS9J7"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<script>
  var chatbox = document.getElementById('fb-customer-chat');
  chatbox.setAttribute("page_id", "100508448639256");
  chatbox.setAttribute("attribution", "biz_inbox");
</script>

<!-- Your SDK code -->
<script>
  window.fbAsyncInit = function() {
    FB.init({
      xfbml            : true,
      version          : 'v14.0'
    });
  };

  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
</body>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js" defer></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/intlTelInput.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.8/jquery.inputmask.bundle.min.js" defer></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/css/intlTelInput.css" rel="stylesheet">
{{--<script id="stripe-js" src="https://js.stripe.com/v3/" async></script>--}}


</html>
