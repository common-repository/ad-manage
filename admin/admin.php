<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*
    Dependencies
*/
include __DIR__ . "/admin.ajax.php";
include __DIR__ . "/admin.templating.php";
include __DIR__ . "/location/widget.php";

/*
    Admin Notices
*/

global $admg_notices;
$admg_notices = array();

function admg_push_all_notices() {

    global $admg_notices;

    $admg_notices = apply_filters("admg_notices", $admg_notices);

    foreach( $admg_notices as $notice ) : ?>
        <div class="notice <?php echo @$notice['tier'] ?> is-dismissible">
            <p><?php echo @$notice['msg'] ?></p>
        </div>
    <?php endforeach;

}

function admg_enqueue_notice($args) {
    global $admg_notices;
    $admg_notices[] = $args;
}

/*
    Current User Can Check
    @param: $permission accepts: "content" | "settings". Refer to the admg_settings admin page.
    @param: $nonce: The WP Nonce action name
*/
function admg_current_user_can($permission, $nonce) {

    if ( $permission === 'content' ) {
        $capability = get_option("admg_settings_capability", "manage_options");
    } else {
        $capability  = get_option("admg_content_capability", "edit_posts");
    }
    if ( !current_user_can($capability) ) {
        return wp_nonce_ays($nonce);
    }

}

/*
    Pages & Menus 
*/

add_action( 'admin_menu', 'admg_admin_page' );

function admg_admin_page() {

    $default_caps = array(
        "settings_permission" => 'install_plugins',
        "content_permission" => 'edit_posts'
    );

    $validated_caps = admg_validate_settings(array(
        "settings_permission" => sanitize_text_field( get_option("admg_settings_capability") ),
        "content_permission" => sanitize_text_field( get_option("admg_content_capability") )
    ));

    $settings_cap = $validated_caps["settings_permission"] || $default_caps["settings_permission"];
    $content_cap = $validated_caps["content_permission"] || $default_caps["content_permission"];

    add_menu_page(
        'Adverts',              // page title
        'Adverts',              // menu title
        $content_cap,           // capability
        'ad-manager',           // menu slug
        'admg_admin_page_render', // callback function
        'dashicons-megaphone',  // icon
        6                       // Position
    );
    add_submenu_page( 
        'ad-manager', 
        'Advert', 
        'New Advert',
        $content_cap, 
        'am-advert',
        'admg_advert_page_render'
    );
    add_submenu_page( 
        'ad-manager', 
        'Locations', 
        'Locations',
        $content_cap, 
        'am-locations',
        'admg_locations_page_render'
    );
    add_submenu_page( 
        'ad-manager', 
        'Settings', 
        'Settings',
        $settings_cap, 
        'am-settings',
        'admg_settings_page_render'
    );
    add_submenu_page( 
        'ad-locations', 
        'Location', 
        '',
        $content_cap, 
        'am-location',
        'admg_location_page_render'
    );
    

}

function admg_page_render($template = false) {

    global $title;

    do_action("admg_admin");

    print '<div class="wrap">';

    wp_enqueue_style("ADMG Admin", plugins_url( 'admin.css', __FILE__ ));
    wp_enqueue_script("ADMG Admin", plugins_url( 'admin.js', __FILE__ ));
    wp_localize_script( 'ADMG Admin', 'admg', array( 
        'ajax_url' => admin_url( '/admin-ajax.php'),
        'advert_nonce' => wp_create_nonce( 'admg_advert_nonce' )
    ));

    $template = __DIR__ . "/" . $template;
    if ( file_exists( $template ) )
        include $template;

    print '</div>';

}
function admg_admin_page_render() {
    admg_page_render("admin.template.php");
}
function admg_advert_page_render() {
    admg_page_render("advert/advert.php");
}
function admg_locations_page_render() {
    admg_page_render("locations/locations.template.php");
}
function admg_location_page_render() {
    admg_page_render("location/location.php");
}
function admg_settings_page_render() {
    admg_page_render("settings/settings.php");
}

/*
    Validating Functions
*/
function admg_validate_advert($data) {

    $validate = array(
        "status"      => function($val) {

            if ( !in_array($val, array('running', 'paused', 'private')) ) return;

            return $val;

        },
        "name"        => function($val) {

            if ( !is_string($val) ) return;
            if ( strlen($val) > 200 ) return;

            return $val;

        },
        "type"        => function($val) {

            if ( !is_string($val) ) return;
            if ( strlen($val) > 50 ) return;

            return $val;

        },
        "slug"        => function($val) {

            if ( !is_string($val) ) return;
            if ( strlen($val) > 200 ) return;

            return $val;

        },
        "location"    => function($val) {

            if ( !is_int($val) ) return;

            return $val;

        },
        "graphic"     => function($val) {

            if ( !is_int($val) ) return;

            return $val;

        },
        "url"         => function($val) {

            if ( !is_string($val) ) return;

            return $val;

        },
        "created_by"  => function($val) {

            if ( !is_int($val) ) return;

            return $val;

        },
        "modified_by" => function($val) {

            if ( !is_int($val) ) return;

            return $val;

        },
    ); 

    $validated_data = array();
    $props = array_keys($validate);

    foreach( $props as $prop ) {

        if ( isset($data[$prop]) ) 
            $validated_data[$prop] = $validate[$prop]( $data[$prop] );
    }

    return $validated_data;

}
function admg_validate_location($data) {

    $validate = array(
        "name"        => function($val) {

            if ( !is_string($val) ) return;
            if ( strlen($val) > 200 ) return;

            return $val;

        },
        "slug"        => function($val) {

            if ( !is_string($val) ) return;
            if ( strlen($val) > 200 ) return;

            return $val;

        },
        "description"        => function($val) {

            if ( !is_string($val) ) return;
            if ( strlen($val) > 200 ) return;

            return $val;

        },
        "system" => function($val) {

            if ( !is_int($val) ) return;

            return $val;

        },
    ); 

    $validated_data = array();
    $props = array_keys($validate);

    foreach( $props as $prop ) {

        if ( isset($data[$prop]) ) 
            $validated_data[$prop] = $validate[$prop]( $data[$prop] );
    }

    return $validated_data;

}
function admg_validate_rule($data) {

    $validate = array(
        "advert" => function($val) {

            if ( !is_int($val) ) return;

            return $val;

        },
        "parent" => function($val) {

            if ( !is_int($val) ) return;

            return $val;

        },
        "function"        => function($val) {

            if ( !is_string($val) ) return;
            if ( strlen($val) > 50 ) return;

            return $val;

        },
        "operator"        => function($val) {

            if ( !is_string($val) ) return;
            if ( strlen($val) > 20 ) return;

            return $val;

        },
        "result"        => function($val) {

            if ( !is_string($val) ) return;
            if ( strlen($val) > 500 ) return;

            return $val;

        },
    ); 

    $validated_data = array();
    $props = array_keys($validate);

    foreach( $props as $prop ) {

        if ( isset($data[$prop]) ) 
            $validated_data[$prop] = $validate[$prop]( $data[$prop] );
    }

    return $validated_data;

}

function admg_validate_settings($data) {

    $validate = array(
        "settings_permission" => function($val) {

            if ( !is_string($val) ) return;
            if ( strlen($val) > 100 ) return;

            return $val;

        },
        "content_permission" => function($val) {

            if ( !is_string($val) ) return;
            if ( strlen($val) > 100 ) return;

            return $val;

        },
        "intra_post_min_length" => function($val) {

            if ( !is_int($val) ) return;

            return $val;

        },
    ); 

    $validated_data = array();
    $props = array_keys($validate);

    foreach( $props as $prop ) {

        if ( isset($data[$prop]) ) 
            $validated_data[$prop] = $validate[$prop]( $data[$prop] );
    }

    return $validated_data;

}



/*
    Advert DB Interactions
*/

function admg_create_advert($args) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_adverts";
    $wpdb->insert( $table_name, $args['data'] );
    return $wpdb->insert_id;

}
function admg_update_advert($args) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_adverts";
    return $wpdb->update( $table_name, $args['data'], array("id" => $args['id']) );

}
function admg_delete_advert($args) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_adverts";
    return $wpdb->delete( $table_name, array("id" => $args['id']) );

}
function admg_get_advert($args) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_adverts";
    return $wpdb->get_row( "SELECT * FROM $table_name WHERE id = " . $args['id'] );

}
function admg_get_adverts($args = array("where" => "*")) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_adverts";
    if ( $args['where'] === "*" ) {
        $args['where'] = '';
    } else {
        $args['where'] = " WHERE " . $args['where'];
    }
    return $wpdb->get_results( "SELECT * FROM $table_name" . $args['where'] );

}

/*
    Location DB Interactions
*/
function admg_create_location($args) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_advert_locations";
    $wpdb->insert( $table_name, $args['data'] );
    return $wpdb->insert_id;

}
function admg_update_location($args) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_advert_locations";
    $wpdb->update( $table_name, $args['data'], array("id" => $args['id']) );

}
function admg_delete_location($args) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_advert_locations";
    return $wpdb->delete( $table_name, array("id" => $args['id']) );

}
function admg_get_location($args) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_advert_locations";
    if ( isset($args['where'] ) ) {
        return $wpdb->get_row( "SELECT * FROM $table_name WHERE " . $args["where"] );
    } else {
        return $wpdb->get_row( "SELECT * FROM $table_name WHERE id = " . $args['id'] );
    }

}
function admg_get_locations($args = array("where" => "*")) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_advert_locations";
    if ( $args['where'] === "*" ) {
        $args['where'] = '';
    } else {
        $args['where'] = "WHERE " . $args['where'];
    }
    return $wpdb->get_results( "SELECT * FROM $table_name " . $args['where'] );

}

/*
    Rule DB Interactions
*/
function admg_create_rule($args) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_advert_rules";
    $wpdb->insert( $table_name, $args['data'] );
    return $wpdb->insert_id;

}
function admg_update_rule($args) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_advert_rules";
    return $wpdb->update( $table_name, $args['data'], array("id" => $args['id']) );

}
function admg_delete_rule($args) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_advert_rules";
    return $wpdb->delete( $table_name, array("id" => $args['id']) );

}
function admg_dump_rules($args) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_advert_rules";
    $wpdb->delete( $table_name, array("advert" => $args['advert']) );

}
function admg_get_rule($args) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_advert_rules";
    return $wpdb->get_row( "SELECT * FROM $table_name WHERE id = " . $args['id'] );

}
function admg_get_rules($args = array("where" => "*")) {

    global $wpdb;

    $table_name = $wpdb->prefix . "admg_advert_rules";
    if ( $args['where'] === "*" ) {
        $args['where'] = '';
    } else {
        $args['where'] = " WHERE " . $args['where'];
    }
    return $wpdb->get_results( "SELECT * FROM $table_name" . $args['where'] );

}


?>