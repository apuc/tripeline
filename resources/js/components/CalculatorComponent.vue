<template>
    <div class="calc" id="calculator">
        <form data-entity="search" class="js-calculator" data-submit="calculatorSubmit" :action="searchActionsUrl">
            <div class="custom-select">
                <input type="number" :value="route_id" name="route" hidden>
                <input type="number" :value="invert" name="invert" hidden>
                <div class="custom-select__item" :class="{'--active': openedFrom }">
                    <div class="custom-select__head" data-input-parent :class="{error: errorFrom}">
                        <input
                            name="from"
                            placeholder="From"
                            @input="debounceInput"
                            @keyup.delete="fromCityId = ''"
                            required="required"
                            type="search"
                            v-model="selectedFrom"
                            autocomplete="off"
                            @keyup="openedFrom = true"
                            @blur="toggle"
                            @focus="fromRouteFocus()"
                        />
                    </div>
                    <div class="custom-select__options" v-if="cities.length > 0 || routes.length > 0" :class="{ '--opened': openedFrom }">
                        <div v-if="routes.length === 0" class="custom-select__option" @click="selectFrom(item.city)" v-for="(item, index) in cities" :key="index">
                            <b>{{ item.city}}</b>
                            <em>{{ item.country }}</em>
                        </div>
                        <div v-if="routes.length > 0" class="custom-select__option" @click="selectFrom(item.invert === 1 ? item.toCity : item.fromCity, item.id, item.invert)" v-for="(item, index) in routes" :key="index">
                          <b>{{ item.invert === 1 ? item.toCity : item.fromCity }}</b>
                          <em>{{ item.invert === 1 ? item.toCountry : item.fromCountry }}</em>
                        </div>
                    </div>
                    <div class="custom-select__options option-clear" v-if="cities.length === 0 && routes.length === 0 && selectedFrom.length" :class="{ '--opened': openedFrom }">
                      <div class="loader" v-if="isDownloadRoutesFrom">
                        <img class="loader-img" src="/img/loader.png" alt="loader">
                      </div>
                      <span v-else>Route not found</span>
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
                            @input="debounceInput"
                            @keyup.delete="toCityId = ''"
                            required="required"
                            type="search"
                            v-model="selectedTo"
                            autocomplete="off"
                            @focus="toRouteFocus"
                            @keyup="openedTo = true"
                            @blur="toggle"
                        />
                    </div>
                    <div class="custom-select__options" v-if="cities.length > 0 || routes.length > 0" :class="{ '--opened': openedTo }">
                        <div v-if="routes.length === 0" class="custom-select__option" @click="selectTo(item.city)" v-for="(item, index) in cities" :key="index">
                            <b>{{ item.city }}</b>
                            <em>{{ item.country }}</em>
                        </div>
                        <div v-if="routes.length > 0" class="custom-select__option" @click="selectTo(item.invert === 1 ? item.fromCity : item.toCity, item.id, item.invert)" v-for="(item, index) in routes" :key="index">
                          <b>{{ item.invert === 1 ? item.fromCity : item.toCity }}</b>
                          <em>{{ item.invert === 1 ? item.fromCountry : item.toCountry }}</em>
                        </div>
                    </div>
                    <div class="custom-select__options option-clear" v-if="cities.length === 0 && routes.length === 0 && selectedTo.length" :class="{ '--opened': openedTo }">
                      <div class="loader" style="height: 140px;width: 220px;" v-if="isDownloadRoutesTo">
                        <img class="loader-img" src="/img/loader.png" alt="loader">
                      </div>
                      <span v-else>Route not found</span>
                    </div>
                </div>
            </div>
            <div class="date-time">
                <v-custom-calendar></v-custom-calendar>
                <v-time></v-time>
              <v-humans v-if="this.width < 767" :data="{adults,childrens,luggage}"></v-humans>
            </div>
            <v-humans v-if="this.width > 767" :data="{adults,childrens,luggage}"></v-humans>
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
            <template v-if="(filteredRoutes.length === 0 || filteredRoutesTo.length === 0) && mode === 'home'">
                <div class="form-vue__footer --line">
                    <div class="label mobile">{{ $t('Chauffeur will wait 15 minutes free of charge') }}</div>
                    <span :style="{display:'block', color:'#ffffff'}">{{ $t("Can't find your destination?") }}</span>
                    <a :style="{display:'block'}" :href="getRequestUrl">{{ $t("Request a custom route") }}</a>
                </div>
            </template>
            <template v-else>
                <div v-if="mode === 'request' || mode === 'home'" class="label --white">* {{ $t("required for departures within 48 hours") }}</div>
            </template>
            <button class="btn-submit">
                <span v-if="mode === 'home'">{{ $t("Search") }}</span>
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
          width: 0,
          routes: [],
          from: '',
          to: '',
          fromList: [],
          toList: [],
          isDownloadRoutesFrom: true,
          isDownloadRoutesTo: true,
          filteredRoutes: [],
          filteredRoutesTo: [],
          filteredRoutesFrom: [],
          toCityId: '',
          fromCityId: '',
          cities: [],
        };
    },
    props: {
        mode: {
            type: String,
            default: "home"
        },
        error: {
            type: Boolean,
            default: false
        },
        // routes: {
        //     type: String,
        //     default: "[]"
        // },
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
        selectFrom(value, id=null, invert) {
          console.log(id)
            this.selectedFrom = value;
            this.updateError();
            this.route_id = id;
            this.invert = invert;
        },
        inputFrom() {
            this.updateError();
        },
        selectTo(value, id=null, invert) {
          console.log('-------', id)
            this.selectedTo = value;
            this.route_id = id;
            this.invert = invert;
            this.updateError();
        },
        fromRouteFocus () {
          this.openedFrom = true
          this.updateError()
          if (this.selectedFrom.length && this.selectedTo.length === 0) {
            this.getCities(this.selectedFrom);
          } else if (this.selectedTo.length) {
            this.getRoutes(this.selectedFrom, this.selectedTo);
          }
        },
        toRouteFocus () {
          this.openedTo = true;
          this.updateError();
          if (this.selectedTo.length && this.selectedFrom.length === 0) {
            this.getCities(this.selectedTo);
          } else if (this.selectedFrom.length) {
            this.getRoutes(this.selectedFrom, this.selectedTo);
          }
        },
        inputTo() {
            this.updateError();
            this.debounceInput
        },
        change() {
            let from = this.selectedFrom;
            let to = this.selectedTo;
            this.selectedFrom = to;
            this.selectedTo = from;
            this.$store.commit('clearPoint');
            if (this.invert === null) {
              this.invert = 0;
            }
            this.invert = this.invert === 0 ? 1 : 0;
            this.updateError();
        },
        search() {
            this.$store.commit('setCart', cart);
        },
        updateWidth() {
          this.width = window.innerWidth
        },
        debounceInput: _.debounce(function (e) {
          this.updateError();
          const input = e.target.name === 'from' ? this.selectedFrom : this.selectedTo;
          if (e.target.name === 'from' && this.selectedTo.length === 0 || e.target.name === 'to' && this.selectedFrom.length === 0 ) {
            this.getCities(input);
          } else {
            this.getRoutes(this.selectedFrom, this.selectedTo);
          }
        }, 500),
        async getCities(city) {
          this.routes = [];
          this.cities = [];
          const splitted = city.split("")
          const first = splitted[0].toUpperCase()
          const rest = [...splitted]
          rest.splice(0, 1)
          const resultCity = [first, ...rest].join("")
          if (resultCity.length > 0) {
            this.isDownloadRoutesFrom = true;
            this.isDownloadRoutesTo = true;
            await axios.get(`https://api.drivermytripline.com/api/city/search?city=${resultCity}`).then(resp => {
              this.cities = resp.data.map(el => {
                return {
                  city: el._source.city,
                  country: el._source.country
                }
              });
              this.isDownloadRoutesFrom = false;
              this.isDownloadRoutesTo = false;
            })
          }
        },
        async getRoutes(fromCity, toCity) {
          let fromCityUppercase = '';
          let toCityUppercase = '';
          let splitted = '';
          let first = '';
          let rest = '';
          if (fromCity.length > 0) {
            splitted = fromCity.split("")
            first = splitted[0].toUpperCase()
            rest = [...splitted]
            rest.splice(0, 1)
            fromCityUppercase = [first, ...rest].join("")
          }
          if (toCity.length > 0) {
            splitted = toCity.split("")
            first = splitted[0].toUpperCase()
            rest = [...splitted]
            rest.splice(0, 1)
            toCityUppercase = [first, ...rest].join("")
          }
          if (fromCityUppercase.length > 0 || toCityUppercase.length > 0) {
            this.cities = [];
            this.routes = [];
            this.isDownloadRoutesFrom = true;
            this.isDownloadRoutesTo = true;
            await axios.get(`https://api.drivermytripline.com/api/routes/search?fromCity=${fromCityUppercase}&toCity=${toCityUppercase}`).then(resp => {
              this.routes = resp.data.map(el => {
                return {
                  fromCity: el._source.fromCityName,
                  toCity: el._source.toCityName,
                  fromCountry: el._source.fromCountryName,
                  toCountry: el._source.toCountryName,
                  id: el._source.id,
                  invert: el._source.invert
                }
              })
              this.isDownloadRoutesFrom = false;
              this.isDownloadRoutesTo = false;
            })
          }
        }
    },
    mounted() {
        this.width = window.innerWidth
        window.addEventListener('resize', this.updateWidth );

        initValidation(".js-calculator");

        let $this = this;

        document.addEventListener("bouncerFormValid", function (el) {
            if (el.target.dataset?.entity === 'search') {
                try {
                    $this.submitForm(el)
                } catch (e) {
                }
            }
            return false
        });

        document.addEventListener("bouncerFormValidRequest", function (el) {
            if (el.target.dataset?.entity === 'search') {
                try {
                    $this.submitForm(el)
                } catch (e) {
                }
            }
            return false
        });


        // this.parsedRoutes = JSON.parse(this.routes)
        // axios.get('/route-list').then( resp => {
        //   this.parsedRoutes = resp.data
        //   // localStorage.setItem('routes', resp.data)
        //   // console.log(this.parsedRoutes)
        // });
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
.option-clear{
  height: 150px;
  width: 100%;
  background-color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: visible;
}
.option-clear img{
  width: 250px;
}
.loader{
  height: 100%;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}
.loader-img{
  width: 40px !important;
  height: 40px !important;
  animation-name: rotation;
  animation-duration: 2s;
  animation-iteration-count: infinite;
  animation-timing-function: linear;
}
@keyframes rotation {
  0% {
    transform:rotate(0deg);
  }
  100% {
    transform:rotate(360deg);
  }
}
</style>
