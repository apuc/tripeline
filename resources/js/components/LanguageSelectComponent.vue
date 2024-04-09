<template>
    <select id="language_select">
        <option
            :selected="currentlang === key"
            v-for="(lang, key) in JSON.parse(languages)"
                :value="key">
            {{ key.toUpperCase() }}
        </option>
    </select>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";
import SlimSelect from "slim-select";

let selectElements = [].slice.call(document.querySelectorAll(".js-select"));
selectElements.forEach(function (selectElement) {
    new SlimSelect({
        select: selectElement,
        showSearch: false,
    });
});


export default Vue.component("v-language-select", {
    props: {
        languages: [],
        currentlang: 'en'
    },
    mounted() {
        new SlimSelect({
            select: document.getElementById('language_select'),
            showSearch: false,
            onChange: (info) => {
                window.location.href = '/setlocale/' + info.value + '?path=' + window.location.pathname
            }
        });
    }
});
</script>
