<?php 

	// Header options
	delete_option('graphene_light_header');	
	delete_option('graphene_link_header_img');	
	
	// Posts Display options
	delete_option('graphene_hide_post_author');
	delete_option('graphene_hide_post_date');
	delete_option('graphene_hide_post_commentcount');
	delete_option('graphene_hide_post_cat');
	delete_option('graphene_hide_post_tags');
	delete_option('graphene_show_post_avatar');
	delete_option('graphene_show_post_author');
	
	// Text style options
	delete_option('graphene_header_title_font_type');
	delete_option('graphene_header_title_font_size');
	delete_option('graphene_header_title_font_lineheight');
	delete_option('graphene_header_title_font_weight');
	delete_option('graphene_header_title_font_style');
	
	delete_option('graphene_header_desc_font_type');
	delete_option('graphene_header_desc_font_size');
	delete_option('graphene_header_desc_font_lineheight');
	delete_option('graphene_header_desc_font_weight');
	delete_option('graphene_header_desc_font_style');
	
	delete_option('graphene_content_font_type');
	delete_option('graphene_content_font_size');
	delete_option('graphene_content_font_lineheight');
	delete_option('graphene_content_font_colour');
	
	// Bottom widget display options
	delete_option('graphene_footerwidget_column');
	delete_option('graphene_alt_footerwidget_column');
	
	// Nav menu display options
	delete_option('graphene_navmenu_child_width');
	
	// Comments display options
	delete_option('graphene_hide_allowedtags');
	
	// Miscellaneous options
	delete_option('graphene_swap_title');
	
	// Slider options
	delete_option('graphene_slider_cat');
	delete_option('graphene_slider_postcount');
	delete_option('graphene_slider_img');
	delete_option('graphene_slider_imgurl');
	delete_option('graphene_slider_height');
	delete_option('graphene_slider_speed');
	delete_option('graphene_slider_position');
	delete_option('graphene_slider_disable');
	
	// AdSense options
	delete_option('graphene_show_adsense');
	delete_option('graphene_adsense_code');
	delete_option('graphene_adsense_show_frontpage');
	
	// AddThis options
	delete_option('graphene_show_addthis');
	delete_option('graphene_show_addthis_page');
	delete_option('graphene_addthis_code');
	
	// Google Analytics options
	delete_option('graphene_show_ga');
	delete_option('graphene_ga_code');
	
	// Widget area options
	delete_option('graphene_alt_home_sidebar');
	delete_option('graphene_alt_home_footerwidget');
	
	// Footer options
	delete_option('graphene_show_cc');
	delete_option('graphene_copy_text');
	delete_option('graphene_hide_copyright');
	
	delete_option('graphene');
	switch_theme('default', 'default');
	wp_cache_flush();
	
	wp_redirect('themes.php?activated=true');
	exit();
?>