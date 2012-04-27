<?php

/*
Plugin Name: Easy Embed
Plugin URI: http://wordpress.org/#
Description: Allows the embedding of any code and protects it from being modified by the WordPress editor.
Author: Alex Mansfield
Version: 1.0
Author URI: http://alexmansfield.com/
*/

function am_easy_embed($array) {
   extract(shortcode_atts(array('field' => 'custom'), $array));
   global $post;
   $html = get_post_meta($post->ID, $field, true);
   return $html;
}
add_shortcode('easyembed', 'am_easy_embed');  

?>