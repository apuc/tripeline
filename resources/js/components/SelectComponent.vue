<template>
    <div class="custom-select">
        <div class="custom-select__item" :class="{'--active': openedFrom }">
            <div class="custom-select__head" data-input-parent :class="{error: errorFrom}">
                <input type="number" :value="filteredRoutesTo.length > 0 ? filteredRoutesTo[0].id : 0" name="route" hidden>
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
            <div class="custom-select__options" :class="{ '--opened': openedFrom }">
                <div class="custom-select__option" @click="selectFrom(item)" v-for="(item, index) in filteredRoutes" :key="index">
                    <b>{{ item.from_city }}</b>
                    <em>{{ item.from_country }}</em>
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
                    @keyup="openedTo = true"
                    @blur="toggle"
                />
            </div>
            <div class="custom-select__options" :class="{ '--opened': openedTo }">
                <div class="custom-select__option" @click="selectTo(item)" v-for="(item, index) in filteredRoutes" :key="index">
                    <b>{{ item.to_city }}</b>
                    <em>{{ item.to_country }}</em>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";
import ClickOutside from "vue-click-outside";

export default Vue.component("v-select", {
    data() {
        return {
            parsedRoutes: [],
            openedFrom: false,
            openedTo: false,
            selectedFrom: "",
            errorFrom: false,
            selectedTo: "",
            errorTo: false,
            firstStart: false,
            route_id: null
        };
    },
    props: {
        errorFirst: {
            type: Boolean,
            default: false
        },
        returnFrom: {
            type: Function
        },
        routes: {
            type: Array,
            default: function () {
                return []
            }
        },
        orderRoute: []
    },
    mounted() {
        this.parsedRoutes = JSON.parse(this.routes)

    },
    created() {
        this.firstStart = true;
        //console.log(this.routes);
        //console.log('dddddddd');
        // //console.log(this.filteredRoutes);
        //console.log('dddddddd');
    },

    computed: {
        filteredRoutes() {
            return this.parsedRoutes.filter(r => {
                return this.selectedFrom.length > 0 ? r.from_city.toLowerCase().indexOf(this.selectedFrom.toLowerCase()) >= 0 : true;
            }).filter(r => {
                return this.selectedTo.length > 0 ? r.to_city.toLowerCase().indexOf(this.selectedTo.toLowerCase()) >= 0 : true;
            })
        },
        filteredRoutesTo() {
            return this.parsedRoutes.filter(r => {
                return this.selectedFrom.length > 0 ? r.from_city.toLowerCase().indexOf(this.selectedFrom.toLowerCase()) >= 0 : true;
            }).filter(r => {
                return this.selectedTo.length > 0 ? r.to_city.toLowerCase().indexOf(this.selectedTo.toLowerCase()) >= 0 : true;
            })
        }
    },
    methods: {
        updateError() {
            // this.errorFrom = this.selectedFrom.length <= 2;
            // this.errorTo = this.selectedTo.length <= 2;
        },
        selectFrom(item) {
            // this.openedFrom = false;
            this.selectedFrom = item.from_city;
            this.updateError();
        },
        inputFrom() {
            this.updateError();
        },
        selectTo(item) {
            // this.openedTo = false;
            this.selectedTo = item.to_city;
            this.route_id = item.route_id;
            this.updateError();
        },
        inputTo() {
            this.updateError();
        },
        change() {
            let from = this.selectedFrom;
            let to = this.selectedTo;
            this.selectedFrom = to;
            this.selectedTo = from;
            this.updateError();
        },
        toggle() {
            setTimeout(() => {
                this.openedFrom = false;
                this.openedTo = false;
            }, 300);
        }
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
</style>
