<?php
/*
 * Helper Functions
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$innerlinks_angelcards_plugin_version = '1.0.0';

function innerlinks_angelcards_enqueue_scripts() {

	// Load scripts and styles only on 'pick-an-angel.php' page template
	if ( is_page_template('pick-an-angel.php') ) {

		// STYLES
		
		// Pick an Angel CSS
		wp_enqueue_style( 'innerlinks_angelcards-styles', INNERLINKS_ANGELCARDS_PLUGIN_URL . 'public/css/pick-an-angel-styles.css', array(), '1.0.0' );

		// SCRIPTS
		
		// Slider properties JS
		wp_enqueue_script( 'innerlinks_angelcards-scripts', INNERLINKS_ANGELCARDS_PLUGIN_URL . 'public/js/pick-an-angel-scripts.js', array(), '1.0.0', true );
	}
	
}
add_action('wp_enqueue_scripts', 'innerlinks_angelcards_enqueue_scripts');




