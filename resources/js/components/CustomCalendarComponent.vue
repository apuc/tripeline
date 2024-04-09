<template>
    <div class="custom-calendar" v-click-outside="close">
        <div class="custom-calendar__btn" @click="opened = !opened"><span>{{ selectedDate }}</span>
            <svg class="icon calendar">
                <use xlink:href="/img/sprites/sprite.svg#calendar"></use>
            </svg>
        </div>
        <div class="calendar" v-show="opened">
            <input name="data" type="hidden" :value="selectedDate">
            <v-date-picker @change="changeDate" mode="single" :min-date="getMinDate()" :model-config="modelConfig" v-model="selectedDate" is-required locale="en-EN"></v-date-picker>
        </div>
    </div>
</template>

<script>
import Vue from "vue/dist/vue.esm.browser.min";
import ClickOutside from "vue-click-outside";

export default Vue.component("v-custom-calendar", {
    props: {
        routes: [],
        d: {
            type: Date,
            default: new Date()
        },
    },
    data() {
        return {
            opened: false,
            selectedDate: new Date().toLocaleDateString(),
            modelConfig: {
                type: "string",
                mask: "DD.MM.YYYY", // Uses 'iso' if missing
            },
            attrs: [
                {
                    key: "today",
                    highlight: "red",
                    dates: new Date(),
                },
            ],
        };
    },
    mounted() {
        var current = new Date();
        var followingDay = new Date(current.getTime() + (86400000 / 2));
        this.selectedDate = followingDay.toLocaleDateString()
    },
    computed: {
        calendarData() {
            return this.selectedDate ? (new Date(this.selectedDate).toLocaleDateString()) : "";
        }
    },
    methods: {
        changeDate(e){
            //console.log(e);
        },
        getMinDate() {
            let current = new Date();
            return new Date(current.getTime() + (86400000 / 2))
        },
        close() {
            this.opened = false;
        }
    },
    watch: {
        selectedDate() {
            this.opened = false;
        }
    },
    directives: {
        ClickOutside
    }
});
</script>
