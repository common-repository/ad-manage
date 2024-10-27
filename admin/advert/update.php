<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $admg_form;

if ( isset($_POST['submit']) ) :

	check_admin_referer( 'admg_update_advert_' . $_POST['advert-id'] );
	admg_current_user_can( 'content', 'admg_update_advert_' . $_POST['advert-id'] );

	$admg_data = array(
		"status" 	  => sanitize_text_field($_POST['advert-status']),
		"name" 		  => sanitize_text_field($_POST['advert-name']),
		"type" 		  => sanitize_text_field($_POST['advert-type']),
		"slug" 		  => sanitize_title($_POST['advert-name']),
		"location" 	  => intval($_POST['advert-location']),
		"graphic" 	  => intval($_POST['advert-graphic']),
		"url" 		  => esc_url($_POST['advert-url'])
	); 

	$admg_data = stripslashes_deep($admg_data);

	$admg_data = admg_validate_advert($admg_data);

	// Check if the incoming data was passed
	$admg_passed = true;
	$admg_fields = array_keys($admg_data);
	foreach( $admg_fields as $admg_name ) {
		if ( !isset( $_POST['advert-' . $admg_name] ) ) {
			$admg_passed = false;
		}
	}

	// Append system data
	$admg_data["modified_by"] = intval( get_current_user_id() );

	if ( $admg_passed ) {

		$admg_id = intval($_GET['advert']);

		if ( is_int( $admg_id ) ) {
			
			// Update the advert
			admg_update_advert( array(
				"data" => $admg_data,
				"id" => $admg_id
			));

			admg_dump_rules( array(
				"advert" => intval( $_POST['advert-id'] )
			));

			if ( isset( $_POST['rule'] ) ) {
				foreach( $_POST['rule'] as $admg_rule ) {
					if ( is_array($admg_rule) ) {

						// Sanitize
						$admg_rule['function'] = sanitize_text_field($admg_rule['function']);
						$admg_rule['operator'] = sanitize_text_field($admg_rule['operator']);
						$admg_rule['result'] = sanitize_text_field($admg_rule['result']);
						$admg_rule['parent'] = intval($admg_rule['parent']);

						// Validate 
						$admg_rule = admg_validate_rule($admg_rule);

						// Create
						$admg_rule_data = array_merge( $admg_rule, array("advert" => intval($_POST['advert-id']) ) );
						admg_create_rule( array(
							"data" => $admg_rule_data
						));

					}
				}
			}

			admg_enqueue_notice( array(
				"msg" => "Advert updated. <a href='" . admg_get_list_advert_link() . "'>View all adverts</a>",
				"tier" => "updated"	
			));

			// Caution user (Empty non-mandatory fields)
			admg_advert_alerts($admg_data);
		}
		
	}

endif;

$admg_advert = admg_get_advert( array(
	"id" => intval( $_GET['advert'] )
)); 
$admg_rules = admg_get_rules( array(
	"where" => "advert = $admg_advert->id"
));
$admg_location = admg_get_location( array(
	"id" => $admg_advert->id
));

$admg_form = array(
	"_title" 		=> "Edit Advert",
	"_submit_to" 	=> admg_get_edit_advert_link($admg_advert->id, true),
	"_submit" 		=> "Update",
	"_allow-delete" => true,
	"_show-preview" => true,
	"_allow-new"    => true,
	"_update" 		=> true,

	"id" 		=> $admg_advert->id,
	"status" 	=> $admg_advert->status,
	"name" 		=> $admg_advert->name,
	"slug"		=> $admg_advert->slug,
	"location" 	=> $admg_advert->location,
	"url" 		=> $admg_advert->url,
	"graphic" 	=> $admg_advert->graphic,
	"rules" 	=> $admg_rules,
	
);

?>