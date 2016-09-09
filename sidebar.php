<?php if ( is_sidebar_active('sidebar_left') ) : ?>
  <div id="left-sidebar" class="prime-area">
	<div class="upper"></div>
	<div class="deats">
		<?php
		/* When we call the dynamic_sidebar() function, it'll spit out
		 * the widgets for that widget area. If it instead returns false,
		 * then the sidebar simply doesn't exist, so we'll hard-code in
		 * some default sidebar stuff just in case.
		 */
		
		if (!dynamic_sidebar('sidebar_left')) { 
			if(function_exists('the_widget')) {  // only in wp 2.8+
				
				$prime_area_args = array (
					'before_widget' => '<div class="slice">',
					'after_widget' => '<div id="clear"></div></div>',
					'before_title' => '<h2 class="title">',
					'after_title' => '<span class="sep"></span></h2>'
				);
				
				the_widget('WP_Widget_Search', '', $prime_area_args);
				the_widget('NavigateWidget', '', $prime_area_args);
			//	the_widget('Follow_Widget', '', $prime_area_args);
			//	the_widget('Share_Widget', '', $prime_area_args);
				the_widget('LoginWidget', '', $prime_area_args);
			}

		} // end sidebar_left
		?>
	</div>
	<div class="lower"></div>
  </div><!– #left-sidebar –>
<?php endif; ?>  
 
<?php if ( is_sidebar_active('sidebar_right') ) : ?>
  <div id="right-sidebar" class="widget-area">
   <ul class="xoxo">   
    <?php 
	/* * *
	 * Call the dynamic_sidebar() function, same as sidebar left.
	 */
	 
	if (!dynamic_sidebar('sidebar_right')) { 
		if(function_exists('the_widget')) {  // only in wp 2.8+
			
			$widget_area_args = array (
				'before_widget' => widget_area('before'),
				'after_widget' => widget_area('after'),
				'before_title' => widget_area('title_before'),
				'after_title' => widget_area('title_after')
			);
			
			the_widget('WP_Widget_Pages', '', $widget_area_args);
			the_widget('WP_Widget_Categories', 'dropdown=1', $widget_area_args);
			the_widget('WP_Widget_Calendar', '', $widget_area_args);
			the_widget('WP_Widget_Recent_Comments', 'number=10', $widget_area_args);
			the_widget('WP_Widget_Archives', 'dropdown=1', $widget_area_args);
			the_widget('WP_Widget_Text', 'text=Just plain text and no title.', $widget_area_args);
			the_widget('WP_Widget_Links', '', $widget_area_args);
			the_widget('WP_Widget_Recent_Posts', 'number=7', $widget_area_args);
			the_widget('WP_Widget_Tag_Cloud', '', $widget_area_args);
			the_widget('WP_Widget_RSS', 'title=Wordpress&url=http://en.blog.wordpress.com/feed/&number=5&show_author=1&show_date=1', $widget_area_args);
			the_widget('WP_Widget_Meta', '', $widget_area_args);
		}

	} // end sidebar_right
	?>
   </ul>
  </div><!– #right-sidebar –>
<?php endif; ?>