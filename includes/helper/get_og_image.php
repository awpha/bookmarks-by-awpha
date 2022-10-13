<?php
/*
 * Get og:image URL helper function
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function awpha_bookmarks_og_image_url($url) {

	$meta_og_img = null;
	$meta_og_title = null;

	$content_html = @file_get_contents($url);

	// Avoid errors
	if(!empty($content_html)) {

		$html = new DOMDocument();
		@$html->loadHTML($content_html);

		foreach($html->getElementsByTagName('meta') as $meta) {
			if($meta->getAttribute('property')=='og:title'){
				$meta_og_title = $meta->getAttribute('content');
			}
			if($meta->getAttribute('property')=='og:image'){
				$meta_og_img = $meta->getAttribute('content');
			}
		}

	};

	return $meta_og_img;

}

