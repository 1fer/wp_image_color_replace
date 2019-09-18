<?php

/**
 * =============================================================================
 * Plugin To change one color to another color in an image.
 * =============================================================================
 * Plugin Name: Image color replace
 * Plugin URI: 
 * Description: REPLACECOLOR changes one color to another color in an image by 
 * modifying the input color hue to the desired output color hue and adjusting 
 * the saturation and brightness.
 * 
 * Author: Roman Panevnyk
 * Author URI:
 * License:
 * License URI:
 * 
 * REQUIREMENTS: IM 6.5.3-7 or higher, so that -modulate uses HSL and not 
 * 
 * @author Panevnyk Roman <panevnyk.roman@gmail.com>
 * @package WordPress
 * @version 1.0.0
 */ 

if ( ! defined( 'ICR_DIR' ) ) {
    define( 'ICR_DIR', plugin_dir_path( __FILE__ ) );
 }

 if ( ! defined( 'ICR_INC' ) ) {
    define( 'ICR_INC', ICR_DIR . 'includes/' );
 }
 
 if ( ! defined( 'ICR_TEMPLATES' ) ) {
    define( 'ICR_TEMPLATES', ICR_DIR . 'templates/' );
 }

 if ( ! defined( 'ICR_URL' ) ) {
    define( 'ICR_URL', plugin_dir_url( __FILE__ ) );
 }
 
if ( ! defined( 'ICR_CSS' ) ) {
   define( 'ICR_CSS', ICR_URL . 'access/css/' );
}

 
if ( ! defined( 'ICR_JS' ) ) {
   define( 'ICR_JS', ICR_URL . 'access/js/' );
}

/** 
 * =============================================================================
 * Init Plugin 
 * =============================================================================
 * @action plugins_loaded
 * 
 * @method icr_init
 * @param null
 * 
 * @return void
 * @since 1.0.0
 * @author Panevnyk Roman <panevnyk.roman@gmail.com>
 */
function icr_init() {

    require_once( ICR_INC . 'class.icr.php' );
 
 }
 add_action('plugins_loaded', 'icr_init', 0);