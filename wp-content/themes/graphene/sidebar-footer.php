<?php
/**
 * The Sidebar for display in the content page.
 * This file closes the <div> of #content-main 
 *
 * @package WordPress
 * @subpackage Graphene
 * @since graphene 1.0.8
 */
if (((!get_option('graphene_alt_home_footerwidget') || !is_front_page()) && is_active_sidebar('footer-widget-area')) 
	|| (get_option('graphene_alt_home_footerwidget') && is_active_sidebar('home-footer-widget-area') && is_front_page())) : ?>
    
    <?php do_action('graphene_before_bottomsidebar'); ?>
    
<div id="sidebar_bottom" class="sidebar clearfix">
	<?php if (is_front_page() && get_option('graphene_alt_home_footerwidget')) : ?>
    	<?php dynamic_sidebar('home-footer-widget-area'); ?>
    <?php else : ?>
		<?php dynamic_sidebar('footer-widget-area'); ?>
    <?php endif; ?>
</div>

	<?php do_action('graphene_after_bottomsidebar'); ?>
<?php endif; ?>