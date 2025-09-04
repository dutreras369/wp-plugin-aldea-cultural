<?php
if ( ! defined('ABSPATH') ) exit;

add_action('wp_enqueue_scripts', function(){
  // Puedes hacerlo global o condicionar por tipo de contenido/shortcodes
  // if ( ! is_front_page() ) return;

  // Bootstrap
  wp_enqueue_style('bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',[], '5.3.3');
  wp_enqueue_script('bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',[], '5.3.3', true);
  wp_enqueue_style( 'bootstrap-icon', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css', array(), '1.10.5' );

  // Swiper
  wp_enqueue_style('swiper','https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css',[], null);
  wp_enqueue_script('swiper','https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js',[], null, true);

  // GLightbox
  wp_enqueue_style('glightbox','https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css',[], null);
  wp_enqueue_script('glightbox','https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js',[], null, true);

  // AOS
  wp_enqueue_style('aos','https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css',[], null);
  wp_enqueue_script('aos','https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js',[], null, true);

  // Tus assets
  wp_enqueue_style('aldea-front', ALDEA_CORE_URL.'public/css/front.css', ['bootstrap'], '0.2.0');
  wp_enqueue_script('aldea-front', ALDEA_CORE_URL.'public/js/front.js', ['bootstrap','swiper','glightbox','aos'], '0.2.0', true);
}, 20);
