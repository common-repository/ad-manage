<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

	global $admg_form; 

	$admg_form = array_merge( array(
		"_title" => "",
		"_submit_to" => admg_get_new_advert_link(true),
		"_submit" => "Submit",
		"_allow-delete" => false,
		"_show-preview" => false,
		"_allow-new" => false,
		"_update" => false,

		"id" => '',
		"type" => 'image',
		"name" => '',
		"slug" => '',
		"location" => '',
		"url" => '',
		"graphic" => '',
		"html" => '',
		"css" => '',
		"js" => '',
		"status" => ''
	), $admg_form );

?>

<div class="admg-dashboard advert-form" id="admg-dashboard">

	<form action="<?php echo $admg_form['_submit_to'] ?>" method="post" id="advert-form">

		<header>
			<h1 class="wp-heading-inline">
				<?php echo apply_filters("admg_admin_title", $admg_form['_title']); ?>
			</h1>

			<?php if ( $admg_form['_allow-new'] ) : ?>
				<a href="<?php esc_url( admg_new_advert_link() ); ?>" class="page-title-action">Add New</a>
			<?php endif; ?>

			<hr class="wp-header-end">

		</header>

		<p><?php admg_push_all_notices(); ?></p>

		<div class="metabox-holder columns-2">
			<div class="form-body"> 

				<?php if ( $admg_form['_update'] ) {
					wp_nonce_field( 'admg_update_advert_' . $admg_form['id'] ); 
				} else {
					wp_nonce_field( 'admg_create_advert' ); 
				}
				?>


		    	<input type="hidden" name="advert-id" class="advert-field" value="<?php echo esc_attr($admg_form['id']) ?>">
		    	<input type="hidden" name="advert-graphic" class="advert-field" value="<?php echo esc_attr($admg_form['graphic']) ?>">
		    	<input type="hidden" name="advert-slug" class="advert-field" value="<?php echo esc_attr($admg_form['slug']) ?>">
		    	<input type="hidden" name="advert-type" class="advert-field" value="<?php echo esc_attr($admg_form['type']) ?>">

				<div class="postbox" id="boxid">
				    <div title="Click to toggle" class="handlediv"><br></div>
					<h3 class="hndle"><span>General</span></h3>
				    <div class="inside">
				        <table class="form-table">
						    <tbody>
						        <tr>
						        	<td>
						        		<label for="advert-name">Advert Name</label>
						        		<small>Give this advert a descriptive name so administators can recognise it</small>
						        	</td>
						        	<td>
						        		<input type="text" maxlength="200" name="advert-name" class="advert-field widefat" value="<?php echo esc_attr( $admg_form['name'] ) ?>">
						        	</td>
						        </tr>

						        <tr>
						        	<td>
						        		<label>Location</label>
						        		<small>Assign this advert to a location, or <?php printf( "<a tabindex='-1' href='%s' target='_blank'>create a new one</a>", esc_url( admg_get_new_location_link() ) ); ?></small>
					        		</td>
						        	<td>
						        		<?php 
						        			$admg_locations = admg_get_locations();
						        			if ( $admg_locations ) :
						        				echo "<select name='advert-location' class='advert-field widefat'>";
						        				echo "<option value='-1'>Select a location</option>";

						        				foreach ( $admg_locations as $admg_location ) :

						        					$admg_selected = $admg_location->id == @$admg_advert->location ? " selected" : "";
						        					$admg_label = $admg_location->system ? $admg_location->name : $admg_location->name . " (custom)";

						        					printf(
						        						"<option value='%s' %s>%s</option>", 
						        						esc_attr( $admg_location->id ),
						        						esc_attr( $admg_selected ), 
						        						esc_html( $admg_label )
						        					);
						        				endforeach;

						        				echo "</select>";
						        			else : 
						        				printf( "<a href='%s' target='_blank'>Add a location</a>", esc_url( admg_get_new_location_link() ) );
						        			endif;
						        		?>
						        	</td>
						        </tr>

						    </tbody>
						</table>
				    </div>
				</div>

				<div class="postbox artwork-box" id="boxid" data-type="<?php echo $admg_form['type']; ?>">
				    <div title="Click to toggle" class="handlediv"><br></div>
					<h3 class="hndle"><span>Artwork</span></h3>

				    <div class="inside divided">
				    	
				    	<div class="main">
				    		<table class="form-table">
							    <tbody>
							        <tr>
							        	<td>
							        		<label>URL</label>
							        		<small>The URL for this advert to link to</small>
							        	</td>
							        	<td>
							        		<input type="url" name="advert-url" class="widefat advert-field" value="<?php echo esc_attr( $admg_form['url'] ) ?>">
							        	</td>
							        </tr>

							        <tr>
							        	<td>
							        		<label>Graphic</label>
							        		<small>Upload the advert's image</small>
							        	</td>
							        	<td>
							        		<button class="button media-upload" role='button' type='button'>Upload</button>
							        	</td>
							        </tr>
							    </tbody>
							</table>
						</div>
						<div class="aside">

							<h4>Preview</h4>

							<?php if ( !empty($admg_form['graphic']) ) : ?>
								<?php

									function get_size($file){
										$bytes = filesize($file);
										$s = array( 'b', 'KB', 'Mb', 'Gb' );
										$e = floor( log($bytes) / log(1024) );
										return sprintf( '%s %s', round( $bytes / pow( 1024, floor($e) ) ), $s[$e] );
									}

									$admg_media = array(
										"url" => wp_get_attachment_url( $admg_form['graphic'] ),
										"meta" => wp_get_attachment_metadata( $admg_form['graphic'] ),	
										"file_type" => str_replace( "image/", "", get_post_mime_type( $admg_form['graphic'] ) ),
										"size" => get_size( get_attached_file( $admg_form['graphic'] ) )
									);

								?>
								<div class="graphic">
									
									<?php if ( $admg_media['url'] ) : ?>
										<a href="<?php echo esc_url( $admg_form['url'] ); ?>" target="_blank">
											<img src="<?php echo esc_url( $admg_media['url'] ) ?> ">
										</a>
									<?php else : ?>
										Image not found
									<?php endif; ?>
								
								</div>
								<div class="details">
									<?php if ( $admg_media['url'] ) : ?>
										<ul>
											<li>Width: <?php echo esc_html( $admg_media['meta']['width'] ) ?> px</li>
											<li>Height: <?php echo esc_html( $admg_media['meta']['height'] ) ?> px</li>
											<li>File type: <?php echo esc_html( $admg_media['file_type'] ) ?></li>
											<li>File size: <?php echo esc_html( $admg_media['size'] ) ?></li>
										</ul>
									<?php endif; ?>
								</div>
							<?php else : ?>
								<div class="graphic"></div>
								<div class="details"></div>
							<?php endif; ?>
							
				    	</div>
				    </div>

				</div>

				<div class="postbox rule-box" id="boxid">
				    <div title="Click to toggle" class="handlediv"><br></div>
					<h3 class="hndle"><span>Display Rules</span></h3>
				    <div class="inside divided">
				    	
				    	<div class="aside">
				    		<h4>Rules</h4>
				    		<p class="description">Create a set of rules to control the visibility of this ad</p>
				    	</div>
				    	<div class="main">
						    <div class="panel-description">
							    <strong>Show this advert if</strong>
						    </div>
						    <div class="advert-rules"> 
						        <?php 
						        	if ( isset($admg_form['rules']) ) {

						        		$admg_groups = array();
						        		foreach( $admg_form["rules"] as $admg_rule ) {
						        			$admg_groups[ $admg_rule->parent ][] = $admg_rule;
						        		}

						        		$i = 0;
						        		foreach( $admg_groups as $admg_group ) {

						        			echo '
						        			<table class="form-table rule-group" data-group="' . esc_attr( $admg_group[0]->parent ) . '">
												<tbody>';

						        			foreach( $admg_group as $admg_rule ) {
						        				admg_advert_rule_markup( $i, $admg_rule->id );
						        				$i ++;
						        			}

						        			echo '
						        				</tbody>
											</table>';

						        		}
						        	}
						        ?>
							</div>
							<button type="button" role="button" class="button group-add">add rule group</button>
						</div>
						
				    </div>
				</div>

			</div>
			<div class="form-aside">

				<div class="postbox" id="boxid">
				    <div title="Click to toggle" class="handlediv"><br></div>
					<h3 class="hndle"><span>Publish</span></h3>
				    <div class="inside">
				        <table class="form-table">
						    <tbody>
						    	<tr>
						    		<td style="width:50%">
							    		<label>Status</label>
							    		<small>Advert visibility</small>
						    		</td>
						    		<td style="text-align: right;">

						    			<select name="advert-status" class="advert-form select-inline">
						    				<?php 

						    					$admg_options = array('running', 'paused', 'private');
						    					foreach( $admg_options as $admg_opt ) {
						    						$admg_select = '';
						    						if ( $admg_form['status'] == $admg_opt ) 
						    							$admg_select = "selected";
						    						printf(
						        						"<option value='%s' %s>%s</option>", 
						        						esc_attr($admg_opt),
						        						esc_attr($admg_select), 
						        						esc_html(ucfirst($admg_opt))
						        					);
						    					}
						    				?>
						    			</select>
						    			
						    		</td>
						    	</tr>
						        <tr>
						        	<td>	
						        		<?php if ( $admg_form['_allow-delete'] ) : ?>
							        		<a href="<?php esc_url( admg_delete_advert_link($admg_advert->id) ); ?>" class="deletion text-danger">Delete Advert</a>
							        	<?php endif; ?>
						        	</td>
						        	<td style="text-align: right;">
						        		<input type="submit" name="submit" class="button button-primary button-large" value="<?php echo $admg_form['_submit'] ?>">
						        	</td>
						        </tr>
						    </tbody>
						</table>
				    </div>
				</div>
				
			</div>
		</div>

	</form>
</div>