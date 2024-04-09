<template>
    <div class="choosecar" v-click-outside="close">
        <div class="choosecar__head" @click="toggle">
            <div class="choosecar__item">
                <i>
                    <img :src="'/' + selected.image" alt="sedan">
                </i>
                <span>{{ selected.title }} - {{ selected.brand }}</span>
                <svg class="icon arrow-down">
                    <use xlink:href="/img/sprites/sprite.svg#arrow-down"></use>
                </svg>
            </div>
        </div>

        <ul v-show="opened">
            <li v-for="(item, index) in cars" :key="index" @click="select(item)">
                <div class="choosecar__item">
                    <i>
                        <img :src="'/' + item.image" :alt="item.title">
                    </i>
                    <span>{{ item.title }} - {{ item.brand }}</span>
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";
import ClickOutside from "vue-click-outside";

export default Vue.component("v-choosecar", {
    props:{
        cars: {
            type: String,
            default: "[]"
        },
    },
    data() {
        return {
            selected: {},
            opened: false
        };
    },
    created() {
        this.selected = this.cars[0]
    },
    methods: {
        select(item) {
            this.selected = item;
            this.opened =  false;
        },
        toggle() {
            this.opened = !this.opened;
        },
        close() {
            this.opened = false;
        }
    },
    directives: {
        ClickOutside
    }
});
</script>
