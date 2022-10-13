<?php
/*
 * Plugin Name: Bookmarks by Awpha
 * Plugin URI: http://awpha.com.br
 * Description: Setup WordPress for a favorites (bookmarks) section with it's own categories and tags.
 * Version: 1.0.0
 * Author: Awpha
 * Author URI: http://awpha.com.br
 * License: GPLv2 or later
 * Domain Path: /languages/
 * Text Domain: awpha_bookmarks
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Global variables
 */

// CPT
define( 'AWPHA_BOOKMARKS_CPT_SLUG', 'awpha_bookmarks');

$awpha_bookmarks_plugin_version = '1.0';							    // for use on admin pages
$plugin_file = plugin_basename(__FILE__);								// plugin file for reference
define( 'AWPHA_BOOKMARKS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );	// define the absolute plugin path for includes
define( 'AWPHA_BOOKMARKS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );		// define the plugin url for use in enqueue

/**
 * Includes - keeping it modular
 */
include( AWPHA_BOOKMARKS_PLUGIN_PATH . 'includes/functions.php' );                          // General Functions

include( AWPHA_BOOKMARKS_PLUGIN_PATH . 'includes/helper/helper_functions.php' );            // Helper
include( AWPHA_BOOKMARKS_PLUGIN_PATH . 'includes/helper/get_favicon.php' );                 // Favicon helper
include( AWPHA_BOOKMARKS_PLUGIN_PATH . 'includes/helper/get_og_image.php' );                // og:image helper

include( AWPHA_BOOKMARKS_PLUGIN_PATH . 'includes/register_cpt_tax/register_cpt.php' );      // regsiter cpt
include( AWPHA_BOOKMARKS_PLUGIN_PATH . 'includes/register_cpt_tax/register_taxonomy.php' ); // regsiter taxonomies
include( AWPHA_BOOKMARKS_PLUGIN_PATH . 'includes/register_cpt_tax/register_meta_boxes.php' );// add extra info to cpt

include( AWPHA_BOOKMARKS_PLUGIN_PATH . 'includes/options/options.php' );                    // settings page
include( AWPHA_BOOKMARKS_PLUGIN_PATH . 'includes/shortcodes.php' );                         // Shortcode functions
include( AWPHA_BOOKMARKS_PLUGIN_PATH . 'includes/hooks.php' );                              // Hooks

