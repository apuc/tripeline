<template>
    <div class="withstops__list-item">
        <i>
            <img :src="'/'+data.image" :alt="data.title"/>
        </i>
        <div>
            <b>{{ data.title }}</b>
            <span>{{ data.date }}</span>
        </div>
        <div class="incdec --max">
            <div class="incdec__plus" @click="minus">
                <svg class="icon minus">
                    <use xlink:href="/img/sprites/sprite.svg#minus"></use>
                </svg>
            </div>
            <div class="incdec__input"><span>{{ (data.durations + data.extra).toFixed(2) }} min</span></div>
            <input type="hidden" :name="'withstops-' + data.id" :value="selected">
            <div class="incdec__minus" @click="plus">
                <svg class="icon plus">
                    <use xlink:href="/img/sprites/sprite.svg#plus"></use>
                </svg>
            </div>
        </div>
        <b>
            <i :class="currency.toLowerCase() +'_money'"></i>
            {{ parseFloat(getPrice).toFixed(0) }}
        </b>
        <button type="button" @click="remove">
            <svg class="icon minus">
                <use xlink:href="/img/sprites/sprite.svg#close-small"></use>
            </svg>
        </button>
    </div>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";
import {mapState} from "vuex";

export default Vue.component("v-withstops", {
    data() {
        return {
            selected: 0,
            step: 30,
            max: 2880
        };
    },
    props: ["data"],
    mounted() {
        this.selected = 0
    }, computed: {
        ...mapState({
            rate: store => store.rate,
            total_rate: store => (store.rate + store.country_rate) > 0 ? (store.rate + store.country_rate) : 1,
            currency: store => store.currency,
        }),
        getPrice() {
            //console.log('getPrice:', this.data);
            if (this.selected > 0){
                return (this.rate * (this.data.price + (((this.data.price_per_hour).toFixed(2) / 2) * ((this.selected / this.step))))).toFixed(2)
            }else{
                return (this.rate * this.data.price).toFixed(2)
            }
        }
    },
    methods: {
        plus() {
            if (this.selected + this.data.durations + this.step > this.max) return false;
            this.selected += this.step;
            this.$emit("update_time", {data: this.data, extra: this.selected});
        },
        minus() {
            if (this.selected <= 0) return false;
            this.selected -= this.step;
            this.$emit("update_time", {data: this.data, extra: this.selected});
        },
        remove() {
            this.$emit("remove", this.data);
        }
    }
});
</script>
