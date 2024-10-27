<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $admg_form;
$admg_form = array_merge( array(
	"_title" => "New Location",
	"_submit_to" => admg_get_new_location_link(true),
	"_submit" => "Submit",
	"_allow-delete" => false,
	"_show-shortcode" => false,
	"_update" => false,

	"id" => '',
	"name" => '',
	"description" => '',
	"slug" => '',
), $admg_form );

$admg_form = stripslashes_deep( $admg_form );

?>

<div class="admg-dashboard location-form" id="admg-dashboard">

	<header>
		<h1><?php echo apply_filters("admg_admin_title", $admg_form['_title']) ?></h1>
		<p><?php admg_push_all_notices(); ?></p>
	</header>

	<form action="<?php echo $admg_form['_submit_to'] ?>" method="post" id="location-form">

		<?php if ( $admg_form['_update'] ) {
			wp_nonce_field( 'admg_update_location_' . $admg_form['id'] ); 
		} else {
			wp_nonce_field( 'admg_create_location' ); 
		}
		?>

		<input type="hidden" name="location-id" class="location-field" value="<?php echo esc_attr($admg_form['id']) ?>">
		<input type="hidden" name="location-slug" class="location-field" value="<?php echo esc_attr($admg_form['slug']) ?>">

	    <table class="form-table" style="max-width: 800px">
		    <tbody>
		        <tr>
		        	<td>
		        		<label>Location Name</label>
		        	</td>
		        	<td>
		        		<input type="text" maxlength="200" name="location-name" class="location-field widefat" value="<?php echo esc_attr($admg_form['name']) ?>">
		        	</td>
		        </tr>
		        <tr>
		        	<td>
		        		<label>Description</label>
		        	</td>
		        	<td>
		        		<textarea name="location-description" maxlength="200" class="location-field widefat"><?php echo esc_textarea($admg_form['description']) ?></textarea>
		        	</td>
		        </tr>

		        <?php if ( $admg_form['_show-shortcode'] ) : ?>
			        <tr>
			        	<td>
			        		<label>Shortcode</label>
		        		</td>
			        	<td>
			        		<input type="text" disabled="true" value="<?php admg_shortcode( $admg_form['id'] ); ?>">
			        	</td>
			        </tr>
			    <?php endif; ?>

		        <tr>
		        	<td>
		        		<input type="submit" name="submit" class="button button-primary button-large" value="<?php echo $admg_form['_submit'] ?>">
		        	</td>
		        	<td style="text-align: right;">
		        		<?php if ( $admg_form['_allow-delete'] ) : ?>
			        		<a href="<?php esc_url(admg_delete_location_link($admg_location->id)); ?>" class="deletion text-danger">Delete Location</a>
			        	<?php endif; ?>
			        </td>
		        </tr>
		    </tbody>
		</table>
	</form>

</div>