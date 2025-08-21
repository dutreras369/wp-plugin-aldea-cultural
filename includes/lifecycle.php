<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * NOTA: este reset NO borra contenidos (posts/talleres/alianzas) ni medios.
 * Solo limpia configuración del plugin (options/transients/cron) y reescrituras.
 */

function aldea_reset_runtime() {
  global $wpdb;

  // 1) Transients del plugin
  // (si creas transients, respétales prefijo; aquí limpiamos genérico por si acaso)
  $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_aldea_%' OR option_name LIKE '_transient_timeout_aldea_%'" );
  if ( is_multisite() ) {
    $wpdb->query( "DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE '_site_transient_aldea_%' OR meta_key LIKE '_site_transient_timeout_aldea_%'" );
  }

  // 2) Opciones del plugin (prefijo aldea_)
  $wpdb->query( $wpdb->prepare(
    "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like('aldea_') . '%'
  ) );

  // 3) Cron events propios (hooks que empiecen con aldea_)
  if ( function_exists('wp_clear_scheduled_hook') ) {
    // Si tuvieras jobs, límpialos aquí (ejemplos):
    wp_clear_scheduled_hook('aldea_cron_sync');
    wp_clear_scheduled_hook('aldea_cron_cleanup');
  }

  // 4) Cache de objetos
  if ( function_exists('wp_cache_flush') ) {
    wp_cache_flush();
  }
}

/**
 * ACTIVACIÓN
 * - Registra CPT/tax
 * - Limpia runtime
 * - Flush reescrituras
 * - (Opcional) setea defaults del plugin
 */
function aldea_plugin_activate() {
  if ( function_exists('aldea_register_cpts') ) aldea_register_cpts();
  if ( function_exists('aldea_register_taxonomies') ) aldea_register_taxonomies();

  aldea_reset_runtime();

  // Defaults del plugin (ejemplo, guarda un array de ajustes)
  $defaults = get_option('aldea_settings', []);
  if ( empty($defaults) || ! is_array($defaults) ) {
    update_option('aldea_settings', [
      'version'      => '0.3.0',
      'dev_mode'     => defined('ALDEA_DEV_MODE') ? (bool)ALDEA_DEV_MODE : false,
      'assets_scope' => 'front', // front|global, por ejemplo
    ]);
  }

  flush_rewrite_rules(); // importante para CPT
}

/**
 * DESACTIVACIÓN
 * - Limpia runtime
 * - Flush reescrituras
 * (¡no borra contenidos!)
 */
function aldea_plugin_deactivate() {
  aldea_reset_runtime();
  flush_rewrite_rules();
}

/**
 * Herramienta DEV visible solo a administradores para reset manual desde el admin bar
 * (activa solo si ALDEA_DEV_MODE = true)
 */
add_action('admin_bar_menu', function($wp_admin_bar){
  if ( ! is_user_logged_in() || ! current_user_can('manage_options') ) return;
  if ( ! defined('ALDEA_DEV_MODE') || ! ALDEA_DEV_MODE ) return;

  $wp_admin_bar->add_node([
    'id'    => 'aldea-reset',
    'title' => '🔄 Reset Aldea Core (DEV)',
    'href'  => wp_nonce_url( admin_url('admin-post.php?action=aldea_reset_now'), 'aldea_reset_now' ),
    'meta'  => ['title'=>'Limpiar opciones/transients/cron y flush rewrites']
  ]);
}, 100);

add_action('admin_post_aldea_reset_now', function(){
  if ( ! current_user_can('manage_options') ) wp_die('No permitido');
  check_admin_referer('aldea_reset_now');
  aldea_reset_runtime();
  flush_rewrite_rules();
  wp_safe_redirect( admin_url('index.php?aldea_reset=1') );
  exit;
});
