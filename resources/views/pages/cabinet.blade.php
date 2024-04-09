@extends('layouts.app')

@section('content')
    <div class="pcabinet">
        <div class="header-block --border" style="background-image: url(/img/cabinet-bg.jpg);">
            <div class="container">
                <div class="header-block__wrap">
                    <h1>My cabinet</h1>
                </div>
            </div>
        </div>
        <div class="container">
            <!--Wrap-->
            <div class="pcabinet__wrap">
                <!--Tab Button-->
                <div class="pcabinet__tab">
                    <button class="active" data-cabinet-btn><span>Personal data</span></button>
                    <button data-cabinet-btn><span>Tickets</span></button>
                </div>
                <!--cabinet-->
                <section class="pcabinet__cabinet active" data-cabinet-content>
                    <header>
                        <h4>Personal data</h4><a href="#Edit">Edit</a>
                    </header>
                    <form method="POST" class="js-form-validator" action="{{ route('edit_profile', app()->getLocale()) }}">
                    @csrf
                    <!-- {{ csrf_field() }} -->
                        <div class="input-block --half"><em>First name</em>
                            <input name="first_name" required value="{{$user['first_name'] ?? ''}}">
                        </div>
                        <div class="input-block --half"><em>Last name</em>
                            <input name="last_name" required value="{{$user['last_name'] ?? ''}}">
                        </div>
                        <div class="input-block"><em>Email</em>
                            <input name="email" type="email" required readonly value="{{$user['email'] ?? ''}}">
                        </div>
                        <div class="input-block"><em>Day of birth</em>
                            <input name="day_of_birth" id="dayofbirth" placeholder="__.__.____" required value="{{date('d.m.Y',strtotime($user['day_of_birth'])) ?? ''}}">
                        </div>
                        <div class="input-block input-phone"><em>Phone number</em>
                            <input type="tel" id="phone" name="phone" required placeholder="Phone number" value="{{$user['full_number'] ?? ''}}">
                        </div>
                        <button class="btn-submit --simple"><span>save</span></button>
                    </form>
                </section>
                <!--Ticket-->
                <section class="pcabinet__ticket" data-cabinet-content>
                    <header>
                        <h4>Tickets</h4>
                    </header>
                    <div class="tickets">
                        @foreach ($orders as $order)
                            <div class="tickets__item js-ticket">
                                <div class="tickets__wrap">
                                    <div class="tickets__fromto">
                                        <div class="tickets__ft"><b>{{$order->getRoute()->fromCity['name']}}</b>
                                        </div>
                                        <div class="tickets__between">
                                            <svg class="icon">
                                                <use xlink:href="/img/sprites/sprite.svg#arrow-long"></use>
                                            </svg>
                                        </div>
                                        <div class="tickets__ft --last"><b>{{$order->getRoute()->toCity['name']}}</b>
                                        </div>
                                    </div>
                                    <div class="tickets__amount">
                                        <div><span>{{$order['adults'] + $order['childrens']}}</span>
                                            <svg class="icon">
                                                <use xlink:href="/img/sprites/sprite.svg#users"></use>
                                            </svg>
                                        </div>
                                        <div><span>{{$order['luggage']}}</span>
                                            <svg class="icon">
                                                <use xlink:href="/img/sprites/sprite.svg#suitecase"></use>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="tickets__price"><b><i class="{{$order['currency'] ?? 'eur'}}_money"></i>{{$order['amount']}}</b></div>
                                    <div class="tickets__arrow">
                                        <svg class="icon">
                                            <use xlink:href="/img/sprites/sprite.svg#arrow-down"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="tickets__content">
                                    <div class="tickets__list"><em>With stops in</em>
                                        @foreach ($order->places as $place)
                                            <div class="tickets__list-item"><i><img src="/{{$place->image}}" alt="IMG"></i>
                                                <div>
                                                    <h4>{{$place->title_en}}</h4>
                                                </div>
                                                <span><i style="margin-right: 5px" class="{{$order['currency'] ?? 'eur'}}_money"></i>{{$place->pivot['price']}}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="tickets__footer">
                                        @foreach ($order->getCars() as $car)
                                            <i><img src="/{{$car->image}}" alt="sedan"></i>
                                            <div class="tickets__footer-info">
                                                <h4>{{$car->title}}</h4><em>{{$car->brand}}</em>
                                                <div><span>{{$car->places_min}}-{{$car->places_max}}</span>
                                                    <svg class="icon">
                                                        <use xlink:href="/img/sprites/sprite.svg#users"></use>
                                                    </svg>
                                                </div>
                                                <div><span>{{$car->luggage}}x</span>
                                                    <svg class="icon">
                                                        <use xlink:href="/img/sprites/sprite.svg#suitecase"></use>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="tickets__footer-price"><em>Total</em><b><i class="{{$order['currency'] ?? 'eur'}}_money"></i>{{$order['amount']}}</b></div>
                                            <div class="tickets__footer-bottom">
                                                <div class="tickets__amount">
                                                    <div><span>{{$car->places_min}}-{{$car->places_max}}</span>
                                                        <svg class="icon">
                                                            <use xlink:href="/img/sprites/sprite.svg#users"></use>
                                                        </svg>
                                                    </div>
                                                    <div><span>{{$car->luggage}}3</span>
                                                        <svg class="icon">
                                                            <use xlink:href="/img/sprites/sprite.svg#suitecase"></use>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <b><i class="{{$order['currency'] ?? 'eur'}}_money"></i>{{$car->price}}</b>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let tickets = document.querySelectorAll(".js-ticket");
            tickets.forEach(item => {
                item.addEventListener("click", () => {
                    item.classList.toggle("active");
                });
            });
        });

        $(document).ready(function ($) {

            console.log($)
            $(document).ready(function () {
                let dayofbirth = $("#dayofbirth");
                dayofbirth.mask("99.99.9999");
                var phoneInputID = "#phone";
                var input = document.querySelector(phoneInputID);
                var iti = window.intlTelInput(input, {
                    // allowDropdown: false,
                    // autoHideDialCode: false,
                    // autoPlaceholder: "off",
                    // dropdownContainer: $(".input-phone") || document.body,
                    // excludeCountries: ["us"],
                    separateDialCode: true,
                    formatOnDisplay: true,
                    geoIpLookup: function (callback) {
                        $.get("http://ipinfo.io", function () {
                        }, "jsonp").always(function (resp) {
                            var countryCode = (resp && resp.country) ? resp.country : "";
                            callback(countryCode);
                        });
                    },
                    hiddenInput: "full_number",
                    // initialCountry: "auto",
                    // localizedCountries: { 'de': 'Deutschland' },
                    // nationalMode: false,
                    // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                    // placeholderNumberType: "MOBILE",
                    // separateDialCode: true,
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"
                });


                $(phoneInputID).on("countrychange", function (event) {

                    // Get the selected country data to know which country is selected.
                    var selectedCountryData = iti.getSelectedCountryData();

                    // Get an example number for the selected country to use as placeholder.
                    newPlaceholder = intlTelInputUtils.getExampleNumber(selectedCountryData.iso2, true, intlTelInputUtils.numberFormat.INTERNATIONAL),

                        // Reset the phone number input.

                    // if (iti.getNumber().length === 0) {
                    //     iti.setNumber("");
                    // }

                    // Convert placeholder as exploitable mask by replacing all 1-9 numbers with 0s
                    mask = newPlaceholder.replace(/[1-9]/g, "0");

                    // Apply the new mask for the input
                    $(this).mask(mask);
                });

                // When the plugin loads for the first time, we have to trigger the "countrychange" event manually,
                // but after making sure that the plugin is fully loaded by associating handler to the promise of the
                // plugin instance.

                iti.promise.then(function () {
                    $(phoneInputID).trigger("countrychange");
                });
            });


            // Tab
            function tab(btn, content, event = "change") {
                let btns = [].slice.call(document.querySelectorAll(btn));
                let contents = [].slice.call(document.querySelectorAll(content));
                btns.forEach(item => {
                    item.addEventListener(event, e => {
                        let index = btns.indexOf(e.currentTarget);

                        contents.map(item => (item.classList.remove("active")));
                        contents[index].classList.add("active");

                        if (event === "click") {
                            btns.map(item => (item.classList.remove("active")));
                            btns[index].classList.add("active");
                        }
                    });
                });
            }

// Cabinet Tab
            tab("[data-cabinet-btn]", "[data-cabinet-content]", "click");

        });
    </script>
@endsection
