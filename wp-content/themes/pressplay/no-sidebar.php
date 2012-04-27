<?php
/*
Template Name: No Sidebar
*/
?>

<?php get_header(); ?>
	
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php if (function_exists("post_class")) { // for pre-WordPress 2.7 ?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php } else { ?>
<div id="post-<?php the_ID(); ?>">
<?php } ?> 

	<div id="single-body" class="post-body">

		<h2 id="single-title" class="post-title">
			<?php the_title(); ?>
		</h2>
		<?php edit_post_link(__('Edit', 'pressplay'), '<p>', '</p>'); ?>
		<?php the_content(__("Continue reading ", 'pressplay') . the_title('', '', false) . " &rarr;"); ?>
		<?php wp_link_pages(); ?>

<?php comments_template(); ?>
		
	</div><!-- #single-body .post-body -->
	
</div><!-- #post-ID .post_class -->

<?php endwhile; endif; ?>

	</div><!-- #content --> </div><!-- #wrapper --> <div><div><!-- because footer starts with two closing div -->
	
<?php get_footer(); ?>