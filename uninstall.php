<?php
// Ejecuta solo cuando se desinstala desde el administrador de WP
if ( ! defined('WP_UNINSTALL_PLUGIN') ) exit;

global $wpdb;

// Elimina opciones/transients del plugin
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'aldea_%' OR option_name LIKE '_transient_aldea_%' OR option_name LIKE '_transient_timeout_aldea_%'" );

if ( is_multisite() ) {
  $wpdb->query( "DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE '_site_transient_aldea_%' OR meta_key LIKE '_site_transient_timeout_aldea_%'" );
}

// NO borrar contenidos (CPT), por seguridad
// Si quisieras borrar contenidos de CPT (no recomendado en producción), lo harías explícitamente aquí.
