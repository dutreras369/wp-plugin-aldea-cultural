
<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Shortcode: [ev_alianzas cantidad="8"]
 * - Muestra tarjetas con logo, título, descripción y botón.
 * - Título/intro del bloque vienen desde ACF: titulo_alianzas / intro_alianzas
 * - Descripción por ítem: ACF 'descripcion' (fallback: excerpt o contenido truncado).
 */
function ev_sc_alianzas($atts){
  $a = shortcode_atts(['cantidad'=>8], $atts);

  // Cabecera del bloque desde ACF (página actual u opciones)
  $titulo       = function_exists('get_field') ? get_field('titulo_alianzas') : '';
  $introduccion = function_exists('get_field') ? get_field('intro_alianzas') : '';

  // Helper de truncado seguro
  if(!function_exists('ev_trim_words')){
    function ev_trim_words($text, $limit = 22){
      $text = wp_strip_all_tags($text);
      $words = preg_split('/\s+/', trim($text));
      if(count($words) <= $limit) return $text;
      return implode(' ', array_slice($words, 0, $limit)) . '…';
    }
  }

  $q = new WP_Query([
    'post_type'      => 'alianza',
    'posts_per_page' => intval($a['cantidad']),
  ]);

  ob_start(); ?>
  <section class="ev-bloque ev-alianzas" id="alianzas" data-aos="fade-up">
    <div class="container">
      <?php if($titulo): ?>
        <div class="section-header text-center mb-4" data-aos="fade-up" data-aos-delay="50">
          <h2 class="h3"><?php echo esc_html($titulo); ?></h2>
          <?php if($introduccion): ?>
            <p class="text-muted"><?php echo esc_html($introduccion); ?></p>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="row">
        <?php
          $i = 0;
          while($q->have_posts()): $q->the_post(); $i++;
            $delay   = 50 * $i; // “stagger” AOS
            $logo_id = function_exists('get_field') ? get_field('logo', get_the_ID()) : 0;
            $url     = function_exists('get_field') ? get_field('url',  get_the_ID()) : '';
            $desc    = function_exists('get_field') ? get_field('breve', get_the_ID()) : '';
            if(!$desc){
              $desc = has_excerpt() ? get_the_excerpt() : ev_trim_words(get_the_content(''), 28);
            }
        ?>
          <div class="col">
            <div class="card ev-alianza-card h-100 text-center"
                 data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>">
              <div class="ev-alianza-img-wrapper">
                <?php if($logo_id): ?>
                  <?php echo wp_get_attachment_image(
                    $logo_id,
                    'medium',
                    false,
                    ['class'=>'img-fluid ev-alianza-img rounded-circle shadow-sm', 'alt'=>esc_attr(get_the_title())]
                  ); ?>
                <?php else: ?>
                  <div class="ev-alianza-img placeholder rounded-circle shadow-sm" aria-hidden="true"></div>
                <?php endif; ?>
              </div>

              <div class="card-body">
                <h3 class="h6 card-title mb-2"><?php the_title(); ?></h3>
                <?php if($desc): ?>
                  <p class="ev-alianza-desc text-muted small mb-3"><?php echo esc_html($desc); ?></p>
                <?php endif; ?>

                <?php if($url): ?>
                  <a class="btn btn-ev btn-sm"
                     href="<?php echo esc_url($url); ?>"
                     target="_blank" rel="noopener"
                     aria-label="<?php echo esc_attr('Visitar sitio de ' . get_the_title()); ?>">
                    <span>Ver sitio</span>
                    <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M7 17L17 7M17 7H9M17 7v8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
  </section>
  <?php return ob_get_clean();
}
add_shortcode('ev_alianzas','ev_sc_alianzas');
