<?php
	if ( ! defined( 'ABSPATH' ) ) exit;

	include "rules.php";
	include "shortcode.php";
	include "hooks.php";

	function admg_adverts($admg_location_id) {
		global $wpdb;

		$admg_location_id = intval($admg_location_id);
		if ( !is_int($admg_location_id) )
			return false;

	    $table_name = $wpdb->prefix . "admg_adverts";
	    $adverts = $wpdb->get_results( "SELECT * FROM $table_name WHERE location = $admg_location_id" );

	    $markup = '';
	    $rule = new ADMG_Rules();

	    if ( $adverts ) {
	    	
	    	foreach( $adverts as $advert ) : 

	    		// Source rules
	    		$table_name = $wpdb->prefix . "admg_advert_rules";
			    $rules = $wpdb->get_results( "SELECT * FROM $table_name WHERE advert = " . $advert->id );

	    		// Validate this advert
	    		$passed = true;

	    		// Validate rule groups
	    		if ( $rules ) {

	    			// Breaks rules into their groups (::parent)
	    			$groups = array();
	        		foreach( $rules as $condition ) {
	        			$groups[ $condition->parent ][] = $condition;
	        		}

	        		$group_passed = true;

	        		foreach( $groups as $group ) {

	        			$group_passed = true;

	        			foreach( $group as $condition ) {
		    				$result = $rule->{ $condition->function }( $condition->operator, $condition->result );
		    				if ( !$result ) {
		    					$group_passed = false;
		    				}
		    			}

		    			// This group has passed all of its rules. No need to check the other groups
		    			if ( $group_passed ) {
		    				break;
		    			}

	        		}

	        		// If all the groups failed, this advert can't show
	        		if ( !$group_passed ) {
	        			$passed = false;
	        		}

	    		}

	    		// Validate - status
	    		if ( $advert->status === 'paused' || ( $advert->status === 'private' && !is_user_logged_in() ) ) {
	    			$passed = false;
	    		}

	    		// Output
	    		if ( $passed ) {

	    			if ( is_numeric( $advert->graphic ) ) {

    					$src = wp_get_attachment_url( $advert->graphic );
			    		$alt = get_post_meta( $advert->graphic, '_wp_attachment_image_alt', true );

			    		if ( $src ) {
			    			
			    			$markup .= "
					    		<figure class='am-advert am-image-advert am-advert-" . esc_attr($advert->slug) . "'>
					    			<a href='" . esc_url($advert->url) . "'>
						    			<img src='" . esc_url($src) . "' alt='" . esc_attr($alt) . "'>
					    			</a>
					    		</figure>
					    	";
			    			
			    		}
	    			}
	    			
	    		}
	    		
	    	endforeach;

	    }

	    if ( $markup !== '' ) {
	    	return apply_filters("admg_advert_markup", "<div class='am-adverts am-location-$admg_location_id'>" . $markup . "</div>");
    	}
	}
	function admg_print_adverts($admg_location_id) {

		do_action("admg_before_adverts");
		echo admg_adverts($admg_location_id);
		do_action("admg_after_adverts");

	}

	

?>