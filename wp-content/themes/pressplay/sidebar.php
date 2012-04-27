	</div><!-- #content -->

	<div id="sidebar">

		<div id="sidebar-widget-area">

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Upper Sidebar') ) : 
// Widgets placed in the "Upper Sidebar" section will appear above the default elements below. ?>
<?php endif; ?> 

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Default Sidebar') ) : 
// Widgets placed in the "Default Sidebar" section will override the default elements. ?>
		
			<div class="widget">
			
				<h3 class="widget-header"><?php _e('Recent Activity', 'pressplay'); ?></h3>
			
				<div class="tab-box">
				
					<ul class="tabnav">
						<li><a href="#recent-posts"><?php _e('Posts', 'pressplay'); ?></a></li>
						<li><a href="#recent-comments"><?php _e('Comments', 'pressplay'); ?></a></li>
					</ul>
					
					<div id="recent-posts" class="tabdiv">    
						<ul>
							<?php wp_get_archives('type=postbypost&limit=10'); ?>
						</ul>
					</div><!-- #recent-posts -->
					
					<div id="recent-comments" class="tabdiv">

<?php

global $wpdb;
$sql = "
SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url,
SUBSTRING(comment_content,1,30) AS com_excerpt FROM $wpdb->comments
LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
WHERE comment_approved = '1' AND comment_type = '' AND post_password = ''
ORDER BY comment_date_gmt DESC
LIMIT 10
";

$comments = $wpdb->get_results($sql);
$output = $pre_HTML;
$output .= "\n<ul>";

foreach ($comments as $comment) {
$output .= "\n<li><a href=\"" . $comment->comment_author_url . "\">" . strip_tags($comment->comment_author)
."</a> on " . "<a href=\"" . get_permalink($comment->ID) .
"#comment-" . $comment->comment_ID . "\" title=\"" .
strip_tags($comment->com_excerpt) . "\">" . $comment->post_title
."</a></li>";
}
if ($comment == ""){ $output .= "<li>" . __('No comments yet.', 'pressplay') . "</li>"; }
$output .= "\n</ul>";
$output .= $post_HTML;
echo $output;

?>

					</div><!-- #recent-comments -->
				
				</div><!-- .tab-box -->
			</div><!-- .widget -->
			
			<div class="widget">
			
				<h3 class="widget-header"><?php _e('Archives', 'pressplay'); ?></h3>
				
				<div class="tab-box">
				
					<ul class="tabnav">
						<li><a href="#cats"><?php _e('Categories', 'pressplay'); ?></a></li>
						<li><a href="#tags"><?php _e('Tags', 'pressplay'); ?></a></li>
						<li><a href="#dats"><?php _e('Dates', 'pressplay'); ?></a></li>
						<li><a href="#auts"><?php _e('Authors', 'pressplay'); ?></a></li>
					</ul>
					
					<div id="cats" class="tabdiv">
					
						<ul>
<?php wp_list_categories('title_li='); ?>
						</ul>
						
					</div><!-- cats -->
					
					<div id="tags" class="tabdiv">
<?php wp_tag_cloud(); ?>
					</div><!-- tags -->
					
					<div id="dats" class="tabdiv">
						<ul>
<?php wp_get_archives('show_post_count=1&echo=1'); ?>
						</ul>
					</div><!-- dats -->

					<div id="auts" class="tabdiv">
						<ul>
<?php wp_list_authors('optioncount=1'); ?>
						</ul>
					</div><!-- dats -->
				
				</div><!-- tab-box -->
			</div><!-- #widget -->
				
<?php endif; ?>

			
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Lower Sidebar') ) :
// Widgets placed in the "Lower Sidebar" section will appear below the default elements above. ?>
<?php endif; ?> 
			
			
<?php wp_meta(); // for plugins ?>

		</div><!-- #sidebar-widget-area -->