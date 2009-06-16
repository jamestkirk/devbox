<?php 

/*
	Example "functions.php" for a theme, this will isert a custom field into new posts.
*/

$meta_boxes =
	array(
		"image" => array(
			"name" => "image",
			"type" => "text",
			"std" => "",
			"title" => "Image",
			"description" => "Using the \"<em>Add an Image</em>\" button, upload an image and paste the URL here. Images will be resized. This is the Article's main image and will automatically be sized.")
	);

function meta_boxes() {
	global $post, $meta_boxes;
	
	echo'
		<table class="widefat" cellspacing="0" id="inactive-plugins-table">
		
			<tbody class="plugins">';
	
			foreach($meta_boxes as $meta_box) {
				$meta_box_value = get_post_meta($post->ID, $pre.'_value', true);
				
				if($meta_box_value == "")
					$meta_box_value = $meta_box['std'];
				
				echo'<tr>
						<td width="100" align="center">';		
							echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
							echo'<h2>'.$meta_box['title'].'</h2>';
				echo'	</td>
						<td>';
							echo'<input type="text" name="'.$meta_box['name'].'_value" value="'.get_post_meta($post->ID, $meta_box['name'].'_value', true).'" size="40" /><br />';
							echo'<p><label for="'.$meta_box['name'].'_value">'.$meta_box['description'].'</label></p>';
				echo'	</td>
					</tr>';
			}
	
	echo'
			</tbody>
		</table>';		
}

function create_meta_box() {
	global $theme_name;
	if ( function_exists('add_meta_box') ) {
		add_meta_box( 'new-meta-boxes', 'New Post Options', 'meta_boxes', 'post', 'normal', 'high' );
	}
}

function save_postdata( $post_id ) {
	global $post, $meta_boxes;
	
	foreach($meta_boxes as $meta_box) {
		// Verify
		if ( !wp_verify_nonce( $_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
	
		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ))
				return $post_id;
		} else {
			if ( !current_user_can( 'edit_post', $post_id ))
				return $post_id;
		}
	
		$data = $_POST[$meta_box['name'].'_value'];
		
		if(get_post_meta($post_id, $meta_box['name'].'_value') == "")
			add_post_meta($post_id, $meta_box['name'].'_value', $data, true);
		elseif($data != get_post_meta($post_id, $pre.'_value', true))
			update_post_meta($post_id, $meta_box['name'].'_value', $data);
		elseif($data == "")
			delete_post_meta($post_id, $meta_box['name'].'_value', get_post_meta($post_id, $meta_box['name'].'_value', true));
	}
}
