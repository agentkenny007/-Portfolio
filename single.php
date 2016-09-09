<?php get_header(); ?>
 
  <div id="container">
   <div id="content">

    <?php the_post(); ?>
    <stop></stop>
    <h1 class="entry-title aligncenter"><div><?php the_title(); ?></div></h1>
	<div class="single-title-shadow"></div>
   
    <div id="nav-above" class="navigation">
     <div class="nav-previous"><?php next_post_link( '%link', '<span class="meta-nav">&laquo;</span> %title' ); ?></div>
     <div class="nav-next"><?php previous_post_link( '%link', '%title <span class="meta-nav">&raquo;</span>' ); ?></div>
    </div><!– #nav-above –>

	<div id="clear"></div>

    
	<stop></stop>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
 
		<div class="pwrap p">
		
		<table class="ppcont">
			<tr>
			<td class="tl">
			</td>
			<td class="t">
			</td>
			<td class="tr">
			</td>
			</tr>
			<tr>
			<td class="l">
			</td>
			<td class="cont">
		 
			 <div class="entry-content">
			  <?php dynamic_sidebar('share_area_top'); ?>
			  <?php the_content(); ?>
			  <?php dynamic_sidebar('share_area_bottom'); ?>
			  <?php wp_link_pages('before=<div class="page-link"><span>Pages:</span>' . __( '') . '&after=</div>'); ?>
			 </div><!-- .entry-content -->

			</td>
			<td class="r">
			</td>
			</tr>
			<tr>
			<td class="bl">
			</td>
			<td class="b">
			</td>
			<td class="br">
			</td>
			</tr>
			<tr>
			<td colspan="3" class="shadow">
			</td>
			</tr>
		</table>

		<div class="upper"></div>
		<div class="fill"><div></div></div>
		<div class="lower"></div>

		</div>

    </div>	
   
    <div id="nav-below" class="navigation">
     <div class="nav-previous"><?php next_post_link( '%link', '<span class="meta-nav">&laquo;</span> %title' ); ?></div>
     <div class="nav-next"><?php previous_post_link( '%link', '%title <span class="meta-nav">&raquo;</span>' ); ?></div>
    </div><!– #nav-below –>    
	<div id="clear"></div>
	<stop></stop>
	<div class="utility-label-single">
    <div class="entry-utility">
	<div>
      <small><span class="small">
		This entry was posted by
		<?php the_author_posts_link(); ?> on 
		<?php the_time('l, F jS, Y'); ?> at 
		<?php the_time(); ?> and is filed 
		under <?php the_category(', '); ?>. You 
		can follow any responses to this entry 
		through <?php comments_rss_link('RSS 2.0'); ?>.
	  </span></small>
<?php 	the_tags('<span class="post-tags">', ' ', '</span>'); ?> 

<br />

<?php 	if ( ('open' == $post->comment_status) && ('open' == $post->ping_status) ) : // Comments and trackbacks open 
			printf( __( '<a class="comment-link" href="#respond" title="Post a comment">Post a comment</a> or leave a trackback at <a class="trackback-link" href="%s" title="Trackback URL for this post" rel="trackback">this URL</a>.'), get_trackback_url() ); 
		elseif ( !('open' == $post->comment_status) && ('open' == $post->ping_status) ) : // Only trackbacks open 
			printf( __( 'Comments are closed, but you can leave a trackback at <a class="trackback-link" href="%s" title="Trackback URL for this post" rel="trackback">this URL</a>.'), get_trackback_url() );
		elseif ( ('open' == $post->comment_status) && !('open' == $post->ping_status) ) : // Only comments open 
			_e( 'Trackbacks are closed, but you can <a class="comment-link" href="#respond" title="Post a comment">post a comment</a>.');
		elseif ( !('open' == $post->comment_status) && !('open' == $post->ping_status) ) : // Comments and trackbacks closed 
			_e( 'Both comments and trackbacks are currently closed for this post.');
		endif; 
	edit_post_link( __( 'Edit this post?'), "\n\t\t\t\t\t<br><span class=\"edit-link\">", "</span>" )
?>

	</div>
    </div><!– .entry-utility –> 
	</div><! - utility-label-single ->


<div class="pwrap c">

<div class="upper"></div>
<div class="fill"><div></div></div>
<div class="lower"></div>


 
<?php comments_template('', true); ?>


</div>


   </div><!– #content –>  
  </div><!– #container –>
 
<?php get_sidebar(); ?>
<?php get_footer(); ?>