<?php
if ( ! defined('ABSPATH') ) exit;
// [aldea_slider ids="1,2,3"]  // opcional: sin ids, toma ACF 'galeria' del post actual
function aldea_sc_slider_galeria($atts){
  $a = shortcode_atts(['ids'=>''], $atts);
  $ids = array_filter(array_map('intval', explode(',', $a['ids'])));

  if(empty($ids) && function_exists('get_field')){
    $gal = get_field('galeria', get_the_ID());
    if (is_array($gal)) {
      foreach($gal as $img){ if(isset($img['ID'])) $ids[] = intval($img['ID']); }
    }
  }
  if(empty($ids)) return '';

  ob_start(); ?>
  <div class="aldea-swiper swiper" data-aos="fade-up">
    <div class="swiper-wrapper">
      <?php foreach($ids as $id):
        $src = wp_get_attachment_image_url($id,'large');
        $thumb = wp_get_attachment_image($id,'large', false, ['class'=>'img-fluid']); ?>
        <div class="swiper-slide">
          <a href="<?php echo esc_url($src); ?>" class="glightbox" data-gallery="post-gal">
            <?php echo $thumb; ?>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>
  <?php return ob_get_clean();
}
add_shortcode('aldea_slider','aldea_sc_slider_galeria');
