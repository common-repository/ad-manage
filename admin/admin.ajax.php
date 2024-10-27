<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Returns the markup for a rule row 
function admg_ajax_advert_rule_markup() {

	check_ajax_referer( 'admg_advert_nonce', '_ajax_nonce' );
	admg_current_user_can( 'content', 'admg_advert_nonce' );

	include_once __DIR__ . "/advert/form.rules.php";
	admg_advert_rule_markup( $_REQUEST['index'] );
	wp_die();

}
add_action( 'wp_ajax_admg_advert_rule_markup', 'admg_ajax_advert_rule_markup' );

// Returns post categories as JSON
function admg_ajax_categories() {

	check_ajax_referer( 'admg_advert_nonce', '_ajax_nonce' );
	admg_current_user_can( 'content', 'admg_advert_nonce' );

	$categories = get_categories(array(
	    'hide_empty' => false,
	));
	echo json_encode($categories);
	wp_die();

}
add_action( 'wp_ajax_admg_categories', 'admg_ajax_categories' );

// Returns posts as JSON
function admg_ajax_posts() {

	check_ajax_referer( 'admg_advert_nonce', '_ajax_nonce' );
	admg_current_user_can( 'content', 'admg_advert_nonce' );

	$args = array(
		"posts_per_page" => -1,
		"post_status" => "publish,private,draft"
	);
	$posts = get_posts($args);
	echo json_encode($posts);
	wp_die();

}
add_action( 'wp_ajax_admg_posts', 'admg_ajax_posts' );

// Returns pages as JSON
function admg_ajax_pages() {

	check_ajax_referer( 'admg_advert_nonce', '_ajax_nonce' );
	admg_current_user_can( 'content', 'admg_advert_nonce' );

	$args = array(
		"post_status" => "publish,private,draft"
	);
	$pages = get_pages($args);
	echo json_encode($pages);
	wp_die();

}
add_action( 'wp_ajax_admg_pages', 'admg_ajax_pages' );

// Returns post tags as JSON
function admg_ajax_tags() {

	check_ajax_referer( 'admg_advert_nonce', '_ajax_nonce' );
	admg_current_user_can( 'content', 'admg_advert_nonce' );

	$tags = get_terms( 'post_tag', array(
	    'hide_empty' => false,
	));
	echo json_encode($tags);
	wp_die();

}
add_action( 'wp_ajax_admg_tags', 'admg_ajax_tags' );

// Returns post types as JSON
function admg_ajax_post_types() {

	check_ajax_referer( 'admg_advert_nonce', '_ajax_nonce' );
	admg_current_user_can( 'content', 'admg_advert_nonce' );

	$args = array(
		"public" => true
	);
	$post_types = get_post_types( $args, 'objects' );
	echo json_encode($post_types);
	wp_die();

}
add_action( 'wp_ajax_admg_post_types', 'admg_ajax_post_types' );


?>