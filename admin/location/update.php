<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $admg_form;

if ( isset($_POST['submit']) ) :

	check_admin_referer( 'admg_update_location_' . $_POST['location-id'] );
	admg_current_user_can( 'content', 'admg_update_location_' . $_POST['location-id'] );

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

		$admg_id = intval($_GET['location']);

		if ( is_int( $admg_id ) ) {

			admg_update_location( array(
				"data" => $admg_data,
				"id" => $admg_id
			));

			admg_enqueue_notice( array(
				"msg" => "Location updated. <a href='" . admg_get_list_location_link() . "'>View all locations</a>",
				"tier" => "updated"	
			)); 

		}
	}

endif;

$admg_location = admg_get_location( array(
	"id" => $_REQUEST['location']
)); 

$admg_form = array(
	"_title" => "Edit Location",
	"_submit_to" => admg_get_edit_location_link($admg_location->id, true),
	"_submit" => "Update",
	"_allow-delete" => true,
	"_show-shortcode" => true,
	"_update" => true,

	"id" => $admg_location->id,
	"name" => $admg_location->name,
	"slug" => $admg_location->slug,
	"description" => $admg_location->description,
);
?>