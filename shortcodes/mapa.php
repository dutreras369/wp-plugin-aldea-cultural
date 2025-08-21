<?php
if ( ! defined('ABSPATH') ) exit;
// [aldea_mapa lat="-33.45" lng="-70.66" zoom="15"]
function aldea_sc_mapa($atts){
  $a = shortcode_atts(['lat'=>'','lng'=>'','zoom'=>15], $atts);
  if(!$a['lat'] || !$a['lng']) return '';
  $src = sprintf('https://www.google.com/maps?q=%s,%s&z=%d&output=embed', $a['lat'], $a['lng'], intval($a['zoom']));
  return '<div class="aldea-mapa" data-aos="fade-up"><iframe src="'.esc_url($src).'" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>';
}
add_shortcode('aldea_mapa','aldea_sc_mapa');
