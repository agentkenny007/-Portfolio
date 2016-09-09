<?php /* The Comments Template — with, er, comments! */ ?>  
   <div id="comments">
<?php /* Run some checks for bots and password protected posts */ ?>
<?php
 $req = get_option('require_name_email'); // Checks if fields are required.
 if ( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
  die ( 'Please do not load this page directly. Thanks!' );
 if ( ! empty($post->post_password) ) :
  if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) :
?>
    <div class="nopassword"><?php _e('This post is password protected. Enter the password to view any comments.') ?></div>
   </div><!– .comments –>
<?php
  return;
 endif;
endif;
?>
 
<?php /* See IF there are comments and do the comments stuff! */ ?>      
<?php if ( have_comments() ) : ?>
 
<?php /* Count the number of comments and trackbacks (or pings) */
$ping_count = $comment_count = 0;
foreach ( $comments as $comment )
 get_comment_type() == "comment" ? ++$comment_count : ++$ping_count;
?>
 
<?php /* IF there are comments, show the comments */ ?>
<?php if ( ! empty($comments_by_type['comment']) ) : ?>
 
    <div id="comments-list" class="comments">
     <center><h3 class="list-header"><?php printf($comment_count > 1 ? __('<span>%d</span> Comments') : __('<span>One</span> Comment'), $comment_count) ?></h3></center>
 
<?php /* If there are enough comments, build the comment navigation  */ ?>    
<?php $total_pages = get_comment_pages_count(); if ( $total_pages > 1 ) : ?>
     <div id="comments-nav-above" class="comments-navigation">
         <div class="paginated-comments-links"><?php paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') ); ?></div>
     </div><!– #comments-nav-above –> 
	 <div id="clear"></div>
<?php endif; ?>    
   
<?php /* An ordered list of our custom comments callback, custom_comments(), in functions.php   */ ?>    
     <ol>
<?php wp_list_comments('type=comment&callback=custom_comments'); ?>
     </ol>
 
<?php /* If there are enough comments, build the comment navigation */ ?>
<?php 	global $cpage; if($cpage=='') $cpage = 1;
		$total_pages = get_comment_pages_count(); if ( $total_pages > 1 ) : ?>    
     <div id="comments-nav-below" class="comments-navigation">
         <div class="paginated-comments-links"><?php paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') ); ?></div>
     </div><!– #comments-nav-below –>
	 <div id="clear"></div>
<?php endif; ?>    
     
    </div><!– #comments-list .comments –>
 
<?php endif; /* if ( $comment_count ) */ ?>

<?php /* If there are trackbacks(pings), show the trackbacks  */ ?>
<?php if ( ! empty($comments_by_type['pings']) ) : ?>
 
    <div id="trackbacks-list" class="comments">
     <center><h3 class="list-header"><?php if( $cpage > 1 ) _e('<a href="' . get_permalink() . '#trackbacks-list">'); printf($ping_count > 1 ? __('<span>%d</span> Trackbacks') : __('<span>One</span> Trackback'), $ping_count); if($cpage > 1) _e('</a>'); ?></h3></center>
 
<?php /* An ordered list of our custom trackbacks callback, custom_pings(), in functions.php   */ ?>    
     <ol>
<?php wp_list_comments('type=pings&callback=custom_pings'); ?>
     </ol>    
     
    </div><!– #trackbacks-list .comments –>  
 
 
<?php endif /* if ( $ping_count ) */ ?>
<?php endif /* if ( $comments ) */ ?>
 
<?php /* If comments are open, build the respond form */ ?>
<?php if ( 'open' == $post->comment_status ) : ?>
<stop class="respond"></stop>
<div id="respond-buffer"></div>
    <div id="respond">
               
        <div id="cancel-comment-reply"><?php cancel_comment_reply_link('abort') ?></div>
 
<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
     <p id="login-req"><?php printf(__('You must be <a href="%s" title="Log in">logged in</a> to post a comment.'),
     get_option('siteurl') . '/wp-login.php?redirect_to=' . get_permalink() ) ?></p>
 
<?php else : ?>
     <div class="formcontainer aligncenter">
      <form id="commentform" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">
		<h3><?php comment_form_title( 'Leave a Comment!', 'Respond to %s!' ); ?></h3>
<?php if ( $user_ID ) : ?>
       <p id="login"><?php printf(__('<span class="loggedin">Logged in as <a href="%1$s" title="Logged in as %2$s">%2$s</a>.</span> <span class="logout"><a href="%3$s" title="Log out of this account">Log out?</a></span>'),
        get_option('siteurl') . '/wp-admin/profile.php',
        wp_specialchars($user_identity, true),
        wp_logout_url(get_permalink()) ) ?></p>
 
<?php else : ?>

		<p id="comment-notes"><?php _e('<strong>Your email is <em>never</em> published nor shared.</strong>') ?> </p>
	   
	<div class="comment-entry info">
              <div id="form-section-author" class="form-section">
        <div class="form-input"><input id="author" name="author" type="text" value="<?php echo $comment_author ?>" title="Name here, please<?php $req ? _e(' (required)') : _e(' (optional)') ?>" placeholder="name<?php $req ? _e(' (required)') : _e(' (optional)') ?>" size="30" maxlength="20" tabindex="3" /></div>
              </div><!– #form-section-author .form-section –>
 
              <div id="form-section-email" class="form-section">
        <div class="form-input"><input id="email" name="email" type="text" value="<?php echo $comment_author_email ?>" title="Email here, please<?php $req ? _e(' (required)') : _e(' (optional)') ?>" placeholder="email<?php $req ? _e(' (required)') : _e(' (optional)') ?>" size="30" maxlength="50" tabindex="4" /></div>
              </div><!– #form-section-email .form-section –>
 
              <div id="form-section-url" class="form-section">
        <div class="form-input"><input id="url" name="url" type="text" value="<?php echo $comment_author_url ?>" title="Please provide your url, if you have one" placeholder="personal website (optional)" size="30" maxlength="50" tabindex="5" /></div>
              </div><!– #form-section-url .form-section –>
	</div>
<?php endif /* if ( $user_ID ) */ ?>
	<div class="comment-entry<?php if ( $user_ID ) _e(' logged-in') ?>">
              <div id="form-section-comment" class="form-section">
        <div class="form-textarea"><textarea id="comment" name="comment" title="Add your comment here, please" placeholder="comment away!" cols="45" rows="8" tabindex="6"></textarea></div>
              </div><!– #form-section-comment .form-section –>
	</div>
	
	<div id="clear"></div>
              <div id="form-allowed-tags" class="form-section">
               <p><span><?php _e('You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags alone:<br>') ?></span> <code><?php echo allowed_tags(); ?></code></p>
              </div>
        
		<div class="form-submit"><input id="submit" name="submit" type="submit" value="" tabindex="7" /><input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" /></div>
<?php do_action('comment_form', $post->ID); ?>
                 
 
<?php comment_id_fields(); ?>  
 
<?php /* Just … end everything. We're done here. Close it up. */ ?>  
 
      </form><!– #commentform –>          
     </div><!– .formcontainer –>
<?php endif /* if ( get_option('comment_registration') && !$user_ID ) */ ?>
    </div><!– #respond –>
<?php endif /* if ( 'open' == $post->comment_status ) */ ?>
   </div><!– #comments –>