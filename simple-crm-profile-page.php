<?php
/*
Plugin Name: Simple CRM Profile Page Addon
Plugin URI: http://wordpress.org/extend/plugins/simple-crm-profile-page/
Description: Add Public Profile Pages to Simple CRM
Author: Stas Sușcov
Version: 0.1
Author URI: http://stas.nerd.ro/
*/

define( 'SCRM_PP_ROOT', dirname( __FILE__ ) );
define( 'SCRM_PP_WEB_ROOT', WP_PLUGIN_URL . '/' . basename( SCRM_PP_ROOT ) );

require_once SCRM_PP_ROOT . '/includes/pp.class.php';

/**
 * i18n
 */
function scrm_pp_textdomain() {
    load_plugin_textdomain( 'scrm_pp', false, basename( SCRM_PP_ROOT ) . '/languages' );
}
add_action( 'init', 'scrm_pp_textdomain' );

SCRM_PP::init();

?>
