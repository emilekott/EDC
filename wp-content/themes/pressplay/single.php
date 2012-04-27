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
                <p id="single-byline" class="post-byline">
                        <?php _e('By', 'pressplay'); ?> <?php the_author_posts_link(); edit_post_link(__('Edit', 'pressplay'), ' (', ')'); _e(' on ', 'pressplay'); the_time('F jS, Y'); ?>
                </p>
		<?php the_content(__("Continue reading ", 'pressplay') . the_title('', '', false) . " &rarr;"); ?>
		<?php wp_link_pages(); ?>			
	
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

<div class="pagination">
	<div class="pagination-link alignleft older-posts"><?php previous_post_link('&larr; %link') ?></div>
	<div class="pagination-link alignright newer-posts"><?php next_post_link('%link &rarr;') ?></div>
	<div class="divclear"></div>
</div>

<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>