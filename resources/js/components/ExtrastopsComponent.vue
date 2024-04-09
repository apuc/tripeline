<template>
    <div>
        <div class="extrastops disabled">
            <div class="extrastops__item">
                <input type="hidden" :value="selected" :name="'extrastops' + list.length + 1">
                <input class="extrastops__item-1" :placeholder="'Stop' + '' + 1">
                <div class="extrastops__select">
                    <div class="extrastops__select-head" @click="toggle">
                        <span style="color: black">{{ selected }} min</span>
                        <svg class="icon arrow-down">
                            <use xlink:href="/img/sprites/sprite.svg#arrow-down"></use>
                        </svg>
                    </div>
                    <div class="extrastops__list" v-if="opened">
                        <ul>
                            <li v-for="(item, index) in list1" :key="index" @click="select(item)">
                                <span>{{ item }} min</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <button type="button" class="add" @click="addItem">
                <svg class="icon plus">
                    <use xlink:href="/img/sprites/sprite.svg#plus"></use>
                </svg>
            </button>
        </div>

        <v-extrastops-item @remove="removeItem" v-for="(item, index) in list" :key="index" :item="item" :index="index"></v-extrastops-item>

    </div>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";
import ClickOutside from "vue-click-outside";

export default Vue.component("v-extrastops", {
    data() {
        return {
            opened: false,
            list1: [
                60,
                80,
                100,
                120,
                140,
                160,
                180,
                200,
                220,
                240,
                260,
                280,
                300,
                320,
                340,
                360,
                380,
                400,
                420,
                440,
                460,
                480,
                500,
                520,
                540,
                560,
                580,
                600,
                620,
                640,
                660,
                680,
                700,
                720,
                740,
                760,
                780,
                800,
                820,
                840,
                860,
                880,
                900,
                920,
                940,
                960,
                980,
                1000,
                1020,
                1040,
                1060,
                1080,
                1100,
                1120,
                1140,
                1160,
                1180,
                1200,
                1220,
                1240,
                1260,
                1280,
                1300,
                1320,
                1340,
                1360,
                1380,
                1400,
                1420,
                1440
            ],
            selected: 60
        };
    },
    props: {
        disabled: {
            type: Boolean,
            default: false
        },
        list: {
            type: Array,
            default: []
        }
    },
    methods: {
        toggle() {
            this.opened = !this.opened;
        },
        close() {
            this.opened = false;
        },
        select(value) {
            this.selected = value;
            this.opened = false;
            this.$emit("return", value);
        },
        addItem() {
            this.list.push({});
        },
        removeItem(index) {
            this.list.splice(this.list.indexOf(this.list[index]), 1);
        }
    },
    directives: {
        ClickOutside
    }
});
</script>
