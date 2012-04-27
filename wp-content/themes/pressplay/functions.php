<?php

// Language directory initialization
function theme_init(){ load_theme_textdomain('pressplay', get_template_directory() . '/languages'); }
add_action ('init', 'theme_init');

// Theme options
include(TEMPLATEPATH . '/library/theme-options.php');

// Gallery shortcode modifications
include(TEMPLATEPATH . '/library/gallery-mod.php');

// Sidebar registry
register_sidebar(array(  
	'name' => 'Upper Sidebar',  
	'description' => 'Widgets in this section will appear ABOVE the default sidebar elements.',
    'before_widget' => '<div class="widget">',
    'after_widget' => '</div>',
	'before_title' => '<h3 class="widget-header">',  
	'after_title' => '</h3>'  
));

register_sidebar(array(  
	'name' => 'Default Sidebar',
	'description' => 'Widgets in this section will OVERRIDE the default sidebar elements.',
    'before_widget' => '<div class="widget">',
    'after_widget' => '</div>',
	'before_title' => '<h3 class="widget-header">',  
	'after_title' => '</h3>'  
));

register_sidebar(array(  
	'name' => 'Lower Sidebar',
	'description' => 'Widgets in this section will appear BELOW the default sidebar elements.',
    'before_widget' => '<div class="widget">',
    'after_widget' => '</div>',
	'before_title' => '<h3 class="widget-header">',  
	'after_title' => '</h3>' ));

register_sidebar(array(  
	'name' => 'Widget Page',  
	'description' => 'These widgets will appear in the content section of any page using the widget page template.',
    'before_widget' => '',
    'after_widget' => '',
	'before_title' => '<h3 class="footer-widget-header">',  
	'after_title' => '</h3>' ));

register_sidebar(array(  
	'name' => 'Left Footer',
	'description' => 'Widgets in this section will appear on the left of the footer.',
    'before_widget' => '',
    'after_widget' => '',
	'before_title' => '<h3 class="footer-widget-header">',  
	'after_title' => '</h3>' ));

register_sidebar(array(  
	'name' => 'Middle Footer',
	'description' => 'Widgets in this section will appear in the middle of the footer.',
    'before_widget' => '',
    'after_widget' => '',
	'before_title' => '<h3 class="footer-widget-header">',  
	'after_title' => '</h3>' ));

register_sidebar(array(  
	'name' => 'Right Footer',
	'description' => 'Widgets in this section will appear on the right of the footer.',
    'before_widget' => '',
    'after_widget' => '',
	'before_title' => '<h3 class="footer-widget-header">',  
	'after_title' => '</h3>' ));

?>