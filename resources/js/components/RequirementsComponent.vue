<template>
    <div class="requirements">
        <em>{{ textLength }}/100</em>
        <textarea name="requirements" placeholder="Your text" maxlength="100" @input="calcLength" @change="calcLength" @keyup="calcLength"></textarea>
    </div>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";
import ClickOutside from "vue-click-outside";

export default Vue.component("v-requirements", {
    data() {
        return {
            textLength: 0
        };
    },
    methods: {
        calcLength(event) {
            if(event.target.value.length > 100) return false;
            this.textLength = event.target.value.length;
        }
    }
});
</script>
