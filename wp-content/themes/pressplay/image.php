<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php if (function_exists("post_class")) { // for pre-WordPress 2.7 ?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php } else { ?>
<div id="post-<?php the_ID(); ?>">
<?php } ?> 

	<div id="single-body" class="post-body">
		<h2><a href="<?php echo get_permalink($post->post_parent); ?>">&larr; <?php echo get_the_title($post->post_parent); ?></a></h2>
		<h2 id="single-title" class="post-title"><?php the_title(); ?></h2>
		<p id="single-byline" class="post-byline"></p>
		
		<div class="attachment-container">
			<a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a>
			<?php if ( !empty($post->post_excerpt) ) the_excerpt(); // this is the "caption" ?>
		</div><!-- .attachment-container -->

		<?php the_content(__("Continue reading ", 'pressplay') . the_title('', '', false) . " &rarr;"); // this provides the description, if any ?>
		<?php wp_link_pages(); ?>

		<div class="navigation">
			<div class="alignleft"><span class="gallery-cycling"><?php previous_image_link('','&larr; Previous') ?></span><div><?php previous_image_link() ?></div></div>
			<div class="alignright"><span class="gallery-cycling alignright"><?php next_image_link('','Next &rarr;') ?></span><div><?php next_image_link() ?></div></div>
		</div>
		<br class="clear" />

		<div class="post-bottom-meta post-meta">
		
            <div class="inner-post-bottom-meta">

			<?php _e('Categorized under:', 'pressplay'); ?> <?php the_category(', '); ?>. <br />
			<?php _e('Tagged with: ', 'pressplay'); if (get_the_tags() != ""){ the_tags('', ', ', ''); _e('. '); } else { _e('no tags. ', 'pressplay'); } ?>

            </div><!-- .inner-post-bottom-meta -->
		
		</div><!-- .post-bottom-meta .post-meta -->
					
<?php comments_template(); ?>

	</div><!-- #single-body .post-body -->

</div><!-- #post-ID .post_class -->

<?php endwhile; ?>

<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>