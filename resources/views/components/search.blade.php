<v-custom-search
{{--    :routes = "{{json_encode($routeList)}}"--}}
    :current = "{{($currentRoute)}}"
    :current_route_places = "{{json_encode($currentRoutePlaces)}}"
    :debug = "{{json_encode($debug)}}"
    from="{{ $_GET['from'] ?? '' }}"
    to="{{ $_GET['to'] ?? '' }}"
    pm="{{ $_GET['pm'] ?? '' }}"
    adults="{{ $_GET['adults'] ?? '' }}"
    childrens="{{ $_GET['childrens'] ?? '' }}"
    luggage="{{ $_GET['luggage'] ?? '' }}"
    data="{{ $_GET['data'] ?? '' }}"
    hours="{{ $_GET['hours'] ?? '' }}"
    minutes="{{ $_GET['minutes'] ?? '' }}"
    invert="{{ $_GET['invert'] ?? 0 }}"
    invert_route="{{ $_GET['invert'] ?? 0 }}"
>
</v-custom-search>
