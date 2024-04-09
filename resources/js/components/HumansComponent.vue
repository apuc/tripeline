<template>
    <div class="select-humans" v-click-outside="close">
        <input type="number" required name="adults" :value="(adults == 0 ? '' : adults )">
        <input type="number" name="childrens" :value="(childrens == 0 ? 0 : childrens)">
        <input type="number" required name="luggage" :value="luggage">
        <div class="select-humans__head" @click="opened = !opened">
            <div :class="{error: errorHumans}" class="human-block">
                <em>{{$t("passengers")}}:</em>
                <span>{{ parseInt(adults) + parseInt(childrens) }}</span>
                <svg class="icon users">
                    <use xlink:href="/img/sprites/sprite.svg#users"></use>
                </svg>
            </div>
            <div class="human-block"><em>{{$t("luggage")}}: </em><span>{{ luggage }}x</span>
                <svg class="icon suitecase">
                    <use xlink:href="/img/sprites/sprite.svg#suitecase"></use>
                </svg>
            </div>
        </div>
        <div class="select-humans__list" v-show="opened">
            <div class="select-humans__list-item">
                <div><span>{{$t("Adults")}}</span><em>{{$t("Age")}}: 12+</em></div>
                <v-incdec :value="adults" :min="1"
                          :sync="true"
                          @value="changeValue('adults', $event)"></v-incdec>
            </div>
            <div class="select-humans__list-item">
                <div><span>{{$t("Childrens")}}</span><em>{{$t("Age")}}: 0-12</em></div>
                <v-incdec :value="childrens" :min="0"
                    :sync="true" @value="changeValue('childrens', $event)"></v-incdec>
            </div>
            <div class="select-humans__list-item">
                <div><span>{{$t("Luggage")}}</span><em>{{$t("Sets of bags")}}</em></div>
                <v-incdec :value="luggage" :min="0"
                          :sync="true"
                          @value="changeLuggage('luggage', $event)"
                ></v-incdec>
            </div>
        </div>
    </div>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";
import ClickOutside from "vue-click-outside";

export default Vue.component("v-humans", {
    data() {
        return {
            opened: false,
            number: 0,
            adults: 1,
            childrens: 0,
            luggage: 1,
            errorHumans: false
        };
    },
    props: [
        "data"
    ],
    mounted() {
        if (this.data){
            this.adults = parseInt(this.data['adults'] ?? 0)
            this.childrens = parseInt(this.data['childrens'] ?? 0);
            this.luggage = parseInt(this.data['luggage'] ?? 0);
        }
    },
    methods: {
        close() {
            this.opened = false;
        },
        changeValue(param, value) {
            this[param] = value;
            this.luggage = (this.adults + this.childrens)
            this.returnValues();
        },
        changeLuggage(param, value) {
            this[param] = value;
            this.returnValues();
        },
        returnValues() {
            this.$emit("return", {
                passengers: this.adults + this.childrens,
                luggage: this.luggage
            });
        }
    },
    directives: {
        ClickOutside
    },
    watch: {
        adults() {
            this.errorHumans = (parseInt(this.adults) + parseInt(this.childrens)) <= 0;
        },
        childrens() {
            this.errorHumans = (parseInt(this.adults) + parseInt(this.childrens)) <= 0;
        }
    }
});

</script>
