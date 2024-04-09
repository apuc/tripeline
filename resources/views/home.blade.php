@extends('layouts.app')

@section('content')
    <div class="homehead">
        <div class="homehead__top">
            <div class="container">
                <div class="homehead__wrap">
                    <div class="homehead__form">
                        <div class="form-block">
                            <div class="form-block__wrap">
                                <div class="label">@lang('widgets.calculator.title')</div>
                                <div class="homehead-form form-vue calc" id="homehead-form">
                                    <v-calculator :request="{{json_encode($_GET)}}" :short="true" mode="home">
                                    </v-calculator>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="homehead__content">
                        <h1>{!! __("home.home_title") !!}</h1>
                        <div class="homehead__content-label"><i><img src="/img/logo-min.svg" alt="IMG"></i>
                            <div>{!! __("home.home_sub_title") !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="homehead-info">
            <div class="container">
                <div class="homehead-info__grid">
                    <div class="homehead-info__item available-routes"><i><img src="/img/logo-min.svg" alt="IMG"></i>
                        <div>
                            <b>8000+ available routes</b>
                            <span>Choose the most effective<br> option to get where you need</span>
                        </div>
                    </div>
                    <div class="homehead-info__item"><i><img src="/img/map.svg" alt="IMG"></i>
                        <div>
                            {!! __("home.block_1.1") !!}
                        </div>
                    </div>
                    <div class="homehead-info__item"><i><img src="/img/milan-cathedral.svg" alt="IMG"></i>
                        <div>
                            {!! __("home.block_1.2") !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="b-video_old"
                {{--                 data-fancybox="" --}}
                {{--                 data-src="{{$content['embed_video'] ?? 'https://www.youtube.com/watch?v=NpEaa2P7qZI'}}"--}}
                {{--                 style="cursor:pointer; background: url({{ strlen($content['block_image']) > 0  ? $content['block_image'] : '../img/video-bg.jpg'}}) center center no-repeat; background-size: cover;}"--}}
        >
            <div class="video_box" style="text-align: center">
                <!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
                {{--                <div id="player"></div>--}}

                {{--                <script>--}}
                {{--                    // 2. This code loads the IFrame Player API code asynchronously.--}}
                {{--                    var tag = document.createElement('script');--}}

                {{--                    tag.src = "https://www.youtube.com/iframe_api";--}}
                {{--                   var firstScriptTag = document.getElementsByTagName('script')[0];--}}
                {{--                    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);--}}

                {{--                    // 3. This function creates an <iframe> (and YouTube player)--}}
                {{--                    //    after the API code downloads.--}}
                {{--                    var player;--}}

                {{--                    function onYouTubeIframeAPIReady() {--}}
                {{--                        player = new YT.Player('player', {--}}
                {{--                            height: '360',--}}
                {{--                            width: '640',--}}
                {{--                            videoId: 'l8bc3Ffqt0Q',--}}
                {{--                            events: {--}}
                {{--                                'onReady': onPlayerReady,--}}
                {{--                                'onStateChange': onPlayerStateChange--}}
                {{--                            }--}}
                {{--                        });--}}
                {{--                    }--}}

                {{--                    // 4. The API will call this function when the video player is ready.--}}
                {{--                    function onPlayerReady(event) {--}}
                {{--                        console.log('onPlayerReady');--}}
                {{--                        event.target.playVideo();--}}
                {{--                    }--}}

                {{--                    // 5. The API calls this function when the player's state changes.--}}
                {{--                    //    The function indicates that when playing a video (state=1),--}}
                {{--                    //    the player should play for six seconds and then stop.--}}
                {{--                    var done = false;--}}

                {{--                    function onPlayerStateChange(event) {--}}

                {{--                        console.log(event.data);--}}
                {{--                        if (event.data !== 1) {--}}
                {{--                            event.target.playVideo()--}}
                {{--                        }--}}
                {{--                        console.log('onPlayerStateChange: ', event);--}}
                {{--                        if (event.data == YT.PlayerState.PLAYING && !done) {--}}
                {{--                            setTimeout(stopVideo, 6000);--}}
                {{--                            done = true;--}}
                {{--                        }--}}
                {{--                    }--}}

                {{--                    function stopVideo() {--}}
                {{--                        player.stopVideo();--}}
                {{--                    }--}}
                {{--                </script>--}}

                {!! $content['embed_video'] !!}
            </div>
            {{--            <div class="container">--}}
            {{--                <h2>{!! $content['video_block_title'] ?? '' !!}</h2>--}}
            {{--            </div>--}}
        </section>
        <div class="homehead__bottom">
            <div class="container">
                <div class="homehead__list">
                    <div class="homehead__list-item"><i>
                            <svg class="icon">
                                <use xlink:href="/img/sprites/sprite.svg#luggage"></use>
                            </svg>
                        </i>

                        {!! __("home.block_2.1") !!}

                    </div>
                    <div class="homehead__list-item"><i>
                            <svg class="icon">
                                <use xlink:href="/img/sprites/sprite.svg#employees"></use>
                            </svg>
                        </i>
                        {!! __("home.block_2.2") !!}
                    </div>
                    <div class="homehead__list-item"><i>
                            <svg class="icon">
                                <use xlink:href="/img/sprites/sprite.svg#driver"></use>
                            </svg>
                        </i>
                        {!! __("home.block_2.3") !!}
                    </div>
                </div>
                <div class="homehead__info">
                    {!! __("home.block_3.1") !!}
                </div>
            </div>
        </div>
    </div>
    <div class="sliderhome-desktop-mob">
        <div class="sliderhome">
            <div class="sliderhome__wrap">
                <div class="container">
                    <h2 class="--fw-normal">{!! __("home.block_places_slider") !!}</h2>
                </div>
                <div class="sliderhome-slider swiper-container">
                    <!--   Additional required wrapper-->
                    <div class="swiper-wrapper">
                        <!-- Slides-->
                        @foreach($places as $item)

                            <div class="swiper-slide">
                                <a data-fancybox data-src="#hidden-content_{{$item['id']}}" href="javascript:;">
                                    <i><img src="{{$item['image']}}" alt="{{$item['title']}}"></i>
                                    <b>{{$item['title']}}</b>
                                    <div class="swipe_body" style="max-height: 190px">
                                        <p>
                                            {!! $item['body'] !!}
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <div style="display: none;" id="hidden-content_{{$item['id']}}">
                                <h2>{{$item['title']}}</h2>
                                <p>
                                    {!! $item['body'] !!}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="b-efbooking">
        <div class="b-efbooking__wrap">
            <div class="b-efbooking__bg">
                <h2>{!! __("home.block_4.title") !!}</h2>
                <div class="b-efbooking__item">
                    <i>
                        <img src="/img/touch-screen.svg" alt="touch-screen">
                    </i>
                    <div>{!! __("home.block_4.1") !!}</div>
                </div>
                <div class="b-efbooking__item">
                    <i>
                        <img src="/img/security.svg" alt="security">
                    </i>
                    <div>{!! __("home.block_4.2") !!}</div>
                </div>
            </div>
        </div>
    </div>
    @if($partners && count($partners) > 0)
        <section class="partners">
            {{--            <div class="container">--}}
            {{--                <div class="partners__wrap">--}}
            {{--                    <div class="partners__text">--}}
            {{--                        <h4>We made a decision in 2020 to equalize the opportunities and maintain the sameexperience for every customer via this simple system: To interconnect certain destinantions and simplify the travelling itself for everyone.</h4>--}}
            {{--                        <p>We supposed public transport might turn into stressful and time-consuming sport, which has encouraged us to come up with this idea: The services we are offering are managed into from A to B“ journeys, naturally mastered with high comfort and efficiency of the ride.</p>--}}
            {{--                        <div class="btn-border"><span>Read more</span>--}}
            {{--                            <svg class="icon">--}}
            {{--                                <use xlink:href="img/sprites/sprite.svg#arrow-long"></use>--}}
            {{--                            </svg>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            <div class="partners__slider">
                <div class="container">
                    <h2 class="--fw-400">{!! __("home.block_partners_slider") !!}</h2>
                </div>
                <div class="partners-slider swiper-container">
                    <!-- Additional required wrapper-->
                    <div class="swiper-wrapper" style="height: '118px'">
                        <!-- Slides-->
                        @foreach($partners as $partner)
                            <div class="swiper-slide">
                                <img src="{{$partner['image']}}" alt="{{$partner['title']}}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
    <section class="description">
        <div class="container">
            <div class="description__wrap">
                <div class="description__title">
                    <h3>Choose us</h3>
                </div>
                <div class="choose__list">
                    <div class="choose__item">
                        <div class="choose__background">
                            <svg width="299" height="378" viewBox="0 0 299 378" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_d_2952_3998)">
                                    <rect x="39" y="3" width="253" height="364" rx="15" stroke="#03ACD1" stroke-width="6" shape-rendering="crispEdges"/>
                                </g>
                                <rect x="36" y="153" width="6" height="53" fill="white"/>
                                <rect x="33.7507" y="147.32" width="44.1501" height="41.9172" transform="rotate(44.3643 33.7507 147.32)" stroke="#03ACD1" stroke-width="6"/>
                                <rect y="138" width="36" height="87" fill="white"/>
                                <path d="M50 178.5L29 198.852L29 158.148L50 178.5Z" fill="#03ACD1"/>
                                <defs>
                                    <filter id="filter0_d_2952_3998" x="32" y="0" width="267" height="378" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                        <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                        <feOffset dy="4"/>
                                        <feGaussianBlur stdDeviation="2"/>
                                        <feComposite in2="hardAlpha" operator="out"/>
                                        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2952_3998"/>
                                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2952_3998" result="shape"/>
                                    </filter>
                                </defs>
                            </svg>
                        </div>
                        <div class="choose__background_mobile">
                            <svg width="293" height="234" viewBox="0 0 293 234" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="289.83" y="31.4912" width="198.981" height="286.83" rx="15" transform="rotate(90 289.83 31.4912)" stroke="#03ACD1" stroke-width="6"/>
                                <rect x="164.83" y="26.4424" width="9" height="37" transform="rotate(90 164.83 26.4424)" fill="white"/>
                                <rect x="169.587" y="26.7219" width="33.6904" height="31.9232" transform="rotate(134.364 169.587 26.7219)" stroke="#03ACD1" stroke-width="6"/>
                                <rect x="180.83" width="28.4915" height="68.8546" transform="rotate(90 180.83 0)" fill="white"/>
                                <path d="M146.231 39.5713L130.124 22.9512L162.338 22.9512L146.231 39.5713Z" fill="#03ACD1"/>
                            </svg>
                        </div>
                        <div class="choose__content">
                            <div class="choose__header">
                                <div class="choose__icon">
                                    <img src="/img/choose1.svg" alt="icon">
                                </div>
                                <div class="choose__title">
                                    <span>SAFETY</span>
                                </div>
                            </div>
                            <div class="choose__text">
                                <p>Each driver working for MYTRIPLINE has gone through the strict selection procedure. Speaking of our highest aim, getting your from A to B point while offering the greatest possible comfort meets up your and our desire. Time spent on getting to your final point does not have to be time wasted. With us it is simply enjoyable.</p>
                            </div>
                        </div>
                    </div>
                    <div class="choose__item">
                        <div class="choose__background">
                            <svg width="299" height="378" viewBox="0 0 299 378" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_d_2952_3998)">
                                    <rect x="39" y="3" width="253" height="364" rx="15" stroke="#EC824A" stroke-width="6" shape-rendering="crispEdges"/>
                                </g>
                                <rect x="36" y="153" width="6" height="53" fill="white"/>
                                <rect x="33.7507" y="147.32" width="44.1501" height="41.9172" transform="rotate(44.3643 33.7507 147.32)" stroke="#EC824A" stroke-width="6"/>
                                <rect y="138" width="36" height="87" fill="white"/>
                                <path d="M50 178.5L29 198.852L29 158.148L50 178.5Z" fill="#EC824A"/>
                                <defs>
                                    <filter id="filter0_d_2952_3998" x="32" y="0" width="267" height="378" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                        <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                        <feOffset dy="4"/>
                                        <feGaussianBlur stdDeviation="2"/>
                                        <feComposite in2="hardAlpha" operator="out"/>
                                        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2952_3998"/>
                                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2952_3998" result="shape"/>
                                    </filter>
                                </defs>
                            </svg>
                        </div>
                        <div class="choose__background_mobile">
                            <svg width="293" height="234" viewBox="0 0 293 234" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="289.83" y="31.4912" width="198.981" height="286.83" rx="15" transform="rotate(90 289.83 31.4912)" stroke="#EC824A" stroke-width="6"/>
                                <rect x="164.83" y="26.4424" width="9" height="37" transform="rotate(90 164.83 26.4424)" fill="white"/>
                                <rect x="169.587" y="26.7219" width="33.6904" height="31.9232" transform="rotate(134.364 169.587 26.7219)" stroke="#EC824A" stroke-width="6"/>
                                <rect x="180.83" width="28.4915" height="68.8546" transform="rotate(90 180.83 0)" fill="white"/>
                                <path d="M146.231 39.5713L130.124 22.9512L162.338 22.9512L146.231 39.5713Z" fill="#EC824A"/>
                            </svg>
                        </div>
                        <div class="choose__content">
                            <div class="choose__header">
                                <div class="choose__icon">
                                    <img src="/img/choose2.svg" alt="icon">
                                </div>
                                <div class="choose__title">
                                    <span>QUALITY</span>
                                </div>
                            </div>
                            <div class="choose__text">
                                <p>To our precious clients, Mytripline guarantees every single ride to be a comfortable and delightful experience when facilitated through our company. In this respect, we pay full attention to the quality and cleanliness of our vehicles and driver selection, who will become your guide throughtout the journey. </p>
                            </div>
                        </div>
                    </div>
                    <div class="choose__item">
                        <div class="choose__background">
                            <svg width="299" height="378" viewBox="0 0 299 378" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_d_2952_3998)">
                                    <rect x="39" y="3" width="253" height="364" rx="15" stroke="#2AA64B" stroke-width="6" shape-rendering="crispEdges"/>
                                </g>
                                <rect x="36" y="153" width="6" height="53" fill="white"/>
                                <rect x="33.7507" y="147.32" width="44.1501" height="41.9172" transform="rotate(44.3643 33.7507 147.32)" stroke="#2AA64B" stroke-width="6"/>
                                <rect y="138" width="36" height="87" fill="white"/>
                                <path d="M50 178.5L29 198.852L29 158.148L50 178.5Z" fill="#2AA64B"/>
                                <defs>
                                    <filter id="filter0_d_2952_3998" x="32" y="0" width="267" height="378" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                        <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                        <feOffset dy="4"/>
                                        <feGaussianBlur stdDeviation="2"/>
                                        <feComposite in2="hardAlpha" operator="out"/>
                                        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2952_3998"/>
                                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2952_3998" result="shape"/>
                                    </filter>
                                </defs>
                            </svg>
                        </div>
                        <div class="choose__background_mobile">
                            <svg width="293" height="234" viewBox="0 0 293 234" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="289.83" y="31.4912" width="198.981" height="286.83" rx="15" transform="rotate(90 289.83 31.4912)" stroke="#2AA64B" stroke-width="6"/>
                                <rect x="164.83" y="26.4424" width="9" height="37" transform="rotate(90 164.83 26.4424)" fill="white"/>
                                <rect x="169.587" y="26.7219" width="33.6904" height="31.9232" transform="rotate(134.364 169.587 26.7219)" stroke="#2AA64B" stroke-width="6"/>
                                <rect x="180.83" width="28.4915" height="68.8546" transform="rotate(90 180.83 0)" fill="white"/>
                                <path d="M146.231 39.5713L130.124 22.9512L162.338 22.9512L146.231 39.5713Z" fill="#2AA64B"/>
                            </svg>
                        </div>
                        <div class="choose__content">
                            <div class="choose__header">
                                <div class="choose__icon">
                                    <img src="/img/choose3.svg" alt="icon">
                                </div>
                                <div class="choose__title">
                                    <span>RELIABILITY</span>
                                </div>
                            </div>
                            <div class="choose__text">
                                <p>The journey always sets off on time. Our drivers will get you from A to B point while choosing the most comfortable route from multiple possibilities. This journey provided by our company also adds the best stops at must-see destinations. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="countries">
        <div class="container">
            <div class="countries__wrap">
                <h2>{!! __("home.block_6.title") !!}</h2>
                <div class="countries__form">
                    <div class="form-block">
                        <div class="form-block__wrap">
                            <div class="label">{!! __("widgets.calculator.title") !!}</div>
                            <div class="homehead homehead-form form-vue calc" id="homehead-form">
                                <v-calculator :request="{{json_encode($_GET)}}" :short="true" mode="home">
                                </v-calculator>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="countries__img">
                    <img class="desktop" src="/img/map-color.svg" alt="desktop_map">
                    <img class="mobile" src="/img/map-color.svg" alt="mobile_map">
                </div>
            </div>
        </div>
        <div class="map-btn">
            <a href="#app">
                <button class="btn-submit map-btn">
                    <span>travel with us</span>
                </button>
            </a>
        </div>
    </section>
@endsection
<style>

    @media(max-width: 750px) {
        .video_box > iframe {
            width: 100%!important;
            height: calc(100vw / 1.77);
        }

        .video_box{
            padding: 20px 0!important;
        }
    }

    .partners__slider .swiper-wrapper {
    }

    .swiper-wrapper {
        height: auto !important;
    }

    .swiper-wrapper a {
        text-decoration: none;
        text-transform: none;
        color: #000000;
        outline-offset: unset;

    }
</style>
