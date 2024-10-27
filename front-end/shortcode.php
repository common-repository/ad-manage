<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function admg_do_shortcode( $atts ) {

	do_action("am_before_shortcode");
	am_print_adverts($atts['id']);
	do_action("am_after_shortcode");
    
}
add_shortcode( 'ad-manager', 'admg_do_shortcode' );