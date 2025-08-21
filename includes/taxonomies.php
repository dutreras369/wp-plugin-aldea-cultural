
<?php
if ( ! defined('ABSPATH') ) exit;

function aldea_register_taxonomies(){
  register_taxonomy('area', ['taller'], [
    'label' => 'Áreas',
    'hierarchical' => true,
    'rewrite' => ['slug' => 'area'],
    'show_in_rest' => false,
  ]);
}
add_action('init','aldea_register_taxonomies');
