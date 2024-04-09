<template>
    <article :style="'background-image: url(/'+data.image+')'">
        <b>{{ data.title }}</b>
        <p v-html="data.body"></p>
        <input type="checkbox" :name="'visitalong' + index" v-model="checked" style="position: absolute; width: 0; height: 0; opacity: 0;">
        <button type="button" :class="{added: addedPoint}" @click="buy">
            Add for €{{ data.price }}
            <span>
          <svg class="icon check">
            <use xlink:href="/img/sprites/sprite.svg#check"></use>
          </svg>
          added
        </span>
        </button>
    </article>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";

export default Vue.component("v-edit-profile", {
    data() {
        return {
            checked: false
        };
    },
    props: ["data", "index", "addedPoint"],
    methods: {
        buy() {
            this.$emit("return", this.data, this.checked);
            this.checked = !this.checked;
        }
    }
});
</script>
