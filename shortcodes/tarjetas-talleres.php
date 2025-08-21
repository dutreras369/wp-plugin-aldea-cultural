<?php

if ( ! defined('ABSPATH') ) exit;
// [aldea_talleres cantidad="6" area="slug-area" destacados="1"]
function aldea_sc_talleres($atts){
  $a = shortcode_atts(['cantidad'=>6, 'area'=>'', 'destacados'=>''], $atts);

  $args = [
    'post_type' => 'taller',
    'posts_per_page' => intval($a['cantidad']),
    'meta_key' => $a['destacados'] ? 'destacado' : '',
    'meta_value' => $a['destacados'] ? 1 : '',
    'tax_query' => []
  ];
  if($a['area']){
    $args['tax_query'][] = [
      'taxonomy'=>'area','field'=>'slug','terms'=>sanitize_title($a['area'])
    ];
  }
  $q = new WP_Query(array_filter($args));
  ob_start(); ?>
  <div class="container" data-aos="fade-up">
    <div class="row g-4">
    <?php while($q->have_posts()): $q->the_post();
      $id = get_the_ID();
      $fecha = function_exists('get_field') ? get_field('fecha',$id) : '';
      $hora  = function_exists('get_field') ? get_field('hora',$id)  : '';
      $lugar = function_exists('get_field') ? get_field('lugar',$id) : '';
      $valor = function_exists('get_field') ? get_field('valor',$id) : '';
      $link  = function_exists('get_field') ? get_field('link_inscripcion',$id) : '';
      $wa    = function_exists('get_field') ? preg_replace('/\\D/','', (string)get_field('whatsapp',$id)) : '';
      ?>
      <div class="col-md-6 col-lg-4">
        <article class="card h-100 shadow-sm">
          <?php if (has_post_thumbnail()): ?>
            <a href="<?php echo esc_url(get_the_post_thumbnail_url($id,'large')); ?>" class="glightbox" data-gallery="talleres">
              <?php the_post_thumbnail('medium_large', ['class'=>'card-img-top']); ?>
            </a>
          <?php endif; ?>
          <div class="card-body">
            <h3 class="h5 card-title"><?php the_title(); ?></h3>
            <ul class="list-unstyled small mb-3">
              <?php if($fecha): ?><li><strong>Fecha:</strong> <?php echo esc_html($fecha); ?></li><?php endif; ?>
              <?php if($hora):  ?><li><strong>Hora:</strong>  <?php echo esc_html($hora);  ?></li><?php endif; ?>
              <?php if($lugar): ?><li><strong>Lugar:</strong> <?php echo esc_html($lugar); ?></li><?php endif; ?>
              <?php if($valor): ?><li><strong>Valor:</strong> <?php echo esc_html($valor); ?></li><?php endif; ?>
            </ul>
            <div class="d-flex gap-2">
              <?php if($link): ?>
                <a class="btn btn-primary btn-sm" href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener">Inscribirse</a>
              <?php elseif($wa): ?>
                <a class="btn btn-success btn-sm" href="<?php echo esc_url('https://wa.me/'.$wa); ?>" target="_blank" rel="noopener">WhatsApp</a>
              <?php endif; ?>
              <a class="btn btn-outline-secondary btn-sm" href="<?php the_permalink(); ?>">Ver detalle</a>
            </div>
          </div>
        </article>
      </div>
    <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
  <?php return ob_get_clean();
}
add_shortcode('aldea_talleres','aldea_sc_talleres');
