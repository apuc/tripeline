<template>
  <div>
    <form data-entity="payment" id="payment-form" class="order js-form-validator" :data-submit="submit_form">
      <input type="text" name="csrf" :value="csrf" hidden>
      <div class="container">
        <div class="order__wrap">
          <div class="order__form">
            <section>
              <h2>Lead passenger</h2>
              <div class="block-input">
                <input v-model="orderDetails.email" type="email" name="email" :placeholder="$t('Email')" required>
              </div>
              <div class="block-input half">
                <input v-model="orderDetails.first_name" name="first-name" :placeholder="$t('First name')" required>
              </div>
              <div class="block-input half">
                <input v-model="orderDetails.last_name" name="last-name" :placeholder="$t('Last name')" required>
              </div>
              <div class="block-input">
                <input v-model="orderDetails.birth_day" id="dayofbirth" class="js-input-mask" name="day-of-birth" data-mask="99.99.9999" :placeholder="$t('Day of birth')" required>
              </div>
              <div class="input-block input-phone">
                <input type="tel" id="phone" name="phone" required :placeholder="$t('Phone number')">
              </div>
              <div class="textarea-block">
                <textarea v-model="orderDetails.comment" name="comment" :placeholder="$t('Comment')"></textarea>
              </div>
            </section>
            <section class="--two">
              <h2>{{ $t("Pick up and drop-off") }}</h2>
              <em>{{ $t("You can add or change these up to 24 hours before departure.") }}</em>
              <div class="block-input">
                <input ref="pickup_address" v-model="orderDetails.pickup_address" name="pickup-address" :placeholder="$t('Pickup address')" required>
              </div>
              <div class="block-input">
                <input ref="drop_off_address" v-model="orderDetails.drop_off_address" name="drop-off-address" :placeholder="$t('Drop-off address')" required>
              </div>
            </section>
            <div id="payment-message" class="hidden"></div>

            <!--                    <p>Test Card</p>-->
            <!--                    <p>4242424242424242 12/25 123 12345</p>-->
            <!--                    <p>4000002500003155 12/25 123 12345</p>-->
            <section class="--last">
              <div class="order__payment">
                <div class="order__payment-item">
                  <div class="order__payment-wrap">
                    <h2>{{ $t("Payment") }}</h2>
                    <div class="checkbox --orange">
                      <input v-model="payment_type" value="1" type="radio" data-payment-check="1" id="check-strip" name="payment">
                      <label for="check-strip"><span>{{ $t("Credit cart") }}</span></label>
                    </div>
                    <div class="checkbox --orange">
                      <input v-model="payment_type" value="2" type="radio" data-payment-check="2" id="check-cash" name="payment">
                      <label for="check-cash">
                        <svg class="icon">
                          <use xlink:href="/img/sprites/sprite.svg#users"></use>
                        </svg>
                        <span>{{ $t("Cash") }}</span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="order__payment-item">
                  <div v-show="parseInt(payment_type) === 1" class="order__payment-type --violete active" data-payment-content="1">
                    <label>Card Number</label>
                    <div id="card-number"></div>
                    <ul>
                      <li v-for="(item, index) in payment_methods">
                        <img :src="item['img']">
                      </li>
                    </ul>
                    <br>
                    <div id="card-element">
                      <div class="stripe">
                        <div class="card_details">
                          <div>
                            <label>Card Expiry</label>
                            <div id="card-expiry"></div>
                          </div>
                          <div>
                            <label>Card CVC</label>
                            <div id="card-cvc"></div>
                          </div>
                        </div>
                        <div class="card-name">
                          <label>Card Name</label>
                          <input id="card-name" placeholder="card name"/>
                        </div>
                        <div id="card-error"></div>
                      </div>
                    </div>
                  </div>
                  <div v-show="parseInt(payment_type) === 2" class="order__payment-type --violete active" data-payment-content="1">
                    <div class="order__cash_text">
                      <b>Pay your driver directly at the end of your trip.</b>
                      <p>-  Pay in any currency. <br/>-  Gratuity isn’t included in the total price. While not required, if you <br/>&nbsp;&nbsp;had a great trip, you can reward your driver with an optional tip <br/>&nbsp;&nbsp;(10% is  sufficient).</p>
                    </div>
                  </div>
                  <div class="order__payment-type" data-payment-content="2">
                    <b>{{ $t("Pay your driver directly at the end of your trip") }}.</b>
                    <p>- {{ $t("Pay in any currency") }}.</p>
                    <p>- {{ $t("Gratuity isn’t included in the total price. While not required, if you had a great trip, you can reward your driver with an optional tip (10% is sufficient)") }}.</p>
                  </div>
                </div>
              </div>
            </section>
          </div>
          <aside class="order__aside">
            <div class="order__aside-wrap">
              <div class="order-sum js-order-sum-toggle">
                <div class="order-sum__title-mobile"><b><i :class="currency.toLowerCase() +'_money'"></i> {{ parseFloat(getTotalOrderAmount()).toFixed(0) }}</b><em>{{ $t("VAT included") }}</em>
                  <div class="tickets__footer-info">
                    <div><span>1-3</span>
                      <svg class="icon">
                        <use xlink:href="/img/sprites/sprite.svg#users"></use>
                      </svg>
                    </div>
                    <div><span>3x</span>
                      <svg class="icon">
                        <use xlink:href="/img/sprites/sprite.svg#suitecase"></use>
                      </svg>
                    </div>
                  </div>
                  <div class="arrow">
                    <svg class="icon">
                      <use xlink:href="/img/sprites/sprite.svg#arrow-down"></use>
                    </svg>
                  </div>
                </div>
                <div class="order-sum__head">
                  <h2>{{ $t("Trip summary") }}</h2>
                  <a :href="editOrder()">{{ $t("Edit itinerary") }}</a>
                </div>
                <div class="order-sum__country">
                  <div class="order-sum__country-item">
                    <b>{{ selected.orderRoute['from'] }}</b>
                    <span>{{ $t("Departure") }}: {{ selected.orderRoute.route_start | moment("ddd, MMM, D, h:mm A") }}</span>
                  </div>
                  <div class="order-sum__country-item">
                    <b>{{ selected.orderRoute['to'] }}</b>
                    <span>{{ $t("Estimated arrival") }}: {{ selected.orderRoute.route_end | moment("add", getExtraMinues + " m", "ddd, MMM, D, h:mm A") }}</span>

                  </div>
                </div>
                <div class="order-sum__cars">
                  <div>
                    <a v-if="selected.passengersExtra.length > 1" data-fancybox data-src="#select-ride" href="#">{{ $t("Other cars") }}</a>
                  </div>
                  <div class="order-sum__cars-item tickets__footer">
                    <template v-for="(item, index) in selected.passengers">
                      <i><img :src="'/'+ item.car['image']" alt="sedan"></i>
                      <div class="tickets__footer-info">
                        <h4>{{ item.car['title'] }}</h4><em>{{ item.car['brand'] }}</em>
                        <div><span>{{ item.car['places_min'] }}-{{ item.car['places_max'] }}</span>
                          <svg class="icon">
                            <use xlink:href="/img/sprites/sprite.svg#users"></use>
                          </svg>
                        </div>
                        <div><span>3x</span>
                          <svg class="icon">
                            <use xlink:href="/img/sprites/sprite.svg#suitecase"></use>
                          </svg>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>
                <div class="order-sum__footer">
                  <div>
                                    <span>{{ $t("Total") }} (<i :class="currency.toLowerCase() +'_money'"></i>)
                                </span>
                    <em>{{ $t("VAT included") }}</em>
                  </div>
                  <div><b><i :class="currency.toLowerCase() +'_money'"></i> {{ parseFloat(getTotalOrderAmount()).toFixed(0) }}</b></div>
                </div>
              </div>
              <div class="order-sum__submit">
                <button class="btn" type="submit">
                                <span>
                                    {{ $t("confirm and pay") }} <i :class="currency.toLowerCase() +'_money'"></i>{{ parseFloat(getTotalOrderAmount()).toFixed(0) }} *</span></button>
                <b>* {{ $t("The actual amount in AUD depends on your bank's exchange rate") }}.</b>
              </div>
            </div>
          </aside>
        </div>
      </div>

      <div class="popup --xl" id="select-ride">
        <form class="popup__wrap">
          <h3>{{ $t("Select your ride") }}</h3>
          <div class="popup-select-rider">
            <div v-for="(item, index) in selected.passengersExtra" :key="index" @click="setCar(item)">
              <label for="select-auto-1">
                <div class="tickets__footer">
                  <i><img :src="'/' + item.car.image" :alt="item.car.title"></i>
                  <div class="tickets__footer-info">
                    <h4>{{ item.car.title }}</h4><em>{{ item.brand }}</em>
                    <div><span>{{ item.car.places_min }} - {{ item.car.places_max }}</span>
                      <svg class="icon">
                        <use xlink:href="/img/sprites/sprite.svg#users"></use>
                      </svg>
                    </div>
                    <div><span>{{ item.car.luggage }}</span>
                      <svg class="icon">
                        <use xlink:href="/img/sprites/sprite.svg#suitecase"></use>
                      </svg>
                    </div>
                  </div>
                  <div class="tickets__footer-price">
                    <b>
                      <i :class="currency.toLowerCase() +'_money'"></i>
                      {{ parseFloat(getCarUpdatePrice(item.car.price)).toFixed(0) }}
                      <!--                                        {{ (parseFloat(item.car.price) * total_rate).toFixed(2) }}-->
                    </b>
                  </div>
                </div>
              </label>
            </div>
          </div>
          <!--                <button class="btn-submit &#45;&#45;simple &#45;&#45;no-opacity &#45;&#45;sm"><span>Save</span></button>-->
        </form>
      </div>
    </form>
  </div>
</template>
<script>
import Vue from "vue/dist/vue.esm.browser.min";
import {mapState} from "vuex";
import initValidation from "./helper/validator";

export default Vue.component("v-order-route", {
  data() {
    return {
      token: null,
      cardNumber: null,
      cardExpiry: null,
      cardCvc: null,
      cardName: null,

      loading: false,
      lineItems: [
        {
          price: 'some-price-id', // The id of the one-time price you created in your Stripe dashboard
          quantity: 1,
        },
      ],
      payment_type: 1,
      checked: false,
      stripeToken: null,
      orderDetails: {
        email: this.user ? this.user.email : '',
        first_name: this.user ? this.user.first_name : '',
        last_name: this.user ? this.user.last_name : '',
        phone: this.user ? this.user.phone : '',
        birth_day: this.user ? this.user.day_of_birth : '',
        comment: '',
        pickup_address: '',
        drop_off_address: '',
      },
      sel: {},
    };
  },
  props: ["data", "index", "addedPoint", "payment_methods", "csrf", "user"],

  mounted() {
    const pickupAddressAutocomplete = new google.maps.places.Autocomplete(this.$refs['pickup_address']);
    const dropoffAddressAutocomplete = new google.maps.places.Autocomplete(this.$refs['drop_off_address']);
    pickupAddressAutocomplete.addListener("place_changed", () => {
      this.orderDetails.pickup_address = pickupAddressAutocomplete.getPlace().formatted_address
    })
    dropoffAddressAutocomplete.addListener("place_changed", () => {
      this.orderDetails.drop_off_address = dropoffAddressAutocomplete.getPlace().formatted_address
    })

    const style = {
      base: {
        color: 'black',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
          color: '#aab7c4',
        },
        background: '#f8f8f8',
        border: '1px solid',
        'border-radius': '7px',
        padding: '5px'
      },
      invalid: {
        color: '#fa755a',
        iconColor: '#fa755a',
      },
    };
    this.cardNumber = this.stripeElements.create('cardNumber', {style});
    this.cardNumber.mount('#card-number');
    this.cardExpiry = this.stripeElements.create('cardExpiry', {style});
    this.cardExpiry.mount('#card-expiry');
    this.cardCvc = this.stripeElements.create('cardCvc', {style});
    this.cardCvc.mount('#card-cvc');


    initValidation(".js-form-validator");

    let $this = this;
    document.addEventListener("bouncerFormValid", async function (el) {
      'bouncerFormValid payment'

      if (el.target.dataset?.entity === 'payment') {
        if ($this.payment_type === 1) {
          //4242424242424242
          const token = await $this.createStripeToken()
          //console.log('token: ', token);
          if (token) {
            $this.stripeToken = token.id
            $this.submit_form(el)
          } else {
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
          }
        } else {
          $this.submit_form(el)

        }
      }


    });

    $( document ).ready(function() {
      let iti

      let dayofbirth = $("#dayofbirth");
      dayofbirth.mask("99.99.9999");
      /*
      * International Telephone Input v16.0.0
      * https://github.com/jackocnr/intl-tel-input.git
      * Licensed under the MIT license
      */
      var input = document.querySelectorAll("input[name=phone]");
      var iti_el = $('.iti.iti--allow-dropdown.iti--separate-dial-code');
      if (iti_el.length) {
        iti.destroy();

        // Get the current number in the given format


      }
      console.log('------------------input-------------', input);
      for (var i = 0; i < input.length; i++) {
        window.iti = intlTelInput(input[i], {
          autoHideDialCode: false,
          autoPlaceholder: "aggressive",
          initialCountry: "us",
          separateDialCode: true,
          preferredCountries: ['ru', 'th'],
          customPlaceholder: function (selectedCountryPlaceholder, selectedCountryData) {
            var phone = $('input[name="phone"]').val()
            var placeholder = '' + selectedCountryPlaceholder.replace(/[0-9]/g, 'X');
            $('input[name="phone"]').data('placeholder', placeholder)
            return !phone ? 'Phone number' : placeholder
          },
          geoIpLookup: function (callback) {
            $.get('https://ipinfo.io', function () {
            }, "jsonp").always(function (resp) {
              var countryCode = (resp && resp.country) ? resp.country : "";
              callback(countryCode);
            });
          },
          utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.0/js/utils.js" // just for
        });
        $('input[name="phone"]').on("focus click countrychange", function (e, countryData) {
          var pl = $(this).data('placeholder') + '';
          var res = pl.replace(/X/g, '9');
          if (res !== 'undefined') {
            $(this).inputmask(res, {placeholder: "X", clearMaskOnLostFocus: true});
          }
          console.log(res, pl)

        });
        $('input[name="phone"]').on("focusout", function (e, countryData) {
          console.log('iti',window.iti);
          var intlNumber = window.iti.getNumber();
          console.log(intlNumber)
        });
      }
    })
  },
  methods: {
    async createStripeToken() {
      const {token, error} = await this.$stripe.createToken(this.cardNumber);
      if (error) {
        this.showMessage(error.message)
        document.getElementById('card-error').innerHTML = error.message;
        return false;
      }

      return token
    },
    gooleMapsAutocomplete () {
      google.maps.event.addListener(autocomplete, 'place_changed', () => {
        console.log(auto)
      })
    },
    searchPickUpAdresse () {
      const url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address";
      const token = "1ad9cf1ccda9b3c8af5fdb4747edd6459cbddf9f";
      const api_key = "AIzaSyA4lFGDJ5wJKl23wNI3XxLmZ-QMCJkk3CM"
      const body = {query: this.orderDetails.pickup_address, language: "en"};

      const options = {
        method: "POST",
        mode: "cors",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "Authorization": "Token " + token
        },
        body: JSON.stringify(body)
      }
      console.log(window.google)

      // fetch(url, options)
      //     .then(response => response.text())
      //     .then(result => console.log(result))
      //     .catch(error => console.log("error", error));
    },
    setLoading(isLoading) {
      if (isLoading) {
        // Disable the button and show a spinner
        document.querySelector("#submit").disabled = true;
        document.querySelector("#spinner").classList.remove("hidden");
        document.querySelector("#button-text").classList.add("hidden");
      } else {
        document.querySelector("#submit").disabled = false;
        document.querySelector("#spinner").classList.add("hidden");
        document.querySelector("#button-text").classList.remove("hidden");
      }
    },
    showMessage(messageText) {
      const messageContainer = document.querySelector("#payment-message");

      messageContainer.classList.remove("hidden");
      messageContainer.textContent = messageText;

      setTimeout(function () {
        messageContainer.classList.add("hidden");
        messageText.textContent = "";
      }, 4000);
    },
    setCar(car) {
      this.passengers = [car]
      this.passengers = [car]
      var box = window.document.getElementById('select-ride')
      var f = window.document.getElementsByClassName('fancybox__container')
      var b = box.getElementsByClassName('carousel__button')
      if (f && f[0])
        f[0].click()

      if (b && b[0])
        b[0].click()

      this.$store.commit('choseCar', car)
      return true
    },
    editOrder() {
      return '/' + window.App.language + '/' + `search?route=${this.cart.route_id}&from=Barcelona&to=Bajram+Curri&data=${this.cart.data}&hours=${this.cart.hours}&minutes=${this.cart.minutes}&adults=${this.cart.adults}&childrens=${this.cart.childrens}&luggage=${this.cart.luggage}`
    },
    getActions() {
      return '/' + window.App.language + '/' + 'order-success'
    },
    async submit_form(e) {

      e.preventDefault()

      this.$emit("return", this.data, this.checked);

      this.checked = !this.checked;

      let data = this.cart
      data['order_details'] = this.orderDetails
      data['stripe_token'] = this.stripeToken
      data['payment_type'] = this.payment_type
      data['phone'] = window.iti.getNumber()
      data['currency'] = this.currency
      data['total'] = this.getTotalOrderAmount()

      axios.post('/' + window.App.language + '/set_order', data)
          .then(res => {
            if (res) {
              if (res.data['status'] === 'success') {
                this.places = res.data ?? [];
                this.$store.commit('clearOrder');
                // window.location.href = this.getUrl(res.data['path']);
                console.log(res)
              }
            }
          }).catch(e => {
        // window.location.href = this.getUrl('order-cancel');
      })
    },
    getUrl(path) {
      return '/' + window.App.language + '/' + path
    },
    getOrderSuccessUrl() {
      return '/' + window.App.language + '/order-success'
    },
    getOrderCancelUrl() {
      return '/' + window.App.language + '/order-cancel'
    },
    getTotalOrderAmount() {
      return (
          (
              this.total_rate *
              (
                  parseFloat(this.selected['car_price']) +
                  parseFloat(this.selected['price'])
              )) +
          parseFloat(this.selected['withstopsListPrice'])
      ).toFixed(2)
    },
    getCarUpdatePrice(car_price) {
      return (this.total_rate *
          (
              parseFloat(car_price) +
              parseFloat(this.selected['price']) +
              parseFloat(this.selected['withstopsListPrice'])
          ).toFixed(2)).toFixed(2)
    }
  },
  computed: {
    ...mapState({
      cart: store => store.cart,
      count: store => store.count,
      route: store => store.route,
      points: store => store.points,
      selected: store => store.selected,
      rate: store => store.rate,
      total_rate: store => store.total_rate,
      currency: store => store.currency,
    }),
    stripeElements() {
      return this.$stripe.elements();
    },
    getExtraMinutes() {
      let extraMinutes = 0;
      this.points.forEach(item => {
        extraMinutes += item.extra;
      });
      return extraMinutes;
    },
  }
});
</script>
<style scoped>
.card_details {
  display: flex;
  flex-direction: row;
  justify-content: space-between
}
#card-number, #card-name{
  border-radius: 7px;
  padding: 13px;
  margin: 5px 0;
  background: #f8f8f8;
}
#card-expiry{
  border-radius: 7px;
  padding: 13px;
  margin: 5px 0;
  background: #f8f8f8;
}
#card-cvc{
  border-radius: 7px;
  padding: 13px;
  margin: 5px 0;
  background: #f8f8f8;
}
</style>
