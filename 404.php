<?php get_header(); ?>
 
  <div id="container">
   <div id="content">
	<div class="results-title">
		<div class="upper"></div>
		<div class="fill"><div></div></div>
		<div class="lower"></div>
		<div class="cont">
		 <div id="post-0" class="post error404 not-found">
		  <h1><?php _e( 'Not Found!'); ?></h1>
		   <p><?php _e( 'Oops! Looks as if the page you thought you were going to was not found, or never existed. Sorry, maybe searching will help?'); ?></p>
		   <?php get_search_form(); ?>
		 </div><!– #post-0 –>      
		</div>
	</div>
   </div><!– #content –>  
  </div><!– #container –>
 
<?php get_sidebar(); ?>
<?php get_footer(); ?>