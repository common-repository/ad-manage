<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Adds ADMG_Location_Widget widget.
 */
class ADMG_Location_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'admg_location_widget', // Base ID
			'Adverts', // Name
			array( 
				'description' => 'Displays adverts from the Ad Manager' 
			) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		if ( !empty( $instance['location'] ) ) {
			echo $args['before_widget'];
				echo admg_adverts( $instance['location'] );
			echo $args['after_widget'];
		}

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		global $wpdb;

		$admg_location = ! empty( $instance['location'] ) ? $instance['location'] : -1;
		?>
		<p>
			<label>Location</label> 
			<?php

			$table_name = $wpdb->prefix . "admg_advert_locations";
		    $admg_locations = $wpdb->get_results( "SELECT * FROM $table_name WHERE system = 0" );

			if ( $admg_locations ) :
				echo "<select name='" . esc_attr( $this->get_field_name( 'location' ) ) . "' class='widefat'>";
				foreach ( $admg_locations as $item ) :
					$selected = $item->id == $admg_location ? "selected" : "";
					printf(
						"<option value='%s' %s>%s</option>", 
						esc_attr($item->id),
						esc_attr($selected), 
						esc_html($item->name)
					);
				endforeach;
				echo "</select>";
				echo "<div><small>Choose from one of your custom locations or <a href='" . esc_url(admg_get_new_location_link()) . "' target='_blank'>create a new one</a></small></div>";

			else : 

				printf( "<a href='%s' target='_blank'>Add a location</a>", esc_url(admg_get_new_location_link()) );

			endif;
			?>
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['location'] = ( ! empty( $new_instance['location'] ) && is_int($new_instance['location']) ) ? strip_tags( intval( $new_instance['location'] ) ) : '';

		return $instance;
	}

}

function admg_register_widget() {
	register_widget( 'ADMG_Location_Widget' );
}
add_action( 'widgets_init', "admg_register_widget" );


?>