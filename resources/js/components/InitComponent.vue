<template>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";

import Swiper from 'swiper/bundle';

export default Vue.component("v-init", {
    props: {
        auth: false,
    },
    mounted() {
        let menuOpenBtn = document.querySelector(".js-menu-burger");
        let hederElement = document.querySelector(".header");

        menuOpenBtn.addEventListener("click", function () {
            menuOpenBtn.classList.toggle("--active");
            hederElement.classList.toggle("--active");
        });


        if (document.location.hash === '#login') {
            document.getElementById('auth_button').click()
        }

        if (document.querySelector(".partners-slider"))
            new Swiper(".partners-slider", {
                slidesPerView: "auto",
                spaceBetween: 47,
                loop: true,
                lazy: true,
                centeredSlides: true,
                roundLengths: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    0: {
                        spaceBetween: 15,
                    },
                    570: {
                        spaceBetween: 47,
                    }
                }
            });

        if (document.querySelector(".sliderhome-slider"))
            new Swiper(".sliderhome-slider", {
                slidesPerView: "auto",
                spaceBetween: 47,
                loop: true,
                lazy: true,
                centeredSlides: true,
                roundLengths: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    0: {
                        spaceBetween: 19,
                    },
                    570: {
                        spaceBetween: 58,
                    }
                }
            });
    }
});
</script>
