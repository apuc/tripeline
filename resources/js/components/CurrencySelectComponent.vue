<template>
    <select id="currency_select" class="">
        <option
            :selected="currentcurrency === key"
            v-for="(currency, key) in JSON.parse(currencylist)"
            :value="key">{{ currency }}
        </option>
    </select>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";
import SlimSelect from "slim-select";
import {mapState} from "vuex";

export default Vue.component("v-currency-select", {
    props: {
        currencylist: [],
        currentcurrency: 'eur',
        currentcurrencyexchanges: []
    },
    data() {
        return {}
    },
    mounted() {
        //console.log(this.currentcurrencyexchanges);
        this.$store.commit('setCurrency', this.currentcurrency);
        this.$store.commit('setCurrencyRates', JSON.parse(this.currentcurrencyexchanges));

        new SlimSelect({
            select: document.getElementById('currency_select'),
            showSearch: false,
            onChange: (info) => {
                axios.get('/setcurrency/' + info.value)
                .then(res=>{

                })
                this.$store.commit('setRate', this.currencyRates[info.value] || {rate:1});
                this.$store.commit('setCurrency', info.value);
            }
        });
    }, computed: {
        ...mapState({
            currency: store => store.currency,
            currencyRates: store => store.currency_rates
        }),
    }
});
</script>
