<?php
/**
 * Swanwick functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Swanwick
 */

if ( ! function_exists( 'swanwick_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function swanwick_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Swanwick, use a find and replace
	 * to change 'swanwick' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'swanwick', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * Linkifies the featured image.
	 *
	 * @param  string       $html              The HTML for the image.
	 * @param  int          $post_id           The current post ID.
	 * @param  int          $featured_image_id The featured image ID.
	 * @param  string|int[] $size              Requested image size; can be a
	 *                                         registered image size
	 *                                         or an int array(w,h).
	 * @param  string       $attr              Query string of attributes.
	 * @return string                          The filtered HTML.
	 * @since  1.1.0
	 */
	function swanwick_linkify_featured_image( $html, $post_id, $featured_image_id, $size, $attr ) {
		if ( is_singular() ) {
			$full_size = wp_get_attachment_image_src( $featured_image_id, 'full' );
			if ( ! empty( $full_size ) ) {
				$url = esc_url( $full_size[0] );
				if ( ! empty( $url ) ) {
					$caption = wp_get_attachment_caption( $featured_image_id );
					if ( empty( $caption ) ) {
						$caption = esc_html__( 'Full size', 'swanwick' );
					}
					$html = '<figure><a href="' . $url . '" title="' . $caption . '">' . $html . '</a></figure>';
				}
			}
		}
		return $html;
	}
	add_filter( 'post_thumbnail_html', 'swanwick_linkify_featured_image', 10, 5 );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'swanwick' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'swanwick_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'swanwick_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function swanwick_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'swanwick_content_width', 640 );
}
add_action( 'after_setup_theme', 'swanwick_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function swanwick_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'swanwick' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'swanwick' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'swanwick_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function swanwick_scripts() {
	wp_enqueue_style( 'swanwick-style', get_stylesheet_uri(), array( 'libre-baskerville' ) );

	// Loads the Google font CSS file.
	wp_enqueue_style( 'libre-baskerville', 'https://fonts.googleapis.com/css?family=Libre+Baskerville:400,400i,700&amp;subset=latin-ext' );

	wp_enqueue_script( 'swanwick-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'swanwick-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'swanwick_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Loads the extended Recent Posts widget.
 */
require get_template_directory() . '/inc/class-pj-recent-posts.php';

/**
 * Adds the "Get a print!" tag to the end of the content.
 *
 * Checks first to see if "print" and ("order" or "get" or "interested")
 * are in the content first. If not, adds the contact link to the end.
 *
 * @param  string $content The post content.
 * @return string          The filtered content.
 */
function pj_hey_get_a_print_already( $content ) {
	if ( is_single() && has_category( 'Photos' ) ) {
		$already_there =
			// Searches for "print[s]".
			stripos( $content, 'print' )
			&& (
				// Searches for "buy[ing]" OR "interested in".
				stripos( $content, 'buy' )
				|| stripos( $content, 'interested in' )
			);
		if ( false === $already_there ) {
			$content .= '<p class="call-to-action photo-prints">' . __( 'Interested in prints of my photos? <a href="/contact">Let me know</a>, and we can work something out.', 'swanwick' ) . '</p>' . PHP_EOL;
		}

	}
	return $content;
}
add_filter( 'the_content', 'pj_hey_get_a_print_already' );

/**
 * Filters the archive title.
 *
 * @param  string $title          The archive page title.
 * @param  string $original_title The original title.
 * @param  string $prefix         The title's prefix.
 * @return string                 The filtered title.
 * @since  1.0.0
 */
function swanwick_archive_title( $title, $original_title = '', $prefix = '' ) {
	if ( ! empty( $original_title ) ) {
		return $original_title;
	}
	$title = str_replace( 'Category: ', '', $title );
	return $title;
}
add_filter( 'get_the_archive_title', 'swanwick_archive_title', 10, 3 );

/**
 * Fakes a featured image, if necessary.
 *
 * If a post doesn't have a featured image, but has an image attached,
 * uses the first attached image as a featured image. Meant for archive
 * pages, but not single pages.
 *
 * @param  boolean          $has_thumbnail The post has a featured image.
 * @param  int|WP_Post|null $post          The current post.
 * @param  int|string       $thumbnail_id  The thumbnail ID, if any.
 * @return boolean                         True if an image is found, false otherwise.
 * @since  1.0.0
 */
function swanwick_find_featured_image( $has_thumbnail, $post, $thumbnail_id ) {
	if ( ! $has_thumbnail ) {
		$post = get_post( $post );
		if ( $post instanceof WP_Post ) {
			$has_thumbnail = strpos( $post->post_content, '<img' );
			if ( false !== $has_thumbnail ) {
				$has_thumbnail = true;
			}
		}
	}
	return $has_thumbnail;
}
add_filter( 'has_post_thumbnail', 'swanwick_find_featured_image', 10, 3 );

/**
 * Gets the first image as a featured image, if necessary.
 *
 * @return string The generated HTML.
 * @since  1.0.0
 */
function swanwick_fake_featured_image( $html, $post_id, $thumbnail_id, $size, $attr ) {
	if ( is_single() ) {
		return $html;
	}
	if ( empty( $html ) ) {
		// Shortcut this using post meta, for speed.
		$thumbnail_id = get_post_meta( $post_id, 'swanwick_fake_thumbnail_id', true );
		if ( empty( $thumbnail_id ) ) {
			// Guess we're doing this.
			$content = get_the_content( null, false, $post_id );
			$regex = '/<!-- wp:(image|gallery) {"ids?":\[?(\d+),/';
			preg_match( $regex, $content, $matches );
			if ( ! empty( $matches ) ) {
				$t_id = array_pop( $matches );
				if ( is_numeric( $t_id ) ) {
					$att = get_post( $t_id );
					if ( false !== strpos( $att->post_mime_type, 'image' ) ) {
						$thumbnail_id = $t_id;
						// Save it in post meta, too.
						update_post_meta( $post_id, 'swanwick_fake_thumbnail_id', $thumbnail_id );
					}
				}
			}
		}

		$html = wp_get_attachment_image( $thumbnail_id, $size, false, $attr );
	}

	return $html;
}
add_filter( 'post_thumbnail_html', 'swanwick_fake_featured_image', 10, 5 );
