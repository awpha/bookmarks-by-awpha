<?php

/*
 * Register Page Templates
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Page Templates
// Add templates to the dropdown list of 'page templates' inside 'Page Attributes' section
function awpha_bookmarks_add_page_template ($templates) {
	$templates['tag-cloud.php'] = 'Tag Cloud';
	return $templates;
}
add_filter ('theme_page_templates', 'awpha_bookmarks_add_page_template');

// WordPress will search for the template-file-name.php in the theme directory
// We need to redirect the template to the plugin directory by using the 'page_template' filter
function awpha_bookmarks_redirect_page_template ($template) {

	$post = get_post();
	$template_meta = get_post_meta( $post->ID, '_wp_page_template', true );

	if( !empty( $template_meta ) ) {

		if ('tag-cloud.php' == basename ($template_meta)) {
			$template = BR_BOOKMARKS_PLUGIN_PATH . 'includes/page-templates/tag-cloud.php';
		}

	}

    return $template; // mandatory
}
add_filter ('page_template', 'awpha_bookmarks_redirect_page_template');

// Add Post States
function awpha_bookmarks_post_states( $states, $post ) { 
	
	if ( ( 'page' == get_post_type( $post->ID ) ) 
		&& ( 'tag-cloud.php' == get_page_template_slug( $post->ID ) ) ) {
			$states[] = __('Tag Cloud');
	}

	return $states;
}
add_filter('display_post_states', 'awpha_bookmarks_post_states', 10, 2);
