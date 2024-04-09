import Swiper from "swiper";

let partenersSlider = new Swiper(".partners-slider", {
  slidesPerView: "auto",
  spaceBetween: 47,
  loop: true,
  centeredSlides: true,
  roundLengths: true,
  breakpoints: {
    0: {
      spaceBetween: 15,
    },
    570: {
      spaceBetween: 47,
    }
  }
});

