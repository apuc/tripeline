<footer class="footer">
    <div class="container">
        <div class="footer__wrap">
            <div class="footer__item">
                <h3>Helpdesk</h3>
                @foreach ($footerContacts['helpdesk'] as $contact)
                    <div class="footer__line-ico --bold">
                        <svg class="icon">
                            <use xlink:href="{{asset('img/sprites/sprite.svg#clock')}}"></use>
                        </svg>
                        {!! $contact['label_'.app()->getLocale()] ?? $contact['label'] !!}

                    </div>
                    <div class="footer__line-ico">
                        <svg class="icon">
                            <use xlink:href="{{asset('img/sprites/sprite.svg#mic')}}"></use>
                        </svg>
                        <a href="mailto:{!! $contact['url_'.app()->getLocale()] ?? $contact['url'] !!}">{!! $contact['url_'.app()->getLocale()] ?? $contact['url'] !!}</a>
                    </div>
                @endforeach
            </div>
            <div class="footer__item">
                <h3>Let us know</h3>
                @foreach ($footerContacts['let_us_know'] as $contact)

                    <div class="footer__line-ico --bold --black">
                        <svg class="icon">
                            <use xlink:href="{{asset('img/sprites/sprite.svg#phone')}}"></use>
                        </svg>
                        <a href="tel:{!! $contact['label_'.app()->getLocale()] ?? $contact['label'] !!} ">{!! $contact['label_'.app()->getLocale()] ?? $contact['label'] !!}</a>
                    </div>
                    <div class="footer__line-ico">
                        <svg class="icon">
                            <use xlink:href="{{asset('img/sprites/sprite.svg#envelope')}}"></use>
                        </svg>
                        <a href="mailto:{!! $contact['url_'.app()->getLocale()] ?? $contact['url'] !!}">{!! $contact['url_'.app()->getLocale()] ?? $contact['url'] !!}</a>
                    </div>
                @endforeach
            </div>
            <div class="footer__item --last">
                <h3>Stay in Touch</h3>
                    <div class="social --white">
                        @foreach ($footerContacts['social'] as $contact)
                        <a href="{{ $contact['url_'.app()->getLocale()] ?? $contact['url'] }}">
                            <svg class="icon">
                                <use xlink:href="{{asset('img/sprites/sprite.svg#').strtolower($contact['label_'.app()->getLocale()] ?? $contact['label'])}}"></use>
                            </svg>
                        </a>
                        @endforeach
                    </div>
            </div>
            <div class="footer__copyright"><span>Copyright 2018-2023 Mytripline. All rights reserved</span>
                <div>
                    <a href="{{route('terms-of-use', app()->getLocale())}}">{!! __("menu.terms_of_use") !!}</a>
                    <a href="{{route('privacy-policy', app()->getLocale())}}">{!! __("menu.privacy_policy") !!}</a>
                </div>
            </div>
        </div>
    </div>
</footer>
