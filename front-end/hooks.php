<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class ADMG_Hooks {

	function __construct() {

		// Excerpt hooks
		add_filter("the_excerpt", array($this, "before_excerpt"));
		add_filter("the_excerpt", array($this, "after_excerpt"));

		// Post hooks
		add_filter("the_content", array($this, "before_post"));
		add_filter("the_content", array($this, "after_post"));
		add_filter("the_content", array($this, "intra_post"));

	}

	/*
		Excerpt
	*/

	public function before_excerpt($content) {
		return $this->content("before-excerpt") . $content;
	}

	public function after_excerpt($content) {
		return  $content . $this->content("after-excerpt");
	}

	/*
		Post
	*/

	public function before_post($content) {
		return $this->content("before-post") . $content;
	}

	public function after_post($content) {
		return $content . $this->content("after-post");
	}

	public function intra_post($content) {

		$content_length = strlen( wp_strip_all_tags($content) );
		if ( $content_length < get_option("admg_intra_post_min_length", 200) ) {

			// Content is too short. Append this to the end of the post
			return $content . $this->content("intra-post");

		} else {

			// Split the html in half, being mindful not to break markup, paragraphs, or words

			// Create a 'DOMNodeList' of of the contents children
			$content = "<div id='am-temp-wrap'>$content</div>";
			$dom = new DOMDocument();
			$dom->preserveWhiteSpace = false;
			libxml_use_internal_errors(true);
			$dom->loadHTML($content);      
			$xpath = new DOMXPath($dom);  
			$obj = $xpath->query('//div[@id="am-temp-wrap"]'); 
			$nodes = $obj->item(0)->childNodes;
			$half = $nodes->length / 2;

			// Loop through the node list and inject adverts apx. halfway
			$i = 0;
			$markup = '';
			foreach( $nodes as $node ) {
				if ( $i === $half ) {
					$markup .= $this->content("intra-post");
				}
				$markup .= $node->ownerDocument->saveHTML($node);
				$i ++;
			}

			return $markup;
		}

	}

	/*
		Helpers
	*/

	private function location( $slug ) {
		$admg_location = admg_get_location( array("where" => "system = 1 AND slug = '$slug'") );
		return $admg_location->id;
	}

	private function content( $slug ) {
		return admg_adverts( $this->location($slug) );
	}

}
$ADMG_Hooks = new ADMG_Hooks;