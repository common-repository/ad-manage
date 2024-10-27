<?php
if ( ! defined( 'ABSPATH' ) ) exit;

	if ( isset($_GET['action']) ) {

		if ( sanitize_text_field($_GET['action']) === 'delete' ) {

			$admg_file = ADMG_Plugin_Path . "admin/bulk/delete.location.php";
			if ( file_exists( $admg_file ) )
	        	include $admg_file;

		}
	}

?>

<div class="admg-dashboard" id="admg-dashboard">

	<h1><?php echo apply_filters("admg_admin_title", "Advert Locations"); ?></h1>
	<p><?php admg_push_all_notices(); ?></p>
	<p><a href="<?php admg_new_location_link(); ?>" class="button">New</a></p>
	<form class="bulk-actions-form">
		<input type="hidden" name="ids">
		<input type="hidden" name="page" value="am-locations">
		<?php wp_nonce_field( 'admg_bulk_locations' ); ?>
		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
				<select name="action" id="bulk-action-selector-top">
					<option value="-1">Bulk Actions</option>
					<option value="delete">Permanently Delete</option>
				</select>
				<input type="submit" id="doaction" class="button action" value="Apply">
			</div>
		</div>
	</form>
    <table class="widefat striped wp-list-table"> 

    	<thead>
		    <tr>
		    	<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
		        <th class="manage-column column-[name]" id="[name]" scope="col">Name</th>
		        <th class="manage-column column-[description]" id="[description]" scope="col">Description</th>
		        <th class="manage-column column-[shortcode]" id="[shortcode]" scope="col">Shortcode</th>
		    </tr>
		</thead>
		 
		<tbody>
			<?php 
			global $admg_location;
			$admg_locations = admg_get_locations(array("where" => "system = 0"));
			foreach( $admg_locations as $admg_location ) :
			?>
		    <tr data-location-id="<?php echo esc_attr($admg_location->id); ?>">
		    	<th class="check-column"><input type="checkbox" value="<?php echo esc_attr($admg_location->id); ?>"></th>
		        <td class="column-[name]">
		        	<a href="<?php admg_edit_location_link($admg_location->id); ?>"><?php esc_html(admg_location_name()) ?></a>
		        	<div class="row-actions">
		        		<?php 
		        		$admg_actions = array();

		        		$admg_actions[] = array(
	        				"class" => "edit",
	        				"label" => "Edit",
	        				"link"  => admg_get_edit_location_link($admg_location->id)
	        			);
	        			
	        			$admg_actions[] = array(
	        				"class" => "trash",
	        				"label" => "Delete Permanently",
	        				"link"  => admg_get_delete_location_link($admg_location->id)
	        			);

	        			admg_table_actions($admg_actions);
	        			?>
		        	</div>
		        </td>
		        <td class="column-[description]" style="font-style: italic">
		        	<?php esc_html( admg_location_description() ) ?>
		        </td>
		        <td class="column-[shortcode]">
		        	<?php admg_shortcode() ?>
		        </td>
		    </tr>
			<?php endforeach; ?>
		</tbody>
    </table>
</div>