<?php
if ( ! defined( 'ABSPATH' ) ) exit;

	if ( isset($_GET['action']) ) {

		$admg_action = sanitize_text_field($_GET['action']);

		if ( in_array($admg_action, array("delete", "pause", "run") ) ) {

			$admg_file = ADMG_Plugin_Path . "admin/bulk/" . $admg_action . ".advert.php";
			if ( file_exists( $admg_file ) )
	        	include $admg_file;

		}
	}

?>

<div class="admg-dashboard" id="admg-dashboard">

	<h1 class="wp-heading-inline">
		<?php echo apply_filters("admg_admin_title", "Ad Manager"); ?>
	</h1>
	<p><?php admg_push_all_notices(); ?></p>
	<p><a href="<?php admg_new_advert_link(); ?>" class="button">New Advert</a> &nbsp; <a href="<?php admg_new_location_link(); ?>" class="button">New Location</a></p>
	</div>
	<form class="bulk-actions-form" action="">
		<input type="hidden" name="ids">
		<input type="hidden" name="page" value="ad-manager">
		<?php wp_nonce_field( 'admg_bulk_advert' ); ?>
		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
				<select name="action" id="bulk-action-selector-top">
					<option value="-1">Bulk Actions</option>
					<option value="delete">Permanently Delete</option>
					<option value="pause">Pause</option>
					<option value="run">Run</option>
				</select>
				<input type="submit" id="doaction" class="button action" value="Apply">
			</div>
		</div>
	</form>
    <table class="widefat striped wp-list-table"> 

    	<thead>
		    <tr>
		        <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
		        <th class="manage-column column-[title]" id="[title]" scope="col">Title</th>
		        <th class="manage-column column-[status]" id="[status]" scope="col">Status</th>
		        <th class="manage-column column-[location]" id="[location]" scope="col">Location</th>
		        <th class="manage-column column-[modified]" id="[modified]" scope="col">Last Updated</th>
		    </tr>
		</thead>
		 
		<tbody>
			<?php 

			$admg_adverts = admg_get_adverts();
			foreach( $admg_adverts as $admg_advert ) : 

				$admg_location = admg_get_location( array(
					"id" => intval( $admg_advert->location )
				));

				$admg_user = get_user_by( 'id', $admg_advert->modified_by );

			?>
		    <tr data-advert-id="<?php echo $admg_advert->id; ?>">
		        <th class="check-column"><input type="checkbox" value="<?php echo $admg_advert->id; ?>"></th>
		        <td class="column-[title]">
		        	<a href="<?php admg_edit_advert_link($admg_advert->id); ?>">
		        		<?php echo $admg_advert->name ?>
		        	</a>
		        	<div class="row-actions">
		        		<?php 

		        		$admg_actions = array();

		        		$admg_actions[] = array(
	        				"class" => "edit",
	        				"label" => "Edit",
	        				"link"  => admg_get_edit_advert_link($admg_advert->id)
	        			);

		        		if ( $admg_advert->status === 'running' ) {
		        			$admg_actions[] = array(
		        				"class" => "edit status pause",
		        				"label" => "Pause",
		        				"link"  => admg_get_pause_advert_link($admg_advert->id)
		        			);
		        		} else { // (Paused or Private ads)
		        			$admg_actions[] = array(
		        				"class" => "edit status run",
		        				"label" => "Run",
		        				"link"  => admg_get_run_advert_link($admg_advert->id)
		        			);
		        		}
	        			
	        			$admg_actions[] = array(
	        				"class" => "trash",
	        				"label" => "Delete Permanently",
	        				"link"  => admg_get_delete_advert_link($admg_advert->id)
	        			);

	        			admg_table_actions($admg_actions);

	        			?>
		        	</div>
		        </td>
		        <td class="column-[status]">
		        	<?php echo ucfirst($admg_advert->status) ?>
		        </td>
		        <td class="column-[location]">
		        	<?php echo $admg_location ? $admg_location->name : ''?>
		        </td>
		        <td class="column-[modified]">
		        	<div class="timestamp">
			        	<?php
			        		$admg_time = strtotime( $admg_advert->modified );
			        		echo $admg_time = date( get_option( 'date_format' ), $admg_time );
			        	?>
		        	</div>
		        	<?php 
		        		echo $admg_user ? "(" . $admg_user->user_nicename . ")" : '';
		        	?>
		        </td>
		    </tr>
			<?php endforeach; ?>
		</tbody>
    </table>
</div>