<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
    <title><?php
        if ( is_single() ) { single_post_title(); }      
        elseif ( is_home() || is_front_page() ) { bloginfo('name'); print ' | '; bloginfo('description'); get_page_number(); }
        elseif ( is_page() ) { single_post_title(''); }
        elseif ( is_search() ) { bloginfo('name'); print ' | Search results for ' . wp_specialchars($s); get_page_number(); }
        elseif ( is_404() ) { bloginfo('name'); print ' | Not Found'; }
        else { bloginfo('name'); wp_title('|'); get_page_number(); }
    ?></title>
 
 <meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

 <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />

 <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
 
 <?php wp_head(); ?>
 
 <link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url'); ?>" title="<?php printf( __( '%s latest posts'), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
 <link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments'), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
 <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
 <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/js/jqtransformplugin/jqtransform.css" />

 
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
 <script src="<?php echo get_template_directory_uri(); ?>/js/jQuery.js"></script>
 <script src="<?php echo get_template_directory_uri(); ?>/js/scripts.js"></script>
 <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.easing.1.3.js"></script>
 <script type="text/javascript"><?php echo 'var blog_url = \''.get_bloginfo('url').'\';'; ?></script>
</head>
<body>
<div id="wrapper" class="hfeed">
	<div id="main">