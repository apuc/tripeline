<template>
    <div class="calc" id="calculator">
        <form data-entity="search" class="js-calculator" data-submit="calculatorSubmit" :action="searchActionsUrl">
            <div class="custom-select">
              <span>test2</span>
                <input type="number" :value="route_id" name="route" hidden>
                <input type="number" :value="invert" name="invert" hidden>
                <div class="custom-select__item" :class="{'--active': openedFrom }">
                    <div class="custom-select__head" data-input-parent :class="{error: errorFrom}">
                        <input
                            name="from"
                            placeholder="From"
                            @input="inputFrom"
                            required="required"
                            type="search"
                            v-model="selectedFrom"
                            autocomplete="off"
                            @keyup="openedFrom = true"
                            @blur="toggle"
                        />
                    </div>
                    <div class="custom-select__options" v-if="filteredRoutes.length > 0" :class="{ '--opened': openedFrom }">
                        <div class="custom-select__option" @click="selectFrom(item)" v-for="(item, index) in filteredRoutes" :key="index">
                            <b>{{ item.city}}</b>
                            <em>{{ item.country }}</em>
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
                            v-model="selectedTo"
                            autocomplete="off"
                            @focus="openedTo = true"
                            @keyup="openedTo = true"
                            @blur="toggle"
                        />
                    </div>
                    <div class="custom-select__options" v-if="filteredRoutesTo.length > 0" :class="{ '--opened': openedTo }">
                        <div class="custom-select__option" @click="selectTo(item)" v-for="(item, index) in filteredRoutesTo" :key="index">
                            <b>{{ invert === 1 ? item.from_city : item.to_city }}</b>
                            <em>{{ invert === 1 ? item.from_country :  item.to_country }}</em>
                        </div>
                    </div>
                </div>
            </div>
            <div class="date-time">
                <v-custom-calendar></v-custom-calendar>
                <v-time></v-time>
                <v-humans :data="{adults,childrens,luggage}"></v-humans>
            </div>
            <div v-if="!this.short" class="calc__items">
                <div class="calc__item"><b @click="toggle('extrastops')">+ {{ $t('Extra stops') }}</b>
                    <v-extrastops v-if="extrastops"></v-extrastops>
                </div>
                <div class="calc__item"><b @click="toggle('choosecar')">+ {{ $t('Choose car') }}</b>
                    <v-choosecar :cars="cars" v-if="choosecar"></v-choosecar>
                </div>
                <div class="calc__item"><b @click="toggle('requirements')">+ {{ $t('Requirements') }}</b>
                    <v-requirements v-if="requirements"></v-requirements>
                </div>
            </div>
            <div v-if="!this.short" class="calc__form">
                <input class="half" name="first-name" :placeholder="$t('First name') + ':'" required>
                <input class="half" name="last-name" :placeholder="$t('Last name') + ':'" required>
                <input type="email" name="email" :placeholder="$t('Email') + ':'" required>
            </div>
            <template v-if="(filteredRoutes.length === 0 || filteredRoutesTo.length === 0) && mode === 'homeMobile'">
                <div class="form-vue__footer --line">
                    <div class="label mobile">{{ $t('Chauffeur will wait 15 minutes free of charge') }}</div>
                    <span :style="{display:'block', color:'#ffffff'}">{{ $t("Can't find your destination?") }}</span>
                    <a :style="{display:'block'}" :href="getRequestUrl">{{ $t("Request a custom route") }}</a>
                </div>
            </template>
            <template v-else>
                <div v-if="mode === 'request' || mode === 'homeMobile'" class="label --white">* {{ $t("required for departures within 48 hours") }}</div>
            </template>
            <button class="btn-submit">
                <span v-if="mode === 'homeMobile'">{{ $t("Search") }}</span>
                <span v-else>{{ $t("Request") }}</span>
            </button>
            <div class="label desktop">{{ $t("Chauffeur will wait 15 minutes free of charge") }}</div>
        </form>

        <div class="popup --sm popup-success" id="success">
            <a id="success_request" data-fancybox data-src="#success" alt="user" style="display: none"></a>
            <form class="popup__wrap js-form-validator">
                <img src="/img/success.svg" alt="success icon">
                <h3 class="--center">{{ popupMessage }}</h3>
            </form>
        </div>
    </div>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";
import initValidation from "./helper/validator";
import Extrastops from "./ExtrastopsComponent";
import Extrasteps from "./ExtrastepsComponent";
import Choosecar from "./ChoosecarComponent";
import Requirements from "./RequirementsComponent";
import ClickOutside from "vue-click-outside";

export default Vue.component("v-calculator", {
    comments: {
        Extrastops,
        Extrasteps,
        Choosecar,
        Requirements
    },
    data() {
        return {
            invert: 0,
            popupMessage: "Your request already sended !!!",
            searchActionsUrl: '',
            parsedRoutes: [],
            extrastops: false,
            choosecar: false,
            requirements: false,
            route_id: null,
            filter: {
                from: '',
                to: ''
            },
            openedFrom: false,
            openedTo: false,
            selectedFrom: "",
            errorFrom: false,
            selectedTo: "",
            errorTo: false,
            firstStart: false,
        };
    },
    props: {
        mode: {
            type: String,
            default: "homeMobile"
        },
        error: {
            type: Boolean,
            default: false
        },
        routes: {
            type: String,
            default: "[]"
        },
        cars: {
            type: String,
            default: "[]"
        },
        short: false,
        adults: {
            type: Number,
            default: 1
        },
        childrens: {
            type: Number,
            default: 0
        },
        luggage: {
            type: Number,
            default: 1
        },
        request: {
            type: Array,
            default: function () {
                return [{
                    from: '',
                    to: ''
                }]
            }
        }
    },
    computed: {
        getRequestUrl() {
            return '/' + window.App.language + '/request?' + 'from=' + this.selectedFrom + '&to=' + this.selectedTo
        },
        ampm() {
            return this.pm ? "PM" : "AM";
        },
        filteredRoutes() {
            if (true){
                //console.log('this.parsedRoutes: ', this.parsedRoutes);

                const allRoutesResult = []

                this.parsedRoutes.forEach(p=>{
                    allRoutesResult.push({city: p.from_city, country: p.from_country, invert: 0})
                    allRoutesResult.push({city: p.to_city, country: p.to_country, invert: 1})
                })

                const fromRoutesResult = allRoutesResult.filter(r => {
                    return this.selectedFrom.length > 0 ? r.city.toLowerCase().indexOf(this.selectedFrom.toLowerCase()) >= 0 : true;
                })

                const fromCitiesList = [];

                fromRoutesResult.forEach(i=>{
                    if (fromCitiesList.findIndex( (element) => element.city === i.city) < 0){
                        fromCitiesList.push(i)
                    }
                })
                return this.selectedFrom.length > 2 ? fromCitiesList : []

            }

            const fromRoutesResult = this.parsedRoutes.filter(r => {
                return this.selectedFrom.length > 0 ? r.from_city.toLowerCase().indexOf(this.selectedFrom.toLowerCase()) >= 0 : true;
            }).filter(r => {
                return this.selectedTo.length > 0 ? r.to_city.toLowerCase().indexOf(this.selectedTo.toLowerCase()) >= 0 : true;
            }).map(i => {
                return {from_city: i.from_city, from_country: i.from_country, to_city: i.to_city, to_country: i.to_country}
            })
            const fromCitiesList = [];

            fromRoutesResult.forEach(i=>{
                if (fromCitiesList.findIndex( (element) => element.from_city === i.from_city) < 0){
                    fromCitiesList.push(i)
                }
            })
            return fromCitiesList
        },
        filteredRoutesTo() {

            if (this.selectedFrom.length > 0){
                if (this.invert === 1){
                    return this.parsedRoutes.filter(r => {
                        return this.selectedFrom.length > 0 ? r.to_city.toLowerCase().indexOf(this.selectedFrom.toLowerCase()) >= 0 : true;
                    }).filter(r => {
                        return this.selectedTo.length > 0 ? r.from_city.toLowerCase().indexOf(this.selectedTo.toLowerCase()) >= 0 : true;
                    })
                }
                return this.parsedRoutes.filter(r => {
                    return this.selectedFrom.length > 0 ? r.from_city.toLowerCase().indexOf(this.selectedFrom.toLowerCase()) >= 0 : true;
                }).filter(r => {
                    return this.selectedTo.length > 0 ? r.to_city.toLowerCase().indexOf(this.selectedTo.toLowerCase()) >= 0 : true;
                })
            }else {
                return []
            }

        }
    },
    methods: {
        submitForm(e) {
            if (this.mode === 'request') {
                e.preventDefault()
                const formData = new FormData(e.target);
                const formProps = Object.fromEntries(formData);

                this.$store.commit('setCart', formProps);
                axios.post('/api/set_request', formProps)
                    .then(res => {

                        //console.log('getPlaces ress;', res);

                        if (res) {
                            // if (res.data['status'] === 'success') {

                            this.popupMessage = "Your request already sended !!!"
                            document.getElementById('success_request').click()

                            this.places = res.data ?? [];

                            this.$store.commit('clearOrder');

                            // window.location.href = this.getUrl(res.data['path']);
                            // }
                        }
                    }).catch(e => {
                    this.popupMessage = "Your request already sended !!!"
                    document.getElementById('success_request').click()
                })
            }else{
                e.target.submit()
            }


            this.$store.commit('clearPoint');
            return false;
        },
        toggle(item) {
            this[item] = !this[item];
            setTimeout(() => {
                this.openedFrom = false;
                this.openedTo = false;
            }, 300);
        },
        updateError() {
            // this.errorFrom = this.selectedFrom.length <= 2;
            // this.errorTo = this.selectedTo.length <= 2;
        },
        selectFrom(item) {
            // this.openedFrom = false;
            //console.log('item.invert: ', item.invert);
            this.selectedFrom = item.city
            this.invert = item.invert
            this.updateError();
        },
        inputFrom() {
            this.updateError();
        },
        selectTo(item) {
            // this.openedTo = false;
            //console.log('selectTo:', item);
            this.selectedTo = this.invert === 1 ? item.from_city : item.to_city;
            this.route_id = item.id;
            this.updateError();
        },
        inputTo() {
            //console.log('inputTo');
            this.updateError();
        },
        change() {
            let from = this.selectedFrom;
            let to = this.selectedTo;
            this.selectedFrom = to;
            this.selectedTo = from;
            this.$store.commit('clearPoint');
            this.invert = this.invert === 0 ? 1 : 0
            this.updateError();
        },
        search() {

            this.$store.commit('setCart', cart);
        }
    },
    mounted() {

        initValidation(".js-calculator");

        let $this = this;

        document.addEventListener("bouncerFormValid", function (el) {
            console.log('bouncerFormValid');
            console.log(el.target.dataset);            console.log(el.target.dataset);
            if (el.target.dataset?.entity === 'search') {
                try {
                    $this.submitForm(el)
                } catch (e) {
                }
            }
            return false
        });

        document.addEventListener("bouncerFormValidRequest", function (el) {
            console.log(el.target.dataset);
            if (el.target.dataset?.entity === 'search') {
                try {
                    $this.submitForm(el)
                } catch (e) {
                }
            }
            return false
        });


        this.parsedRoutes = JSON.parse(this.routes)
        this.searchActionsUrl = '/' + (window.App.language ?? 'en') + '/search';
        this.selectedFrom = this.request.from ?? ''
        this.selectedTo = this.request.to ?? ''
    },
    directives: {
        ClickOutside
    }
});

</script>
<style scoped>
.custom-select__options{
    max-height: 450px;
    overflow-y: scroll;
    overflow-x: hidden;

}
.tickets__footer-price b{
    margin-left: 5px
}
</style>
