@extends('layouts.app')

@section('content')
    <section class="pabout">
        <header class="part_img_d">
            <div class="container">
                <div class="pabout__inner">
                    <v-reg-partner></v-reg-partner>
                    <h1 style="font-family: 'Roboto';font-style: normal;font-weight: 700;line-height: 55px;font-size: 40px;">Do you enjoy driving and meeting new<br> people from all over the world ?
                    </h1>
                </div>
            </div>
        </header>
    </section>

    <section class="partner_description">
        <div class="partner__conteiner">
            <img class="partner__img" src="/img/Group190.png">
            <div class="partner__content">
                <h1>To show them the most beautiful places of Europe ? To<br> become a driver of the fastest growing transport company in<br> Europe ? Do you have basic skills of English ? We see what you<br> are up for.</h1>
                <div class="partner__content_l">
                    <img class="partner__content_i" src="/img/check_4.png">
                    <p>Mytripline ensures professional surface transport throughout the Europe. When <br>cooperating with us, all the partners are annexed to the global customers network.</p>
                </div>
                <div class="partner__content_l">
                    <img class="partner__content_i" src="/img/check_4.png">
                    <p>We collaborate with licensed and insured companies in order to maintain development of<br> their business.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="coopirate">
        <div class="coopirate__title">
            <h1>Why to cooperate with MYTRIPLINE?</h1>
        </div>
        <div class="coopirate__wrapper">
            <div class="coopirate__inner">
                <div class="coopirate__item blue2">
                    <img src="/img/coopirate_img-1.svg" alt="icon">
                    <h5>Develop the possibilities of MYTRIPLINE</h5>
                    <p>We are ready to work with you. Every day there are dozens of companies, hotels and agencies agreeing upon a collabora=on with us. Our technology brings up the possibility for your customers to travel around Europe.</p>
                </div>
                <div class="coopirate__item blue2">
                    <img src="/img/coopirate_img-2.svg" alt="icon">
                    <h5>Entire control in your hands</h5>
                    <p>Everything within our system is entirely under your control. We give you the opportunity to work with our offers according to your desires.</p>
                </div>
                <div class="coopirate__item blue2">
                    <img src="/img/coopirate_img-3.svg" alt="icon">
                    <h5>Your way up</h5>
                    <p>Our team serves as a bridge connecting perfect organization with your successful business. You will be regularly informed of any events and upgrades.</p>
                </div>
                <div class="coopirate__item blue2">
                    <img src="/img/coopirate_img-4.svg" alt="icon">
                    <h5>MYTRIPLINE is comfortable</h5>
                    <p>There are no issues with Mytripline : a straightforward web and application for our business partners, no entry fees, no wasted time to fill the papers of a journey. All that you have to do is to start collaborating with us and we will take care of the rest.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="works works__blue">
            <img class="works__img" src="/img/telephones.png">
            <div class="works_vectra_d">
                <div class="work_text">
                    <h1>How it works</h1>
                    <h2>Register on our web for free</h2>
                    <h2>Download the app and get to know how it works</h2>
                    <h2>Start accepting journeys via app or web</h2>
                </div>
            </div>
        <div class="works__background_img">
{{--            <img src="/img/Vector123.svg" alt="vector">--}}
        </div>
        <div class="markets">
            <img src="/img/app-store.svg" alt="pay">
            <img src="/img/google-pay.svg" alt="pay">
        </div>
    </section>

    <div class="d_vectra"></div>

    <section>
        <div class="conditions_conteiner">
            <div class="conditions_content">
                <img class="conditions_content_i" src="/img/check_1.png">
                <div class="conditions_content_t">
                    <h1>Acceptance of a journey</h1>
                    <p>Our technology smoothly interconnects customers with drivers through our app <br>or Mytripline web. Once you accept a journey, all essential data, such as a<br> departure time, is stored in the app.</p>
                </div>
            </div>
            <div class="conditions_content">
                <img class="conditions_content_i" src="/img/check_2.png">
                <div class="conditions_content_t">
                    <h1>Organize your work day</h1>
                    <p>The majority of journeys are reserved in advance. In that respect, adjustment of<br> journeys according to your schedule has never been easier. You will be informed<br> when there is a new offer in your area. If you have an extra time, offer a<br> customer further exploration, take him to another city or just continue your<br> journey . It is fully up to you and our customers.</p>
                </div>
            </div>
            <div class="conditions_content">
                <img class="conditions_content_i" src="/img/check_3.png">
                <div class="conditions_content_t">
                    <h1>Periodic payments</h1>
                    <p>Accept as many offers as you manage to handle. There is no minimum and<br> neither maximum amount of offers for you to accept. Once you finish a journey,<br> you receive a payment on your account on regular basis.</p>
                </div>
            </div>
            <div class="conditions_content_l">
                <h1>Conditions for cooperation with MYTRIPLINE</h1>
                <p>The partners have a duty to comply with the conditions for cooperation of our company. To help with loading and unloading the luggage. To know the area<br>
                    where a customer travels. To provide fascinating information of a destination. To have a basic knowledge of English language. Simply to become a crucial<br> member of MYTRIPLINE.</p>
            </div>



            <div class="btn_partner">
                <style type="text/css"> a[name="driver"]
                {
                    text-decoration: none;
                    display: inline-block;
                    padding: 25px 120px;
                    border-radius: 10px;
                    background-image: linear-gradient(45deg, #6ab1d7 0%, #33d9de 50%, #002878 100%);
                    background-position: 100% 0;
                    background-size: 200% 200%;
                    font-family: 'Montserrat', sans-serif;
                    font-size: 18px;
                    font-weight: 300;
                    color: white;
                    box-shadow: 0 16px 32px 0 rgba(0, 40, 120, .35);
                    transition: .5s;
                }
                a[name="driver"]:hover {box-shadow: 0 0 0 0 rgba(0, 40, 120, 0);background-position: 0 0;}
                </style>
                <a class="button" name="driver" style="margin-left: 30px;" href="{{ route('a_driver', app()->getLocale()) }}">Become a driver</a>
            </div>
        </div>
    </section>
@endsection
