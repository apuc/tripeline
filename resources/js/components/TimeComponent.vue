<template>
    <div class="custom-time" v-click-outside="close">
        <div class="custom-time__head" :class="{'error': error}" @click="toggle"><span>{{ hours | toNormNumber }}:{{ minutes | toNormNumber }}</span>
            <input hidden name="hours" :value="hours"/>
            <input hidden name="minutes" :value="minutes"/>
            <input hidden name="pm" :value="pm"/>
            <input hidden name="am" :value="!pm"/>
            <svg class="icon arrow-down">
                <use xlink:href="/img/sprites/sprite.svg#arrow-down"></use>
            </svg>
        </div>
        <div class="custom-time__dropdown" v-show="opened">
            <div class="custom-time__dropdown-content">
                <div class="num-select">
                    <button class="plus" type="button" @click="hPlus">
                        <svg class="icon arrow-down">
                            <use xlink:href="/img/sprites/sprite.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <span>{{ hours | toNormNumber }}</span>
                    <button class="minus" type="button" @click="hMinus">
                        <svg class="icon arrow-down">
                            <use xlink:href="/img/sprites/sprite.svg#arrow-down"></use>
                        </svg>
                    </button>
                </div>
                <em>:</em>
                <div class="num-select">
                    <button class="plus" type="button" @click="mPlus">
                        <svg class="icon arrow-down">
                            <use xlink:href="/img/sprites/sprite.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <span>{{ minutes | toNormNumber }}</span>
                    <button class="minus" type="button" @click="mMinus">
                        <svg class="icon arrow-down">
                            <use xlink:href="/img/sprites/sprite.svg#arrow-down"></use>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";
import ClickOutside from "vue-click-outside";

export default Vue.component("v-time", {
    data() {
        return {
            selectedTime: new Date().getHours() + ":" + new Date().getMinutes(),
            hours: new Date().getHours(),
            minutes: new Date().getMinutes(),
            opened: false,
            pm: true
        };
    },
    props: {
        error: {
            type: Boolean,
            default: false
        },
        d: {
            type: Date,
            default: new Date().getHours() + ":" + new Date().getMinutes()
        },
        h: {
            type: Date,
            default: new Date().getHours()
        },
        m: {
            type: Date,
            default: new Date().getMinutes()
        },
    },
    mounted() {
        this.selectedTime = this.d;
        this.hours = this.h;
        this.minutes = this.m;
    },
    methods: {
        toggle() {
            this.opened = !this.opened;
        },
        close() {
            this.opened = false;
        },
        updateTime() {
            // this.$store.commit("changeState", {
            //   name: "time",
            //   content: this.hours + ":" + this.minutes
            // });
        },
        hPlus() {
            this.updateTime();
            this.hours = this.hours >= 24 ? 0 : this.hours + 1;
        },
        hMinus() {
            this.updateTime();
            this.hours = this.hours <= 0 ? 24 : this.hours - 1;
        },
        mPlus() {
            this.updateTime();
            this.minutes = this.minutes >= 59 ? 0 : this.minutes + 1;
        },
        mMinus() {
            this.updateTime();
            this.minutes = this.minutes <= 0 ? 59 : this.minutes - 1;
        }
    },
    directives: {
        ClickOutside
    },
    filters: {
        toNormNumber(number) {
            return (number <= 9 ? "0" + number : number).toString();
        }
    }
});

</script>
<style scoped>
.num-select button{
    flex-direction: column
}
</style>
