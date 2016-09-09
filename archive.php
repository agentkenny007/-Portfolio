<?php get_header(); ?>
 
  <div id="container">
   <div id="content">
 
    <?php the_post(); ?>
	<stop></stop>
 	<div class="results-title">
		<div class="upper"></div>
		<div class="fill"><div></div></div>
		<div class="lower"></div>
		<div class="cont">    
			<?php if ( is_day() ) : ?>
				<h1 class="page-title"><?php printf( __( 'Daily Archives: <span>%s</span>' ), get_the_time(get_option('date_format')) ) ?></h1>
			<?php elseif ( is_month() ) : ?>
				<h1 class="page-title"><?php printf( __( 'Monthly Archives: <span>%s</span>' ), get_the_time('F Y') ) ?></h1>
			<?php elseif ( is_year() ) : ?>
				<h1 class="page-title"><?php printf( __( 'Yearly Archives: <span>%s</span>' ), get_the_time('Y') ) ?></h1>
			<?php elseif ( isset($_GET['paged']) && !empty($_GET['paged']) ) : ?>
				<h1 class="page-title"><?php _e( 'Blog Archives' ) ?></h1>
			<?php endif; ?>
		</div>
	</div>
<?php rewind_posts(); ?>
   
<?php global $wp_query; $total_pages = $wp_query->max_num_pages; if ( $total_pages > 1 ) { ?>
    <div id="nav-above" class="navigation">
<?php	global $wp_rewrite;
		$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

		$pagination = array(
			'base' => @add_query_arg('page','%#%'),
			'format' => '',
			'total' => $wp_query->max_num_pages,
			'current' => $current,
			'show_all' => true,
			'type' => 'plain',
			'prev_text'    => __('&laquo; Back'),
			'next_text'    => __('More &raquo;'),
			);

		if( $wp_rewrite->using_permalinks() )
			$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

		if( !empty($wp_query->query_vars['s']) )
			$pagination['add_args'] = array( 's' => get_query_var( 's' ) );

		echo paginate_links( $pagination );
?>
    </div><!– #nav-above –>
<?php } ?>  

<stop></stop>
<?php while ( have_posts() ) : the_post(); ?>
<div class="results">
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
     <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
 
     <div class="entry-meta">
	  <span class="meta-prep meta-prep-entry-date"><?php _e('Published '); ?></span>
	  <span class="entry-date published" title="<?php the_time('Y-m-d\TH:i:sO') ?>"><?php the_time( get_option( 'date_format' ) ); ?></span>
	  <span class="meta-prep meta-prep-author"><?php _e('by '); ?></span>
      <span class="author vcard"><a class="url fn n" href="<?php echo get_author_link( false, $authordata->ID, $authordata->user_nicename ); ?>" title="<?php printf( __( 'View all posts by %s'), $authordata->display_name ); ?>"><?php the_author(); ?></a></span>
      <?php edit_post_link( __( 'Edit'), "<span class=\"meta-sep\">|</span>\n\t\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t" ) ?>
     </div><!– .entry-meta –>
     
     <div class="entry-summary">
<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&raquo;</span>' )  ); ?>
     </div><!– .entry-summary –>
 
     <div class="entry-utility">
      <span class="cat-links"><span class="icon"></span><?php echo get_the_category_list(', '); ?></span><br />
      <?php the_tags( '<span class="tag-links"><span class="icon"></span>', ", ", "</span><br />" ) ?>
      <span class="comments-link"><span class="icon"></span><?php comments_popup_link( __( 'Comment?'), __( 'Just one'), __( '% and counting') ) ?></span>
      <?php edit_post_link( __( 'Edit'), "<br /><span class=\"edit-link right\">", "</span><span class=\"clear\"></span>" ) ?>
     </div><!– #entry-utility –>
    </div><!– #post-<?php the_ID(); ?> –>
</div>
<stop></stop>
<?php endwhile; ?>  
 
<?php global $wp_query; $total_pages = $wp_query->max_num_pages; if ( $total_pages > 1 ) { ?>
    <div id="nav-below" class="navigation">
<?php 	global $wp_rewrite;
		$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

		$pagination = array(
			'base' => @add_query_arg('page','%#%'),
			'format' => '',
			'total' => $wp_query->max_num_pages,
			'current' => $current,
			'show_all' => true,
			'type' => 'plain',
			'prev_text'    => __('&laquo; Back'),
			'next_text'    => __('More &raquo;'),
			);

		if( $wp_rewrite->using_permalinks() )
			$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

		if( !empty($wp_query->query_vars['s']) )
			$pagination['add_args'] = array( 's' => get_query_var( 's' ) );

		echo paginate_links( $pagination );
?>
    </div><!– #nav-below –>
<?php } ?>  
 
   
   </div><!– #content –>  
  </div><!– #container –>
 
<?php get_sidebar(); ?>
<?php get_footer(); ?>