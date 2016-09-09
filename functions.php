<?php

// FIRST ENSURE CORRECT WP VERSION
if ($wp_version < 3): 

  function unsupported_wp_version(){ ?>
  <div class="error">
   <p>
   <?php
    printf(__('Whoa! Your site is running on Wordpress %1$s. This theme requires at least %2$s.'), $wp_version, '<a href="http://codex.wordpress.org/Upgrading_WordPress">Wordpress 3.0</a>');
	if (current_user_can('switch_themes') && !is_admin()) echo '<br /><a href="'.get_bloginfo('wpurl').'/wp-admin/">'.__("Return to Dashboard").'</a>';
   ?>
   </p>
  </div>
  <?php if(!is_admin()) die();
  }

  add_action('admin_notices', 'unsupported_wp_version');
  add_action('wp', 'unsupported_wp_version');

else:

################################################
### REGISTER EDITABLE THEME OPTIONS FOR USER ###
################################################

// Set up Theme information
$this_theme_data = get_theme_data(TEMPLATEPATH.'/style.css');
define('THEME_NAME', $this_theme_data['Name']);
define('THEME_AUTHOR', $this_theme_data['Author']);
define('THEME_HOMEPAGE', $this_theme_data['URI']);
define('THEME_VERSION', trim($this_theme_data['Version']));
define('THEME_URL', get_template_directory_uri());
define('THEME_FILE', "kglogoh");

/*
function sandbox_globalnav() {
	if ( $menu = str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages('title_li=&sort_column=menu_order&echo=0') ) )
		$menu = '<ul>' . $menu . '</ul>';
	$menu = '<div id="menu">' . $menu . "</div>\n";
	echo apply_filters( 'globalnav_menu', $menu ); // Filter to override default globalnav: globalnav_menu
}
*/
// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain(THEME_FILE, TEMPLATEPATH . '/languages');

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) ) require_once( $locale_file );


// Set up Theme Options
$theme_options = array (
	
	array(	"name" => __("Site Width"),
			"desc" => __("Select the width of your site."),
			"id" => "site_width",
			"default" => "800",
			"type" => "site"),

	array(  "name" => __("Custom Logo"),
			"desc" => __("Would you like to display a logo in your header?"),
            		"id" => "custom_logo",
			"default" => "no",
            		"type" => "custom-logo")
);

// Set up theme options values variable
$options_values = get_option(THEME_FILE);
//delete_option(THEME_FILE);

function get_index($array, $index) {
  return isset($array[$index]) ? $array[$index] : null;
}

// CALL THEME OPTIONIS
function theme_option($var) {
	global $options_values;
	$val = get_index($options_values,$var);
	return $val;
}

// Set all default options
if(!$options_values) {
	foreach ($theme_options as $default) {
		if(isset($default['id']) && isset($default['default'])) {
			$setdefaultvalues1[ $default['id'] ] = $default['default'];
		}
	}
	update_option(THEME_FILE, $setdefaultvalues1);
}

// Ajax save function
function save_theme_callback() {
	global $wpdb; // this is how you get access to the database

	$savevalues = array();
	
	$items = explode("&", $_POST['option']);

	foreach ($items as $value) {
		$key_value = explode("=",$value);
		$key = urldecode($key_value[0]);
		$value = urldecode($key_value[1]);
		$savevalues[ $key ] = $value; 
	}
	update_option(THEME_FILE, $savevalues);
	die();
}
add_action('wp_ajax_save_theme_options', 'save_theme_callback');

// Create Theme
function mytheme_add_admin() {
	// Register Theme Options jQuery with WordPress
	wp_register_script('effects_js', THEME_URL.'/admin/effects.js', array( 'jquery' , 'jquery-ui-core' , 'jquery-ui-tabs' ),'',true);
	
	// Provide Administrator menu pages
	add_menu_page(THEME_FILE, THEME_NAME, 'manage_options', THEME_FILE, 'theme_options', THEME_URL.'/admin/images/icon.png');
	$themelayout = add_submenu_page(THEME_FILE, THEME_NAME." - Layout", __("Layout Options"), 'manage_options', 'theme_layout', 'theme_options_layout');
	$themehtml = add_submenu_page(THEME_FILE, THEME_NAME." - HTML", __("HTML Options"), 'manage_options', 'theme_html', 'theme_options_html');
	add_action( "admin_print_scripts-$themelayout", 'theme_admin_css' );

}
// Initialize the theme
add_action('admin_menu', 'mytheme_add_admin'); 

// Load the js and css on theme options page
function theme_admin_css() {
	echo '<link rel="stylesheet" href="'.THEME_URL.'/admin/style.css" />'."\n";
	wp_enqueue_script('effcts_js');
}


///////////////////////////////////////////////
// Theme Options page
///////////////////////////////////////////////
function theme_options() { 
    global $theme_options;
?>
<div class="wrap">
    <h2><?php echo THEME_NAME." ".__("Explore Options", "magazine-basic"); ?></h2>


This is the Theme Admin Options Page

<div> <!-- end wrap-->
<?php
}

///////////////////////////////////////////////
// Layout Options page
///////////////////////////////////////////////

function theme_options_layout() { 
    global $theme_options;
?>
<div class="wrap">
    <h2><?php echo THEME_NAME." ".__("Layout Options", "magazine-basic"); ?></h2>


This is the Theme Admin Options Page

<br><br><br>
<?php
function header_options() {
    global $theme_options;

	foreach ($theme_options as $value) { 
		switch ( get_index($value,'type') ) {
	
			case "custom-logo":
			?>
			Custom Logo?<input  name="<?php echo $value['id']; ?>" type="Checkbox" value="logo"<?php if(theme_option($value['id']) == "yes") { echo " checked=\"checked\""; } ?> />
			<?php 
			
			break;
		}
	}
}
header_options();			
?>
<div> <!-- end wrap-->
<?php
}

///////////////////////////////////////////////
// HTML Options page
///////////////////////////////////////////////
function theme_options_html() { 
    global $theme_options;
?>
<div class="wrap">
    <h2><?php echo THEME_NAME." ".__("HTML Options"); ?></h2>


This is the Theme Admin Options Page for HTML


<div> <!-- end wrap-->
<?php
}



###########################################
### DEFINE MISCELANEOUS THEME FUNCTIONS ###
###########################################

// Get current page if needed
function currentPageURL() {
  $request = esc_url($_SERVER["REQUEST_URI"]);

  // wp-themes fake request url fix :)
  if (strpos($_SERVER["SERVER_NAME"], 'wp-themes.com') !== false) $request = str_replace($request, '/wordpress/', '/');

  $pageURL = (is_ssl() ? 'https' : 'http').'://';
  $_SERVER["SERVER_PORT"] != "80" ? $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$request : $pageURL .= $_SERVER["SERVER_NAME"].$request;

  if (false === strpos(get_option('home'), '://www.')) $pageURL = str_replace('://www.', '://', $pageURL);
  if (false !== strpos(get_option('home'), '://www.') && false === strpos($pageURL, '://www.')) $pageURL = str_replace('://', '://www.', $pageURL);

  return $pageURL;
}

// Get the page number
function get_page_number() {
    if ( get_query_var('paged') ) {
        print ' | ' . __( 'Page ') . get_query_var('paged');
    }
}

// Custom callback to list comments in the style
function custom_comments($comment, $args, $depth) {
 $GLOBALS['comment'] = $comment;
 $GLOBALS['comment_depth'] = $depth;
 $add_no_avatar = get_option('show_avatars') ? '' : ' no-avatar';
 if (get_option('avatar_default') == 'blank' && !validate_gravatar(get_comment_author_email()))
	$add_no_avatar = ' no-avatar';
  ?>
	<stop class="commentaire"></stop>
	<li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
      <div class="comment-author vcard">
		<div class="comment-meta<?php _e($add_no_avatar) ?>"><?php printf(__('comment on <a href="%3$s" title="Permalink to this comment">%1$s</a> at <span class="comment-time">%2$s</span>'),
		   get_comment_date(),
		   get_comment_time(),
		   '#comment-' . get_comment_ID() );
		   edit_comment_link(__('Edit'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?>
		</div>
		<span class="sep<?php _e($add_no_avatar) ?>"></span>
		<?php commenter_link() ?>
	  </div>
  <?php if ($comment->comment_approved == '0') _e("\t\t\t\t\t<span class='unapproved'>Thank you! Your comment has been sent for approval.</span>\n") ?>
  		    <?php // echo the comment reply link
			if (get_option('comment_registration')==0 || is_user_logged_in()) {
				if($args['type'] == 'all' || get_comment_type() == 'comment') :
					comment_reply_link(array_merge($args, array(
					'reply_text' => __('reply'), 
					'depth' => $depth,
					'before' => '<div class="comment-reply">', 
					'after' => '</div>'
					)));
				endif;
			} else {
				if($args['type'] == 'all' || get_comment_type() == 'comment') :
					comment_reply_link(array_merge($args, array(
					'login_text' => __('login/register'),
					'depth' => $depth,
					'before' => '<div class="comment-login">', 
					'after' => '</div>'
					)));
				endif;
			}
			?>
	  <div class="comment-content">
		<?php comment_text() ?>
      </div>
<?php
}

// Custom callback to list pings
function custom_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
        ?>
	<stop class="ping"></stop>
      <li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
       <div class="comment-author"><?php printf(__('By %1$s on %2$s at %3$s'),
         get_comment_author_link(),
         get_comment_date(),
         get_comment_time() );
         edit_comment_link(__('Edit'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
    <?php if ($comment->comment_approved == '0') _e('\t\t\t\t\t<span class="unapproved">Your trackback is awaiting moderation.</span>\n') ?>
            <div class="comment-content">
       <?php comment_text() ?>
   </div>
<?php
}

// Produces an avatar image with the hCard-compliant photo class
function validate_gravatar($email) {
	// Craft a potential url and test its headers
	$hash = md5($email);
	$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
	$headers = @get_headers($uri);
	if (!preg_match("|200|", $headers[0])) {
		$has_valid_avatar = FALSE;
	} else {
		$has_valid_avatar = TRUE;
	}
	return $has_valid_avatar;
}
function commenter_link() {
  $commenter = get_comment_author_url();
  if ($commenter == '') {
		$commenter = get_comment_author();
  } else {
		$commenter = get_comment_author_url_link(get_comment_author());
  }
  $avatar_email = get_comment_author_email();
  $avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, 80, $default = get_template_directory_uri().'/images/vabatar.jpg' ) );
  $avatar_overlay = get_option('show_avatars') && !(get_option('avatar_default') == 'blank' && !validate_gravatar($avatar_email)) ? '  <span class="overlay"></span><span class="fn n commenter">' : '<span class="fn n commenter no-avatar">';
  echo $avatar . $avatar_overlay . $commenter . '<span class="says"> said this:</span></span>';
}

// For category lists on category archives: Returns other categories except the current one (redundant)
function cats_meow($glue) {
  $current_cat = single_cat_title( '', false );
  $separator = "\n";
  $cats = explode( $separator, get_the_category_list($separator) );
  foreach ( $cats as $i => $str ) {
    if ( strstr( $str, ">$current_cat<" ) ) {
    unset($cats[$i]);
    break;
    }
  }
  if ( empty($cats) ) return false;
  return trim(join( $glue, $cats ));
}

// For tag lists on tag archives: Returns other tags except the current one (redundant)
function tag_ur_it($glue) {
  $current_tag = single_tag_title( '', '',  false );
  $separator = "\n";
  $tags = explode( $separator, get_the_tag_list( "", "$separator", "" ) );
  foreach ( $tags as $i => $str ) {
    if ( strstr( $str, ">$current_tag<" ) ) {
    unset($tags[$i]);
    break;
    }
  }
  if ( empty($tags) ) return false;
  return trim(join( $glue, $tags ));
}

###############
### WIDGETS ###
###############

// Register widgetized areas
function theme_widgets_init() {

  register_sidebar( array (
	'name' => 'Left Sidebar',
	'id' => 'sidebar_left',
	'before_widget' => '<div class="slice">',
	'after_widget' => '<div id="clear"></div></div>',
	'before_title' => '<h2 class="title">',
	'after_title' => '<span class="sep"></span></h2>'
  ) );

  register_sidebar( array (
	'name' => 'Right Sidebar',
	'id' => 'sidebar_right',
	'before_widget' => widget_area('before'),
	'after_widget' => widget_area('after'),
	'before_title' => widget_area('title_before'),
	'after_title' => widget_area('title_after')
  ) );

  register_sidebar( array (
	'name' => 'Share Area Top',
	'id' => 'share_area_top',
	'before_widget' => '<div class="share-area top">',
	'after_widget' => '<div id="clear"></div></div>',
	'before_title' => '<h2>',
	'after_title' => '</h2>'
  ) );

  register_sidebar( array (
	'name' => 'Share Area Bottom',
	'id' => 'share_area_bottom',
	'before_widget' => '<div class="share-area bottom">',
	'after_widget' => '<div id="clear"></div></div>',
	'before_title' => '<h2>',
	'after_title' => '</h2>'
  ) );

  register_sidebar( array (
	'name' => 'Footer',
	'id' => 'footer',
	'before_widget' => '',
	'after_widget' => '',
	'before_title' => '<h2>',
	'after_title' => '</h2>'
  ) );
}

add_action( 'init', 'theme_widgets_init' );

// Pre-set default widgets
$preset_widgets = array (
	'sidebar_left'  => array( 'search', 'navigate-widget', 'follow-widget', 'share-widget', 'login-widget' ),
	'sidebar_right'  => array( 'pages', 'categories', 'archives', 'links', 'meta' ),
	'share_area_top' => array( 'strx-simple-sharing-sidebar-widget' )
);

// Set before and after content
function widget_area($string) {
	$content = '';
	switch($string) {
		case 'before' :
			$content = '
			<li class="widget">
				<div class="widget-set">
					<div class="wrap no-title">
						<div class="upper"></div>
						<div class="fill"><div></div></div>
						<div class="lower"></div>
						<div class="no-title top"></div>
					</div>
					<div class="widget-deats no-title">';
			break;
		case 'title_before' :
			$content = '
					</div>
					<div class="wrap">
						<div class="upper"></div>
						<div class="fill"><div></div></div>
						<div class="lower"></div>
						<div class="title top">';
			break;
		case 'title_after' :
			$content = '</div>
					</div>
					<div class="widget-deats">';
			break;
		case 'after' :
			$content = '
					</div>
				</div>
				<div class="wrap">
					<div class="upper"></div>
					<div class="fill"><div></div></div>
					<div class="lower"></div>
					<div class="no-title bottom"></div>
				</div>
			</li>';
			break;			
	}
	return $content;
}

if ( isset( $_GET['activated'] ) ) {
	update_option( 'sidebars_widgets', $preset_widgets );
}
// Uncomment following line to reset widgets for any reason
// update_option( 'sidebars_widgets', NULL );

// Check for static widgets in widget-ready areas
function is_sidebar_active( $index ){
  global $wp_registered_sidebars;

  $widgetcolums = wp_get_sidebars_widgets();

  if ($widgetcolums[$index]) return true;

	return false;
}

// This theme uses wp_nav_menu()
if(function_exists('register_nav_menu')) {
	register_nav_menu('main', 'Main Navigation Menu');
}

class custom_menu_walker extends Walker {
  var $tree_type = array('post_type', 'taxonomy', 'custom');
  var $db_fields = array('parent' => 'menu_item_parent', 'id' => 'db_id');

  function start_lvl(&$output, $depth) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"level-".($depth+2)."\">\n";
  }

  function end_lvl(&$output, $depth) {
   $indent = str_repeat("\t", $depth);
   $output .= "$indent</ul>\n";
  }

  function start_el(&$output, $item, $depth, $args) {
    global $wp_query;
    $indent = ($depth) ? str_repeat("\t", $depth) : '';
    $class_names = $value = '';
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;

    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
    if($class_names) $class_names = ' class="' .esc_attr($class_names). '"';

    $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

    $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
    $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target)     .'"' : '';
    $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn)        .'"' : '';
    $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url)        .'"' : '';

    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'>';
    $item_output .= $args->link_before .apply_filters( 'the_title', $item->title, $item->ID );
    $item_output .= $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }

  function end_el(&$output, $item, $depth) {
    $output .= "</li>\n";
  }
}
class custom_comments_walker extends Walker {
  var $tree_type = array('post_type', 'taxonomy', 'custom');
  var $db_fields = array('parent' => 'menu_item_parent', 'id' => 'db_id');
/*
  function start_lvl(&$output, $depth) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"level-".($depth+2)."\">\n";
  }

  function end_lvl(&$output, $depth) {
   $indent = str_repeat("\t", $depth);
   $output .= "$indent</ul>\n";
  }

  function start_el(&$output, $item, $depth, $args) {
    global $wp_query;
    $indent = ($depth) ? str_repeat("\t", $depth) : '';
    $class_names = $value = '';
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;

    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
    if($class_names) $class_names = ' class="' .esc_attr($class_names). '"';

    $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

    $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
    $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target)     .'"' : '';
    $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn)        .'"' : '';
    $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url)        .'"' : '';

    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'>';
    $item_output .= $args->link_before .apply_filters( 'the_title', $item->title, $item->ID );
    $item_output .= $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }
*/
  function end_el(&$output, $item, $depth) {
    $output .= "</li>\n<stop></stop>";
  }
  
}

add_theme_support( 'automatic-feed-links' );

function display_home() {
	echo '<div class="main-navigation"><ul class="nav-menu"><li><a href="'.get_bloginfo('url').'">Home</a></li>';
	wp_list_categories('title_li=&depth=1&number=5');
	echo '</ul></div>';
}

endif;
?>
