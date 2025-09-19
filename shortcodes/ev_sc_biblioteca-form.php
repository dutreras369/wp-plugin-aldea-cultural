<?php
if ( ! defined('ABSPATH') ) exit;

// [aldea_biblioteca_form endpoint="https://script.google.com/macros/s/XXXX/exec" titulo="Inscripción a Biblioteca & Ludoteca"]
function aldea_sc_biblioteca_form($atts){
  $a = shortcode_atts([
    'endpoint' => '',
    'titulo'   => 'Inscripción a Biblioteca & Ludoteca'
  ], $atts);

  if (!$a['endpoint']) return '';

  ob_start(); ?>
  <section class="aldea-sc aldea-form" data-aos="fade-up">
    <div class="aldea-sc__inner is-card is-shadow is-rounded">
      <header class="aldea-sc__header u-center">
        <h2 class="aldea-sc__title"><?php echo esc_html($a['titulo']); ?></h2>
      </header>

      <form class="aldea-form__form"
            data-endpoint="<?php echo esc_url($a['endpoint']); ?>"
            data-origin="<?php echo esc_url(home_url()); ?>"
            novalidate>
        <div class="aldea-form__grid">
          <label>
            <span>Nombre</span>
            <input type="text" name="nombre" required aria-required="true">
          </label>

          <label>
            <span>Email</span>
            <input type="email" name="email" required aria-required="true">
          </label>

          <label>
            <span>Teléfono</span>
            <input type="tel" name="telefono" inputmode="numeric" placeholder="56912345678" pattern="[0-9]{9,12}">
          </label>

          <label>
            <span>Interés</span>
            <select name="interes">
              <option value="">Selecciona…</option>
              <option>Biblioteca</option>
              <option>Ludoteca</option>
              <option>Ambas</option>
            </select>
          </label>

          <label class="is-full">
            <span>Mensaje</span>
            <textarea name="mensaje" rows="4" placeholder="Cuéntanos qué necesitas…"></textarea>
          </label>

          <label class="aldea-form__consent is-full">
            <input type="checkbox" name="consent" required aria-required="true">
            <span>Acepto el uso de mis datos para fines de contacto y coordinación.</span>
          </label>
        </div>

        <div class="aldea-form__actions">
          <button type="submit" class="btn btn-primary">Enviar</button>
          <div class="aldea-form__status" aria-live="polite"></div>
        </div>
      </form>
    </div>
  </section>
  <?php
  return ob_get_clean();
}
add_shortcode('aldea_biblioteca_form','aldea_sc_biblioteca_form');
