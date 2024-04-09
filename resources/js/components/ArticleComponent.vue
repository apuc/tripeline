<template>
    <article :style="'background-image: url(/'+data.image+')'">
        <div style="
      width: 18rem;
      background: rgba(0, 0, 0, .6);
      display: inline-table;
      justify-content: center;
      flex-direction: column;
      padding: 21px;
      border-radius: 22px;">
            <a data-fancybox :data-src="'#hidden-content_' + data.id" href="javascript:;">

                <b>{{ data.title }}</b>
                <div :style="{'max-height':'40px', 'overflow':'hidden', 'margin-bottom': '5px'}">
                    <p v-html="data.body"></p>
                </div>
            </a>
            <input type="checkbox" :name="'visitalong' + index" v-model="checked" style="position: absolute; width: 0; height: 0; opacity: 0;">
            <button type="button" :class="{added: addedPoint}" @click="buy">
                {{ $t('Add for') }} <i :class="currency.toLowerCase() +'_money'"></i>
                {{ (parseFloat(data.price) * rate).toFixed(0) }}
                <span>
          <svg class="icon check">
            <use xlink:href="/img/sprites/sprite.svg#check"></use>
          </svg>
                    {{ $t('added') }}
        </span>
            </button>
            <div style="display: none;" :id="'hidden-content_' + data.id">
                <h2>{{ data.title }}</h2>
                <p v-html="data.body"></p>
            </div>
        </div>
    </article>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";
import {mapState} from "vuex";

export default Vue.component("v-article", {
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
    },
    computed: {
        ...mapState({
            rate: store => store.rate,
            currency: store => store.currency,
        }),
    }
});
</script>
<style scoped>
.psearch__other article p {
    max-height: 127px;
    overflow: hidden;
}
</style>
<style scoped>
article a {
    text-decoration: none;
    text-transform: none;
}
</style>
