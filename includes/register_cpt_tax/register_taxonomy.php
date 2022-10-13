<?php
/*
 * Register awpha_bookmarks Custom Post Type
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function awpha_bookmarks_register_taxes() {

	// Get info from Options page
	$options = get_option( 'awpha_bookmarks_settings', array() );

	$tax_cat_rewrite_slug = isset( $options['tax_cat_rewrite_slug'] ) ? $options['tax_cat_rewrite_slug'] : __('bookmarks-category' , 'awpha_bookmarks' );


	/**
	 * Taxonomy: Categories.
	 */

	$labels = [
		"name" => esc_html__( "Bookmark Categories", "awpha_bookmarks" ),
		"singular_name" => esc_html__( "Category", "awpha_bookmarks" ),
		"menu_name" => esc_html__( "Categories", "awpha_bookmarks" ),
		"all_items" => esc_html__( "All Categories", "awpha_bookmarks" ),
		"edit_item" => esc_html__( "Edit Category", "awpha_bookmarks" ),
		"view_item" => esc_html__( "View Category", "awpha_bookmarks" ),
		"update_item" => esc_html__( "Update Category name", "awpha_bookmarks" ),
		"add_new_item" => esc_html__( "Add new Category", "awpha_bookmarks" ),
		"new_item_name" => esc_html__( "New Category name", "awpha_bookmarks" ),
		"parent_item" => esc_html__( "Parent Category", "awpha_bookmarks" ),
		"parent_item_colon" => esc_html__( "Parent Category:", "awpha_bookmarks" ),
		"search_items" => esc_html__( "Search Categories", "awpha_bookmarks" ),
		"popular_items" => esc_html__( "Popular Categories", "awpha_bookmarks" ),
		"separate_items_with_commas" => esc_html__( "Separate Categories with commas", "awpha_bookmarks" ),
		"add_or_remove_items" => esc_html__( "Add or remove Categories", "awpha_bookmarks" ),
		"choose_from_most_used" => esc_html__( "Choose from the most used Categories", "awpha_bookmarks" ),
		"not_found" => esc_html__( "No Categories found", "awpha_bookmarks" ),
		"no_terms" => esc_html__( "No Categories", "awpha_bookmarks" ),
		"items_list_navigation" => esc_html__( "Categories list navigation", "awpha_bookmarks" ),
		"items_list" => esc_html__( "Categories list", "awpha_bookmarks" ),
		"back_to_items" => esc_html__( "Back to Categories", "awpha_bookmarks" ),
		"name_field_description" => esc_html__( "The name is how it appears on your site.", "awpha_bookmarks" ),
		"parent_field_description" => esc_html__( "Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.", "awpha_bookmarks" ),
		"slug_field_description" => esc_html__( "The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.", "awpha_bookmarks" ),
		"desc_field_description" => esc_html__( "The description is not prominent by default; however, some themes may show it.", "awpha_bookmarks" ),
	];

	
	$args = [
		"label" => esc_html__( "Categories", "awpha_bookmarks" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => $tax_cat_rewrite_slug, 'with_front' => true,  'hierarchical' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "awpha_bookmarks_category",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "awpha_bookmarks_category", [ "awpha_bookmarks" ], $args );

	/**
	 * Taxonomy: Tags.
	 */

	$tax_tag_rewrite_slug = isset( $options['tax_tag_rewrite_slug'] ) ? $options['tax_tag_rewrite_slug'] : __('bookmarks-tag' , 'awpha_bookmarks' );

	$labels = [
		"name" => esc_html__( "Bookmark Tags", "awpha_bookmarks" ),
		"singular_name" => esc_html__( "Tag", "awpha_bookmarks" ),
		"menu_name" => esc_html__( "Tags", "awpha_bookmarks" ),
		"all_items" => esc_html__( "All Tags", "awpha_bookmarks" ),
		"edit_item" => esc_html__( "Edit Tag", "awpha_bookmarks" ),
		"view_item" => esc_html__( "View Tag", "awpha_bookmarks" ),
		"update_item" => esc_html__( "Update Tag name", "awpha_bookmarks" ),
		"add_new_item" => esc_html__( "Add new Tag", "awpha_bookmarks" ),
		"new_item_name" => esc_html__( "New Tag name", "awpha_bookmarks" ),
		"parent_item" => esc_html__( "Parent Tag", "awpha_bookmarks" ),
		"parent_item_colon" => esc_html__( "Parent Tag:", "awpha_bookmarks" ),
		"search_items" => esc_html__( "Search Tags", "awpha_bookmarks" ),
		"popular_items" => esc_html__( "Popular Tags", "awpha_bookmarks" ),
		"separate_items_with_commas" => esc_html__( "Separate Tags with commas", "awpha_bookmarks" ),
		"add_or_remove_items" => esc_html__( "Add or remove Tags", "awpha_bookmarks" ),
		"choose_from_most_used" => esc_html__( "Choose from the most used Tags", "awpha_bookmarks" ),
		"not_found" => esc_html__( "No Tags found", "awpha_bookmarks" ),
		"no_terms" => esc_html__( "No Tags", "awpha_bookmarks" ),
		"items_list_navigation" => esc_html__( "Tags list navigation", "awpha_bookmarks" ),
		"items_list" => esc_html__( "Tags list", "awpha_bookmarks" ),
		"back_to_items" => esc_html__( "Back to Tags", "awpha_bookmarks" ),
		"name_field_description" => esc_html__( "The name is how it appears on your site.", "awpha_bookmarks" ),
		"parent_field_description" => esc_html__( "Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.", "awpha_bookmarks" ),
		"slug_field_description" => esc_html__( "The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.", "awpha_bookmarks" ),
		"desc_field_description" => esc_html__( "The description is not prominent by default; however, some themes may show it.", "awpha_bookmarks" ),
	];

	
	$args = [
		"label" => esc_html__( "Tags", "awpha_bookmarks" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => $tax_tag_rewrite_slug, 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "awpha_bookmarks_tag",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "awpha_bookmarks_tag", [ "awpha_bookmarks" ], $args );
}
add_action( 'init', 'awpha_bookmarks_register_taxes' );