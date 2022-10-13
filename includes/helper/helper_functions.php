<?php
/*
 * Helper Functions
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Generate the <option> with selected
function awpha_bookmarks_select_category_options($tax_category, $post_category) {
	
	$output = array();
	
	// Generate <option>
	foreach( $tax_category as $category ) {


		if( $category->parent == 0 ) {
			$term_id 	= $category->term_id;
			$name 		= $category->name;
			// Check if is selected
			$selected 	= in_array($term_id, $post_category) ? 'selected' : '';

			$output[] 	= '<option value="' . esc_attr( $term_id ) . '"' . $selected . '>' .  esc_attr( $name ) . '</option>';

			foreach( $tax_category as $subcategory ) {

				if($subcategory->parent == $term_id) {
					$subcategory_term_id = $subcategory->term_id;
					$subcategory_name = $subcategory->name;
					// Check if is selected
					$subcategory_selected = in_array($subcategory_term_id, $post_category) ? 'selected' : '';
					
					$output[]	= '<option value="' . esc_attr( $subcategory_term_id ) . '"' . $subcategory_selected . '> &nbsp' . esc_html( $subcategory_name ) .'</option>';
				}
			}
		}
	}


	return implode('', $output);

}


function awpha_bookmarks_tagify_json_to_array( $value ) {

	// Because the $value is an array of json objects
	// we need this helper function.

	// First check if is not empty
	if( empty( $value ) ) {
		
		return $output = array();

	} else {

		// Remove squarebrackets
		$value = str_replace( array('[',']') , '' , $value );

		// Fix escaped double quotes
		$value = str_replace( '\"', "\"" , $value );

		// Create an array of json objects
		$value = explode(',', $value);

		// Let's transform into an array of inputed values
		// Create an array
		$value_array = array();

		// Check if is array and not empty
		if ( is_array($value) && 0 !== count($value) ) {

			foreach ($value as $value_inner) {
				$value_array[] = json_decode( $value_inner );
			}

			// Convert object to array
			// Note: function (array) not working.
			// This is the trick: create a json of the values
			// and then transform back to an array
			$value_array = json_decode(json_encode($value_array), true);

			// Create an array only with the values of the child array
			$output = array();

			foreach($value_array as $value_array_inner) {
				foreach ($value_array_inner as $key=>$val) {
				    $output[] = $val;
				}
			}

		}

		return $output;

	}

}


function awpha_bookmarks_get_upload_dir_var( $param, $subfolder = '' ) {
    $upload_dir = wp_upload_dir();
    $url = $upload_dir[ $param ];
 
    if ( $param === 'baseurl' && is_ssl() ) {
        $url = str_replace( 'http://', 'https://', $url );
    }
 
    return $url . '/' . $subfolder;
}


// Check if saved option is set and not empty
function awpha_bookmarks_check_option( $option ) {
    if( isset($option) && !empty($option)) {
        return true;
    } else {
        return false;
    }
}


// Add awpha_bookmarks to $query->post_type
function awpha_bookmarks_cpt_query_post_type( $query, $custom_post_type = AWPHA_BOOKMARKS_CPT_SLUG ) {

    // Get default $query
    $query_post_types = $query->get('post_type');

    if( !is_array($query_post_types) && !empty($query_post_types) ) {
        //not array -> is single value
        $query_post_types = [$query_post_types, $custom_post_type ];
    } elseif( !empty($query_post_types) ) {
        //is array -> multiple values
        $query_post_types = array_push($query_post_types, $custom_post_type);
    } else {
        //is empty
        $query_post_types = $custom_post_type;
    }
    return $query_post_types;

}