@extends('layouts.app')

@section('content')
    <section class="pabout">
        <div class="agency_img_a banner__top">
            <div class="container">
                <h1 style="font-family: 'Roboto';color: white;text-align:center;font-style: normal;font-weight: 700;line-height: 30px;font-size: 40px;">Boost your business with MYTRIPLINE. </h1>
            </div>
        </div>
        <div class="banner__bottom">
            <img src="/img/banner_bot_orange.svg" alt="banner">
        </div>
    </section>

    <section class="partner_description">
        <div class="t__conteiner">
            <div class="btn-t_agency">
                <a href="{{ route('a_travel_agency', app()->getLocale()) }}.html#block-form">
                    <img class="img_agency" src="/img/OGroup3.png">
                </a>
            </div>
            <div class="t__content">
                <h1>Do you own a hotel chain ? Do you need<br> to secure transport for your customers ? </h1>
            </div>
        </div>
    </section>

    <section class="pabout">
        <header class="group_agency_h">
            <div class="container">
                <h1 style="text-align:center;font-family: 'Roboto';font-style: normal;font-weight: 700;line-height: 40px;font-size: 32px; padding-top: 15%;">Do you consider working with us ? Are you a travel agency ? You are in<br> the right place ! If this offer strikes you, you have to take few steps.<br> There is a register system you need to find and we will reach out to you<br> within 24 hours to give you further instructions.</h1>
                <h1 style="text-align:center;font-family: 'Roboto';font-style: normal;font-weight: 700;line-height: 40px;font-size: 32px; padding-top: 50px;">We are thrilled to work with you</h1>
            </div>
        </header>
    </section>

    <section class="coopirate">
        <div class="coopirate__title">
            <h1>Why to cooperate with MYTRIPLINE?</h1>
        </div>
        <div class="coopirate__wrapper">
            <div class="coopirate__inner">
                <div class="coopirate__item orange">
                    <img src="/img/coopirate_img-1.svg" alt="icon">
                    <h5>Develop the possibilities of MYTRIPLINE</h5>
                    <p>We are ready to work with you. Every day there are dozens of companies, hotels and agencies agreeing upon a collabora=on with us. Our technology brings up the possibility for your customers to travel around Europe.</p>
                </div>
                <div class="coopirate__item orange">
                    <img src="/img/coopirate_img-2.svg" alt="icon">
                    <h5>Entire control in your hands</h5>
                    <p>Everything within our system is entirely under your control. We give you the opportunity to work with our offers according to your desires.</p>
                </div>
                <div class="coopirate__item orange">
                    <img src="/img/coopirate_img-3.svg" alt="icon">
                    <h5>Your way up</h5>
                    <p>Our team serves as a bridge connecting perfect organization with your successful business. You will be regularly informed of any events and upgrades.</p>
                </div>
                <div class="coopirate__item orange">
                    <img src="/img/coopirate_img-4.svg" alt="icon">
                    <h5>MYTRIPLINE is comfortable</h5>
                    <p>There are no issues with Mytripline : a straightforward web and application for our business partners, no entry fees, no wasted time to fill the papers of a journey. All that you have to do is to start collaborating with us and we will take care of the rest.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="h_a_vectra"></div>

    <section class="coopirate">
        <div>
            <h1>How it works </h1>
        </div>
        <div class="coopirate__content">
            <img class="coopirate__content__img desktop-scheme" src="/img/OGroup2.jpg">
            <img class="coopirate__content__img mobile-scheme" src="/img/scheme-orange.png">
        </div>
    </section>

    <div class="h_a_vectra"></div>

    <section>
        <div class="conditions_conteiner">
            <div class="conditions_content_l">
                <h1>Conditions for cooperation with MYTRIPLINE</h1>
                <p>All partners have a duty to comply with the conditions for cooperation of our company as well as a basic<br> knowledge of English language. Simply to become a<br> crucial member of MYTRIPLINE.</p>
            </div>


            <div class="btn_partner">
                <style type="text/css"> a[name="travel"]
                {
                    text-decoration: none;
                    display: inline-block;
                    padding: 25px 120px;
                    border-radius: 10px;
                    background-image: linear-gradient(45deg, #ff5c00 0%, #d94f00 50%, #682600 100%) !important;
                    background-position: 100% 0;
                    background-size: 200% 200%;
                    font-family: 'Montserrat', sans-serif;
                    font-size: 18px;
                    font-weight: 300;
                    color: white;
                    box-shadow: 0 16px 32px 0 rgba(0, 40, 120, .35);
                    transition: .5s;
                }
                a[name="travel"]:hover {box-shadow: 0 0 0 0 rgba(0, 40, 120, 0);background-position: 0 0;}
                </style>
                <a type="button" class="travel__btn travel__btn3" name="travel" href="{{ route('a_travel_agency', app()->getLocale()) }}.html#block-form">Become a Travel agency</a>
            </div>
        </div>
@endsection
