<?php

if ( ! defined('ABSPATH') ) exit;
// [aldea_alianzas cantidad="12"]
function aldea_sc_alianzas($atts){
  $a = shortcode_atts(['cantidad'=>12], $atts);
  $q = new WP_Query([
    'post_type'=>'alianza','posts_per_page'=>intval($a['cantidad'])
  ]);
  ob_start(); ?>
  <div class="container aldea-alianzas" data-aos="fade-up">
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
      <?php while($q->have_posts()): $q->the_post();
        $logo_id = function_exists('get_field') ? get_field('logo',get_the_ID()) : 0;
        $url = function_exists('get_field') ? get_field('url',get_the_ID()) : '';
        ?>
        <div class="col">
          <div class="card h-100 text-center p-3">
            <?php if($logo_id): echo wp_get_attachment_image($logo_id,'medium', false, ['class'=>'img-fluid mx-auto']); endif; ?>
            <div class="card-body">
              <h3 class="h6 card-title"><?php the_title(); ?></h3>
              <?php if($url): ?><a class="stretched-link" href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener">Ver sitio</a><?php endif; ?>
            </div>
          </div>
        </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
  <?php return ob_get_clean();
}
add_shortcode('aldea_alianzas','aldea_sc_alianzas');
