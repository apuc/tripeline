import Swiper from "swiper";

let sliderhomeSlider = new Swiper(".slidercompany-slider", {
  slidesPerView: "auto",
  spaceBetween: 47,
  loop: true,
  centeredSlides: true,
  roundLengths: true,
  breakpoints: {
    0: {
      spaceBetween: 19,
    },
    570: {
      spaceBetween: 58,
    }
  }
});
