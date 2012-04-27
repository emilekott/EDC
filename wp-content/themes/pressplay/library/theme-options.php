<?php

$themename = "PressPlay";
$shortname = "pp";

// Admin page options
$options = array (

	array(
		"name" => __('Layout', 'pressplay'),
		"type" => "title"),
	 
	array( "type" => "open" ),
	 
	array(
		"type" => "radio",
		"options" => array(
			"right_sidebar" => __('Right Sidebar', 'pressplay'),
			"left_sidebar" => __('Left Sidebar', 'pressplay')),
		"id" => $shortname."_sidebar",
		"std" => "right_sidebar" ),
			
	array( "type" => "close" ),
	
	array(
		"name" => __('Width', 'pressplay'),
		"type" => "title"),
	
	array( "type" => "open" ),

	array(
		"type" => "radio",
		"options" => array(
			"fixed_width" => __('Fixed', 'pressplay'),
			"stretched_width" => __('Stretched', 'pressplay')),
		"id" => $shortname."_width",
		"std" => "fixed_width" ),
			
	array( "type" => "close" ),
	
	array( "name" => __('Menu Above Header', 'pressplay'),
	"type" => "title"),
	
	array( "type" => "open" ),
	
	array(
	"type" => "radio",
	"options" => array(
		"above_header_pages" => __('Display list of Pages', 'pressplay'),
		"above_header_categories" => __('Display list of Categories', 'pressplay'),
		"above_header_nothing" => __('Display nothing', 'pressplay')),
	"id" => $shortname."_above_header",
	"std" => "above_header_pages" ),
	
	array( "type" => "close" ),
	
	array( "name" => __('Menu Below Header', 'pressplay'),
	"type" => "title"),
	
	array( "type" => "open" ),
	
	array(
	"type" => "radio",
	"options" => array(
		"below_header_pages" => __('Display list of Pages', 'pressplay'),
		"below_header_categories" => __('Display list of Categories', 'pressplay'),
		"below_header_nothing" => __('Display nothing', 'pressplay')),
	"id" => $shortname."_below_header",
	"std" => "below_header_categories" ),
	
	array( "type" => "close" ),
			
	array( "name" => __('Header', 'pressplay'),
		"type" => "title"),
	 
	array( "type" => "open" ),
		
	array(
		"name" => "",
		"desc" => __('If you\'d like to customize the header with your own background image, input the image URL below:', 'pressplay'),
		"note" => __('Will be cropped to 121 pixels high. Example:', 'pressplay'),
		"id" => $shortname."_header_background_url",
		"type" => "text",
		"std" => "" ),
		
	array(
		"name" => "",
		"desc" => __('If you\'d like to add a logo next to the site title & description, input the image URL below:', 'pressplay'),
		"note" => __('Will be cropped to 80x80 pixels. Example:', 'pressplay'),
		"id" => $shortname."_header_logo_url",
		"type" => "text",
		"std" => "" ),
		
	array(
		"name" => "",
		"desc" => __('If you\'d like to replace the site title & description with your own title image, input the image URL below:', 'pressplay'),
		"note" => __('Will be cropped to 80 pixels high. Example:', 'pressplay'),
		"id" => $shortname."_header_title_url",
		"type" => "text",
		"std" => "" ),
	 
	array( "type" => "close" )
 
);

// Admin page functionality
function pressplay_add_admin() {
 
	global $themename, $shortname, $options;
	 
	if ( $_GET['page'] == basename(__FILE__) ) {
		
		// if the page has just been saved
		if ( 'save' == $_REQUEST['action'] ) {
			
			foreach ($options as $value) {
				update_option( $value['id'], $_REQUEST[ $value['id'] ] );
			}
			
			foreach ($options as $value) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) { 
					update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
				} else { 
					delete_option( $value['id'] );
				}
			}
			
			header("Location: themes.php?page=theme-options.php&saved=true");
			die;
		
		// if the page has just been reset
		} else if( 'reset' == $_REQUEST['action'] ) {
			
			foreach ($options as $value) {
				delete_option( $value['id'] );
			}
			
			header("Location: themes.php?page=theme-options.php&reset=true");
			die;
		}	
	}
	
	// syntax: add_theme_page( page title, menu title, capability, file, functon );
	add_theme_page($themename . " Options", "" . $themename . " Options", 'edit_themes', basename(__FILE__), 'pressplay_admin_options');
}

// Admin page display -- loop through the $options and display each with these HTML rules
function pressplay_admin_options() {
 
	global $themename, $shortname, $options;
	
	// The alert messages shown after saving or reseting.
	if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>' . $themename . ' options saved.</strong></p></div>';
	if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>' . $themename . ' options have been reset to defaults.</strong></p></div>'; ?>
	
	<div class="wrap">
		
		<h2><?php echo $themename; ?> <?php _e('Options', 'pressplay'); ?></h2>
		<p><?php _e('Mix and match the options below to create a custom, professional look for your site.', 'pressplay'); ?><br /><?php _e('If you enjoy using this theme and would like to donate,', 'pressplay'); ?> <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZJAWHH6UUVRLL"><?php _e('click here', 'pressplay'); ?></a>.
	 
		<form method="post">
		<table width="100%" border="0" style="padding:20px;">
	
	<?php foreach ($options as $value) {
		switch ( $value['type'] ) {
		
			case "title": ?>
			
			<tr>
				<td valign="top" width="20%"><?php echo $value['name']; ?>:</td>
			
			<?php break; case "open": ?>
			
				<td width="80%">
			
			<?php break; case "close": ?>
				
				</td>
			</tr>
				
			<?php break; case 'text': ?>
				
					<p><?php echo $value['desc']; ?><br />
					<?php echo $value['name']; ?>
						<input style="width:400px;"
							name="<?php echo $value['id']; ?>"
							id="<?php echo $value['id']; ?>" 
							type="<?php echo $value['type']; ?>"
							value="<?php if ( get_option( $value['id'] ) != "") { // if it has a value, display the value; otherwise display standard
								echo get_option( $value['id'] );
							} else {
								echo $value['std']; } ?>" />
					<br />
					<small><?php echo $value['note']; ?></small><br />
					<img src="<?php bloginfo('template_directory'); ?>/images/examples/<?php echo $value['id']; ?>.jpg" /></p><br />
					 
			<?php break; case 'textarea': ?>
						
					<p><?php echo $value['name']; ?></p>
					<p>
						<textarea style="width:400px; height:200px;"
							name="<?php echo $value['id']; ?>"
							type="<?php echo $value['type']; ?>"
							cols=""
							rows="">
							
							<?php if ( get_option( $value['id'] ) != ""){ // if it has a value, display the value; otherwise display standard
								echo get_option( $value['id'] );
							} else {
								echo $value['std'];
							} ?>
							
						</textarea>
					</p>		 
					<p><small><?php echo $value['desc']; ?></small></p>
				
			<?php break; case "checkbox": ?>
				
				<?php if(get_option($value['id']) != ""){
					$checked = "checked=\"checked\"";
				} elseif($value['std'] == "true") {
					$checked = "checked=\"checked\"";
				} else {
					$checked = "";
				} ?>
					
					<p>
						<input
							type="checkbox"
							name="<?php echo $value['id']; ?>" 
							id="<?php echo $value['id']; ?>"
							value="true"
							<?php echo $checked; ?> />
						<?php echo $value['name']; ?>
					</p>
					<p><small><?php echo $value['desc']; ?></small></p>
					
			<?php break; case "radio": ?>
			
				<?php foreach( $value['options'] as $key => $option ){
					
					$radio_setting = get_option($value['id']);
					if($radio_setting != ''){
			    		if ($key == get_option($value['id']) ) {
							$checked = "checked=\"checked\"";
							} else {
								$checked = "";
							}
					} else {
						if($key == $value['std']){
							$checked = "checked=\"checked\"";
						} else {
							$checked = "";
						}
					}?>
					<p><input
						type="radio"
						name="<?php echo $value['id']; ?>"
						value="<?php echo $key; ?>"
						<?php echo $checked; ?> />
					<?php echo $option; ?></p>
					
				<?php } ?>
				 
			<?php break;
		}
	} ?>
		
		</table>
			
			<p class="submit">
				<input name="save" type="submit" value="<?php _e('Save changes', 'pressplay'); ?>" />
				<input type="hidden" name="action" value="save" />
			</p>
		</form>
		
		<form method="post">
			<p class="submit">
				<input name="reset" type="submit" value="<?php _e('Reset to defaults', 'pressplay'); ?>" />
				<input type="hidden" name="action" value="reset" />
			</p>
		</form>
		
	</div><!-- #wrap -->
	 
	<?php
}

add_action('admin_menu', 'pressplay_add_admin'); ?>