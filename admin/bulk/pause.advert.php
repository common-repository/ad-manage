<?php

if ( ! defined( 'ABSPATH' ) ) exit;

check_admin_referer( 'admg_bulk_advert' );
admg_current_user_can( 'content', 'admg_bulk_advert' );

global $wpdb;

$admg_ids = $_GET['ids'];
if ( !empty( $admg_ids ) ) {
	$admg_ids = urldecode($admg_ids);
	$admg_ids = explode( ",", $admg_ids );

	$admg_paused = 0;
	foreach( $admg_ids as $admg_id ) {

		$admg_id = intval($admg_id);
		if ( is_int($admg_id) ) {
			$admg_table_name = $wpdb->prefix . "admg_adverts";
			$admg_result = $wpdb->update( $admg_table_name, array("status" => "paused"), array("id" => $admg_id) );
			if ( $admg_result ) $admg_paused ++;
		}
	}

	if ( $admg_paused === count($admg_ids) ) {
		$admg_msg = sprintf( 
			"<strong>Success: </strong>%s %s paused",
			count($admg_ids),
			count($admg_ids) > 1 ? "Adverts were" : "Advert was"
			
		);
		admg_enqueue_notice(array(
			"msg" => $admg_msg,
			"tier" => "updated"
		));
	}
} else {
	admg_enqueue_notice(array(
		"msg" => 'Please select one or more adverts',
		"tier" => "warning"
	));
}

?>