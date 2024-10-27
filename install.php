<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function admg_install() {

    global $wpdb;

    /*
      Create Tables
    */
      
    $charset_collate = $wpdb->get_charset_collate();
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    // Create admg_adverts
    
    $table_name = $wpdb->prefix . "admg_adverts";

    $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      created_by mediumint(9) NOT NULL,
      modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      modified_by mediumint(9) NOT NULL,
      location mediumint(9) NOT NULL,
      type varchar(30) NOT NULL,
      name tinytext NOT NULL,
      status varchar(20) NOT NULL,
      slug tinytext NOT NULL,
      graphic mediumint(9) NOT NULL,
      url varchar(55) DEFAULT '' NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";

    dbDelta( $sql );

    // Create admg_advert_locations
    
    $table_name = $wpdb->prefix . "admg_advert_locations";

    $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      name varchar(100) NOT NULL,
      slug varchar(100) NOT NULL,
      description tinytext NOT NULL,
      system boolean NOT NULL DEFAULT 0,
      PRIMARY KEY  (id)
    ) $charset_collate;";

    dbDelta( $sql );


    // Create admg_advert_rules
    
    $table_name = $wpdb->prefix . "admg_advert_rules";

    $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      advert mediumint(9) NOT NULL,
      parent mediumint(9) NOT NULL,
      function tinytext NOT NULL,
      operator varchar(20) NOT NULL,
      result tinytext NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";

    dbDelta( $sql );

    /*
      Create Default Locations
    */
    $admg_locations = array(
      "Before Excerpt",
      "After Excerpt",
      "Before Post",
      "Intra Post",
      "After Post"
    );

    foreach ( $admg_locations as $admg_location ) {

      $table_name = $wpdb->prefix . "admg_advert_locations";
      $query = $wpdb->get_row( "SELECT * FROM $table_name WHERE system = 1 AND name = '" . $admg_location . "'" );

      if ( !$query ) {

        $table_name = $wpdb->prefix . "admg_advert_locations";
        $sanitized_data = admg_validate_location( array( 
          "name" => $admg_location,
          "slug" => $admg_location,
          "system" => 1
        ));
        $wpdb->insert( $table_name, $sanitized_data );

      }

    }

    /*
      Options
    */
    add_option( "admg_content_permission", "edit_posts" );
    add_option( "admg_settings_permission", "install_plugins" );
    add_option( "admg_intra_post_min_length", 200 );

}

?>