<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php if (function_exists("post_class")) { // for pre-WordPress 2.7 ?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php } else { ?>
<div id="post-<?php the_ID(); ?>">
<?php } ?> 

    <div class="post-meta post-top-meta">
		<span class="categories-above-post">
<?php the_category(', '); ?>
		</span>
		<span class="comments-above-post">
<?php comments_popup_link(__(' No comments yet', 'pressplay'), __(' 1 comment', 'pressplay'), __(' % comments', 'pressplay'), 'comments-link', __(' Comments turned off', 'pressplay')); ?>
		</span>
	</div>
	
	<div class="post-heading">
		<h2 class="post-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?> permalink">
				<?php the_title(); ?>
			</a>
		</h2>
			<p class="post-byline">
					<?php _e('By', 'pressplay'); ?> <?php the_author_posts_link(); edit_post_link(__('Edit', 'pressplay'), ' (', ')'); _e(' on ', 'pressplay'); the_time('F jS, Y'); ?>
			</p>
	</div><!-- .post-heading -->
	
	<div class="top-post-body"></div>
	
	<div class="post-body">
		<?php the_content(__("Continue reading ", 'pressplay') . the_title('', '', false) . " &rarr;"); ?>
		<?php wp_link_pages(); ?>
		<div class="divclear"></div>
	</div><!-- .post-body -->

</div><!-- #post-ID .post_class -->

<?php endwhile; ?>

<?php if(get_next_posts_link() || get_previous_posts_link()){ ?>
<div class="pagination">
<?php include(TEMPLATEPATH . '/library/page-navigation.php'); if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
</div>
<?php } ?>

<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>