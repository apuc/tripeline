<template>
    <div class="popup_wrapper fancybox__container" id="popup_box" v-show="active" @click="close">
        <modal v-show="showLoginModal" @close="closeModal()">
            <div class="popup --sm-- popup-login" id="login" v-show="form === '#login'">
                <div class="popup__wrap">
                    <h3 class="--center">{{ $t('Login') }}</h3>
                    <form data-entity="login" @submit.prevent="loginRequest" method="POST" class="js-form-validator_popup">
                        <input type="hidden" name="_token" :value="csrf">
                        <div class="input-block">
                            <input type="email" placeholder="Email" name="email" required>
                        </div>
                        <div class="input-block">
                            <input type="password" placeholder="Password" name="password" required>
                        </div>
                        <button type="submit" class="btn-submit --simple --no-opacity"><span>{{ $t('Login in') }}</span></button>
                        <a @click="openForgot">Forgot password</a>
                        <a @click="openRegistration">Registration</a>
                    </form>
                </div>
                <button @click="closeModal()"
                        class="carousel__button is-close"
                        title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" tabindex="-1">
                        <path d="M20 20L4 4m16 0L4 20"></path>
                    </svg>
                </button>
            </div>
        </modal>
        <modal v-show="showRegisterModal" @close="closeModal()">
            <div class="popup --sm-- popup-login" id="registration" v-show="form === '#registration'">
                <div class="popup__wrap">
                    <h3 class="--center">{{ $t('Register') }}</h3>
                    <form data-entity="register" class="js-form-validator_popup">
                        <input type="hidden" name="_token" :value="csrf">
                        <div class="input-block">
                            <input type="email" placeholder="Email" name="email" required>
                        </div>
                        <div class="input-block">
                            <input id="password" type="password" placeholder="Password" name="password" required>
                        </div>
                        <div class="input-block">
                            <input
                                data-bouncer-match="#password"
                                type="password" placeholder="Password confirm" name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn-submit --simple --no-opacity"><span>{{ $t('Register') }}</span></button>
                    </form>
                </div>
                <button @click="closeModal()"
                        class="carousel__button is-close"
                        title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" tabindex="-1">
                        <path d="M20 20L4 4m16 0L4 20"></path>
                    </svg>
                </button>
            </div>
        </modal>


        <modal v-show="showForgotModal" @close="closeModal()">
            <div class="popup --sm popup-forgotpass" id="forgotpass" v-show="form === '#forgotpass'">
                <div class="popup__wrap">
                    <h3 class="--center">{{ $t('Forgot password') }}</h3>
                    <form data-entity="forgot" class="js-form-validator_popup">
                        <input type="hidden" name="_token" :value="csrf">
                        <div class="input-block">
                            <input type="email" placeholder="Email" name="email" required>
                        </div>
                        <button class="btn-submit --simple --no-opacity"><span>{{ $t('Send') }}</span></button>
                    </form>
                </div>
                <button @click="closeModal()"
                        class="carousel__button is-close"
                        title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" tabindex="-1">
                        <path d="M20 20L4 4m16 0L4 20"></path>
                    </svg>
                </button>
            </div>
        </modal>
        <div class="popup --sm-- popup-success" id="message_success_box" style="display:none">
            <a id="message_success_box_request" data-fancybox data-src="#message_success_box" alt="user" style="display: none"></a>
            <form class="popup__wrap js-form-validator_popup">
                <img src="/img/success.svg" alt="success icon">
                <h3 style="font-size:20px" class="--center">{{ popupMessage }}</h3>
            </form>
        </div>
        <div class="popup --sm popup-success" id="message_error_box" style="display:none">
            <a id="message_error_box_request" data-fancybox data-src="#message_error_box" alt="user" style="display: none"></a>
            <form class="popup__wrap js-form-validator_popup">
                <img src="/img/error.svg" alt="success icon">
                <h3 style="font-size:20px" class="--center">{{ popupMessage }}</h3>
            </form>
        </div>
    </div>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";

import Swiper from 'swiper/bundle';
import initValidation from "./helper/validator";

export default Vue.component("v-popups", {
    props: {
        auth: false,
        csrf: '',
    },
    data() {
        return {
            showLoginModal: false,
            showRegisterModal: false,
            showForgotModal: false,
            active: false,
            form: '',
            popupMessage: '',
            pageUrl: window.location.href,
            link: '',
            popups: ['#login', '#registration'],
            loginForm: {
                email: '',
                password: ''
            },
            registerForm: {
                email: '',
                password: '',
                rePassword: ''
            }
        };
    },
    mounted() {

        initValidation(".js-form-validator_popup");
        if (document.location.hash === '#login') {
            this.showLoginModal = true
            this.active = true;
            this.form = '#login'
        }

        const $this = this

        document.addEventListener("bouncerFormValid", function (el) {
            console.log('bouncerFormValid');
            console.log(el.target.dataset);
            if (el.target.dataset?.entity === 'login') {
                    try {
                        var form = el.target;
                        $this.loginRequest(form)
                    } catch (e) {
                    }

                }

                if (el.target.dataset?.entity === 'register') {
                    try {
                        var form = el.target;

                        $this.registerRequest(form)

                    } catch (e) {
                        //console.log(e);
                        //console.log("Form Submit Error!");
                    }

                }

                if (el.target.dataset?.entity === 'forgot') {
                    try {
                        var form = el.target;
                        $this.registerForgot(form)

                    } catch (e) {
                        //console.log(e);
                        //console.log("Form Submit Error!");
                    }

                }

                if (el.target.dataset?.entity === 'search') {
                    try {
                        if (typeof window.CustomEvent !== 'function') return;
                        let event = new CustomEvent('bouncerFormValidRequest', {
                            bubbles: true,
                            detail: {}
                        });
                        el.target.dispatchEvent(event);

                    } catch (e) {
                        //console.log(e);
                        //console.log("Form Submit Error!");
                    }

                }
        });
        window.addEventListener('hashchange', this.hashChange.bind(this))
    },
    methods: {

        loginRequest(e) {
            const formData = new FormData(e.target);
            const formProps = Object.fromEntries(formData);
            axios.post('/' + window.App.language + '/login', formProps
            )
                .then(res => {
                    if (res) {
                        window.location.href = '/' + window.App.language + '/cabinet'
                    }
                }).catch(e => {
                this.popupMessage = e?.response?.data?.message
                document.getElementById('message_error_box_request').click()
            })
        },

        registerRequest(e) {
            const formData = new FormData(e);
            const formProps = Object.fromEntries(formData);
            this.popupMessage = ''

            axios.post('/' + window.App.language + '/register', formProps
            )
                .then(res => {
                    if (res) {
                        window.location.href = '/' + window.App.language + '/cabinet'
                    }

                    this.popupMessage = 'Success !!!'
                    document.getElementById('message_success_box_request').click()
                }).catch(e => {
                let errors = e?.response?.data?.errors || []

                try {
                    this.popupMessage += this.$t(e?.response?.data?.errors?.email[0]) || ''

                }catch (e){
                    //console.log(e);
                }
                try {
                    this.popupMessage += this.$t(e?.response?.data?.errors?.password[0]) || ''
                }catch (e){
                    //console.log(e);
                }

                document.getElementById('message_error_box_request').click()
            })
            return false
        },

        registerForgot(e) {
            // e.preventDefault()
            //console.log('registerRequest', e);
            const formData = new FormData(e);
            const formProps = Object.fromEntries(formData);
            this.popupMessage = ''

            axios.post('/' + window.App.language + '/password/email', formProps
            )
                .then(res => {
                    //console.log(res);
                    if (res) {
                        // window.location.href = '/' + window.App.language + '#login'
                    }

                    this.popupMessage = 'Success !!!'
                    document.getElementById('message_success_box_request').click()
                }).catch(e => {
                let errors = e?.response?.data?.errors || []

                try {
                    this.popupMessage += this.$t(e?.response?.data?.errors?.email[0]) || ''

                }catch (e){
                    //console.log(e);
                }
                try {
                    this.popupMessage += this.$t(e?.response?.data?.errors?.password[0]) || ''
                }catch (e){
                    //console.log(e);
                }

                // this.popupMessage = e?.response?.data?.message
                document.getElementById('message_error_box_request').click()
            })
            return false
        },

        hashChange() {
            if (this.$data.popups.indexOf(location.hash) >= 0) {
                if (location.hash === '#login') {
                    this.$data.showLoginModal = true
                }
                if (location.hash === '#registration') {
                    this.$data.showRegisterModal = true
                }
                this.$data.active = true
                this.$data.form = location.hash
            }

        },

        closeModal() {
            this.showLoginModal = false
            this.showRegisterModal = false
            this.showForgotModal = false
            this.active = false;
            this.form = ''
            location.hash = ''

        },

        openRegistration() {
            this.closeModal()
            this.showRegisterModal = true
            this.active = true;
            this.form = '#registration'

        },

        openForgot() {
            this.closeModal()
            this.showForgotModal = true
            this.active = true;
            this.form = '#forgotpass'
        },
        close(e) {
            if (e.target.id === 'popup_box') {
                this.active = false;
                this.form = ''
                location.hash = ''
            }

        }
    },
    watch: {
        'location.hash': function () {
            this.link = window.location.href
        }
    },
});
</script>
<style scoped>

.popup {
    display: inherit;
    position: relative;
}

.popup_wrapper {
    justify-content: center;
    align-items: center;
    -webkit-backdrop-filter: blur(3px);
    backdrop-filter: blur(3px);
    background: rgba(0, 0, 0, .7);
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    direction: ltr;
    margin: 0;
    padding: 0px;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    color: rgb(55, 65, 81);
    -webkit-tap-highlight-color: transparent;
    overflow: hidden;
    z-index: 1050;
    outline: 0;
    -webkit-transform-origin: top left;
    -ms-transform-origin: top left;
    transform-origin: top left;
    --carousel-button-width: 48px;
    --carousel-button-height: 48px;
    --carousel-button-svg-width: 24px;
    --carousel-button-svg-height: 24px;
    --carousel-button-svg-stroke-width: 2.5;
    --carousel-button-svg-filter: drop-shadow(1px 1px 1px rgba(0, 0, 0, 0.4))
}
</style>
