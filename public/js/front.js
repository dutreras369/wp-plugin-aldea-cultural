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

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.aldea-form__form[data-endpoint]').forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = form.querySelector('button[type="submit"]');
      const status = form.querySelector('.aldea-form__status');
      const ep = form.getAttribute('data-endpoint');
      const origin = form.getAttribute('data-origin') || location.origin;

      if (!form.checkValidity()) { status.textContent='Completa los obligatorios.'; form.reportValidity?.(); return; }
      const fd = new FormData(form);
      if (!fd.get('consent')) { status.textContent='Debes aceptar el consentimiento.'; return; }

      const params = new URLSearchParams();
      ['nombre','email','telefono','interes','mensaje'].forEach(k => params.set(k, (fd.get(k)||'').trim()));
      params.set('origen', origin);

      btn.disabled = true;
      status.textContent = 'Enviando…';

      try{
        const res = await fetch(ep, {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
          body: params.toString()
        });
        const json = await res.json().catch(()=>({}));
        if (res.ok && json.ok) { status.textContent='¡Gracias! Hemos recibido tu inscripción.'; form.reset(); }
        else { status.textContent='No se pudo enviar. Intenta nuevamente.'; }
      }catch(_){
        status.textContent='Error de conexión. Intenta más tarde.';
      }finally{
        btn.disabled = false;
      }
    });
  });
});
