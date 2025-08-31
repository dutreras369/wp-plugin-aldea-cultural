<?php
/**
 * Shortcode: [ev-menu_botones menu="footer-subpaginas" class="mi-clase"]
 * Crea una botonera tipo pestañas con Bootstrap + sombras.
 */

function ev_menu_botones_shortcode($atts) {
    $atts = shortcode_atts([
        'menu'  => 'footer-subpaginas', // Nombre o slug del menú
        'class' => ''                   // Clases adicionales opcionales
    ], $atts, 'ev-menu_botones');

    $menu = wp_get_nav_menu_object($atts['menu']);
    if (!$menu) return '<!-- Menú no encontrado -->';

    $items = wp_get_nav_menu_items($menu->term_id);
    if (!$items) return '';

    $current_url = home_url(add_query_arg([], $_SERVER['REQUEST_URI']));
    $output = '<div class="ev-botonera-footer d-flex justify-content-center flex-wrap gap-2 ' . esc_attr($atts['class']) . '">';

    foreach ($items as $item) {
        $is_active = trailingslashit($item->url) === trailingslashit($current_url) ? ' active' : '';
        $output .= '<a href="' . esc_url($item->url) . '" class="ev-boton-tab btn btn-outline-primary shadow-sm' . $is_active . '">' . esc_html($item->title) . '</a>';
    }

    $output .= '</div>';
    return $output;
}

add_shortcode('ev-menu_botones', 'ev_menu_botones_shortcode');
