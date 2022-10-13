<?php
/*
 *  Tag Cloud
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Change wp_tag_cloud classes
function awpha_bookmarks_extend_tag_cloud( $tag_data ) {
	return array_map (
		function ( $item ) {
			$item['class'] .= ' text-reset ';
			return $item;
		},
		(array) $tag_data
	);
}
add_filter( 'wp_generate_tag_cloud_data', 'awpha_bookmarks_extend_tag_cloud');