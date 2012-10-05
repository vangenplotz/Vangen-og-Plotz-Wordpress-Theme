<?php
/**
 * Twenty Eleven functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyeleven_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 584;

/**
 * Tell WordPress to run twentyeleven_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'twentyeleven_setup' );

if ( ! function_exists( 'twentyeleven_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyeleven_setup() in a child theme, add your own twentyeleven_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, and Post Formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_setup() {

	/* Make Twenty Eleven available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Eleven, use a find and replace
	 * to change 'twentyeleven' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentyeleven', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Load up our theme options page and related code.
	require( get_template_directory() . '/inc/theme-options.php' );

	// Grab Twenty Eleven's Ephemera widget.
	require( get_template_directory() . '/inc/widgets.php' );

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'twentyeleven' ) );

	// Add support for a variety of post formats
	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );

	// Add support for custom backgrounds
	add_custom_background();

	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	add_theme_support( 'post-thumbnails' );

	// The next four constants set how Twenty Eleven supports custom headers.

	// The default header text color
	define( 'HEADER_TEXTCOLOR', '000' );

	// By leaving empty, we allow for random image rotation.
	define( 'HEADER_IMAGE', '' );

	// The height and width of your custom header.
	// Add a filter to twentyeleven_header_image_width and twentyeleven_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyeleven_header_image_width', 1000 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyeleven_header_image_height', 288 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be the size of the header image that we just defined
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Add Twenty Eleven's custom image sizes
	add_image_size( 'large-feature', HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true ); // Used for large feature (header) images
	add_image_size( 'small-feature', 500, 300 ); // Used for featured posts if a large-feature doesn't exist

	// Turn on random header image rotation by default.
	add_theme_support( 'custom-header', array( 'random-default' => true ) );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See twentyeleven_admin_header_style(), below.
	add_custom_image_header( 'twentyeleven_header_style', 'twentyeleven_admin_header_style', 'twentyeleven_admin_header_image' );

	// ... and thus ends the changeable header business.

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'wheel' => array(
			'url' => '%s/images/headers/wheel.jpg',
			'thumbnail_url' => '%s/images/headers/wheel-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Wheel', 'twentyeleven' )
		),
		'shore' => array(
			'url' => '%s/images/headers/shore.jpg',
			'thumbnail_url' => '%s/images/headers/shore-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Shore', 'twentyeleven' )
		),
		'trolley' => array(
			'url' => '%s/images/headers/trolley.jpg',
			'thumbnail_url' => '%s/images/headers/trolley-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Trolley', 'twentyeleven' )
		),
		'pine-cone' => array(
			'url' => '%s/images/headers/pine-cone.jpg',
			'thumbnail_url' => '%s/images/headers/pine-cone-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Pine Cone', 'twentyeleven' )
		),
		'chessboard' => array(
			'url' => '%s/images/headers/chessboard.jpg',
			'thumbnail_url' => '%s/images/headers/chessboard-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Chessboard', 'twentyeleven' )
		),
		'lanterns' => array(
			'url' => '%s/images/headers/lanterns.jpg',
			'thumbnail_url' => '%s/images/headers/lanterns-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Lanterns', 'twentyeleven' )
		),
		'willow' => array(
			'url' => '%s/images/headers/willow.jpg',
			'thumbnail_url' => '%s/images/headers/willow-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Willow', 'twentyeleven' )
		),
		'hanoi' => array(
			'url' => '%s/images/headers/hanoi.jpg',
			'thumbnail_url' => '%s/images/headers/hanoi-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Hanoi Plant', 'twentyeleven' )
		)
	) );
}
endif; // twentyeleven_setup

if ( ! function_exists( 'twentyeleven_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_header_style() {

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#site-title,
		#site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // twentyeleven_header_style

if ( ! function_exists( 'twentyeleven_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyeleven_setup().
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1,
	#desc {
		font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
	}
	#headimg h1 {
		margin: 0;
	}
	#headimg h1 a {
		font-size: 32px;
		line-height: 36px;
		text-decoration: none;
	}
	#desc {
		font-size: 14px;
		line-height: 23px;
		padding: 0 0 3em;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	#headimg img {
		max-width: 1000px;
		height: auto;
		width: 100%;
	}
	</style>
<?php
}
endif; // twentyeleven_admin_header_style

if ( ! function_exists( 'twentyeleven_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyeleven_setup().
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
		?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // twentyeleven_admin_header_image

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function twentyeleven_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 */
function twentyeleven_continue_reading_link() {
	return '';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyeleven_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function twentyeleven_auto_excerpt_more( $more ) {
	return ' &hellip;' . twentyeleven_continue_reading_link();
}
add_filter( 'excerpt_more', 'twentyeleven_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function twentyeleven_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= twentyeleven_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyeleven_custom_excerpt_more' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function twentyeleven_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyeleven_page_menu_args' );

/**
 * Register our sidebars and widgetized areas. Also register the default Epherma widget.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_widgets_init() {

	register_widget( 'Twenty_Eleven_Ephemera_Widget' );

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Showcase Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-2',
		'description' => __( 'The sidebar for the optional Showcase Template', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area One', 'twentyeleven' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'twentyeleven' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Three', 'twentyeleven' ),
		'id' => 'sidebar-5',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'twentyeleven_widgets_init' );

if ( ! function_exists( 'twentyeleven_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function twentyeleven_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentyeleven' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyeleven' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></div>
		</nav><!-- #nav-above -->
	<?php endif;
}
endif; // twentyeleven_content_nav

/**
 * Return the URL for the first link found in the post content.
 *
 * @since Twenty Eleven 1.0
 * @return string|bool URL or false when no link is present.
 */
function twentyeleven_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function twentyeleven_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-5' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}

if ( ! function_exists( 'twentyeleven_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyeleven_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyeleven' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'twentyeleven' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'twentyeleven' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentyeleven' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'twentyeleven' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for twentyeleven_comment()

if ( ! function_exists( 'twentyeleven_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own twentyeleven_posted_on to override in a child theme
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'twentyeleven' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentyeleven' ), get_the_author() ) ),
		get_the_author()
	);
}
endif;

/**
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_body_classes( $classes ) {

	if ( function_exists( 'is_multi_author' ) && ! is_multi_author() )
		$classes[] = 'single-author';

	if ( is_singular() && ! is_home() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) )
		$classes[] = 'singular';

	return $classes;
}
add_filter( 'body_class', 'twentyeleven_body_classes' );

/* Includes description text for each menu item in output */
class description_walker extends Walker_Nav_Menu
{
      function start_el($output, $item, $depth, $args)
      {
           global $wp_query;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';

           $classes = empty( $item->classes ) ? array() : (array) $item->classes;

           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . '"';

           $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		   $attributes .= ' class="menu-link"';

           $prepend = '<i class="menu-item-icon"></i><strong class="menu-link-title">';
           $append = '</strong>';
           $description  = ! empty( $item->description ) ? '<span class="menu-link-desc">'.esc_attr( $item->description ).'</span>' : '';

           if($depth != 0)
           {
                     $description = $append = $prepend = "";
           }

            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'>';
            $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
            $item_output .= $description.$args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            }
}

/* Add orbit slider image size with hard crop */
if ( function_exists( 'add_image_size' ) ) { add_image_size( 'orbit-custom', 640, 396, true ); };

/* Portfolio */
register_taxonomy(
	'prosjektkategori',
	null,
	array(
		'label' => __( 'Prosjektkategorier' ),
		'labels' => array(
			'name' => __( 'Prosjektkategorier' ),
			'singular_name' => __( 'Prosjektkategori' ),
			'search_items' => __( 'Søk i prosjektkategorier' ),
			'popular_items' => __( 'Populære produktkategorier' ),
			'all_items' => __( 'Alle produktkategorier' ),
			'parent_item' => __( 'Foreldrekategori' ),
			'parent_item_colon' => __( 'Foreldrekategori:' ),
			'edit_item' => __( 'Rediger kategori' ),
			'update_item' => __( 'Oppdater kategori' ),
			'add_new_item' => __( 'Legg til ny kategori' ),
			'new_item_name' => __( 'Nytt kategorinavn' ),
			'separate_items_with_commas' => __( 'Skill kategorier med komma' ),
			'add_or_remove_items' => __( 'Legg til eller fjern kategorier' ),
			'choose_from_most_used' => __( 'Velg blant mest brukte kategorier' )
		),
		'hierarchical' => true,
		'rewrite' => array( 'with_front' => false, 'hierarchical' => true ),
		'capabilities' => array( 'manage_terms', 'edit_terms', 'delete_terms', 'assign_terms')
	)
);
register_taxonomy(
	'prosjektstikkord',
	null,
	array(
		'label' => __( 'Stikkord' ),
		'labels' => array(
			'name' => __( 'Stikkord' ),
			'singular_name' => __( 'Stikkord' ),
			'search_items' => __( 'Søk i stikkord' ),
			'popular_items' => __( 'Populære stikkord' ),
			'all_items' => __( 'Alle stikkord' ),
			'parent_item' => __( 'Foreldrestikkord' ),
			'parent_item_colon' => __( 'Foreldrestikkord:' ),
			'edit_item' => __( 'Rediger stikkord' ),
			'update_item' => __( 'Oppdater stikkord' ),
			'add_new_item' => __( 'Legg til nytt stikkord' ),
			'new_item_name' => __( 'Nytt stikkordnavn' ),
			'separate_items_with_commas' => __( 'Skill stikkord med komma' ),
			'add_or_remove_items' => __( 'Legg til eller fjern stikkord' ),
			'choose_from_most_used' => __( 'Velg blant mest brukte stikkord' )
		),
		'hierarchical' => false,
		'rewrite' => array( 'with_front' => false, 'hierarchical' => false ),
		'capabilities' => array( 'manage_terms', 'edit_terms', 'delete_terms', 'assign_terms')
	)
);
register_taxonomy(
	'kunde',
	null,
	array(
		'label' => __( 'Kunder' ),
		'labels' => array(
			'name' => __( 'Kunder' ),
			'singular_name' => __( 'Kunde' ),
			'search_items' => __( 'Søk i kunder' ),
			'popular_items' => __( 'Gjentatte kunder' ),
			'all_items' => __( 'Alle kunder' ),
			'parent_item' => __( 'Foreldrekunde' ),
			'parent_item_colon' => __( 'Foreldrekunde:' ),
			'edit_item' => __( 'Rediger kunde' ),
			'update_item' => __( 'Oppdater kunde' ),
			'add_new_item' => __( 'Legg til ny kunde' ),
			'new_item_name' => __( 'Nytt kundenavn' ),
			'separate_items_with_commas' => __( 'Skill kunder med komma' ),
			'add_or_remove_items' => __( 'Legg til eller fjern kunder' ),
			'choose_from_most_used' => __( 'Velg blant mest brukte kunder' )
		),
		'hierarchical' => false,
		'rewrite' => array( 'with_front' => false, 'hierarchical' => false ),
		'capabilities' => array( 'manage_terms', 'edit_terms', 'delete_terms', 'assign_terms')
	)
);

add_action( 'init', 'vp_projects' );
function vp_projects() {
	$labels = array(
		'name' => __( 'Prosjekter' ),
		'singular_name' => __( 'Prosjekt' ),
		'add_new_item' => __( 'Legg til nytt prosjekt' ),
		'edit_item' => __( 'Rediger prosjekt' ),
		'new_item' => __( 'Nytt prosjekt' ),
		'view_item' => __( 'Se prosjekt' ),
		'search_items' => __( 'Søk i prosjekter' ),
		'not_found' => __( 'Ingen prosjekter funnet' ),
		'not_found_in_trash' => __( 'Ingen prosjekter funnet i papirkurven' ),
		'parent_item_colon' => __( 'Foreldreprosjekt' )
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'menu_position' => 5,
		'hierarchical' => true,
		'has_archive' => true,
		'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
		'taxonomies' => array( 'prosjektkategori', 'prosjektstikkord', 'kunde' ),
		'rewrite' => array( 'with_front' => false )
	);
	register_post_type('prosjekter', $args);
}

// Ansatte

add_action( 'init', 'vp_employees' );
function vp_employees() {
	$labels = array(
		'name' => __( 'Ansatte' ),
		'singular_name' => __( 'Ansatt' ),
		'add_new_item' => __( 'Legg til ny ansatt' ),
		'edit_item' => __( 'Rediger ansatt' ),
		'new_item' => __( 'Ny ansatt' ),
		'view_item' => __( 'Se ansatt' ),
		'search_items' => __( 'Søk i ansatte' ),
		'not_found' => __( 'Ingen ansatte funnet' ),
		'not_found_in_trash' => __( 'Ingen ansatte funnet i papirkurven' ),
		'parent_item_colon' => __( 'Hovedansatt' )
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'menu_position' => 5,
		'hierarchical' => true,
		'has_archive' => true,
		'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
		'taxonomies' => array(),
		'rewrite' => array( 'with_front' => false )
	);
	register_post_type('ansatte', $args);
}

require( dirname( __FILE__ ) . '/theme-options.php' );

function is_odd($n){
	return (boolean) ($n % 2);
}

// [year]
function year_func(){
 return date('Y');
}
add_shortcode( 'year', 'year_func' );

// [age birthday="24/12/2012" append="years"]
function age_func( $atts ) {
	extract( shortcode_atts( array(
			'birthday' => '',
			'append' => ''
		), $atts ) );
	if( $birthday ) {
	    //explode the date to get month, day and year
	    $birthday = explode("/", $birthday);
	    //get age from date or birthdate
	    $age = ( date( "md", date( "U", mktime( 0, 0, 0, $birthday[1], $birthday[0], $birthday[2] ) ) ) > date( "md" ) ? ( ( date( "Y" ) - $birthday[2] ) -1 ) : ( date( "Y" ) - $birthday[2] ) );
		if( $append ) {
			$age .= ' ' . $append;
		}
	}
	else {
		$age = '-= Oppgi en fødselsdato ved å skrive [age birthday="mm/dd/yyyy" append="år"] =-';
	}
    return $age;
}
add_shortcode( 'age', 'age_func' );

// [ansattliste]
function employee_list( $atts ) {
	extract( shortcode_atts( array(
			'show_all' => false,
			'title' => '',
			'title_level' => 'h2'
		), $atts ) );
	
	if( $show_all ) {
		$employee_page_ids = array();
		$args = array( 'post_type' => 'ansatte', 'post__not_in' => array( get_the_ID() ), 'posts_per_page' => 10, 'order' => 'ASC', 'orderby' => 'title' );
		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ) : $loop->the_post();
			$employee_page_ids[][1] = get_the_ID();
		endwhile;
	}
	else {
		// Fetch the data from "Bygg ansatt field group"
		$employee_page_ids = simple_fields_get_post_group_values(get_the_id(), 4, false, 2);
	}
	
	if( $title ) {
		$output = '<' . $title_level . '>' . $title . '</' . $title_level . '>';
	}
	else {
		$output = '';
	}
	
	// If there is data in the field
	if( count( $employee_page_ids ) > 0 ) {
		$i = 1;
		$output .= '<div class="two-column-wrapper">';
		foreach( $employee_page_ids as $employee_page_id ) {
			// If first element in row (1,3,5 ...) start row-div
			if( is_odd( $i ) ) {
				$output .= '<div class="content-row">';
				$output .= '<div class="content-wrapper content-wrapper-left">';
			}
			else {
				$output .= '<div class="content-wrapper content-wrapper-right">';
			}
				// Single option in this group so we only use first element in array (which is 1)
				$employee_page_id = $employee_page_id[1];
				$employee = get_page( $employee_page_id );
				$output .= '<div class="content-cell-thumb"><a href="' . get_permalink( $employee_page_id ) . '">' . get_the_post_thumbnail( $employee_page_id, 'thumbnail' ) . '</a></div>';
				$output .= '<div class="content-cell-text"><header class="entry-header"><h1 class="entry-title"><a href="' . get_permalink( $employee_page_id ) . '">' . $employee->post_title . '</a></h1></header></div>';
				
				// Now lets get som employee details
				$employee_position = simple_fields_get_post_value( $employee_page_id, array(3,1), true);
				$employee_email = simple_fields_get_post_value( $employee_page_id, array(3,2), true);
				$employee_telephone = simple_fields_get_post_value( $employee_page_id, array(3,3), true);
				// Start entry content
				$output .= '<div class="entry-content">';
				if( $employee_position || $employee_email || $employee_telephone ) {
					$output .= '<ul class="emplyee-details">';
					if( $employee_position ) {
						$output .= '<li class="employee-details-position">' . $employee_position . '</li>';
					}
					if( $employee_email ) {
						$output .= '<li class="employee-details-email"><a href="mailto:' . $employee_email . '">' . $employee_email . '</a></li>';
					}
					if( $employee_telephone ) {
						$output .= '<li class="employee-details-telephone">' . $employee_telephone . '</li>';
					}
					$output .= '</ul>';
				}
				$output .= '<p>' . $employee->post_excerpt . '</p>';
				
				// End entry content
				$output .= '</div>';
				
			// End content wrapper div
			$output .= '</div>';
			
			// If last element in row (2,4,6 ...) end row-div
			if( !is_odd( $i ) ) {
				$output .= '</div>';
			}
			$i++;
		}
		
		// If loop ended on an odd number (which is then made even by $i++), we need to close the row
		if( !is_odd( $i ) ) {
			$output .= '</div>';
		}
		
		// Close div.two-column-wrapper
		$output .= '</div>';
	}
	return $output;
}
add_shortcode( 'ansattliste', 'employee_list' );