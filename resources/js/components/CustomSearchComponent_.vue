<template>
    <section class="psearch" id="psearch">
        <form class="container psearch__wrap js-psearch-from" data-submit="psearchSubmit">
            <div class="psearch__form">
<!--                {{orderRoute}}-->
<!--                {{current}}-->
                <div class="psearch__head">
                  tyuiyuoyuio
                    <h2>{{orderRoute.from}}
                        <svg class="icon">
                            <use xlink:href="/img/sprites/sprite.svg#arrow-long"></use>
                        </svg>
                        {{orderRoute.to}}
                    </h2>
                    <em>Estimated arrival 12:45 PM</em>
                </div>
                <div class="calc">
                    <div class="custom-select">
                        <div class="custom-select__item" :class="{'--active': openedFrom }">
                            <div class="custom-select__head" data-input-parent :class="{error: errorFrom}">
                                <input
                                    name="from"
                                    placeholder="From"
                                    @input="inputFrom"
                                    required="required"
                                    type="search"
                                    v-model="orderRoute.from"
                                    autocomplete="off"
                                    @keyup="openedFrom = true"
                                    @blur="toggle"
                                />
                            </div>
                            <div class="custom-select__options" :class="{ '--opened': openedFrom }">
                                <div class="custom-select__option" @click="selectFrom(item)" v-for="(item, index) in filteredRoutes" :key="index">
                                    <b>{{ item.from_city }}</b>
                                    <em>{{ item.from_country }}</em>
                                </div>
                            </div>
                        </div>
                        <div class="custom-select__change" @click="change">
                            <svg class="icon">
                                <use xlink:href="/img/sprites/sprite.svg#icn-arrows2"></use>
                            </svg>
                        </div>
                        <div class="custom-select__item" :class="{'--active': openedTo }">
                            <div class="custom-select__head" data-input-parent :class="{error: errorTo}">
                                <input
                                    name="to"
                                    placeholder="To"
                                    @input="inputTo"
                                    required="required"
                                    type="search"
                                    v-model="orderRoute.to"
                                    autocomplete="off"
                                    @keyup="openedTo = true"
                                    @blur="toggle"
                                />
                            </div>
                            <div class="custom-select__options" :class="{ '--opened': openedTo }">
                                <div class="custom-select__option" @click="selectTo(item)" v-for="(item, index) in filteredRoutes" :key="index">
                                    <b>{{ item.to_city }}</b>
                                    <em>{{ item.to_country }}</em>
                                </div>
                            </div>
                        </div>
                    </div>
<!--                    <v-select :orderRoute={orderRoute} :filteredRoutes="filteredRoutes"></v-select>-->
                    <div class="date-time">
                        <v-custom-calendar></v-custom-calendar>
                        <v-time></v-time>
                    </div>
                    <v-humans @return="returnPersone"></v-humans>
                </div>
            </div>
            <div class="psearch__other">
                <h3>Visit along the way</h3>
                <div class="glide">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            <li class="glide__slide" v-for="(item, index) in current_route_places" :key="index">
                                <v-article :data="item" :index="index" @return="addNewStopItem"></v-article>
                            </li>
                        </ul>
                    </div>
                    <div class="glide__bullets" data-glide-el="controls[nav]">
                        <button class="glide__bullet" v-for="(item, index) in articleList" :key="index" type="button" :data-glide-dir="'=' + index"></button>
                    </div>
                </div>
            </div>
            <div class="psearch__last">
                <div class="withstops" v-if="withstopsList.length"><em>With stops in</em>
                    <div class="withstops__list">
                        <v-withstops v-for="(item, index) in withstopsList" @remove="removeWithstopsItem(item)" :key="index" :data="item"></v-withstops>
                    </div>
                </div>
                <div class="yourride">
                    <div class="yourride__head">
                        <h3>Your ride</h3><a href="#Other-cars">Other cars</a>
                    </div>
                    <div class="yourride__selected" :class="{two: passengers.length &gt; 1}">
                        <div class="tickets__footer">
                            <i>
                                <img v-for="(item, index) in passengers" :key="index" :src="'/' + item.image" :alt="item.title">
                            </i>
                            <div class="tickets__footer-info" v-for="(item, index) in passengers" :key="index">
                                <header>
                                    <h4>{{ item.title }}</h4><em>{{ item.brand }}</em>
                                </header>
                                <div>
                                    <span>{{ item.places_min }} - {{ item.places_max }}</span>
                                    <svg class="icon">
                                        <use xlink:href="/img/sprites/sprite.svg#users"></use>
                                    </svg>
                                </div>
                                <div>
                                    <span>{{ item.luggage }}</span>
                                    <svg class="icon">
                                        <use xlink:href="/img/sprites/sprite.svg#suitecase"></use>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <button><span>BUY FOR {{ price + withstopsListPrce }}</span></button>
                    </div>
                    <div class="yourride__footer">
                        <ul>
                            <li><i>
                                <svg class="icon">
                                    <use xlink:href="/img/sprites/sprite.svg#chat"></use>
                                </svg>
                            </i><span>Support 24/7</span></li>
                            <li><i>
                                <svg class="icon">
                                    <use xlink:href="/img/sprites/sprite.svg#driver-2"></use>
                                </svg>
                            </i><span>English-speaking driver</span></li>
                            <li><i>
                                <svg class="icon">
                                    <use xlink:href="/img/sprites/sprite.svg#disabled"></use>
                                </svg>
                            </i><span>Prepared for handicapped</span></li>
                        </ul>
                        <ul>
                            <li><i>
                                <svg class="icon">
                                    <use xlink:href="/img/sprites/sprite.svg#car"></use>
                                </svg>
                            </i><span>Clean, comfortable car</span></li>
                            <li><i>
                                <svg class="icon">
                                    <use xlink:href="/img/sprites/sprite.svg#bottle"></use>
                                </svg>
                            </i><span>Bottled water</span></li>
                            <li><i>
                                <svg class="icon">
                                    <use xlink:href="/img/sprites/sprite.svg#undefined"></use>
                                </svg>
                            </i><span>From a to be service</span></li>
                        </ul>
                        <ul>
                            <li><i>
                                <svg class="icon">
                                    <use xlink:href="/img/sprites/sprite.svg#little-kid"></use>
                                </svg>
                            </i><span>Child seats</span></li>
                            <li><i>
                                <svg class="icon">
                                    <use xlink:href="/img/sprites/sprite.svg#smoking"></use>
                                </svg>
                            </i><span>No-smoking</span></li>
                            <li><i>
                                <svg class="icon">
                                    <use xlink:href="/img/sprites/sprite.svg#paw"></use>
                                </svg>
                            </i><span>Pet friendly</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </section>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";
import ClickOutside from "vue-click-outside";
import initValidation from "../../../tripline-master/src/blocks/modules/calculator/validator";
import debounce from "lodash.debounce";
import Glide from "@glidejs/glide";
import {Controls} from "@glidejs/glide/dist/glide.modular.esm";
import Extrastops from "./ExtrastopsComponent";
import Extrasteps from "./ExtrastepsComponent";
import Choosecar from "./ChoosecarComponent";
import Requirements from "./RequirementsComponent";
import Withstops from "./WithstopsComponent";
import Article from "./ArticleComponent";

export default Vue.component("v-custom-search", {
    comments: {
        Extrastops,
        Extrasteps,
        Choosecar,
        Requirements,
        Withstops,
        Article
    },
    el: "#psearch",
    props: {
        // routes: {
        //     type: Array,
        //     default: function () {
        //         return []
        //     }
        // },
        routes: [],
        errors: [],
        current: false,
        current_route_places: [],
        debug: [],
    },
    data() {
        return {
            price: 0,
            auto: [
                {
                    type: "sedan",
                    title: "Sedan",
                    name: "Skoda Superb",
                    img: "sedan.png",
                    passengers: "1-3",
                    luggage: "3",
                    minPassengers: 1,
                    maxPassengers: 3,
                    minLuggage: 1,
                    maxLuggage: 3,
                },
                {
                    type: "mvp",
                    title: "MPV",
                    name: "Volkswagen Sharan",
                    img: "sharan.png",
                    passengers: "4",
                    luggage: "4",
                    minPassengers: 4,
                    maxPassengers: 4,
                    minLuggage: 4,
                    maxLuggage: 4,
                },
                {
                    type: "suv",
                    title: "SUV",
                    name: "KIA Sportage",
                    img: "sportage.png",
                    passengers: "4-5",
                    luggage: "5",
                    minPassengers: 4,
                    maxPassengers: 5,
                    minLuggage: 5,
                    maxLuggage: 5,
                },
                {
                    type: "van",
                    title: "Van",
                    name: "Mercedes Vito",
                    img: "vito.jpg",
                    passengers: "5-7",
                    luggage: "7",
                    minPassengers: 5,
                    maxPassengers: 7,
                    minLuggage: 5,
                    maxLuggage: 7,
                },
                {
                    type: "shared",
                    title: "Shared",
                    name: "Shuttle - IVECO Tourer",
                    img: "vito.jpg",
                    passengers: "8",
                    luggage: "8",
                    minPassengers: 8,
                    maxPassengers: 8,
                    minLuggage: 8,
                    maxLuggage: 8,
                },
                {
                    type: "minibus",
                    title: "Minibus",
                    name: "Sprinter Tourer",
                    img: "sprinter.png",
                    passengers: "16",
                    luggage: "16",
                    minPassengers: 9,
                    maxPassengers: 16,
                    minLuggage: 9,
                    maxLuggage: 16,
                },
                {
                    type: "minibus",
                    title: "Minibus",
                    name: "Mercedes Sprinter",
                    img: "minibus.png",
                    passengers: "16",
                    luggage: "16",
                    minPassengers: 9,
                    maxPassengers: 16,
                    minLuggage: 9,
                    maxLuggage: 16,
                },
                {
                    type: "bus",
                    title: "Bus",
                    name: "SETRA",
                    img: "bus.png",
                    passengers: "50",
                    luggage: "50",
                    minPassengers: 17,
                    maxPassengers: 50,
                    minLuggage: 17,
                    maxLuggage: 50,
                }
            ],
            withstopsList: [],
            withstopsListPrce: 0,
            articleList: [
                {
                    id: 1,
                    img: "/img/search-article-1.jpg",
                    title: "Los Angeles",
                    text: "Pay full attention to the quality and cleanliness of our vehicles and driver selection, who will",
                    price: 82
                }, {
                    id: 2,
                    img: "/img/search-article-2.jpg",
                    title: "Los Angeles",
                    text: "Pay full attention to the quality and cleanliness of our vehicles and driver selection, who will",
                    price: 82
                }
            ],
            passengers: [],
            glide: {},
            orderRoute: {
                from: null,
                to: null,
                passengers: null,
                luggage: null,
            },
            openedFrom: false,
            openedTo: false,
            selectedFrom: "",
            errorFrom: false,
            selectedTo: "",
            errorTo: false,
            firstStart: false,
        }
    },
    created() {
        this.firstStart = true;
    },
    mounted() {
        initValidation(".js-psearch-from");

        if (this.current){
            this.orderRoute.from = this.current.from_city.name
            this.orderRoute.to = this.current.to_city.name
        }


        if (window.matchMedia("(max-width: 900px)").matches) {
            this.glideMount();
        }

        window.addEventListener("resize", debounce(() => {
            if (window.matchMedia("(max-width: 900px)").matches) {
                this.glideMount();
            } else {
                let glideEl = document.querySelector(".glide");
                if (!glideEl) return false;
                if (glideEl.classList.contains("glide--slider")) {
                    this.glideDestroy();
                }
            }
        }, 500));
    },
    methods: {
        glideMount() {
            this.glide = new Glide(".glide").mount({Controls});
        },
        glideDestroy() {
            this.glide.destroy();
        },
        removeWithstopsItem(item) {
            this.withstopsList.splice(this.withstopsList.indexOf(item), 1);
        },
        returnPersone(e) {
            this.passengers = [];
            // this.$nextTick(() => {
            //   if(e.passengers >= 1 && e.passengers <= 4 || e.luggage >= 1 && e.luggage <= 4) {
            //     ////console.log("sedan");
            //   } else {
            //     ////console.log("other");
            //   }
            // });
            // e.luggage >= item.minLuggage && e.luggage >= item.maxLuggage
            // this.auto.forEach(item => {
            this.current.cars.forEach(item => {
                let pas = e.passengers >= item.places_max && e.passengers <= item.places_min;
                let lug = e.luggage <= item.luggage;
                let result = false;
                if (e.passengers > e.luggage) {
                    if (pas) {
                        result = true;
                    }
                } else {
                    if (lug) {
                        result = true;
                    }
                }

                if (result) this.passengers.push(item);
            });
        },
        addNewStopItem(item, type) {
            let exists = this.withstopsList.find(val => {
                return val.id === item.id;
            });

            if (type) {
                this.withstopsList.splice(this.withstopsList.indexOf(item), 1);
            } else {
                if (!exists) this.withstopsList.push(item);
            }
        },

        updateError() {
            // this.errorFrom = this.selectedFrom.length <= 2;
            // this.errorTo = this.selectedTo.length <= 2;
        },
        selectFrom(item) {
            // this.openedFrom = false;
            this.orderRoute.from = item.from_city;
            this.updateError();
        },
        inputFrom() {
            this.updateError();
        },
        selectTo(item) {
            // this.openedTo = false;
            this.orderRoute.to = item.to_city;
            this.updateError();
        },
        inputTo() {
            this.updateError();
        },
        change() {
            let from = this.selectedFrom;
            let to = this.selectedTo;
            this.selectedFrom = to;
            this.selectedTo = from;
            this.updateError();
        },
        toggle() {
            setTimeout(() => {
                this.openedFrom = false;
                this.openedTo = false;
            }, 300);
        }
    },
    computed: {
        ampm() {
            return this.pm ? "PM" : "AM";
        },
        filteredRoutes(){
            return this.routes.filter(r => {
                return this.selectedFrom.length >0 ? r.from_city.toLowerCase().indexOf(this.selectedFrom.toLowerCase()) >= 0 : true;
            }).filter(r => {
                return this.selectedTo.length >0 ? r.to_city.toLowerCase().indexOf(this.selectedTo.toLowerCase()) >= 0 : true;
            })
        }
    },
    watch: {
        withstopsList() {
            this.withstopsListPrce = 0;

            this.withstopsList.forEach(item => {
                this.withstopsListPrce += item.price;
            });
        }
    }
});

</script>
