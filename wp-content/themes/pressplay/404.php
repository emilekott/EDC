<?php get_header(); ?>

<div>

	<div class="post-meta post-top-meta" style="text-align:left;">
		<?php _e('That annoying 404 error.', 'pressplay'); ?>
	</div>
	
	<div class="post-heading">
		<h2 class="post-title">
					<?php _e('Page not found.', 'pressplay'); ?>
		</h2>
	</div><!-- .post-heading -->
	
	<div class="top-post-body"></div>
	
	<div class="post-body">
		<p><?php _e('The page you tried to reach doesn\'t exist. Check for typos or perform a search:', 'pressplay'); ?></p>
		<form method="get" action="<?php bloginfo('home'); ?>/">
			<div class="searchinput">
				<input type="text" class="inputbox" name="s" />
				<input type="submit" class="button" value="<?php _e('Search', 'pressplay'); ?>" />
			</div>
		</form>
		<div class="divclear"></div>
	</div><!-- .post-body -->

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>