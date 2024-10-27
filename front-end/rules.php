<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class ADMG_Rules {

	private $tz;

	function __construct() {
		$t = get_the_date('T', true);
		$this->tz = apply_filters( "admg_timezone", new DateTimeZone($t) );
	}

	/*
		Public Methods
	*/

	public function get_rules( $advert_id ) {

	    global $wpdb;

	    $table_name = $wpdb->prefix . "admg_advert_rules";
	    return apply_filters("admg_fe_rules", $wpdb->get_results( "SELECT * FROM $table_name WHERE advert = $advert_id" ));

	}

	public function title( $operator, $result ) {

		global $wp_query;
		global $post;

		$handlers = array(
			"contains" => function( $post, $result ) {
				return $this->str_contains( $post->post_title, $result );
			},
			"exactly" => function( $post, $result ) {
				return $this->str_exact( $post->post_title, $result );
			},
			"does not contain" => function( $post, $result ) {
				return $this->str_not_contain( $post->post_title, $result );
			}
		);

		return $handlers[ $operator ]( $post, $result );

	}

	public function category( $operator, $result ) {

		global $wp_query;

		$handlers = array(
			"is" => function( $category, $result ) {
				return ( $category->term_id == $result );
			},
			"is not" => function( $category, $result ) {
				return ( $category->term_id != $result );
			},
			"is child of" => function( $category, $result ) {
				return ( cat_is_ancestor_of( $result, $category->term_id) or is_category($result) );
			}
		);

		if ( is_category() ) {
			return $handlers[ $operator ]( get_queried_object(), $result );
		} else {
			return false;
		}
		
	}

	public function post( $operator, $result ) {

		global $wp_query;
		global $post;

		$handlers = array(
			"is" => function( $post, $result ) {
				return $post->ID == $result;
			},
			"is not" => function( $post, $result ) {
				return $post->ID != $result;
			},
			"type" => function( $post, $result ) {
				return get_post_type( $post ) === $result;
			},
			"has tag" => function( $post, $result ) {
				return has_tag( $result, $post );
			},
			"has category" => function( $post, $result ) {
				return in_category( $result, $post );
			},
			"format" => function( $post, $result ) {
				return get_post_format( $post ) === $result;
			},
			"is child of" => function( $post, $result ) {
				return $this->post_is_child_of( $result, $post->id );
			}
		);

		return $handlers[ $operator ]( $post, $result );
		
	}

	public function content( $operator, $result ) {

		global $wp_query;
		global $post;

		$handlers = array(
			"contains" => function( $post, $result ) {
				return $this->str_contains( $post->post_content, $result );
			},
			"exactly" => function( $post, $result ) {
				return $this->str_exact( $post->post_content, $result );
			},
			"does not contain" => function( $post, $result ) {
				return $this->str_not_contain( $post->post_content, $result );
			}
		);

		return $handlers[ $operator ]( $post, $result );
		
	}

	public function date( $operator, $result ) {

		global $wp_query;

		$handlers = array(

			"is before" => function( $now, $then ) {
				return $then > $now;
			},
			"is after" => function( $now, $then ) {
				return $then < $now;
			},
		);

		$now = new DateTime('now', $this->tz);
		$then = new DateTime($result, $this->tz);

		if ( !$then ) {
			return false;
		} 

		return $handlers[ $operator ]( $now, $then );
		
	}

	/*
		Comparison Helpers
	*/

	private function str_contains( $haystack, $needle = '' ) {
		$haystack = strtolower( wp_strip_all_tags( $haystack ) );
		$needle = strtolower( $needle );
		return ( strpos( $haystack, $needle ) !== false );
	}

	private function str_exact( $haystack, $needle = '' ) {
		$haystack = strtolower( wp_strip_all_tags( $haystack ) );
		$needle = strtolower( $needle );
		return $haystack == $needle;
	}

	private function str_not_contain( $haystack, $needle = '' ) {
		return ( $this->str_contains( $haystack, $needle ) === false );
	}

	private function post_is_child_of( $ancestor, $post_id ) {

		// Source the post
		$post = get_post($post_id);

		// Source the category
		if ( is_numeric($ancestor) ) {
			// We have a category ID
			$ancestor = get_category($ancestor);
		} else {
			// Probably a slug? 
			$ancestor = get_category_by_slug($ancestor);
		}

		// Exit early if we stuffed up!
		if ( is_wp_error($ancestor) )
			return false;

		// Source the post's categories
		$posts_categories = get_the_category($post_id);

		// Loop through each of the cats and run an ancestor check
		foreach ( $posts_categories as $cat ) {

			// Check to see if the current cat is a child of $ancestor, or if it is $ancestor
			if ( cat_is_ancestor_of( $ancestor->term_id, $cat) or $ancestor->term_id === $cat->term_id ) {
				// Successful match
				return true;
			} 

		}

		// You're not my real dad.
		return false;
	}
}