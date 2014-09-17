<?php
/**
* Support Desk Functions and definitions
* by Swish Themes (http://swishthemes.com)
*/



/**
* Set the content width based on the theme's design and stylesheet.
*/
if ( ! isset( $content_width ) ) $content_width = 700;

if ( ! function_exists( 'st_theme_setup' ) ):
/**
* Sets up theme defaults and registers support for various WordPress features.
*/
function st_theme_setup() {

	/**
	* If BBPress is active, add theme support
	*/
	if ( class_exists( 'bbPress' ) ) {
		add_theme_support( 'bbpress' );
	}
	
	/**
	* Custom Theme Options
	*/
	if ( !function_exists( 'optionsframework_init' ) ) {
		define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/framework/admin/' );
		require_once dirname( __FILE__ ) . '/framework/admin/options-framework.php';
	}
	
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 */
	load_theme_textdomain( 'framework', get_template_directory() . '/languages' );
	
	
	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );
	
	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 60, 60, true );
	
	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
			'primary-nav' => __( 'Primary Navigation', 'framework' ),
			'footer-nav' => __( 'Footer Navigation', 'framework' )
	));
	
	/**
	 * This theme styles the visual editor with editor-style.css to match the theme style.
	 */
	add_editor_style();

	
}
endif; // st_theme_setup
add_action( 'after_setup_theme', 'st_theme_setup' );


/**
 * Thumbnail Sizes
 */

if ( function_exists( 'add_theme_support' ) ) {
	add_image_size( 'post', 700, '300', true ); // Post thumbnail	
}


/**
 * Add theme customizer
 */
 
require("framework/theme-customizer.php");


/**
 * Enqueues scripts and styles for front-end.
 */
 
require("framework/scripts.php");
require("framework/styles.php");

/**
* If BBPress is active, load functions
*/
if ( class_exists( 'bbPress' ) ) {
require_once (get_template_directory() . '/bbpress/bbpress-functions.php');
}

/**
 * Register widgetized area and update sidebar with default widgets
 */

require("framework/register-sidebars.php");


/**
 * Add responsive menu functions
 */
//require("framework/responsive-menu.php");


/**
 * Add Template Navigation Functions
 */
 
require("framework/template-navigation.php");


/**
 * Comment Functions
 */
 
require("framework/comment-functions.php");


/**
 * Add class if post has thumbnail
 */

function st_thumb_class($classes) {
	global $post;
	if( has_post_thumbnail($post->ID) ) { $classes[] = 'has_thumb'; }

		return $classes;
}
add_filter('post_class', 'st_thumb_class');



/**
 * Add post types
 */
require("framework/post-types.php");


/**
 * Add metabox library
 */

function st_initialize_cmb_meta_boxes() {
	if ( !class_exists( 'cmb_Meta_Box' ) ) {
		require_once( 'framework/post-meta/library/init.php' );
	}
}
add_action( 'init', 'st_initialize_cmb_meta_boxes', 9999 );

/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';

}

// Add Meta Box Componenets
require("framework/post-meta/page-meta.php");
require("framework/post-meta/faq-meta.php");
require("framework/post-meta/hpblock-meta.php");

/**
 * Add Widget Functions
 */ 
require("framework/widgets/widget-functions.php");

/**
 * Adds theme shortcodes
 * (will be mvoed to plugin soon)
 */
 
require("framework/shortcodes/shortcodes.php");

// Add shortcode manager
require("framework/shortcodes/wysiwyg/wysiwyg.php");


/**
 * Removes inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Skeleton's style.css. This is just
 * a simple filter call that tells WordPress to not use the default styles.
 *
 */
add_filter( 'use_default_gallery_style', '__return_false' );



/**
 * KB Pagination
 */ 
function change_posttype() {
  if( is_tax('st_kb_category') && !is_admin() ) {
    set_query_var( 'post_type', array( 'post', 'st_kb' ) );
  }
  return;
}
add_action( 'parse_query', 'change_posttype' );

function my_post_queries( $query ) {
$st_kb_ppp = of_get_option('st_kb_articles_per_page');
	if(is_tax('st_kb_category')){
      // show 50 posts on custom taxonomy pages
      $query->set('posts_per_page', $st_kb_ppp);
    }
  }
add_action( 'pre_get_posts', 'my_post_queries' );


/**
 * Root search queries to seperate templates
 */ 
function st_search_template_chooser($template)
{
  global $wp_query;
  $post_type = get_query_var('post_type');
  
  // If posts
  if( $wp_query->is_search && $post_type == 'post' )
  {
    return locate_template('search-posts.php');
  }
  // If KB
  elseif( $wp_query->is_search && $post_type == 'st_kb' )
  {
    return locate_template('search-st_kb.php');
  }
  // If Forum
  elseif ( class_exists( 'bbPress' ) ) {
		if ( bbp_is_search() ) {
			return locate_template('search-topics.php'); 
		}
  } 
  return $template;
}
add_filter('template_include', 'st_search_template_chooser');


/**
 * Add post views
 */
 
function st_set_post_views($postID) {
    $count_key = '_st_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 1;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '1');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

//To keep the count accurate, lets get rid of prefetching
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

function st_get_post_views($postID){
    $count_key = '_st_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '1');
        return "1 View";
    }
    return $count.' Views';
}

// Add post views column to KB admin
add_filter('manage_edit-st_kb_columns', 'st_kb_admin_columns');
    function st_kb_admin_columns($columns) {
		$new_columns = array(
		'views' => __('Views', 'framework'),
	);
	
    return array_merge($columns, $new_columns);

}
add_action('manage_posts_custom_column', 'st_show_kb_admin_columns');
	function st_show_kb_admin_columns($name) {
		global $post;
		switch ($name) {
		case 'views':
			$views = get_post_meta($post->ID, '_st_post_views_count', true);
			if ($views) {
				echo $views .__(' View', 'framework');
			} else {
				echo __('No Views', 'framework');
			}
		}
}


// show events menu in admin bar only for admins

if (!current_user_can('manage_options')) {
  define('TRIBE_DISABLE_TOOLBAR_ITEMS', true);
}

add_filter('wpmu_validate_user_signup', 'skip_email_exist');
function skip_email_exist($result){
    if(isset($result['errors']->errors['user_email']) && ($key = array_search(__('Sorry, that email address is already used!'), $result['errors']->errors['user_email'])) !== false) {
        unset($result['errors']->errors['user_email'][$key]);
        if (empty($result['errors']->errors['user_email'])) unset($result['errors']->errors['user_email']);
    }
    define( 'WP_IMPORTING', 'SKIP_EMAIL_EXIST' );
    return $result;
}

/**
 * Multi-page Navigation
 * http://gravitywiz.com/
 */

class GWMultipageNavigation {

    private $script_displayed;

    function __construct( $args = array() ) {

        $form_ids = false;
        if( isset( $args['form_id'] ) ) {
            $form_ids = is_array( $args['form_id'] ) ? $args['form_id'] : array( $args['form_id'] );
        }

        if( !empty($form_ids) ) {
            foreach( $form_ids as $form_id ) {
                add_filter("gform_pre_render_{$form_id}", array( &$this, 'output_navigation_script' ), 10, 2 );
                //add_filter('gform_register_init_scripts', array( &$this, 'register_script') );
            }
        } else {
            add_filter('gform_pre_render', array( &$this, 'output_navigation_script' ), 10, 2 );
        }

    }

    function output_navigation_script( $form, $is_ajax ) {

        // only apply this to multi-page forms
        if( count($form['pagination']['pages']) <= 1 )
            return $form;

        $this->register_script( $form );

        if( $this->is_last_page( $form ) || $this->is_last_page_reached() ) {
            $input = '<input id="gw_last_page_reached" name="gw_last_page_reached" value="1" type="hidden" />';
            add_filter( "gform_form_tag_{$form['id']}", create_function('$a', 'return $a . \'' . $input . '\';' ) );
        }

        // only output the gwmpn object once regardless of how many forms are being displayed
        // also do not output again on ajax submissions
        if( $this->script_displayed || ( $is_ajax && rgpost('gform_submit') ))
            return $form;

        ?>

        <script type="text/javascript">

            (function($){

                window.gwmpnObj = function( args ) {

                    this.formId = args.formId;
                    this.formElem = jQuery('form#gform_' + this.formId);
                    this.currentPage = args.currentPage;
                    this.lastPage = args.lastPage;
                    this.labels = args.labels;

                    this.init = function() {

                        // if this form is ajax-enabled, we'll need to get the current page via JS
                        if( this.isAjax() )
                            this.currentPage = this.getCurrentPage();

                        if( !this.isLastPage() && !this.isLastPageReached() )
                            return;

                        var gwmpn = this;
                        var steps = $('form#gform_' + this.formId + ' .gf_step');

                        steps.each(function(){

                            var stepNumber = parseInt( $(this).find('span.gf_step_number').text() );

                            if( stepNumber != gwmpn.currentPage ) {
                                $(this).html( gwmpn.createPageLink( stepNumber, $(this).html() ) )
                                    .addClass('gw-step-linked');
                            } else {
                                $(this).addClass('gw-step-current');
                            }

                        });

                        if( !this.isLastPage() )
                            this.addBackToLastPageButton();

                        $(document).on('click', '#gform_' + this.formId + ' a.gwmpn-page-link', function(event){
                            event.preventDefault();

                            var hrefArray = $(this).attr('href').split('#');
                            if( hrefArray.length >= 2 ) {
                                var pageNumber = hrefArray.pop();
                                gwmpn.postToPage( pageNumber );
                            }

                        });

                    }

                    this.createPageLink = function( stepNumber, HTML ) {
                        return '<a href="#' + stepNumber + '" class="gwmpn-page-link">' + HTML + '</a>';
                    }

                    this.postToPage = function(page) {
                        this.formElem.append('<input type="hidden" name="gw_page_change" value="1" />');
                        this.formElem.find('input[name="gform_target_page_number_' + this.formId + '"]').val(page);
                        this.formElem.submit();
                    }

                    this.addBackToLastPageButton = function() {
                        this.formElem.find('#gform_page_' + this.formId + '_' + this.currentPage + ' .gform_page_footer')
                            .append('<input type="button" onclick="gwmpn.postToPage(' + this.lastPage + ')" value="' + this.labels.lastPageButton + '" class="button gform_last_page_button">');
                    }

                    this.getCurrentPage = function() {
                        return this.formElem.find( 'input#gform_source_page_number_' + this.formId ).val();
                    }

                    this.isLastPage = function() {
                        return this.currentPage >= this.lastPage;
                    }

                    this.isLastPageReached = function() {
                        return this.formElem.find('input[name="gw_last_page_reached"]').val() == true;
                    }

                    this.isAjax = function() {
                        return this.formElem.attr('target') == 'gform_ajax_frame_' + this.formId;
                    }

                    this.init();

                }

            })(jQuery);

        </script>

        <?php
        $this->script_displayed = true;
        return $form;
    }

    function register_script( $form ) {

        $page_number = GFFormDisplay::get_current_page($form['id']);
        $last_page = count($form['pagination']['pages']);

        $args = array(
            'formId' => $form['id'],
            'currentPage' => $page_number,
            'lastPage' => $last_page,
            'labels' => array(
                'lastPageButton' => __('Back to Last Page')
                )
            );

        $script = "window.gwmpn = new gwmpnObj(" . json_encode( $args ) . ");";
        GFFormDisplay::add_init_script( $form['id'], 'gwmpn', GFFormDisplay::ON_PAGE_RENDER, $script );

    }

    function is_last_page( $form ) {

        $page_number = GFFormDisplay::get_current_page($form['id']);
        $last_page = count($form['pagination']['pages']);

        return $page_number >= $last_page;
    }

    function is_last_page_reached() {
        return rgpost('gw_last_page_reached');
    }

}

$gw_multipage_navigation = new GWMultipageNavigation();

/**
* Gravity Wiz // Gravity Forms Unrequire Required Fields for Testing 
* 
* When bugs pop up on your forms, it can be really annoying to have to fill out all the required fields for every test
* submission. This snippet saves you that hassle by unrequiring all required fields so you don't have to fill them out.
* 
* @version   1.0
* @author    David Smith <david@gravitywiz.com>
* @license   GPL-2.0+
* @link      http://gravitywiz.com/...
* @copyright 2013 Gravity Wiz
*/

class GWUnrequire {
    
    var $args = null;
    
    public function __construct( $args = array() ) {
        
        extract( wp_parse_args( $args, array(
            'admins_only' => true,
            'require_query_param' => true
        ) ) );
        
        if( $admins_only && ! current_user_can( 'activate_plugins' ) )
            return;
        
        if( $require_query_param && ! isset( $_GET['gwunrequire'] ) )
            return;
        
        add_filter( 'gform_pre_validation', array( $this, 'unrequire_fields' ) );
        
    }
    
    function unrequire_fields( $form ) {
        
        foreach( $form['fields'] as &$field ) {
            $field['isRequired'] = false;
        }
        
        return $form;
    }
        
}

new GWUnrequire();

register_nav_menus( array(
        'primary' => __( 'Primary Navigation', 'twentyten' ),
        'members' => __( 'Members Only Navigation', 'twentyten' ),
    ) );