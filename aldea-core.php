<?php
/*
Plugin Name: Aldea Core
Description: CPT + metaboxes (ACF) + shortcodes + assets para Aldea Cultural.
Version: 0.1.0
Author: Espacios Virtuales
License: GPLv2 or later
Requires Plugins: advanced-custom-fields, elementor, contact-form-7
*/

if ( ! defined('ABSPATH') ) exit;

define('ALDEA_CORE_VERSION', '0.1.0');
define('ALDEA_CORE_PATH', plugin_dir_path(__FILE__));
define('ALDEA_CORE_URL', plugin_dir_url(__FILE__));

// Includes
require_once ALDEA_CORE_PATH . 'includes/cpt.php';
require_once ALDEA_CORE_PATH . 'includes/taxonomies.php';
require_once ALDEA_CORE_PATH . 'includes/acf-fields.php';
require_once ALDEA_CORE_PATH . 'includes/assets.php';
require_once ALDEA_CORE_PATH . 'includes/shortcodes-init.php';

// Activation: flush rewrite for CPTs
register_activation_hook(__FILE__, function(){
    aldea_register_cpts();
    aldea_register_taxonomies();
    flush_rewrite_rules();
});
register_deactivation_hook(__FILE__, function(){
    flush_rewrite_rules();
});
