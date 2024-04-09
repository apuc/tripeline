@extends('layouts.app')

@section('content')
    <section class="pabout partners__banner">
        <header style="background-image: url({{asset('img/partner_a.jpg')}})">
            <div class="container">
                <h1 style="text-align:center;font-family: 'Roboto';font-style: normal;font-weight: 700;line-height: 55px;font-size: 40px; padding-top: 50px; padding-left: 10%; padding-right: 10%;">Travel with us and our customers all around the Europe. Take a chance to learn something new and you will surely fall in love with this work.
                </h1>

                <style type="text/css">
                    @media (max-width: 960px) {
                        .block__partners{
                            height: auto !important;
                        }
                    }
                    @media (max-width: 520px) {
                        .block__partners{
                            width: 100% !important;
                            margin: 80px auto !important;
                            margin-bottom: 0 !important;
                        }
                        .container > h1{
                            line-height: 40px !important;
                            font-size: 24px !important;
                        }
                    }
                </style>
                <div class="block__partners" style="background-color: rgba(0, 0, 0, 0.6);font-family: 'Roboto';font-style: normal;font-weight: 400;font-size: 18px; margin-left: 10%;margin-right: 10%; height: 180px; margin-top: 180px; border-radius: 22px; padding: 35px; text-align: center;">
                    <p5>Choose your cooperation with MYTRIPLINE</p5>
                    <div class="partners__type" style="margin-top: 25px;" >

                        <style type="text/css"> a[name="partner"]
                            {
                                text-decoration: none;
                                display: inline-block;
                                padding: 20px 20px !important;
                                border-radius: 10px;
                                background-image: linear-gradient(45deg, #068421 0%, #029a24  50%, #024b12  100%);
                                background-position: 100% 0;
                                background-size: 200% 200%;
                                font-family: 'Montserrat', sans-serif;
                                font-size: 18px;
                                font-weight: 300;
                                color: white;
                                box-shadow: 0 16px 32px 0 rgba(0, 40, 120, .35);
                                transition: .5s;
                            }
                            @media (max-width: 960px) {
                                a[name="partner"]{
                                    width: 80%;
                                    margin: 0 !important;
                                    margin-bottom: 20px !important;
                                }
                            }
                            @media (max-width: 520px) {
                                a[name="partner"]{
                                    width: 100%;
                                    margin: 0 !important;
                                    margin-bottom: 20px !important;
                                }
                            }
                            a[name="partner"]:hover {box-shadow: 0 0 0 0 rgba(0, 40, 120, 0);background-position: 0 0;}
                        </style>
                        <a class="button partner__btn1"  name="partner" style="margin-left: 30px;" href="{{ route('a_partner', app()->getLocale()) }}">Become a partner</a>

                        <a class="button driver__btn1"  name="driver" style="margin-left: 30px;" href="{{ route('a_driver', app()->getLocale()) }}">Become a driver</a>

                        <a class="button travel__btn1"  name="travel" style="margin-left: 30px;" href="{{ route('a_travel_agency', app()->getLocale()) }}">Become a Travel agency</a>


                        <style type="text/css"> a[name="travel"]

                            a[name="travel"]:hover {box-shadow: 0 0 0 0 rgba(0, 40, 120, 0);background-position: 0 0;}
                        </style>
                    </div>
                </div>
            </div>
        </header>
    </section>
@endsection
