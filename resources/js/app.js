/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

import Bouncer from "./import/bouncer.polyfills.min";


Vue = window.Vue = require('vue/dist/vue.esm.browser.min').default;

import {StripePlugin} from '@vue-stripe/vue-stripe';

const options = {
    pk: 'pk_test_kpe60iKVJCwXf6qeQ6ZvkzMl',
    // stripeAccount: process.env.STRIPE_ACCOUNT,
    apiVersion: '2020-08-27',
    locale: 'en',
};


Vue.use(StripePlugin, options);


import Vuex from 'vuex';

window.Vuex = Vuex;

Vue.use(Vuex);

import de from '../lang/de.json'
import en from '../lang/en.json'
import es from '../lang/es.json'
import ru from '../lang/ru.json'
import ch from '../lang/ch.json'


import VueI18n from 'vue-i18n';

const i18n = new VueI18n({
    locale: 'en',
    fallbackLocale: 'en',
    messages: {
        de, en, es, ru, ch
    }
})

Vue.use(VueI18n);

// Vue.use(SlimSelect);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

import {Fancybox} from "@fancyapps/ui";

import store from "./store/index";
import VCalendar from "v-calendar";

Vue.use(VCalendar);

// Vue.component('v-init', ()=> import('./components/InitComponent').default);
Vue.component('v-popups', require('./components/PopupsComponent').default);
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Vue.component('v-select', require('./components/SelectComponent.vue').default);
Vue.component('v-custom-calendar', require('./components/CustomCalendarComponent').default);
Vue.component('v-time', require('./components/TimeComponent').default);
Vue.component('v-humans', require('./components/HumansComponent').default);
Vue.component('v-incdec', require('./components/IncdecComponent').default);
Vue.component('v-calculator', require('./components/CalculatorComponent').default);
Vue.component('v-reg-partner', require('./components/RegPartnerComponent').default);
// Vue.component('v-custom-search', require('./components/CustomSearchComponent').default);
// Vue.component('v-language-select', require('./components/LanguageSelectComponent').default);
// Vue.component('v-currency-select', require('./components/CurrencySelectComponent').default);
//Vue.component('v-order-route', require('./components/OrderComponent').default);

Vue.use(require('vue-moment'));

Vue.config.devtools = false;
export default new Vue({
    components: {
        vInit: () => import(/* webpackPrefetch: true */ './components/InitComponent'),
        // vPopups: () => import(/* webpackPrefetch: true */ './components/PopupsComponent'),
        vSelect: () => import(/* webpackPrefetch: true */ './components/SelectComponent'),
        // vCustomCalendar: () => import(/* webpackPrefetch: true */ './components/CustomCalendarComponent'),
        // vTime: () => import(/* webpackPrefetch: true */ './components/TimeComponent'),
        // vHumans: () => import(/* webpackPrefetch: true */ './components/HumansComponent'),
        // vIncdec: () => import(/* webpackPrefetch: true */ './components/IncdecComponent'),
        // vCalculator: () => import(/* webpackPrefetch: true */ './components/CalculatorComponent'),
        vCustomSearch: () => import(/* webpackPrefetch: true */ './components/CustomSearchComponent'),
        vLanguageSelect: () => import(/* webpackPrefetch: true */ './components/LanguageSelectComponent'),
        vCurrencySelect: () => import(/* webpackPrefetch: true */ './components/CurrencySelectComponent'),
        vOrderRoute: () => import(/* webpackPrefetch: true */ './components/OrderComponent'),
    },
    el: '#app',
    i18n,
    store: new Vuex.Store(store)
});
