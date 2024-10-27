<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $admg_form; 

if ( isset($_POST['submit']) ) :

	check_admin_referer( 'admg_create_location' );
	admg_current_user_can( 'content', 'admg_create_location' );

	$admg_data = array(
		"name" => sanitize_text_field( $_POST['location-name'] ),
		"slug" => sanitize_title( $_POST['location-name'] ),
		"description" => sanitize_text_field( $_POST['location-description'] )
	); 

	$admg_data = admg_validate_location($admg_data);

	// Check if the incoming data was passed
	$admg_passed = true;
	$admg_fields = array_keys($admg_data);
	foreach( $admg_fields as $admg_name ) {
		if ( !isset( $_POST['location-' . $admg_name] ) ) {
			$admg_passed = false;
		}
	}

	if ( $admg_passed ) {

		$admg_id = admg_create_location( array(
			"data" => $admg_data
		));

		admg_enqueue_notice( array(
			"msg" => "Location created. <a href='" . admg_get_list_location_link() . "'>View all locations</a>",
			"tier" => "updated"	
		));
	}

	$admg_location = admg_get_location( array(
		"id" => $admg_id
	));

	$admg_form = array(
		"_title" => "Edit Location",
		"_submit_to" => admg_get_edit_location_link($admg_location->id, true),
		"_submit" => "Update",
		"_allow-delete" => true,
		"_show-shortcode" => true,
		"_update" 		=> true,

		"id" => $admg_location->id,
		"name" => $admg_location->name,
		"description" => $admg_location->description,
		"slug" => $admg_location->slug
	);

else :

	$admg_form = array(
		"_title" => "New Location",
		"_submit_to" => admg_get_new_location_link(true),
		"_submit" => "Submit",
		"_allow-delete" => false
	);

endif;

?>