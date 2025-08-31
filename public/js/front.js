document.addEventListener('DOMContentLoaded', () => {
  // AOS
  if (window.AOS && typeof AOS.init === 'function') {
    AOS.init({ once: true, duration: 700, offset: 80 });
  }
  
  // GLightbox
  if (typeof GLightbox !== 'undefined') {
    GLightbox({ selector: '.glightbox' });
  }

  // Swiper sliders: cada contenedor con clase .aldea-swiper
  if (typeof Swiper !== 'undefined') {
    document.querySelectorAll('.aldea-swiper').forEach((el) => {
      new Swiper(el, {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 16,
        pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
        navigation: { nextEl: el.querySelector('.swiper-button-next'), prevEl: el.querySelector('.swiper-button-prev') },
        breakpoints: { 768:{slidesPerView:2}, 1024:{slidesPerView:3} }
      });
    });
  }
});
