<?php
/*
 * Shortcodes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

//Tag Cloud
function awpha_bookmarks_tag_cloud_shortcode() {
	//Tag Cloud
	$tag_cloud_args = array(
		'smallest'   => 1,
		'largest'    => 4,
		'unit'       => 'rem',
		'number'     => 100,
		'format'     => 'flat',
		'separator'  => "\n",
		'orderby'    => 'name',
		'order'      => 'ASC',
		'exclude'    => '',
		'include'    => '',
		'link'       => 'view',
		'taxonomy'   => 'awpha_bookmarks_tag',
		'post_type'  => '',
		'echo'       => true,
		'show_count' => 0,
	);

	return wp_tag_cloud( $tag_cloud_args );
}
add_shortcode( 'bookmarks_tag_cloud', 'awpha_bookmarks_tag_cloud_shortcode' );