import Vue from "vue";
import VuexPersistence from 'vuex-persist'

const vuexLocal = new VuexPersistence({
    storage: window.localStorage
})


let cart = window.localStorage.getItem('cart');
let points = window.localStorage.getItem('points');


const store = {
    state: {
        cart: cart ? JSON.parse(cart) : [],
        route: null,
        points: cart ? JSON.parse(points) : [],
        count: 0
    },
    mutations: {
        setRoute(state, item) {
            state.route = item
            state.count++;
        },
        addPoint(state, item) {
            state.points.push(item)
        },
        saveCart(state) {
            console.log('saveCart');
        },
        removePoint(state, item) {
            state.points.splice(state.points.indexOf(item), 1);
        }
    },
    plugins: [vuexLocal.plugin]
}

export default store