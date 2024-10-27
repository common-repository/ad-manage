<?php
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

global $wpdb;

// Delete Options
delete_option( "admg_content_permission" );
delete_option( "admg_settings_permission" );
delete_option( "admg_intra_post_min_length" );

// Drop tables
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}admg_adverts" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}admg_advert_locations" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}admg_advert_rules" );

?>