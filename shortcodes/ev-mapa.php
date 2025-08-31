<?php
if ( ! defined('ABSPATH') ) exit;

// [aldea_mapa q="Centro Cultural La Aldea, Talca, Chile" zoom="18" titulo="¿CÓMO LLEGAR A LA ALDEA?" full="1"]
// o  [aldea_mapa lat="-35.4258741" lng="-71.6439918" zoom="18" titulo="..." full="1"]
function aldea_sc_mapa($atts){
  $a = shortcode_atts([
    'q'      => '',              // ← NUEVO (query de Google Maps)
    'lat'    => '',
    'lng'    => '',
    'zoom'   => 15,
    'titulo' => '',
    'full'   => '0',
  ], $atts);

  // Construir URL
  if ($a['q']) {
    $src = sprintf('https://www.google.com/maps?q=%s&z=%d&output=embed',
      rawurlencode($a['q']),
      intval($a['zoom'])
    );
  } elseif ($a['lat'] && $a['lng']) {
    $src = sprintf('https://www.google.com/maps?q=%s,%s&z=%d&output=embed',
      $a['lat'], $a['lng'], intval($a['zoom'])
    );
  } else {
    return ''; // faltan datos
  }

  $full_class = ($a['full'] === '1') ? ' is-full-bleed' : '';

  ob_start(); ?>
  <section class="aldea-sc aldea-map<?php echo esc_attr($full_class); ?>" data-aos="fade-up">
    <div class="aldea-sc__inner is-card is-shadow is-rounded">
      <?php if ($a['titulo'] !== ''): ?>
        <header class="aldea-sc__header u-center">
          <h2 class="aldea-sc__title"><?php echo esc_html($a['titulo']); ?></h2>
        </header>
      <?php endif; ?>
      <div class="aldea-map__frame">
        <iframe
          src="<?php echo esc_url($src); ?>"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          title="<?php echo esc_attr($a['titulo'] ?: 'Mapa'); ?>"
          aria-label="<?php echo esc_attr($a['titulo'] ?: 'Mapa'); ?>">
        </iframe>
      </div>
    </div>
  </section>
  <?php
  return ob_get_clean();
}
add_shortcode('aldea_mapa','aldea_sc_mapa');
