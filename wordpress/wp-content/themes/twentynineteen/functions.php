<?php
/**
 * Twenty Nineteen functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */

/**
 * Twenty Nineteen only works in WordPress 4.7 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.7', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

if ( ! function_exists( 'twentynineteen_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function twentynineteen_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Twenty Nineteen, use a find and replace
		 * to change 'twentynineteen' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'twentynineteen', get_template_directory() . '/languages' );

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
		set_post_thumbnail_size( 1568, 9999 );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'menu-1' => __( 'Primary', 'twentynineteen' ),
				'footer' => __( 'Footer Menu', 'twentynineteen' ),
				'social' => __( 'Social Links Menu', 'twentynineteen' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
				'navigation-widgets',
			)
		);

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 190,
				'width'       => 190,
				'flex-width'  => false,
				'flex-height' => false,
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );

		// Add custom editor font sizes.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => __( 'Small', 'twentynineteen' ),
					'shortName' => __( 'S', 'twentynineteen' ),
					'size'      => 19.5,
					'slug'      => 'small',
				),
				array(
					'name'      => __( 'Normal', 'twentynineteen' ),
					'shortName' => __( 'M', 'twentynineteen' ),
					'size'      => 22,
					'slug'      => 'normal',
				),
				array(
					'name'      => __( 'Large', 'twentynineteen' ),
					'shortName' => __( 'L', 'twentynineteen' ),
					'size'      => 36.5,
					'slug'      => 'large',
				),
				array(
					'name'      => __( 'Huge', 'twentynineteen' ),
					'shortName' => __( 'XL', 'twentynineteen' ),
					'size'      => 49.5,
					'slug'      => 'huge',
				),
			)
		);

		// Editor color palette.
		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => 'default' === get_theme_mod( 'primary_color' ) ? __( 'Blue', 'twentynineteen' ) : null,
					'slug'  => 'primary',
					'color' => twentynineteen_hsl_hex( 'default' === get_theme_mod( 'primary_color' ) ? 199 : get_theme_mod( 'primary_color_hue', 199 ), 100, 33 ),
				),
				array(
					'name'  => 'default' === get_theme_mod( 'primary_color' ) ? __( 'Dark Blue', 'twentynineteen' ) : null,
					'slug'  => 'secondary',
					'color' => twentynineteen_hsl_hex( 'default' === get_theme_mod( 'primary_color' ) ? 199 : get_theme_mod( 'primary_color_hue', 199 ), 100, 23 ),
				),
				array(
					'name'  => __( 'Dark Gray', 'twentynineteen' ),
					'slug'  => 'dark-gray',
					'color' => '#111',
				),
				array(
					'name'  => __( 'Light Gray', 'twentynineteen' ),
					'slug'  => 'light-gray',
					'color' => '#767676',
				),
				array(
					'name'  => __( 'White', 'twentynineteen' ),
					'slug'  => 'white',
					'color' => '#FFF',
				),
			)
		);

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Add support for custom line height.
		add_theme_support( 'custom-line-height' );
	}
endif;
add_action( 'after_setup_theme', 'twentynineteen_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function twentynineteen_widgets_init() {

	register_sidebar(
		array(
			'name'          => __( 'Footer', 'twentynineteen' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your footer.', 'twentynineteen' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

}
add_action( 'widgets_init', 'twentynineteen_widgets_init' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * @since Twenty Nineteen 2.0
 *
 * @param string $link Link to single post/page.
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function twentynineteen_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf(
		'<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Post title. */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentynineteen' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'twentynineteen_excerpt_more' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width Content width.
 */
function twentynineteen_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'twentynineteen_content_width', 640 );
}
add_action( 'after_setup_theme', 'twentynineteen_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function twentynineteen_scripts() {
	wp_enqueue_style( 'twentynineteen-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

	wp_style_add_data( 'twentynineteen-style', 'rtl', 'replace' );

	if ( has_nav_menu( 'menu-1' ) ) {
		wp_enqueue_script( 'twentynineteen-priority-menu', get_theme_file_uri( '/js/priority-menu.js' ), array(), '20181214', true );
		wp_enqueue_script( 'twentynineteen-touch-navigation', get_theme_file_uri( '/js/touch-keyboard-navigation.js' ), array(), '20181231', true );
	}

	wp_enqueue_style( 'twentynineteen-print-style', get_template_directory_uri() . '/print.css', array(), wp_get_theme()->get( 'Version' ), 'print' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'twentynineteen_scripts' );

/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function twentynineteen_skip_link_focus_fix() {
	// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
	?>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'twentynineteen_skip_link_focus_fix' );

/**
 * Enqueue supplemental block editor styles.
 */
function twentynineteen_editor_customizer_styles() {

	wp_enqueue_style( 'twentynineteen-editor-customizer-styles', get_theme_file_uri( '/style-editor-customizer.css' ), false, '1.1', 'all' );

	if ( 'custom' === get_theme_mod( 'primary_color' ) ) {
		// Include color patterns.
		require_once get_parent_theme_file_path( '/inc/color-patterns.php' );
		wp_add_inline_style( 'twentynineteen-editor-customizer-styles', twentynineteen_custom_colors_css() );
	}
}
add_action( 'enqueue_block_editor_assets', 'twentynineteen_editor_customizer_styles' );

/**
 * Display custom color CSS in customizer and on frontend.
 */
function twentynineteen_colors_css_wrap() {

	// Only include custom colors in customizer or frontend.
	if ( ( ! is_customize_preview() && 'default' === get_theme_mod( 'primary_color', 'default' ) ) || is_admin() ) {
		return;
	}

	require_once get_parent_theme_file_path( '/inc/color-patterns.php' );

	$primary_color = 199;
	if ( 'default' !== get_theme_mod( 'primary_color', 'default' ) ) {
		$primary_color = get_theme_mod( 'primary_color_hue', 199 );
	}
	?>

	<style type="text/css" id="custom-theme-colors" <?php echo is_customize_preview() ? 'data-hue="' . absint( $primary_color ) . '"' : ''; ?>>
		<?php echo twentynineteen_custom_colors_css(); ?>
	</style>
	<?php
}
add_action( 'wp_head', 'twentynineteen_colors_css_wrap' );

/**
 * SVG Icons class.
 */
require get_template_directory() . '/classes/class-twentynineteen-svg-icons.php';

/**
 * Custom Comment Walker template.
 */
require get_template_directory() . '/classes/class-twentynineteen-walker-comment.php';

/**
 * Common theme functions.
 */
require get_template_directory() . '/inc/helper-functions.php';

/**
 * SVG Icons related functions.
 */
require get_template_directory() . '/inc/icon-functions.php';

/**
 * Enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Custom template tags for the theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Block Patterns.
 */
require get_template_directory() . '/inc/block-patterns.php';

function prefix_create_custom_post_type() {
	/*
	 * The $labels describes how the post type appears.
	 */
	$labels = array(
		'name'          => 'job', // Plural name
		'singular_name' => 'job'   // Singular name
	);

	/*
	 * The $supports parameter describes what the post type supports
	 */
	$supports = array(
		'title',        // Post title
		'editor',       // Post content
		'excerpt',      // Allows short description
		'author',       // Allows showing and choosing author
		'thumbnail',    // Allows feature images
		'comments',     // Enables comments
		'trackbacks',   // Supports trackbacks
		'revisions',    // Shows autosaved version of the posts
		'custom-fields' // Supports by custom fields
	);

	/*
	 * The $args parameter holds important parameters for the custom post type
	 */
	$args = array(
		'labels'              => $labels,
		'description'         => 'Post type post job', // Description
		'supports'            => $supports,
		//'taxonomies'          => array( 'category', 'post_tag' ), // Allowed taxonomies
		'hierarchical'        => false, // Allows hierarchical categorization, if set to false, the Custom Post Type will behave like Post, else it will behave like Page
		'public'              => true,  // Makes the post type public
		'show_ui'             => true,  // Displays an interface for this post type
		'show_in_menu'        => true,  // Displays in the Admin Menu (the left panel)
		'show_in_nav_menus'   => true,  // Displays in Appearance -> Menus
		'show_in_admin_bar'   => true,  // Displays in the black admin bar
		'menu_position'       => 5,     // The position number in the left menu
		'menu_icon'           => true,  // The URL for the icon used for this post type
		'can_export'          => true,  // Allows content export using Tools -> Export
		'has_archive'         => true,  // Enables post type archive (by month, date, or year)
		'exclude_from_search' => false, // Excludes posts of this type in the front-end search result page if set to true, include them if set to false
		'publicly_queryable'  => true,  // Allows queries to be performed on the front-end part if set to true
		'capability_type'     => 'post' // Allows read, edit, delete like “Post”
	);

	register_post_type('job', $args); //Create a post type with the slug is ‘product’ and arguments in $args.
}
add_action('init', 'prefix_create_custom_post_type');

function my_taxonomies_job() {
$labels = array(
'name'              => _x( 'type', 'taxonomy general name' ),
'singular_name'     => _x( 'job Category', 'taxonomy singular name' ),
'search_items'      => __( 'Search job Categories' ),
'all_items'         => __( 'All job Categories' ),
'parent_item'       => __( 'Parent job Category' ),
'parent_item_colon' => __( 'Parent job Category:' ),
'edit_item'         => __( 'Edit job Category' ), 
'update_item'       => __( 'Update job Category' ),
'add_new_item'      => __( 'Add New job Category' ),
'new_item_name'     => __( 'New job Category' ),
'menu_name'         => __( 'type' ),
);
$args = array(
'labels' => $labels,
'hierarchical' => true,
);
register_taxonomy( 'job_category', 'job', $args );
}
add_action( 'init', 'my_taxonomies_job', 0 );




function wpdocs_theme_name_scripts() {
    wp_enqueue_style( 'style-name', get_stylesheet_uri() );
	wp_enqueue_script( 'api-custom-js', get_stylesheet_directory_uri() . '/js/api.js', NULL, 1.0, true );
	wp_localize_script( 'api-custom-js', 'additionalaData', array(
		'nonce' => wp_create_nonce( 'wp_rest' ),
		'siteURL' => site_url(),
	  ) );
	  
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );
  function pippin_create_post_form() {
	pippin_process_post_creation();

	?>
	<form id="pippin_create_post" action="" method="POST">
		<fieldset>
			<input name="job_title" id="job_title" type="text" required=""/>
			<label for="job_title">Job Title</label>
		</fieldset>
		<fieldset>
			<input name="user_name" id="user_name" type="text" required=""/>
			<label for="user_name">Your Name</label>
		</fieldset>
		<fieldset>
			<input name="user_email" id="user_email" type="email" required=""/>
			<label for="user_email">Your Email</label>
		</fieldset>
		<fieldset>
			<label for="job_desc">Job Description</label>
			<textarea name="job_desc" id="job_desc"></textarea>
		</fieldset>
		<fieldset>
			<input name="number" id="number" type="number" required=""/>
			<label for="number">contact number</label>
		</fieldset>
		<fieldset>
			<?php wp_nonce_field('jobs_nonce', 'jobs_nonce_field'); ?>
			<input type="submit" id="job_submit" name="job_submit" value="<?php _e('Submit Job Posting', 'pippin'); ?>"/>
		</fieldset>
	</form>
	<?php 
	//return ob_get_clean();
}
add_shortcode('post_form', 'pippin_create_post_form');

function pippin_process_post_creation() {
	if(isset($_POST['jobs_nonce_field']) && wp_verify_nonce($_POST['jobs_nonce_field'], 'jobs_nonce')) {
		
		if(strlen(trim($_POST['job_title'])) < 1 || strlen(trim($_POST['job_desc'])) < 1) {
			$redirect = add_query_arg('post', 'failed', home_url($_POST['_wp_http_referer']));
		} else {		
			$job_info = array(
				'post_title' => esc_attr(strip_tags($_POST['job_title'])),
				'post_type' => 'job',
				'post_content' => esc_attr(strip_tags($_POST['job_desc'])),
				'post_status' => 'publish'
			);
			$job_id = wp_insert_post($job_info);
			echo 'Saved your post successfully! :)';

		
	}
}
}

function user_form(){
	

 if (isset($_POST['job_submit'])) { 
//simple sanitize member form input
global $user_name, $username;
$user_name = sanitize_user( $_POST['user_name'] );
$email = get_post_meta($post_id, 'user_email', true);
$username= $email;
//$user_email = sanitize_email( $_POST['user_email'] );
$number = esc_attr( $_POST['number'] );

/*$memberdata = array(
'Username' => $user_name,
'Email' => $user_email,
'user_pass' => $password,
);*/
$member = wp_create_user( $user_name,$username,$number);
print_r($member);
    //   die('here');
echo 'Saved your user successfully! :)';

//}

}

}

add_action( 'init', 'user_form' );
function show_template() {
    if( is_super_admin() ){
        global $template;
        print_r($template);
    } 
}
add_action('wp_footer', 'show_template');