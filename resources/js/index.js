// import "./import/modules";
// import "./import/components";
// import { Fancybox } from "@fancyapps/ui";

// import Bouncer from "./import/bouncer.polyfills.min";
// import SlimSelect from "slim-select";

let selectElements = [].slice.call(document.querySelectorAll(".js-select"));
selectElements.forEach(function (selectElement) {
    new SlimSelect({
        select: selectElement,
        showSearch: false,
    });
});


// TIcket items
let tickets = document.querySelectorAll(".js-ticket");
tickets.forEach(item => {
    item.addEventListener("click", () => {
        item.classList.toggle("active");
    });
});


// Tab
function tab(btn, content, event = "change") {
    let btns = [].slice.call(document.querySelectorAll(btn));
    let contents = [].slice.call(document.querySelectorAll(content));
    btns.forEach(item => {
        item.addEventListener(event, e => {
            let index = btns.indexOf(e.currentTarget);

            contents.map(item => (item.classList.remove("active")));
            contents[index].classList.add("active");

            if (event === "click") {
                btns.map(item => (item.classList.remove("active")));
                btns[index].classList.add("active");
            }
        });
    });
}

// Pyment Select Type
tab("[data-payment-check]", "[data-payment-content]");

// Cabinet Tab
tab("[data-cabinet-btn]", "[data-cabinet-content]", "click");

// Validation
// Form Validation
document.addEventListener("DOMContentLoaded", function (event) {
    var validatorClass = document.querySelectorAll(".js-form-validator");
    if (validatorClass.length) {
        var bouncer = new Bouncer(".js-form-validator", {
            disableSubmit: true,
            fieldClass: "error", // Applied to fields with errors
            errorClass: "error-message", // Applied to the error message for invalid fields
            fieldPrefix: "bouncer-field_", // If a field doesn't have a name or ID, one is generated with this prefix
            errorPrefix: "bouncer-error_", // Prefix used for error message IDs
            patterns: {
                email: /^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*(\.\w{2,})+$/,
                // password: /(?=.*\d)(?=.*[a-zа-яё|A-ZА-ЯЁ]).{8,}/,
                password: /[\d\w\W\D\d].{7,}/,
                tel: /^(\+7|7|8)?[\s\-]?\(?[0-9]{2,3}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/,
                phone: /^(\+7|7|8)?[\s\-]?\(?[0-9]{2,3}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/,
                passengers: /^[1-9][0-9]*$/,
                "day-of-birth": /(0[1-9]|[12]\d|3[01])-(0[1-9]|1[0-2])-([12]\d{3})/
            },
            customValidations: {
                valueMismatch: function (field) {
                    // Look for a selector for a field to compare
                    // If there isn't one, return false (no error)
                    var selector = field.getAttribute("data-bouncer-match");
                    if (!selector) return false;

                    // Get the field to compare
                    var otherField = field.form.querySelector(selector);
                    if (!otherField) return false;

                    // Compare the two field values
                    // We use a negative comparison here because if they do match, the field validates
                    // We want to return true for failures, which can be confusing
                    return otherField.value !== field.value;

                }
            },
        });

        document.addEventListener("bouncerFormInvalid", function (event) {
            window.scrollTo(0, event.target.offsetTop);
        }, false);

        document.addEventListener("bouncerFormValid", function (el) {
            //console.log('bouncerFormValid index');
            try {
                var fn = el.target.dataset.submit;
                window[fn](el);
            } catch (e) {
                //console.log("Form Submit Error!");
            }
        }, false);

        let arrinput = [].slice.apply(document.querySelectorAll("input[type='password']"));
        arrinput.forEach(function (input) {
            input.addEventListener("input", function () {
                check(this.value, this);
            });
        });
    }
});

// input Mask
let allMaskElement = [].slice.call(document.querySelectorAll(".js-input-mask"));
allMaskElement.forEach(item => {
    let maskPattern = item.dataset.mask || undefined;
    if (!maskPattern) return false;
    $(item).mask(maskPattern);
});

// Order Sum Toggle
let ordersumEl = document.querySelector(".js-order-sum-toggle");
if (ordersumEl) {
    ordersumEl.addEventListener("click", () => {
        ordersumEl.classList.toggle("active");
    });
}
