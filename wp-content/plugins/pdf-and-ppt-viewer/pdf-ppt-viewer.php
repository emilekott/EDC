<?php

/*
Plugin Name: PDF and PPT Viewer
Plugin URI: http://vladimir-k.blogspot.com/2009/09/pdf-and-ppt-viewer-wordpress-plugin-for.html
Description: This plugin helps you embed PDF documents and Power Point presentations to your posts quickly and easily. No Flash required, pure JavaScript!
Author: Vladimir Kadalashvili
Version: 0.0.1
Author URI: http://vladimir-k.blogspot.com
*/


function pdfppt_root() {
	return get_bloginfo('url').'/wp-content/plugins/pdf-ppt-viewer';
}

function pdfppt_get_url_filetype($file) {
	$path = parse_url($file, PHP_URL_PATH);
	$parts = explode('.', $path);
	$ext = $parts[count($parts) - 1];
	return $ext;
}


function pdfppt_is_ppt_ext($ext) {
	return 'ppt' == $ext || 'pptx' == $ext;
}

function pdfppt_has_curl() {
	return false;
	//curl cause some problems
	return function_exists('curl_init');
}


function pdfppt_check_url($url) {

}


function pdfppt_our_mime($mime_type) {
	return ('application/pdf' == $mime_type) || ('application/vnd.ms-powerpoint' == $mime_type);
}


function pdfppt_is_applicable_ext($file) {
	$ext = pdfppt_get_url_filetype($file);
    return ('pdf' == $ext) || (pdfppt_is_ppt_ext($ext));	
}





function pdfppt_is_apllicable($file) {
	if (is_string($file)) {
		if (pdfppt_has_curl()) {
			return pdfppt_check_url($file);
		}
		else {
			return pdfppt_is_applicable_ext($file);
		}
	}
	else if (is_array($file)) {
		$mime_type = $file['post_mime_type'];

		if (pdfppt_our_mime($mime_type)) {
			return true;
		}
		else {
			return false;
		}
	}
	
	return false;
}

function pdfppt_file_html($href, $title) {
	$icon = 'icon_pdf.gif';
	
	if (pdfppt_is_ppt_ext(pdfppt_get_url_filetype($href))) {
		$icon = 'icon_ppt.gif';
	}
	
	$root = pdfppt_root();
    return "<a class='pdfppt-link' href='$href' title='$title'><img src='$root/$icon' alt='$title' /></a><br />";	
}

function pdfppt_send_to_editor($html, $href, $title) {

    if (pdfppt_is_apllicable($href)) {
    	$html = pdfppt_file_html($href, $title);
    }
  
    return $html;
}






function pdfppt_print_scripts() {
	$width = (int) get_option('pdfppt_width');
	$height = (int) get_option('pdfppt_height');
	
	if (!$width) {
		$width = 400;
	}
	
	if (!$height) {
		$height = 500;
	}
?>
<script type="text/javascript">
//<![CDATA[
var pdfpptWidth = <?php echo $width; ?>;
var pdfpptHeight = <?php echo $height ?>;
//]]>
</script>
<?php
	if (!is_admin()) {
        wp_enqueue_script('jquery');
        $root = pdfppt_root();
        wp_enqueue_script('pdfppt_settings', "$root/pdfppt-settings.js.php");
        wp_enqueue_script('pdfppt_renderer', "$root/pdfppt-renderer.js", array('jquery', 'pdfppt_settings'));
	}
}


function pdfppt_media_send_to_editor($html, $send_id, $attachment) {
    $post = (array) get_post($send_id);
  
    if ($post['guid'] != $attachment['url']) {
    	return $html;
    }
    
    if (pdfppt_is_apllicable($post)) {
    	$html =  pdfppt_file_html($post['guid'], $post['title']);
    }
    
    return $html;
}

function pdfppt_empty() {
	
}

function pdfppt_input_field($val, $name) {
	?>
	<input type="text" class="small-text" value="<?php echo $val; ?>" name="<?php echo $name ?>" id="<?php echo $name; ?>" />&nbsp;<span class="description">px</span>
	<?	
}

function pdfppt_width_input_field() {
	pdfppt_input_field(get_option('pdfppt_width'), 'pdfppt_width');
}

function pdfppt_height_input_field() {
	pdfppt_input_field(get_option('pdfppt_height'), 'pdfppt_height');
}

function pdfppt_add_settings_sections() {
	add_settings_section('pdfppt_settings', __('PDF and PPT viewer settings'), 'pdfppt_empty', 'misc');
	add_settings_field('pdfppt_width', __('Viewer width'), 'pdfppt_width_input_field', 'misc', 'pdfppt_settings');
	add_settings_field('pdfppt_height', __('Viewer height'), 'pdfppt_height_input_field', 'misc', 'pdfppt_settings');
	register_setting('pdfppt', 'pdfppt_width', 'intval');
	register_setting('pdfppt', 'pdfppt_height', 'intval');
}

function pdfppt_valid_options($options) {
	$options['misc'][] = 'pdfppt_width';
	$options['misc'][] = 'pdfppt_height';
	return $options;
}


function pdfppt_shortcode($atts) {
    $defaults = array(
        'href' => 'http://infolab.stanford.edu/pub/papers/google.pdf',
        'width' => get_option('pdfppt_width'),
        'height' => get_option('pdfppt_height')
    );
    
    $atts = shortcode_atts($defaults, $atts);
    
    $atts['width'] = (int) $atts['width'];
    $atts['height'] = (int) $atts['height'];
    $atts['href'] = clean_url($atts['href']);
    
    return 	"<iframe class='pdf-ppt-viewer' src='http://docs.google.com/gview?url={$atts['href']}&embedded=true' style='width:{$atts['width']}px; height:{$atts['height']}px;' frameborder='0'></iframe>";
}


function pdfppt_activate() {
    add_option('pdfppt_width', '400');
    add_option('pdfppt_height', '500');	
}

function pdfppt_deactivate() {
    delete_option('pdfppt_width');
    delete_option('pdfppt_height');
}


add_filter('file_send_to_editor_url', 'pdfppt_send_to_editor', 1, 3);
add_filter('media_send_to_editor', 'pdfppt_media_send_to_editor', 1, 3);
add_filter('whitelist_options', 'pdfppt_valid_options', 1, 1);

add_action('wp_print_scripts', 'pdfppt_print_scripts');
add_action('admin_init', 'pdfppt_add_settings_sections');

add_shortcode('pdf-ppt-viewer', 'pdfppt_shortcode');

register_activation_hook(__FILE__, 'pdfppt_activate');
register_deactivation_hook(__FILE__, 'pdfppt_deactivate');
?>
