<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Registra CPTs: Taller, Evento, Noticia
 */
function aldea_register_cpts() {

    $supports = ['title','editor','thumbnail','excerpt','revisions'];

    register_post_type('taller', [
        'label' => 'Talleres',
        'public' => true,
        'menu_icon' => 'dashicons-welcome-learn-more',
        'supports' => $supports,
        'has_archive' => true,
        'rewrite' => ['slug' => 'talleres'],
        'show_in_rest' => true,
    ]);

    register_post_type('evento', [
        'label' => 'Eventos',
        'public' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => $supports,
        'has_archive' => true,
        'rewrite' => ['slug' => 'agenda'],
        'show_in_rest' => true,
    ]);
    register_post_type('alianza', [
        'label'         => 'Alianzas',
        'public'        => true,
        'menu_icon'     => 'dashicons-networking',
        'has_archive'   => true,
        'rewrite'       => ['slug' => 'alianzas'],
        'supports'      => ['title','editor','thumbnail','revisions'],
        'show_in_rest'  => false, // seguimos sin Gutenberg
      ]);

}
add_action('init','aldea_register_cpts');
