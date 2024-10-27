<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

global $admg_form;

/*
	Dependencies
*/

wp_enqueue_media();
wp_enqueue_script("admg_datepicker", plugins_url( '../scripts/datepicker.min.js', __FILE__ ));
wp_enqueue_style("admg_datepicker", plugins_url( '../styles/datepicker.min.css', __FILE__ ));

wp_localize_script( 'admg_datepicker', 'admg_timezone', array( "gmt_offset" => get_option('gmt_offset') ) );

/*
	Helpers
*/

// Caution user
function admg_advert_alerts($data) {

	$checks = array(
		array(
			"check" => function($data) {
				return !empty($data["graphic"]);
			},
			"notice" => "This advert doesn't have a graphic. It will not run."
		),
		array(
			"check" => function($data) {
				return !empty($data["url"]);
			},
			"notice" => "This advert doesn't have a URL to link to. Users won't be able to click on it."
		),
		array(
			"check" => function($data) {
				return $data["status"] === 'running';
			},
			"notice" => array(
				"msg" => "This advert isn't running.",
				"tier" => "notice-warning"
			)
		)
	);
	
	foreach ( $checks as $check ) {
		if ( $check["check"]($data) === false ) {

			$notice = $check["notice"];

			if ( is_string($notice) ) {
				$notice = array(
					"msg" => $notice,
					"tier" => "error"
				);
			} else if ( empty($notice["tier"]) ) {
				$notice["tier"] = "error";
			}

			admg_enqueue_notice( $notice );

		}
	}
}	

/*
	Load form
*/

include __DIR__ . "/form.rules.php";

switch( @$_REQUEST['action'] ) {
	case "update" :
		include __DIR__ . "/update.php";
		include __DIR__ . "/form.php";
	break;
	default :
		include __DIR__ . "/new.php";
		include __DIR__ . "/form.php";
	break;
}