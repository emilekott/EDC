<?php
/*
Template Name: Archives
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
	
		<?php $archives = wp_get_archives('echo=0&format=custom&before=&after=<br />');
		$archives = explode("<br />", $archives);

		foreach ($archives as $monthstring){
			$monthstring = trim($monthstring);
			$montharray = explode(">", $monthstring);
			$monthname = explode(" ", $montharray[1]);
			$month = $monthname[0];
			$years = explode("<", $monthname[1]);
			$year = $years[0];
			if ($month == "January") { $monthNum = 1; }
			elseif ($month == "February") { $monthNum = 2; }
			elseif ($month == "March") { $monthNum = 3; }
			elseif ($month == "April") { $monthNum = 4; }
			elseif ($month == "May") { $monthNum = 5; }
			elseif ($month == "June") { $monthNum = 6; }
			elseif ($month == "July") { $monthNum = 7; }
			elseif ($month == "August") { $monthNum = 8; }
			elseif ($month == "September") { $monthNum = 9; }
			elseif ($month == "October") { $monthNum = 10; }
			elseif ($month == "November") { $monthNum = 11; }
			elseif ($month == "December") { $monthNum = 12; }
			else { }
			
			if ($monthstring == ""){} else {
				$posts = get_posts('numberposts=-1&monthnum=' . $monthNum . '&year=' . $year);
				echo "<h4>" . $monthstring . "</h4>";
				echo "<ul>";
				foreach ($posts as $post){
					setup_postdata($post);
					echo "<li><a href=\"" . get_permalink() . "\">" . get_the_title() . "</a></li>";
				}
				echo "</ul>";
			}
		}
?>

	</div><!-- #single-body .post-body -->
	
</div><!-- #post-ID .post_class -->

<?php endwhile; endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>