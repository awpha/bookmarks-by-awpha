<?php
/*
 *  Functions
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Include 'awpha_bookmarks' in main query with pre_get_posts
function awpha_bookmarks_cpt_bookmarks_in_main_query( $query ) {

    if ( is_home() && $query->is_main_query() ) {

        // Add awpha_bookmarks to default $query->post_type
        $query_post_types = awpha_bookmarks_cpt_query_post_type( $query );

        // Update default $query
        $query->set( 'post_type', $query_post_types );

    }
    return $query;
}

// Include 'awpha_bookmarks' in search results
function awpha_bookmarks_cpt_bookmarks_in_search_results( $query ) {
		
    if ( $query->is_main_query() && $query->is_search() && !is_admin() ) {

        // Add awpha_bookmarks to default $query->post_type
        $query_post_types = awpha_bookmarks_cpt_query_post_type( $query );

        // Update default $query
        $query->set( 'post_type', $query_post_types );

    }
    return $query;
}


// Prevent duplicated slug
function awpha_bookmarks_prevent_slug_duplicates( $slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug ) {
    $check_post_types = array(
        'post',
        'page',
        AWPHA_BOOKMARKS_CPT_SLUG
    );

    if ( ! in_array( $post_type, $check_post_types ) ) {
        return $slug;
    }

    if ( AWPHA_BOOKMARKS_CPT_SLUG == $post_type ) {
        // Saving a custom_post_type post, check for duplicates in POST or PAGE post types
        $post_match = get_page_by_path( $slug, 'OBJECT', 'post' );
        $page_match = get_page_by_path( $slug, 'OBJECT', 'page' );

        if ( $post_match || $page_match ) {
            $slug .= '-2'; //append to slug
        }
    } else {
        // Saving a POST or PAGE, check for duplicates in custom_post_type post type
        $custom_post_type_match = get_page_by_path( $slug, 'OBJECT', AWPHA_BOOKMARKS_CPT_SLUG );

        if ( $custom_post_type_match ) {
            $slug .= '-2'; //append to slug
        }
    }

    return $slug;
}


// Remove slug from the permalinks
function awpha_bookmarks_cpt_rewrite_post_link( $post_link, $post ) {

    //exclude post type change.
    if ( AWPHA_BOOKMARKS_CPT_SLUG == get_post_type( $post ) ) {

        // Via DB
        //$options = get_option( 'awpha_bookmarks_settings', array() );
        //$post_type_slug = awpha_bookmarks_check_option( $options['cpt_rewrite_slug'] ) ? $options['cpt_rewrite_slug'] : __('bookmarks' , 'awpha_bookmarks' );

        // Via function
        $post_type = get_post_type_object( AWPHA_BOOKMARKS_CPT_SLUG );
        $post_type_slug = $post_type->rewrite['slug'];

        $post_link = str_replace( '/' . $post_type_slug . '/', '/', $post_link );
    }

    return $post_link;
}

// Remove slug from pre get post
function awpha_bookmarks_cpt_parse_request( $query ) {

    //$post_type_list = get_option( 'remove_custom_post_type_slug', true);
    //$post_type_list = apply_filters( 'remove_custom_post_type_slug_filter', $post_type_list);
    $post_type_list = awpha_bookmarks_cpt_query_post_type( $query );

    if ( ! $query->is_main_query() || 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
        return;
    }

    if ( ! empty( $query->query['name'] ) ) {
        $query->set( 'post_type', $post_type_list ); // add post slug here
    }
}