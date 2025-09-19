<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Shortcode: [ev_talleres cantidad="6" area="slug-area" destacados="1"]
 * - Cabecera desde ACF: titulo_talleres / intro_talleres
 * - Ítems con: imagen, título, meta (fecha/hora/lugar/valor), descripción y CTA (inscripción o WhatsApp)
 * - AOS con delay progresivo
 */
function ev_sc_talleres($atts){
  $a = shortcode_atts(['cantidad'=>6, 'area'=>'', 'destacados'=>''], $atts);

  // Cabecera del bloque (ACF en página actual u opciones)
  $titulo       = function_exists('get_field') ? get_field('titulo_talleres') : '';
  $introduccion = function_exists('get_field') ? get_field('intro_talleres')  : '';

  // Helper de truncado seguro (si no existe ya en el tema)
  if(!function_exists('ev_trim_words')){
    function ev_trim_words($text, $limit = 28){
      $text = wp_strip_all_tags($text);
      $words = preg_split('/\s+/', trim($text));
      if(count($words) <= $limit) return $text;
      return implode(' ', array_slice($words, 0, $limit)) . '…';
    }
  }

  $args = [
    'post_type'      => 'taller',
    'posts_per_page' => intval($a['cantidad']),
    'tax_query'      => []
  ];

  // Filtro por área (taxonomía 'area')
  if(!empty($a['area'])){
    $args['tax_query'][] = [
      'taxonomy' => 'area',
      'field'    => 'slug',
      'terms'    => sanitize_title($a['area']),
    ];
  }

  // Filtro por destacados (ACF: 'destacado' = 1)
  if(!empty($a['destacados'])){
    $args['meta_query'] = [[
      'key'     => 'destacado',
      'value'   => '1',
      'compare' => '=',
    ]];
  }

  $q = new WP_Query($args);

  ob_start(); ?>
  <section class="ev-bloque ev-talleres" id="talleres" data-aos="fade-up">
    <div class="container">
      <?php if($titulo): ?>
        <div class="section-header text-center mb-4" data-aos="fade-up" data-aos-delay="50">
          <h2 class="h3"><?php echo esc_html($titulo); ?></h2>
          <?php if($introduccion): ?>
            <p class="text-muted"><?php echo esc_html($introduccion); ?></p>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-3">
        <?php
          $i = 0;
          while($q->have_posts()): $q->the_post(); $i++;
            $delay = 50 * $i;
            $id    = get_the_ID();

            // ACF meta
            $fecha = function_exists('get_field') ? get_field('fecha', $id) : '';
            $hora  = function_exists('get_field') ? get_field('hora',  $id) : '';
            $lugar = function_exists('get_field') ? get_field('lugar', $id) : '';
            $valor = function_exists('get_field') ? get_field('valor', $id) : '';

            $link  = function_exists('get_field') ? get_field('link_inscripcion', $id) : '';
            $wa    = function_exists('get_field') ? preg_replace('/\\D/','', (string)get_field('whatsapp', $id)) : '';

            // Descripción corta
            $desc = function_exists('get_field') ? get_field('descripcion', $id) : '';
            if(!$desc){
              $desc = has_excerpt() ? get_the_excerpt() : ev_trim_words(get_the_content(''), 32);
            }
        ?>
          <div class="col">
            <article class="card ev-taller-card h-100" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>">
              <?php if (has_post_thumbnail($id)): ?>
                <a href="<?php echo esc_url(get_the_post_thumbnail_url($id,'large')); ?>"
                   class="glightbox ev-taller-thumb-link" data-gallery="talleres">
                  <?php echo get_the_post_thumbnail($id, 'medium_large', ['class'=>'card-img-top ev-taller-thumb']); ?>
                </a>
              <?php endif; ?>

              <div class="card-body">
                <h3 class="h5 card-title"><?php the_title(); ?></h3>

                <ul class="list-unstyled small ev-taller-meta">
                  <?php if($fecha): ?><li><strong>Fecha:</strong> <?php echo esc_html($fecha); ?></li><?php endif; ?>
                  <?php if($hora):  ?><li><strong>Hora:</strong>  <?php echo esc_html($hora);  ?></li><?php endif; ?>
                  <?php if($lugar): ?><li><strong>Lugar:</strong> <?php echo esc_html($lugar); ?></li><?php endif; ?>
                  <?php if($valor): ?><li><strong>Valor:</strong> <?php echo esc_html($valor); ?></li><?php endif; ?>
                </ul>

                <?php if($desc): ?>
                  <p class="text-muted small mb-3"><?php echo esc_html($desc); ?></p>
                <?php endif; ?>

                <div class="d-flex flex-wrap gap-2">
                  <?php if($link): ?>
                    <a class="btn btn-ev btn-sm"
                       href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener"
                       aria-label="<?php echo esc_attr('Inscribirse en ' . get_the_title()); ?>">
                      <span>Inscribirse</span>
                      <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M7 17L17 7M17 7H9M17 7v8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </a>
                  <?php elseif($wa): ?>
                    <a class="btn btn-ev-outline btn-sm"
                       href="<?php echo esc_url('https://wa.me/'.$wa); ?>" target="_blank" rel="noopener"
                       aria-label="<?php echo esc_attr('Contactar por WhatsApp sobre ' . get_the_title()); ?>">
                      <span>WhatsApp</span>
                      <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M7 17L17 7M17 7H9M17 7v8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </a>
                  <?php endif; ?>

                  <a class="btn btn-outline-secondary btn-sm" href="<?php the_permalink(); ?>">
                    Ver detalle
                  </a>
                </div>
              </div>
            </article>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
  </section>
  <?php
  return ob_get_clean();
}
add_shortcode('ev_talleres','ev_sc_talleres');
