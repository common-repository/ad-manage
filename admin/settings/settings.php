<?php 

	if ( isset( $_POST['submit'] ) ) {

		check_admin_referer( 'admg_update_settings' );
		admg_current_user_can( 'settings', 'admg_update_settings' );

		$admg_sanitized_settings = admg_validate_settings( array(
			'settings_permission' 	=> sanitize_text_field( $_POST['settings_permission'] ),
			'content_permission' 	=> sanitize_text_field( $_POST['content_permission'] ),
			'intra_post_min_length' => sanitize_text_field( $_POST['intra_post_min_length'] ),
		));

		update_option("admg_settings_permission", $admg_sanitized_settings['settings_permission']);
		update_option("admg_content_permission", $admg_sanitized_settings['content_permission']);
		update_option("admg_intra_post_min_length", $admg_sanitized_settings['intra_post_min_length']);

		admg_enqueue_notice(array(
			"msg" => "<strong>Success: </strong>Advert settings updated",
			"tier" => "updated"
		));
	}

	$admg_form = array(
		"settings_permission" 		 => get_option("admg_settings_permission", "install_plugins"),
		"content_permission" 		 => get_option("admg_content_permission", "edit_posts"),
		"admg_intra_post_min_length" => get_option("admg_intra_post_min_length", 200),
	);

?>

<div class="admg-dashboard settings-form" id="admg-dashboard">
	<form method="post" id="settings-form">

		<header>
			<h1 class="wp-heading-inline">
				<?php echo apply_filters("admg_admin_title", "Ad Manager - Plugin Settings"); ?>
			</h1>
		</header>

		<p><?php admg_push_all_notices(); ?></p>

		<?php wp_nonce_field( 'admg_update_settings' );  ?>

		<table class="form-table">

		    <tbody>
		        <tr>
		        	<td>
		        		<label for="permission">Settings Permission Level</label>
		        		<small>The permission level required to manage these settings. <br>See <a href='https://codex.wordpress.org/Roles_and_Capabilities'>Wordpress roles &amp; capabilities</a></small>
		        	</td>
		        	<td>
		        		<input type="text" maxlength="100" name="settings_permission" value="<?php echo esc_attr($admg_form['settings_permission']) ?>" placeholder="E.g. manage_options">
		        	</td>
		        </tr>

		        <tr>
		        	<td>
		        		<label for="permission">Content Permission Level</label>
		        		<small>The permission level required to create, edit, delete ads. <br>See <a href='https://codex.wordpress.org/Roles_and_Capabilities'>Wordpress roles &amp; capabilities</a></small>
		        	</td>
		        	<td>
		        		<input type="text" maxlength="100" name="content_permission" value="<?php echo esc_attr($admg_form['content_permission']) ?>" placeholder="E.g. edit_posts">
		        	</td>
		        </tr>

		        <tr>
		        	<td>
		        		<label for="permission" class="block-label">Intra-post Minimum Content Length</label>
		        		<small>The minimum number of characters required for intra-post adverts. If the character count isn't met, the advert will display at the bottom of the content.</small>
		        	</td>
		        	<td>
		        		<input type="number" maxlength="100" name="intra_post_min_length" value="<?php echo esc_attr($admg_form['admg_intra_post_min_length']) ?>" placeholder="E.g. 200">
		        	</td>
		        </tr>

		    </tbody>
		    <tfoot>
		    	<td><button type="submit" class="button button-primary" name="submit">Save Changes</button></td>
		    </tfoot>

		</table>

	</form>
</div>