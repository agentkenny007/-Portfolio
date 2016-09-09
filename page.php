<?php get_header(); ?>
 
  <div id="container">
   <div id="content">
   
<?php the_post(); ?>
   
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	  <div class="ppwrap">

		<div class="upper"></div>
		<div class="fill"><div></div></div>
		<div class="lower"></div>
		<stop></stop>
		<div class="entry-content">
		<h1 class="entry-title"><?php the_title(); ?></h1>
<?php 		dynamic_sidebar('share_area_top');
			the_content(); 
			?><div id="clear"></div><?php
			dynamic_sidebar('share_area_bottom');
			wp_link_pages('before=<div class="page-link">' . __( 'Pages:') . '&after=</div>');
			edit_post_link( __('Edit'), '<span class="edit-link">', '</span>' ); ?>		
		</div><!– .entry-content –>

	  </div><!- .ppwrap ->
    </div><!– #post-<?php the_ID(); ?> –>
	<stop></stop><br />
	<div id="comments" class="page-comments">
		<?php comments_template('', true); ?>
	</div>

   </div><!– #content –>  
  </div><!– #container –>
 
<?php get_sidebar(); ?>
<?php get_footer(); ?>