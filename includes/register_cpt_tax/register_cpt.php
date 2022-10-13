<?php
/*
 * Register awpha_bookmarks Custom Post Type
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function awpha_bookmarks_register_cpt_awpha_bookmarks() {

	// Get ALL Options
	$options = get_option( 'awpha_bookmarks_settings', array() );

	// general
	$with_front = true;

	// CPT Archive slug
	$cpt_archive_slug = awpha_bookmarks_check_option( $options['cpt_archive_slug'] ) ? $options['cpt_archive_slug'] : __('bookmarks' , 'awpha_bookmarks' );
	// CPT single post slug
	$cpt_rewrite_slug = awpha_bookmarks_check_option( $options['cpt_rewrite_slug'] ) ? $options['cpt_rewrite_slug'] : __('bookmarks' , 'awpha_bookmarks' );


	// CPT Archive without slug
	$cpt_archive_slug_remove = $options['cpt_archive_slug_remove'];
	// CPT single post without slug
	$cpt_rewrite_slug_remove = $options['cpt_rewrite_slug_remove'];


	// Remove single post slug
	if( $cpt_rewrite_slug_remove ) {
		//remove slug hooks
		add_action( 'pre_get_posts', 'awpha_bookmarks_cpt_parse_request', 1, 1);
		add_filter( 'post_type_link','awpha_bookmarks_cpt_rewrite_post_link', 1, 2);
		add_filter( 'get_the_permalink', 'awpha_bookmarks_cpt_rewrite_post_link', 1, 2);
		add_filter( 'the_permalink', 'awpha_bookmarks_cpt_rewrite_post_link', 1, 2);
	}

	

	// Add 'awpha_bookmarks' to main query
	$cpt_bookmarks_in_main_query = $options['cpt_bookmarks_in_main_query'];
	// Add action
	if( $cpt_bookmarks_in_main_query ) {
		add_action( 'pre_get_posts', 'awpha_bookmarks_cpt_bookmarks_in_main_query' );
	}
	
	// Include 'awpha_bookmarks' in search results
	$cpt_bookmarks_in_search_query = $options['cpt_bookmarks_in_search_query'];
	// Add action
	if( $cpt_bookmarks_in_search_query ) {
		add_action( 'pre_get_posts', 'awpha_bookmarks_cpt_bookmarks_in_search_results' );
	}

	/**
	 * Register Custom Post Type: awpha_bookmarks.
	 */

	$labels = [
		"name" => esc_html__( "Bookmarks", "awpha_bookmarks" ),
		"singular_name" => esc_html__( "Bookmark", "awpha_bookmarks" ),
		"menu_name" => esc_html__( "Bookmarks", "awpha_bookmarks" ),
		"all_items" => esc_html__( "All Bookmarks", "awpha_bookmarks" ),
		"add_new" => esc_html__( "Add new", "awpha_bookmarks" ),
		"add_new_item" => esc_html__( "Add new Bookmark", "awpha_bookmarks" ),
		"edit_item" => esc_html__( "Edit Bookmark", "awpha_bookmarks" ),
		"new_item" => esc_html__( "New Bookmark", "awpha_bookmarks" ),
		"view_item" => esc_html__( "View Bookmark", "awpha_bookmarks" ),
		"view_items" => esc_html__( "View Bookmarks", "awpha_bookmarks" ),
		"search_items" => esc_html__( "Search Bookmarks", "awpha_bookmarks" ),
		"not_found" => esc_html__( "No Bookmarks found", "awpha_bookmarks" ),
		"not_found_in_trash" => esc_html__( "No Bookmarks found in trash", "awpha_bookmarks" ),
		"parent" => esc_html__( "Parent Bookmark:", "awpha_bookmarks" ),
		"featured_image" => esc_html__( "Featured image for this Bookmark", "awpha_bookmarks" ),
		"set_featured_image" => esc_html__( "Set featured image for this Bookmark", "awpha_bookmarks" ),
		"remove_featured_image" => esc_html__( "Remove featured image for this Bookmark", "awpha_bookmarks" ),
		"use_featured_image" => esc_html__( "Use as featured image for this Bookmark", "awpha_bookmarks" ),
		"archives" => esc_html__( "Bookmark archives", "awpha_bookmarks" ),
		"insert_into_item" => esc_html__( "Insert into Bookmark", "awpha_bookmarks" ),
		"uploaded_to_this_item" => esc_html__( "Upload to this Bookmark", "awpha_bookmarks" ),
		"filter_items_list" => esc_html__( "Filter Bookmarks list", "awpha_bookmarks" ),
		"items_list_navigation" => esc_html__( "Bookmarks list navigation", "awpha_bookmarks" ),
		"items_list" => esc_html__( "Bookmarks list", "awpha_bookmarks" ),
		"attributes" => esc_html__( "Bookmarks attributes", "awpha_bookmarks" ),
		"name_admin_bar" => esc_html__( "Bookmark", "awpha_bookmarks" ),
		"item_published" => esc_html__( "Bookmark published", "awpha_bookmarks" ),
		"item_published_privately" => esc_html__( "Bookmark published privately.", "awpha_bookmarks" ),
		"item_reverted_to_draft" => esc_html__( "Bookmark reverted to draft.", "awpha_bookmarks" ),
		"item_scheduled" => esc_html__( "Bookmark scheduled", "awpha_bookmarks" ),
		"item_updated" => esc_html__( "Bookmark updated.", "awpha_bookmarks" ),
		"parent_item_colon" => esc_html__( "Parent Bookmark:", "awpha_bookmarks" ),
	];

	$args = [
		"label" => esc_html__( "Bookmarks", "awpha_bookmarks" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => $cpt_archive_slug, //"bookmarks"
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => true,
		"rewrite" => [ "slug" => $cpt_rewrite_slug, "with_front" => $with_front ], //["bookmarks", true]
		"query_var" => true,
		"menu_position" => 21,
		"menu_icon" => "dashicons-admin-links",
		"supports" => [ "title", "editor", "thumbnail", "author" ],
		"show_in_graphql" => false,
	];

	register_post_type( AWPHA_BOOKMARKS_CPT_SLUG, $args );

	// Extra settings
	// Include awpha_bookmarks CPT in the unique_post_slug function
	add_filter( 'wp_unique_post_slug', 'awpha_bookmarks_prevent_slug_duplicates', 10, 6 );
}

add_action( 'init', 'awpha_bookmarks_register_cpt_awpha_bookmarks' );