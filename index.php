<?php
global $wpdb;

// Requirements
ob_start();
require_once( preg_replace( "/wp-content.*/", "wp-load.php", __FILE__ ) );
require_once( preg_replace( "/wp-content.*/", "/wp-admin/includes/admin.php", __FILE__ ) );
/** WordPress Administration Bootstrap */
require_once( preg_replace( "/wp-content.*/", "/wp-admin/admin.php", __FILE__ ) );
//require_once( preg_replace( "/wp-content.*/", "/wp-admin/includes/post.php", __FILE__ ) );
require_once ABSPATH . '/wp-admin/includes/post.php';
// required libraries for media_sideload_image
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
ob_end_clean();
// End Requirements

// Settings
$options = get_option( 'awpha_bookmarks_settings', array() );
// Security PIN
$awpha_bookmarks_pin = awpha_bookmarks_check_option( $options['pin'] ) ? $options['pin'] : '';

// HEADER - Check PIN/token
if ( $awpha_bookmarks_pin && $awpha_bookmarks_pin != sanitize_text_field( $_GET['pin'] ) ) {

	header("HTTP/1.1 401 Unauthorized");
	exit();

} else {

	header( 'Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' ) );

	// WP User cap
	if ( ! current_user_can( 'edit_posts' ) || ! current_user_can( get_post_type_object( 'post' )->cap->create_posts ) ) {
		wp_die( __( 'Access Denied.' ) );
	}

}

// SUBMISSION

$postTitleError = '';

if ( isset( $_POST['submitted'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {

	if ( trim( $_POST['title'] ) === '' ) {
		$postTitleError = 'Please enter a title.';
		$hasError = true;
	}
	
	$post_information = array(
		'post_type' 		=> AWPHA_BOOKMARKS_CPT_SLUG,									// Post type
		'post_status' 		=> 'publish',													// Publish
		'ID'				=> absint( $_POST['post_id'] ),									// ID
		'post_title' 		=> sanitize_text_field( $_POST['title'] ),						// Title
		'post_name'			=> sanitize_text_field( $_POST['slug'] ),						// Slug
		'post_content' 		=> sanitize_textarea_field( $_POST['content'] ),				// Content
		'post_author'		=> get_current_user_id(),										// Author
		'tax_input'			=> array(
			'awpha_bookmarks_category'	=> !empty($_POST['categories']) ? $_POST['categories'] : [],
			'awpha_bookmarks_tag'		=> awpha_bookmarks_tagify_json_to_array( $_POST['tags'] ),
		),
		'meta_input'		=> array(														// Meta boxes
			'bookmarks_url' 		=> sanitize_url( $_POST['url'] ),						// Url
			'bookmarks_favicon-url' => sanitize_url( $_POST['favicon-url'] ),				// Favicon URL
			'bookmarks_image-url' 	=> sanitize_url( $_POST['og-image-url'] ),				// og:image URL
		),
		//	'tags_input'		=> awpha_bookmarks_tagify_json_to_array( $_POST['tags'] ),		// Tags
		//	'post_category'		=> !empty($_POST['categories']) ? $_POST['categories'] : [],	// Categories
	);
	 
	$wp_insert_post_id = wp_insert_post( $post_information );

	// Set featured image
	if ( $wp_insert_post_id && !empty( $_POST['og-image-url'] ) && !has_post_thumbnail($wp_insert_post_id) ) {

		// load the image
		$result = media_sideload_image($_POST['og-image-url'], $wp_insert_post_id, '', 'id');

		// then find the last image added to the post attachments
	//	$attachments = get_posts( 
	//		array(
	//			'numberposts' => '1', 
	//			'post_parent' => $wp_insert_post_id, 
	//			'post_type' => 'attachment', 
	//			'post_mime_type' => 'image', 
	//			'order' => 'ASC'
	//		)
	//	);
		
		//if( sizeof($attachments) > 0 ) {
		    // set image as the post thumbnail
		    set_post_thumbnail($wp_insert_post_id, $result);
		//}

	} // END Set featured image

	// Close the window after submission
	echo '<script>window.close();</script>';
 
}
 
// END SUBMISSION


// Set Variables
$get_title = isset( $_GET['t'] ) ? trim( strip_tags( html_entity_decode( stripslashes( $_GET['t'] ) , ENT_QUOTES) ) ) : '';


$get_selection = '';
if ( ! empty( $_GET['s'] ) ) {
	$selection = str_replace( '&apos;', "'", stripslashes( $_GET['s'] ) );
	$selection = trim( htmlspecialchars( html_entity_decode( $selection, ENT_QUOTES ) ) );
}

// we stripped the protocol so as to avoid issues with certain
// webhosts (HostGator) that throw 404's if protocols are in GET vars
// but we tracked if it was HTTPS so we'll put the protocol back in
$get_url = isset( $_GET['u'] ) ? esc_url( ( $_GET['m'] ? 'https://' : 'http://' ) . $_GET['u'] ) : '';


//Check if post exist
$post_exists = post_exists( $get_title,'','', AWPHA_BOOKMARKS_CPT_SLUG );

if('0' == $post_exists) {

	$post_exists = post_exists( sanitize_text_field( $get_title ),'','', AWPHA_BOOKMARKS_CPT_SLUG );

	if('0' == $post_exists) {

		$post_exists = get_page_by_title( $get_title,'', AWPHA_BOOKMARKS_CPT_SLUG);

		if (!is_null($post_exists)) {
			$post_exists = $post_exists->ID;
		}

	}
}

//END Check if post exist

// Form VARs
if ($post_exists) {
	// POST EXISTS !!!
	$post_id 		= absint($post_exists);
	$title 			= $get_title;
//	$title 			= get_the_title($post_id); 
	$url 			= sanitize_url($get_url);
	$url_previous	= get_post_meta( $post_id, 'bookmarks_url', true );
	$slug 			= get_post_field( 'post_name', get_post($post_id) );

	$favicon_url	= get_post_meta( $post_id, 'bookmarks_favicon-url', true );
	$og_image_url	= awpha_bookmarks_og_image_url($url);
	
	//Tags
	$post_tags		= get_the_terms( $post_id, 'awpha_bookmarks_tag' );
	$post_tags_name	= !empty($post_tags) ? array_column( $post_tags, 'name' ) : [];
	//$post_tags_id 	= !empty($post_tags) ? array_column( $post_tags, 'term_id' ) : [];

	// Categories
	$post_category	= wp_get_post_terms( $post_id, 'awpha_bookmarks_category', array( 'fields' => 'ids' ) );
	
	$content		= get_post($post_id)->post_content;
	$content 		= str_replace(']]>', ']]&gt;', $content);

} else {
	// POST DOESN'T EXISTS 
	$title 			= (!empty($get_title)) ? $get_title : '';
	$url 			= (!empty($get_url)) ? $get_url : '';
	$slug			= (!empty($get_title)) ? sanitize_title( $title ) : '';
	$content		= (!empty($get_selection)) ? $get_selection : '';

	$post_id 		= '';
	$favicon_url	= awpha_bookmarks_favicon($url,'','');
	$og_image_url	= (!empty(awpha_bookmarks_og_image_url($url))) ? awpha_bookmarks_og_image_url($url) : '';
	$post_tags_name	= [];
	$post_category	= [];
}

// Get all Categories
$all_category = get_terms( array(
	'taxonomy' => 'awpha_bookmarks_category', //category
	'hide_empty' => false
) );

// Get all Tags
$all_tags = get_terms( array(
	'taxonomy' => 'awpha_bookmarks_tag', //post_tag
	'hide_empty' => false,
	//'fields' => 'name',	// only return names
) );
$all_tags = array_column( $all_tags, 'name' );

// END Form VARs

// Visual

$btn_label_publish = ($post_exists) ? __('Update','awpha_bookmarks') : __('Publish','awpha_bookmarks');



?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<title><?php bloginfo( 'name' ); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<?php do_action( 'admin_print_scripts' ); ?>
	<?php do_action('admin_head'); ?>
	<!-- Pico CSS -->
	<link 	rel='stylesheet' 
			id='pico-css' 
			href='<?php echo AWPHA_BOOKMARKS_PLUGIN_URL; ?>public/css/pico.fluid.classless.css' 
			media='all' 
			/>
	<!-- Include Choices -->
	<link 	rel='stylesheet' 
			id='choices-css' 
			href='<?php echo AWPHA_BOOKMARKS_PLUGIN_URL; ?>public/css/choices.css'
			media='all' 
			/>
	<script id='choices-js' src='<?php echo AWPHA_BOOKMARKS_PLUGIN_URL; ?>public/js/choices.min.js'></script>
	<!-- Include Tagify -->
	<link 	rel='stylesheet' 
			id='tagify-css' 
			href='<?php echo AWPHA_BOOKMARKS_PLUGIN_URL; ?>public/css/tagify.css' 
			media='all' 
			/>
	<script id='tagify-js' src='<?php echo AWPHA_BOOKMARKS_PLUGIN_URL; ?>public/js/tagify.min.js'></script>
</head>
<body>
	<main>
		<?php if ( $postTitleError != '' ) { ?>
			<span class="error"><?php echo $postTitleError; ?></span>
			<div class="clearfix"></div>
		<?php } ?>

		<form action="" method="POST">

			<div hidden>
				<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
				<input type="hidden" name="post_type" id="post_type" value="<?php echo AWPHA_BOOKMARKS_CPT_SLUG; ?>"/>
				<input type="hidden" id="post_id" name="post_id" value="<?php echo $post_id; ?>" />
				<input type="hidden" name="submitted" id="submitted" value="true" />
			</div>

			<div>
				<label for="title"><?php _e('Title','awpha_bookmarks'); ?></label>
				<input type="text" name="title" id="title" value="<?php echo $title; ?>" required/>
			</div>

			<div>
				<label for="url"><?php _e('Link URL','awpha_bookmarks'); ?></label>
				<input type="text" name="url" id="url" value="<?php echo esc_url( $url ); ?>" required/>
				<?php if ($post_exists && !empty($url_previous) && $url_previous !== $url ) { ?>
					<small style="padding-left:1rem; color:var(--b-link);"><?php echo esc_url( $url_previous); ?></small>
				<?php } ?>
			</div>
			
			<?php if ( !empty($all_category) ) { ?>

			<div id="row-categories">
				<label for="categories"><?php _e('Category','awpha_bookmarks'); ?></label>

				<select multiple name="categories[]" id="categories">
					<?php //<options>
						$select_category_options = awpha_bookmarks_select_category_options($all_category, $post_category);
						echo $select_category_options;
					?>
				</select>
			</div>

			<?php } ?>

			<div id="row-tags">
				<label for="tags"><?php _e('Tags','awpha_bookmarks'); ?></label>
				<input 	type="text" 
						id="tags" 
						name="tags" 
						value="<?php echo implode(', ', $post_tags_name); ?>"
						data-whitelist="<?php echo implode(',', $all_tags); ?>"
						>
			</div>

			<div>
				<label for="content"><?php _e('Content','awpha_bookmarks'); ?></label>
				<textarea name="content" id="content"><?php echo esc_textarea( $content ); ?></textarea>
			</div>

			<div style="padding-left:50%;">
				<input type="submit" name="publish" id="publish" value="<?php echo $btn_label_publish; ?>" />

			</div>

			<label for="details"><?php _e('Show details','awpha_bookmarks') ?></label>
			<input type="checkbox" id="details" name="details" role="switch">

			<div class="details">
				<div id="row-slug">
					<label for="slug"><?php _e('Slug','awpha_bookmarks'); ?></label>
					<input type="text" name="slug" id="slug" value="<?php echo $slug; ?>" />
				</div>

				<div id="row-ogimage">
					<label for="og-image-url"><?php _e('og:image URL','awpha_bookmarks'); ?></label>
					<input type="text" name="og-image-url" id="og-image-url" value="<?php echo $og_image_url; ?>" />
				</div>

				<div id="row-favicon">
					<label for="favicon-url"><?php _e('Favicon URL','awpha_bookmarks'); ?></label>
					<input type="text" name="favicon-url" id="favicon-url" value="<?php echo $favicon_url; ?>" />
				</div>
			</div>
			
		</form>
		
		<style>
			label[for='details'] {
				margin-top:-4rem;
				padding: 1em 3em 0;
			}

			.details {
				margin-top:2rem;
				display: none;
			}
			#details {
				position: absolute;
    			margin-top: -1.625em;
			}
			#details:checked ~ .details {
				display: block;
			}
		</style>

		<script>
			// Start Choices - Categories - select multiple option 
			const select_categories = document.getElementById('categories');
			const select_categories_choices = new Choices(select_categories);

			// Start Tagify - Tags - input text
			var input_tags = document.getElementById('tags');
			var input_tags_tagify = new Tagify(input_tags, {
				dropdown : {
					enabled			: 1,				// show the dropdown immediately on focus
					maxItems		: 5,
					position		: "text",			// place the dropdown near the typed text
					closeOnSelect	: true,				// close the dropdown open after selecting a suggestion
					highlightFirst	: true				// autoselect first
				}
			});
		</script>
	</main>

<?php
	do_action('admin_footer');
	do_action('admin_print_footer_scripts');
?>
</body>
</html>
