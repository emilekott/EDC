<?php
/**
 * This file adds a new meta box to the Edit Post and Edit Page screens that contain
 * additional post- and page-specific options for use with the theme
 *
 * @package WordPress
 * @subpackage Graphene
 * @since Graphene 1.1
*/

/** 
 * Add the custom meta box 
*/
function graphene_add_meta_box(){
	add_meta_box( 'graphene_custom_meta', __('Graphene post-specific options','graphene'), 'graphene_custom_meta', 'post', 'normal', 'high');
	add_meta_box( 'graphene_custom_meta', __('Graphene page-specific options','graphene'), 'graphene_custom_meta', 'page', 'normal', 'high');
}
add_action('add_meta_boxes', 'graphene_add_meta_box');




/**
 * Add or update the options
*/
function graphene_save_custom_meta($post_id){
	
	/** 
	 * verify this came from our screen and with proper authorization, because
	 * save_post can be triggered at other times 
	*/
	if (isset($_POST['graphene_save_custom_meta'])){
		if ( !wp_verify_nonce($_POST['graphene_save_custom_meta'], 'graphene_save_custom_meta')) {
			  return $post_id;
		}
	} else {
		return $post_id;
	}
  
	/**
	 * verify if this is an auto save routine. If it is our form has not been submitted, 
	 * so we dont want to do anything
	*/
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
	  return $post_id;
  
	/* Check permissions */
	if ('page' == $_POST['post_type']) {
	  if (!current_user_can('edit_page', $post_id))
		return $post_id;
	} else {
	  if (!current_user_can('edit_post', $post_id))
		return $post_id;
	}

	/* OK, we're authenticated: saving the data */
	update_post_meta($post_id, '_graphene_slider_img', $_POST['graphene_slider_img']);
	update_post_meta($post_id, '_graphene_slider_imgurl', $_POST['graphene_slider_imgurl']);
	update_post_meta($post_id, '_graphene_show_addthis', $_POST['graphene_show_addthis']);
	

}
add_action('save_post', 'graphene_save_custom_meta');




/**
 * Display the custom meta box content
*/
function graphene_custom_meta($post){ 

	// Use nonce for verification
	wp_nonce_field('graphene_save_custom_meta', 'graphene_save_custom_meta');
	
	/* Get the current settings */
	$slider_img = (get_post_meta($post->ID, '_graphene_slider_img', true)) ? get_post_meta($post->ID, '_graphene_slider_img', true) : 'global';
	$slider_imgurl = (get_post_meta($post->ID, '_graphene_slider_imgurl', true)) ? get_post_meta($post->ID, '_graphene_slider_imgurl', true) : '';
	$show_addthis = (get_post_meta($post->ID, '_graphene_show_addthis', true)) ? get_post_meta($post->ID, '_graphene_show_addthis', true) : 'global';
	?>
    
	<p><?php _e("These settings will only be applied to this particular post or page you're editing. They will override the global settings set in the Graphene Options or Graphene Display options page.", 'graphene'); ?></p>
    <h4><?php _e('Slider options', 'graphene'); ?></h4>
    <table class="form-table">
    	<tr>
            <th scope="row">
                <label><?php _e('Slider image', 'graphene'); ?></label>
            </th>
            <td>
                <select name="graphene_slider_img">
                	<option value="global" <?php if ($slider_img == 'global') {echo 'selected="selected"';} ?>><?php _e('Use global setting', 'graphene'); ?></option>
                    <option value="disabled" <?php if ($slider_img == 'disabled') {echo 'selected="selected"';} ?>><?php _e("Don't show image", 'graphene'); ?></option>
                    <option value="featured_image" <?php if ($slider_img == 'featured_image') {echo 'selected="selected"';} ?>><?php _e("Featured Image", 'graphene'); ?></option>
                    <option value="post_image" <?php if ($slider_img == 'post_image') {echo 'selected="selected"';} ?>><?php _e("First image in post", 'graphene'); ?></option>
                    <option value="custom_url" <?php if ($slider_img == 'custom_url') {echo 'selected="selected"';} ?>><?php _e("Custom URL", 'graphene'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php _e('Custom slider image URL', 'graphene'); ?></label>
            </th>
            <td>
                <input type="text" name="graphene_slider_imgurl" value="<?php echo $slider_imgurl; ?>" size="60" /><br />
                <span class="description"><?php _e('Make sure you select Custom URL in the slider image option above to use this custom url.', 'graphene'); ?></span>                        
            </td>
        </tr>
    </table>
    <h4><?php _e('Display options', 'graphene'); ?></h4>
    <table class="form-table">
    	<tr>
            <th scope="row">
                <label><?php _e('AddThis Social Sharing button', 'graphene'); ?></label>
            </th>
            <td>
                <select name="graphene_show_addthis">
                	<option value="global" <?php if ($show_addthis == 'global') {echo 'selected="selected"';} ?>><?php _e('Use global setting', 'graphene'); ?></option>
                    <option value="show" <?php if ($show_addthis == 'show') {echo 'selected="selected"';} ?>><?php _e("Show button", 'graphene'); ?></option>
                    <option value="hide" <?php if ($show_addthis == 'hide') {echo 'selected="selected"';} ?>><?php _e("Hide button", 'graphene'); ?></option>
                </select>
            </td>
        </tr>
    </table>
<?php	
}


?>