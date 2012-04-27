<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php wp_title('|', true, 'right'); ?> <?php bloginfo('name'); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url') ?>" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-tabbox-pressplay', get_bloginfo('template_url') . '/library/tabs.js', array('jquery-ui-core','jquery-ui-tabs')); ?>
	<?php wp_head(); // for plugins ?>
	
	<?php // initialize Theme Options variables
	global $options;
	foreach ($options as $value){
		if (get_option( $value['id'] ) === FALSE){
			$$value['id'] = $value['std'];
		} else {
			$$value['id'] = get_option( $value['id'] );
		}
	} ?><style type="text/css">
	
	<?php if($pp_sidebar == "left_sidebar"){ ?>
		div#content { float:right; }
	<?php } 
	
	if($pp_width == "stretched_width"){ ?>
		div#wrapper { width:100%; }
		div#menu { width:100% }
		div#title-headers { width:100%; }
		div#content { width:65%; }
		div.post-top-meta { background-image:none; }
		div.top-post-body { height:0; }
		div#sidebar { width:29%; }
		div.widget { width:100%; }
		div#footer-container { width:100%; }
		div.archive-banner, div.archive-banner-bottom {
			background-image:none;
			background-color:#fd7416;
			border:1px outset #db5204;
		}
		div.archive-banner { border-bottom:0; }
		div.archive-banner-bottom { border-top:0; }
	<?php } 
	
	if(($pp_width == "stretched_width") && ($pp_sidebar == "right_sidebar")){ ?>
		div#sidebar { padding-right:20px; }
	<?php } elseif (($pp_width == "stretched_width") && ($pp_sidebar == "left_sidebar")){ ?>
		div#sidebar { padding-left:20px; }
	<?php } ?>
	
	<?php if($pp_header_background_url != ""){ ?>
		div#header { background-image:url('<?php echo $pp_header_background_url; ?>'); }
	<?php }
	
	if($pp_header_logo_url != ""){ ?>
		div#title-logo {
			width:80px;
			height:80px;
			float:left; 
			margin:15px -15px 20px 20px;
			background-image:url('<?php echo $pp_header_logo_url; ?>');
			background-repeat:no-repeat;
		}
	<?php }
	
	if($pp_header_title_url != ""){ ?>
		h1#site-title { display:none; }
		h4#site-blurb { display:none; }
		div#title-container {
			float:left;
			margin:15px 0 0 25px;
			width:800px;
			height:80px;
			background-image:url('<?php echo $pp_header_title_url; ?>');
			background-repeat:no-repeat;
		}
	<?php } ?></style>
	
</head>

<body>

<div id="menu">

	<div><a href="<?php bloginfo('rss2_url'); ?>"><?php _e('Subscribe', 'pressplay'); ?> &nbsp;<img src="<?php bloginfo('template_directory'); ?>/images/rss.png" alt="RSS" /></a></div>
	<ul id="page-list">
		<?php if($pp_above_header == "above_header_pages"){
			wp_list_pages('title_li=');
		} elseif($pp_above_header == "above_header_categories"){
			wp_list_categories('title_li=');
		} else { } ?>
	</ul><!-- #page-list -->
	
</div><!-- menu -->
	
<div id="header">

	<div id="title-headers">
		<div id="title-logo"></div>
		<div id="title-container">
			<h1 id="site-title"><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
			<h4 id="site-blurb"><?php bloginfo('description'); ?></h4>
		</div>
	</div>
	
</div><!-- #header -->

<div id="wrapper">
	
	<div id="navigation">
		
		<div id="right-navigation">	
			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
		</div><!-- #right-navigation -->
		
		<ul id="category-list">
			<?php if($pp_below_header == "below_header_pages"){
				wp_list_pages('title_li=');
			} elseif($pp_below_header == "below_header_categories"){
				wp_list_categories('title_li=');
			} else { } ?>		
		</ul><!-- #category-list -->
		
		<div class="divclear"></div>
		
	</div><!-- #navigation -->

<?php if (is_page_template('no-sidebar.php')){ ?>
	<div id="content" style="width:auto;">
<?php } else { ?>
	<div id="content">
<?php } ?>