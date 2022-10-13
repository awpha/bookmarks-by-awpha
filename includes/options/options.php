<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*
 * Options Page
 * 
 * Retrive options:
 * $options = get_option( 'awpha_bookmarks_settings', array() );
 * 
 *    Each field id is a key in the options array
 *    $cpt_rewrite_slug = $options['cpt_rewrite_slug'];
 *    $pin = $options['pin'];
 */

 //Instantiate the class
if ( !class_exists( 'awpha_bookmarks_option_page' ) ) {
	require_once('awpha_bookmarks_option_page.php');
}

function awpha_bookmarks_bookmarklet_url() {

	$options = get_option( 'awpha_bookmarks_settings', array() );

	$pin = awpha_bookmarks_check_option( $options['pin'] ) ? '&pin=' . $options['pin'] : '';

	$href = 'javascript:var d=document,w=window,e=w.getSelection,k=d.getSelection,x=d.selection,s=(e?e():(k)?k():(x?x.createRange().text:0)),f=%27' . AWPHA_BOOKMARKS_PLUGIN_URL . '%27,l=d.location,e=encodeURIComponent,u=f+%27?u=%27+e(l.href.replace(new%20RegExp(%27(https?:\/\/)%27,%27gm%27),%27%27))+%27' . $pin . '&t=%27+e(d.title)+%27&s=%27+e(s)+%27&v=4&m=%27+(((l.href).indexOf(%27https://%27,0)===0)?1:0);a=function(){if(!w.open(u,%27t%27,%27toolbar=0,resizable=1,scrollbars=1,status=1,width=720,height=570%27))l.href=u;};if%20(/Firefox/.test(navigator.userAgent))%20setTimeout(a,%200);%20else%20a();void(0)';

	$url_string = __('Drag or Save this link to your bookmarks bar:','awpha_bookmarks');
	$url_title = __('Add to','awpha_bookmarks') . ' ' . get_bloginfo('name');

	$bookmarklet = '<strong>' . $url_string . '</strong> <a href="' . $href . '">' . $url_title . '</a>';

	return $bookmarklet;
}

// Options Page Fields
$awpha_bookmarks_settings_page = array(
	'awpha_bookmarks_settings'	=> array(
		'parent_slug'	=> 'edit.php?post_type=awpha_bookmarks',
		'page_title'	=> __( 'Settings', 'awpha_bookmarks' ),
		'menu_slug '	=> 'awpha_bookmarks_settings',
		'sections'		=> array(
			//Setup
			'section-2'	=> array(
				'title'			=> __( 'Submit bookmark URL', 'awpha_bookmarks' ),
				'text'			=> awpha_bookmarks_bookmarklet_url(),
			),
			//Setup
			'section-1'	=> array(
				'title'			=> __( 'General', 'awpha_bookmarks' ),
				'text'			=> __( 'General settings.', 'awpha_bookmarks' ),
				'fields'		=> array(

					'awpha_bookmarks-1'		=> array(
						'id'			=> 'cpt_archive_slug',
						'title'			=> __( 'Archive base URL slug', 'awpha_bookmarks' ),
						'text'			=> __( 'Set the base URL for this post type archive (default is <code>bookmarks</code>).','awpha_bookmarks' ),
						'placeholder '	=> __( 'bookmarks','awpha_bookmarks' ),
					),
					'awpha_bookmarks-2'		=> array(
						'id'			=> 'cpt_rewrite_slug',
						'title'			=> __( 'Single post base URL slug', 'awpha_bookmarks' ),
						'text'			=> __( 'Set the base URL for this single post type (default is <code>bookmarks</code>).','awpha_bookmarks' ),
						'placeholder '	=> __( 'bookmarks','awpha_bookmarks' ),
					),
					'awpha_bookmarks-3'		=> array(
						'id'			=> 'cpt_archive_slug_remove',
						'title'			=> __( 'Remove archive base URL slug', 'awpha_bookmarks' ),
						'type'			=> 'checkbox',
						'value'			=> '1',
						'text'			=> __( 'Show bookmarks archive without base URL slug. This is useful if WordPress is installed in a subdirectory.','awpha_bookmarks' ),
					),
					'awpha_bookmarks-4'		=> array(
						'id'			=> 'cpt_rewrite_slug_remove',
						'title'			=> __( 'Remove single bookmark posts base URL slug', 'awpha_bookmarks' ),
						'type'			=> 'checkbox',
						'value'			=> '1',
						'text'			=> __( 'Show single bookmarks posts without base URL slug. This is useful if WordPress is installed in a subdirectory.','awpha_bookmarks' ),
					),
					'awpha_bookmarks-5'		=> array(
						'id'			=> 'cpt_bookmarks_in_main_query',
						'title'			=> __( 'Show Bookmarks together with posts', 'awpha_bookmarks' ),
						'type'			=> 'checkbox',
						'value'			=> '1',
						'text'			=> __( 'By default, the Bookmarks will not appear in the main blog loop.<br>Select this option to change that, so bookmarks will appear alongside your blog posts.','awpha_bookmarks' ),
					),
					'awpha_bookmarks-6'		=> array(
						'id'			=> 'cpt_bookmarks_in_search_query',
						'title'			=> __( 'Include Bookmarks in search results', 'awpha_bookmarks' ),
						'type'			=> 'checkbox',
						'value'			=> '1',
						'text'			=> __( 'Select this option to include Bookmarks in search results.<br>By default Custom Post Types are not included in search results.','awpha_bookmarks' ),
					),

					
					'tax_cat_rewrite_slug'		=> array(
						'id'			=> 'tax_cat_rewrite_slug',
						'title'			=> __( 'Category taxonomy URL slug', 'awpha_bookmarks' ),
						'text'			=> __( 'Set the URL slug for the category taxonomy (default is <code>bookmarks-category</code>).<br><strong>CAUTION:</strong> If you set the slug as <code>category</code> remember to rename the default Category base to something else.','awpha_bookmarks' ),
						'placeholder '	=> __( 'bookmarks-category','awpha_bookmarks' ),
					),
					'tax_tag_rewrite_slug'		=> array(
						'id'			=> 'tax_tag_rewrite_slug',
						'title'			=> __( 'Tag taxonomy URL slug', 'awpha_bookmarks' ),
						'text'			=> __( 'Set the URL slug for the tag taxonomy (default is <code>bookmarks-tag</code>).<br><strong>CAUTION:</strong> If you set the slug as <code>tag</code> remember to rename the default Tag base to something else.','awpha_bookmarks' ),
						'placeholder '	=> __( 'bookmarks-tag','awpha_bookmarks' ),
					),

					
				),
			),
			
			//Setup
			'section-3'	=> array(
				'title'			=> __( 'Frontend', 'awpha_bookmarks' ),
				'fields'		=> array(

					'pin'		=> array(
						'id'			=> 'pin',
						'title'			=> __( 'Security PIN', 'awpha_bookmarks' ),
						'text'			=> __( 'Set a security PIN to access the frontend subsmission form.<br>Leave empty to disable it.','awpha_bookmarks' ),
						'placeholder '	=> __( '1234','awpha_bookmarks' ),
					),
				),
			),
		),
	),
);
$option_page = new awpha_bookmarks_option_page( $awpha_bookmarks_settings_page );

