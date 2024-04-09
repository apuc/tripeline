<template>
  <div class="homehead__form">
    <div class="form-block">
      <div class="form-block__wrap center">
        <div v-if="auth" class="homehead-form form-vue calc center">
          <div class="form__img">
            <img src="/img/completed.svg" alt="img">
          </div>
          <div class="form__text">
            <span>Thank you, partner, we will contact you as soon as possible. There is a new chapter to begin.</span>
          </div>
        </div>
        <div v-else-if="check && !auth" class="homehead-form form-vue calc center gif">
          <div class="form__img">
            <img src="https://pear-advert.ru/images/uploads/blog/273/30.gif" alt="img"/>
          </div>
        </div>
        <div v-else class="homehead-form form-vue calc" id="homehead-form">
          <div class="form__header">
            <h3 v-if="typeForm === 'partner'">Become a partner</h3>
            <h3 v-if="typeForm === 'driver'">Become a driver</h3>
            <h3 v-if="typeForm === 'travel_agency'">Become a Travel agency</h3>
          </div>
          <div class="form__from-to">
            <div class="custom-select">
              <input
                  @input="validationFirstName()"
                  :class="{ 'invalid': errors.firstName.length > 0}"
                  name="First name"
                  placeholder="First name"
                  required="required"
                  type="search"
                  autocomplete="off"
                  v-model="formData.firstName"
              />
              <div v-if="errors.firstName.length > 0" class="errors">
                <span>{{ errors.firstName[0] }}</span>
              </div>
            </div>
            <div class="custom-select">
              <input
                  @input="validationLastName()"
                  :class="{ 'invalid': errors.lastName.length > 0}"
                  name="Last name"
                  placeholder="Last name"
                  required="required"
                  type="search"
                  autocomplete="off"
                  v-model="formData.lastName"
              />
              <div v-if="errors.lastName.length > 0" class="errors">
                <span>{{ errors.lastName[0] }}</span>
              </div>
            </div>
          </div>
          <div v-if="typeForm === 'partner'" class="custom-select">
            <input
                @input="validationCompany()"
                :class="{ 'invalid': errors.companyName.length > 0}"
                name="Name of Company"
                placeholder="Name of Company"
                required="required"
                type="search"
                autocomplete="off"
                v-model="formData.companyName"
            />
            <div v-if="errors.companyName.length > 0" class="errors">
              <span>{{ errors.companyName[0] }}</span>
            </div>
          </div>
          <div class="custom-select city">
            <input
                @input="openRoutes()"
                :class="{ 'invalid': errors.city.length > 0}"
                name="Your city"
                placeholder="Your city"
                required="required"
                type="search"
                autocomplete="off"
                v-model="formData.city"
            />
            <div class="custom-select__options" v-if="this.filteredRoutes().length > 0" :class="{ '--opened': openedRoutes }">
              <div class="custom-select__option" @click="selectFrom(item)" v-for="(item, index) in this.filteredRoutes()" :key="index">
                <b>{{ item.city}}</b>
                <em>{{ item.country }}</em>
              </div>
            </div>
            <div v-if="errors.city.length > 0" class="errors">
              <span>{{ errors.city[0] }}</span>
            </div>
          </div>
          <div v-if="isTypeCompany" class="custom-select company">
            <button @click="isOpenDropdown = !isOpenDropdown" :class="{ 'invalid': errors.typeCompany.length > 0 }"><span :class="{ 'bold': isBold }">{{ formData.typeCompany }}</span>
              <svg class="select__icon" viewBox="0 0 53 32" id="arrow-down" xmlns="http://www.w3.org/2000/svg">
                <path d="M26.677 21.248L6.725.875C6.203.333 5.471-.003 4.661-.003S3.119.334 2.598.875l-.001.001L.853 2.652A2.974 2.974 0 000 4.742v.023-.001c0 .8.304 1.547.853 2.107l23.755 24.267a2.858 2.858 0 002.05.864h.02-.001.014c.806 0 1.534-.333 2.055-.869l.001-.001L52.48 6.892a2.974 2.974 0 00.853-2.09v-.017-.015c0-.814-.326-1.552-.854-2.091v.001L50.735.893C50.211.355 49.48.022 48.671.022s-1.54.334-2.063.871l-.001.001-19.931 20.357z"></path>
              </svg>
            </button>
            <div v-if="isOpenDropdown && isTypeCompany" class="select-english__list" style="">
              <div @click="changeDropdown(typeCompany, 'company')" class="select-english__list-item" v-for="typeCompany in typesCompany" :key="typeCompany">
                <div>
                  <span>{{ typeCompany }}</span>
                </div>
              </div>
            </div>
            <div v-if="errors.typeCompany.length > 0 && isTypeCompany" class="errors">
              <span>{{ errors.typeCompany[0] }}</span>
            </div>
          </div>
          <div v-if="!isTypeCompany" class="custom-select english">
            <button @click="isOpenDropdown = !isOpenDropdown" :class="{ 'invalid': errors.levelEnglish.length > 0 }"><span :class="{ 'bold': isBold }">{{ formData.levelEnglish }}</span>
              <svg class="select__icon" viewBox="0 0 53 32" id="arrow-down" xmlns="http://www.w3.org/2000/svg">
                <path d="M26.677 21.248L6.725.875C6.203.333 5.471-.003 4.661-.003S3.119.334 2.598.875l-.001.001L.853 2.652A2.974 2.974 0 000 4.742v.023-.001c0 .8.304 1.547.853 2.107l23.755 24.267a2.858 2.858 0 002.05.864h.02-.001.014c.806 0 1.534-.333 2.055-.869l.001-.001L52.48 6.892a2.974 2.974 0 00.853-2.09v-.017-.015c0-.814-.326-1.552-.854-2.091v.001L50.735.893C50.211.355 49.48.022 48.671.022s-1.54.334-2.063.871l-.001.001-19.931 20.357z"></path>
              </svg>
            </button>
            <div v-if="isOpenDropdown && !isTypeCompany" class="select-english__list" style="">
              <div @click="changeDropdown(level, 'english')" class="select-english__list-item" v-for="level in levelsEnglish" :key="level">
                <div>
                  <span>{{ level }}</span>
                </div>
              </div>
            </div>
            <div v-if="errors.levelEnglish.length > 0 && !isTypeCompany" class="errors">
              <span>{{ errors.levelEnglish[0] }}</span>
            </div>
          </div>
          <div class="custom-select input-phone">
            <input
                @input="validationPhone()"
                :class="{ 'invalid': errors.phone.length > 0 }"
                name="phone"
                placeholder="Phone #"
                required="required"
                type="search"
                autocomplete="off"
            />
          </div>
          <div v-if="errors.phone.length > 0" class="errors">
            <span>{{ errors.phone[0] }}</span>
          </div>
          <div class="custom-select">
            <input
                @input="validationEmail()"
                :class="{ 'invalid': errors.email.length > 0 }"
                name="Email"
                placeholder="Email"
                required="required"
                type="search"
                autocomplete="off"
                v-model="formData.email"
            />
            <div v-if="errors.email.length > 0" class="errors">
              <span>{{ errors.email[0] }}</span>
            </div>
          </div>
          <button @click="regPartner()" data-v-5f42a2ba="" class="btn-submit"><span data-v-5f42a2ba="">Registration</span></button>
          <div v-if="validationError" class="errors errors-check">
            <span>Fill in all the fields</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Vue from "vue/dist/vue.esm.browser.min";
import { validationMixin } from 'vuelidate';
import { required, email, numeric, alpha } from 'vuelidate/lib/validators';

export default Vue.component("v-reg-partner",{
  mixins: [validationMixin],
  data() {
    return {
      auth: false,
      check: false,
      isOpenDropdown: false,
      isBold: false,
      isTypeCompany: false,
      typeForm: '',
      levelsEnglish: ['Pre-Intermediate', 'Intermediate', 'Upper-Intermediate', 'Advanced'],
      typesCompany: ['Travel agency', 'Hotel', 'Ship', 'Airlines'],
      openedRoutes: false,
      routes: [],
      validationError: false,
      companyType: '',
      formData: {
        levelEnglish: 'Level English',
        typeCompany: 'Type of company',
        firstName: '',
        lastName: '',
        companyName: '',
        city: '',
        email: '',
      },
      errors: {
        levelEnglish: [],
        typeCompany: [],
        firstName: [],
        lastName: [],
        companyName: [],
        city: [],
        email: [],
        phone: [],
      },
      roleId: 0,
    }
  },
  validations: {
    formData: {
      typeCompany: { required, alpha },
      firstName: { required, alpha },
      lastName: { required, alpha },
      companyName: { required, alpha },
      city: { required, alpha },
      email: { required, email },
    }
  },
  methods: {
    validationFirstName() {
      this.errors.firstName = [];
      if (!this.$v.formData.firstName.required){
        this.errors.firstName.push('Required field')
      }
      if (this.formData.firstName.match(/^([.'`,_ a-zA-Z\u0080-\u024F])+(-)*([.'`,_ a-zA-Z\u0080-\u024F])*$/) === null){
        this.errors.firstName.push('Only english')
      }
      this.validationError = false;

    },
    validationLastName() {
      this.errors.lastName = [];
      if (!this.$v.formData.lastName.required){
        this.errors.lastName.push('Required field')
      }
      if (this.formData.lastName.match(/^([.'`,_ a-zA-Z\u0080-\u024F])+(-)*([.'`,_ a-zA-Z\u0080-\u024F])*$/) === null){
        this.errors.lastName.push('Only english')
      }
      this.validationError = false;
    },
    validationCompany() {
      this.errors.companyName = [];
      if (!this.$v.formData.companyName.required){
        this.errors.companyName.push('Required field')
      }
      if (this.formData.companyName.match(/^([0-9a-zA-Z\u0080-\u024F.'` ,_/-])+$/) === null) {
        this.errors.companyName.push('Only english')
      }
      this.validationError = false;
    },
    validationEmail() {
      this.errors.email = [];
      if (!this.$v.formData.email.required){
        this.errors.email.push('Required field')
      }
      if (!this.$v.formData.email.email){
        this.errors.email.push('Email')
      }
      this.validationError = false;
      console.log(1)
    },
    validationTypeCompany() {
      this.errors.typeCompany = [];
      if (this.formData.typeCompany === 'Type of company' && this.isTypeCompany){
        this.errors.typeCompany.push('Required field')
      }
      if (this.formData.typeCompany.match(/^([.'`,_ a-zA-Z\u0080-\u024F])+(-)*([.'`,_ a-zA-Z\u0080-\u024F])*$/) === null){
        this.errors.typeCompany.push('Only english')
      }
      this.validationError = false;
    },
    validationEnglish() {
      this.errors.levelEnglish = [];
      if (this.formData.levelEnglish === 'Level English' && !this.isTypeCompany){
        this.errors.levelEnglish.push('Required field')
      }
      this.validationError = false;
    },
    validationPhone(phone) {
      this.errors.phone = [];
      if (phone.indexOf('X') > -1 || !phone){
        this.errors.phone.push('Required field')
      }
      this.validationError = false;
    },
    validationCity() {
      this.errors.city = [];
      if (!this.$v.formData.city.required){
        this.errors.city.push('Required field')
      }
      if (this.formData.city.match(/^([.'`,_ a-zA-Z\u0080-\u024F])+(-)*([.'`,_ a-zA-Z\u0080-\u024F])*$/) === null){
        this.errors.city.push('Only english')
      }
      this.validationError = false;
    },
    changeDropdown(item, type){
      if (type === 'company'){
        this.formData.typeCompany = item;
      }
      else {
        this.formData.levelEnglish = item;
      }
      this.isOpenDropdown = false;
      this.isBold = true;
      this.validationEnglish()
      this.validationTypeCompany()
      this.validationError = false;
    },
    async validations() {
      this.validationError = false;
      this.errors.firstName = [];
      this.errors.lastName = [];
      this.errors.companyName = [];
      this.errors.city = [];
      this.errors.phone = [];
      this.errors.email = [];
      this.errors.typeCompany = [];
      this.errors.levelEnglish = [];
      if (!this.$v.formData.firstName.required){
        this.errors.firstName.push('Required field')
      }
      if (this.formData.firstName.match(/^([.'`,_ a-zA-Z\u0080-\u024F])+(-)*([.'`,_ a-zA-Z\u0080-\u024F])*$/) === null){
        this.errors.firstName.push('Only english')
      }
      if (!this.$v.formData.lastName.required){
        this.errors.lastName.push('Required field')
      }
      if (this.formData.lastName.match(/^([.'`,_ a-zA-Z\u0080-\u024F])+(-)*([.'`,_ a-zA-Z\u0080-\u024F])*$/) === null){
        this.errors.lastName.push('Only english')
      }
      if (!this.$v.formData.companyName.required && this.typeForm === 'partner'){
        this.errors.companyName.push('Required field')
      }
      if (this.formData.companyName.match(/^([0-9a-zA-Z\u0080-\u024F.'`,_/-])+$/) === null && this.typeForm === 'partner'){
        this.errors.companyName.push('Only english')
      }
      if (!this.$v.formData.city.required){
        this.errors.city.push('Required field')
      }
      if (this.formData.city.match(/^([.'`,_ a-zA-Z\u0080-\u024F])+(-)*([.'`,_ a-zA-Z\u0080-\u024F])*$/) === null){
        this.errors.city.push('Only english')
      }
      let input = await document.querySelectorAll("input[name=phone]");
      if (input[0].value.indexOf('X') > -1 || !input[0].value){
        this.errors.phone.push('Required field')
      }
      console.log(input[0].value)
      if (!this.$v.formData.email.required){
        this.errors.email.push('Required field')
      }
      if (!this.$v.formData.email.email){
        this.errors.email.push('Email')
      }
      if (this.formData.typeCompany === 'Type of company' && this.isTypeCompany){
        this.errors.typeCompany.push('Required field')
      }
      if (this.formData.levelEnglish === 'Level English' && !this.isTypeCompany){
        this.errors.levelEnglish.push('Required field')
      }
    },
    regPartner() {
      console.log(document.querySelector('input[name="phone"]'))
      let dialCode = document.querySelector('.iti__selected-dial-code').textContent;
      console.log(dialCode)
      this.validations()
      console.log(this.errors)
      if (this.errors.firstName.length || this.errors.firstName.length || this.errors.companyName.length || this.errors.city.length || this.errors.phone.length || this.errors.email.length || this.errors.typeCompany.length || this.errors.levelEnglish.length){
        this.validationError = true;
      }
      else{
        this.check = true;
        console.log(this.companyType)
        let partner = {
          'roleId': this.roleId,
          'companyType': this.companyType,
          'englishLvl': this.formData.levelEnglish,
          'firstName': this.formData.firstName,
          'lastName': this.formData.lastName,
          'companyName': this.formData.companyName,
          'city': this.formData.city,
          'phone': window.iti.getNumber(),
          'email': this.formData.email,
        }
        if (this.isTypeCompany){
          partner.typeCompany = this.formData.typeCompany
        }
        axios.post('https://mytripline.com/api/partners/add', {partner}).then( resp => {
          console.log(resp)
          if (resp.data.status){
            setTimeout( () => {
              this.check = false;
              this.auth = true;
            },2000)
          }
          else {
            this.check = false;
            this.auth = false;
            if (resp.data.errors.errorInfo[0] === '23000'){
              this.errors.email.push('This email already exists')
            }
            this.getPhoneCountry()
            setTimeout(() =>{
              document.querySelector('input[name="phone"]').value = partner.phone.slice(dialCode.length)
            }, 500)
          }
        })
      }
    },
    async getRoutes() {
      await axios.get('/route-list').then( resp => {
        console.log(resp.data)
        this.routes = resp.data
      });
    },
    filteredRoutes() {
      if (true){
        //console.log('this.parsedRoutes: ', this.parsedRoutes);

        const allRoutesResult = []

        this.routes.forEach(p=>{
          allRoutesResult.push({city: p.from_city, country: p.from_country, invert: 0})
          allRoutesResult.push({city: p.to_city, country: p.to_country, invert: 1})
        })

        const fromRoutesResult = allRoutesResult.filter(r => {
          return this.formData.city.length > 0 ? r.city.toLowerCase().indexOf(this.formData.city.toLowerCase()) >= 0 : true;
        })

        const fromCitiesList = [];

        fromRoutesResult.forEach(i=>{
          if (fromCitiesList.findIndex( (element) => element.city === i.city) < 0){
            fromCitiesList.push(i)
          }
        })
        return this.formData.city.length > 2 ? fromCitiesList : []

      }

      const fromRoutesResult = this.routes.filter(r => {
        return this.formData.city.length > 0 ? r.from_city.toLowerCase().indexOf(this.formData.city.toLowerCase()) >= 0 : true;
      }).filter(r => {
        return this.formData.city.length > 0 ? r.to_city.toLowerCase().indexOf(this.formData.city.toLowerCase()) >= 0 : true;
      }).map(i => {
        return {from_city: i.from_city, from_country: i.from_country, to_city: i.to_city, to_country: i.to_country}
      })
      const fromCitiesList = [];

      fromRoutesResult.forEach(i=>{
        if (fromCitiesList.findIndex( (element) => element.from_city === i.from_city) < 0){
          fromCitiesList.push(i)
        }
      })
      return fromCitiesList
    },
    selectFrom(item) {
      this.openedRoutes = false;
      this.formData.city = item.city
    },
    closeRoutes() {
      let data = this;
      window.addEventListener('click', function (e){
        if (!e.target.classList.contains('custom-select__option')){
          data.openedRoutes = false
        }
      })
    },
    openRoutes(){
      this.validationCity()
      this.openedRoutes = true;
      let value = this.formData.city.length
      let data = this;
      let mouse = false;
      window.addEventListener('mouseover', function (e){
        if (e.target.classList.contains('custom-select__option')){
          mouse = true;
        }
      })
      setTimeout(() => {
        if (value === data.formData.city.length && !mouse){
          data.openedRoutes = false;
        }
      }, 2000)
    },
    getPhoneCountry() {
      let data = this
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
        // this.validationPhone(window.iti)
        console.log('------------------input-------------', input);
        // input[0].addEventListener('oninput', function (){
        //   console.log(1)
        // })
        input[0].oninput = function() {
          data.validationPhone(input[0].value)
        };
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
    }
  },
  created() {
    let url = document.location.pathname;
    if (url === '/a_travel_agency'){
      this.isTypeCompany = true
    }
    if (url === '/a_travel_agency'){
      this.typeForm = 'travel_agency'
      this.roleId = 6;
    }
    if (url === '/a_partner'){
      this.typeForm = 'partner'
      this.roleId = 4;
    }
    if (url === '/a_driver'){
      this.typeForm = 'driver'
      this.roleId = 5;
    }
    this.getRoutes()
    this.closeRoutes()
    if (this.typeForm === 'partner'){
      this.companyType = 'Become a partner'
    }
    if (this.typeForm === 'driver'){
      this.companyType = 'Become a driver'
    }
    if (this.typeForm === 'travel_agency'){
      this.companyType = 'Become a Travel agency'
    }
  },
  async mounted() {
    await this.getPhoneCountry()
  }
})
</script>

<style scoped lang="scss">
.form__from-to{
  display: flex;
  & > div{
    width: 50%;
  }
  & > div:first-child{
    margin-right: 6px;
  }
}
.form__header{
  width: 100%;
  h3{
    text-align: center;
    font-weight: 400;
    font-size: 18px;
    line-height: 55px;
    font-family: 'Roboto';
    font-style: normal;
  }
}
.custom-select{
  button{
    width: 100%;
    height: 49px;
    background: #f8f8f8;
    border-radius: 7px;
    padding-left: 19px;
    padding-right: 19px;
    font-weight: 400;
    font-size: 13px;
    border: 0;
    outline: 0;
    margin-bottom: 7px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    span{
      color: #A7A7A7;
    }
  }
}
.form-block__wrap {
  padding-top: 0;
}
.select__icon{
  width: 10px;
}
.btn-submit{
  margin-top: 10px;
}
.center{
  display: flex;
  align-items: center;
  justify-content: center;
}
.form-block__wrap{
  min-height: 400px;
}
.form__img {
  width: 80px;
  margin-bottom: 20px;
  img{
    width: 100%;
  }
}
.gif{
  width: 80px;
  margin-top: 20px;
  img{
    width: 100%;
  }
}
.bold{
  color: black !important;
}
.errors{
  margin-bottom: 7px;
  span{
    font-size: 14px;
    color: red;
  }
}
.invalid{
  border: 1px solid red !important;
}
.city{
  z-index: 3000;
}
b{
  color: black;
}
.custom-select__options.--opened {
  height: 247px;
  overflow: scroll;
}
.custom-select__options {
  top: 72%;
}
.input-phone .iti {
  -webkit-box-shadow: none;
  box-shadow: none
}

.input-phone .iti__flag {
  width: 14px !important;
  height: 14px !important;
  border-radius: 100%;
  overflow: hidden
}

.input-phone .iti__selected-flag {
  padding-left: 15px;
  border-radius: 7px;
  height: 49px
}

.input-phone .iti__flag-container, .input-phone .iti__selected-flag {
  background-color: transparent !important
}
.input-phone{
  z-index: 200;
}
.select-english__list{
  z-index: 2000;
  top: 66.5%;
}
.company, .english{
  z-index: 1500;
  position: relative;
}
.errors-check{
  margin-top: 10px;
}
</style>
