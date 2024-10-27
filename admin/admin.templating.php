<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*
    Links
*/

// Advert Links

function admg_get_list_advert_link() {
    return admin_url("?page=ad-manager");
}
function admg_list_advert_link() {
    echo admg_get_list_advert_link();
}

function admg_get_new_advert_link() {
    $url = admin_url("admin.php?page=am-advert&action=new");
    return $url;
}
function admg_new_advert_link() { 
    echo admg_get_new_advert_link();
}

function admg_get_pause_advert_link($ids) {
    if ( is_array($ids) ) {
        $ids = implode(",", $ids);
        $ids = urlencode($ids);   
    }
    $url = admin_url("admin.php?page=ad-manager&action=pause&ids=" . $ids);
    $url = wp_nonce_url( $url, 'admg_bulk_advert' );
    return $url;
}
function admg_pause_advert_link($ids) { 
    echo admg_get_pause_advert_link($ids);
}

function admg_get_run_advert_link($ids) {
    if ( is_array($ids) ) {
        $ids = implode(",", $ids);
        $ids = urlencode($ids);   
    }
    $url = admin_url("admin.php?page=ad-manager&action=run&ids=" . $ids);
    $url = wp_nonce_url( $url, 'admg_bulk_advert' );
    return $url;
}
function admg_run_advert_link($ids) { 
    echo admg_get_run_advert_link($ids);
}

function admg_get_edit_advert_link($id) {
    $url = admin_url("admin.php?page=am-advert&action=update&advert=" . $id);
    return $url;
}
function admg_edit_advert_link($id) { 
    echo admg_get_edit_advert_link($id);
}

function admg_get_delete_advert_link($id) {
    $url = admin_url("admin.php?page=ad-manager&action=delete&ids=" . $id);
    $url = wp_nonce_url( $url, 'admg_bulk_advert' );
    return $url;
}
function admg_delete_advert_link($id) { 
    echo admg_get_delete_advert_link($id);
}

// Location links

function admg_get_list_location_link() {
    return admin_url("admin.php?page=am-locations");
}
function admg_list_location_link() {
    echo admg_get_list_location_link();
}

function admg_get_new_location_link() {
    $url = admin_url("admin.php?page=am-location&action=new");
    return $url;
}
function admg_new_location_link() { 
    echo admg_get_new_location_link();
}

function admg_get_edit_location_link($id) {
    $url = admin_url("admin.php?page=am-location&action=update&location=" . $id);
    return $url;
}
function admg_edit_location_link($id) { 
    echo admg_get_edit_location_link($id);
}

function admg_get_delete_location_link($id) {
    $url = admin_url("admin.php?page=am-locations&action=delete&ids=" . $id);
    $url = wp_nonce_url( $url, 'admg_bulk_locations' );
    return $url;
}
function admg_delete_location_link($id) { 
    echo admg_get_delete_location_link($id);
}

/*
    Location Properties
*/

function admg_location_id() {
    global $admg_location;
    if ( $admg_location )
        echo $admg_location->id;
}
function admg_location_name() {
    global $admg_location;
    if ( $admg_location )
        echo $admg_location->name;
}
function admg_location_description() {
    global $admg_location;
    if ( $admg_location )
        echo $admg_location->description;
}
function admg_location_slug() {
    global $admg_location;
    if ( $admg_location )
        echo $admg_location->slug;
}

/*
    Print Actions
*/
function admg_table_actions($actions) {

    $action = apply_filters("admg_table_actions", $actions);
    $markup = '';
    for( $i = 0; $i < count($actions); $i ++ ) {
        $markup .= sprintf( '<span class="%s"><a href="%s">%s</a> %s </span>',
            esc_attr($actions[$i]["class"]),
            esc_url($actions[$i]["link"]),
            esc_html($actions[$i]["label"]),
            $i < count($actions) - 1 ? "|" : ""
        );
    }
    echo $markup;

}

/*
    Shortcode Generator
*/
function admg_shortcode($id = false) {

    global $admg_location;
    
    if ( $id ) {
        $admg_location = admg_get_location(array( "id" => $id ) );
        if ( !$admg_location )
            return;
    }
    echo "[ad-manager id='" . $admg_location->id . "']";

}

?>