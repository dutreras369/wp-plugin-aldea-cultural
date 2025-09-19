<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Shortcode: [ev_noticias cantidad="6" categoria="slug-cat" tag="slug-tag" destacados="1"]
 * - Cabecera desde ACF: titulo_noticias / intro_noticias
 * - Tarjetas con: thumbnail, título, meta (fecha/categorías), descripción (excerpt) y CTA
 * - Soporta filtros por categoría y tag
 * - "destacados=1" usa entradas sticky de WP
 */
function ev_sc_noticias($atts){
  $a = shortcode_atts([
    'cantidad'   => 6,
    'categoria'  => '', // slug de category
    'tag'        => '', // slug de tag
    'destacados' => '', // "1" para usar sticky posts
  ], $atts);

  // Cabecera del bloque (ACF en página actual u opciones)
  $titulo       = function_exists('get_field') ? get_field('titulo_noticias') : '';
  $introduccion = function_exists('get_field') ? get_field('intro_noticias')  : '';

  // Helper de truncado seguro (si no existe ya en el tema)
  if(!function_exists('ev_trim_words')){
    function ev_trim_words($text, $limit = 28){
      $text = wp_strip_all_tags($text);
      $words = preg_split('/\s+/', trim($text));
      if(count($words) <= $limit) return $text;
      return implode(' ', array_slice($words, 0, $limit)) . '…';
    }
  }

  // Query base
  $args = [
    'post_type'      => 'post',
    'posts_per_page' => intval($a['cantidad']),
    'ignore_sticky_posts' => 1,
  ];

  // Filtro por categoría y/o tag (por slug)
  if(!empty($a['categoria'])) $args['category_name'] = sanitize_title($a['categoria']);
  if(!empty($a['tag']))       $args['tag']           = sanitize_title($a['tag']);

  // Destacados: usar sticky posts si existen
  if(!empty($a['destacados'])){
    $sticky = get_option('sticky_posts');
    if(!empty($sticky)){
      rsort($sticky);
      $args['post__in'] = $sticky;
    }
  }

  $q = new WP_Query($args);

  ob_start(); ?>
  <section class="ev-bloque ev-noticias" id="noticias" data-aos="fade-up">
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

            // Descripción: excerpt o truncado del contenido
            $desc = has_excerpt($id) ? get_the_excerpt($id) : ev_trim_words(get_the_content(null, false, $id), 30);

            // Meta
            $fecha = get_the_date('', $id);
            $cats  = get_the_category($id);
        ?>
          <div class="col">
            <article class="card ev-nota-card h-100" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>">
              <?php if (has_post_thumbnail($id)): ?>
                <a href="<?php the_permalink(); ?>" class="ev-nota-thumb-link">
                  <?php echo get_the_post_thumbnail($id, 'medium_large', ['class'=>'card-img-top ev-nota-thumb', 'alt'=>esc_attr(get_the_title())]); ?>
                </a>
              <?php endif; ?>

              <div class="card-body">
                <h3 class="h5 card-title">
                  <a href="<?php the_permalink(); ?>" class="stretched-link text-decoration-none">
                    <?php the_title(); ?>
                  </a>
                </h3>

                <ul class="list-unstyled small ev-nota-meta">
                  <li><strong>Fecha:</strong> <?php echo esc_html($fecha); ?></li>
                  <?php if($cats): ?>
                    <li>
                      <strong>Categorías:</strong>
                      <?php
                        $out = [];
                        foreach($cats as $c){ $out[] = esc_html($c->name); }
                        echo implode(', ', $out);
                      ?>
                    </li>
                  <?php endif; ?>
                </ul>

                <?php if($desc): ?>
                  <p class="text-muted small mb-3"><?php echo esc_html($desc); ?></p>
                <?php endif; ?>

                <div class="d-flex flex-wrap gap-2">
                  <a class="btn btn-ev btn-sm" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr('Leer: ' . get_the_title()); ?>">
                    <span>Leer noticia</span>
                    <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M7 17L17 7M17 7H9M17 7v8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
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
add_shortcode('ev_noticias','ev_sc_noticias');
