<?php
/*
Plugin Name: SGP portfolio
Plugin URI: http://www.chayaneng.com
Author : Chayan Biswas
Author URI: http://www.facebook.com
Description: You will find the very simple but effective portfolio shortcodes in this plugin SGP-Grid Portfolio.
textdomain:sgp_portfolio_textdomain
Tags: Grid, Portfolio, Column, Simple Portfolio.
Version: 3.0
*/

add_action('wp_enqueue_scripts', 'sgp_style_script');
function sgp_style_script() {

	wp_enqueue_style( 'sgp_styles', plugins_url('css/component.css', __FILE__) );
	wp_enqueue_style( 'normalize', plugins_url('css/normalize.css', __FILE__) );


	wp_enqueue_script( 'classie', plugins_url('js/classie.js', __FILE__), array('masonry.pkgd.min','imagesloaded' ), '', true );
	wp_enqueue_script( 'colorfinder', plugins_url('js/colorfinder-1.1.js', __FILE__), array('masonry.pkgd.min','imagesloaded','classie' ), '', true );
	wp_enqueue_script( 'gridScrollFx', plugins_url('js/gridScrollFx.js', __FILE__), array('masonry.pkgd.min','imagesloaded','classie','colorfinder' ), '', true );
	wp_enqueue_script( 'imagesloaded', plugins_url('js/imagesloaded.pkgd.min.js', __FILE__), array('masonry.pkgd.min',), '', true );
	wp_enqueue_script( 'masonry.pkgd.min', plugins_url('js/masonry.pkgd.min.js', __FILE__), array('jquery'), '', true );
	wp_enqueue_script( 'modernizr', plugins_url('js/modernizr.custom.js', __FILE__), array(), '1.0', false );
	wp_enqueue_script( 'initiate', plugins_url('js/initiate.js', __FILE__), array('masonry.pkgd.min','imagesloaded','classie','colorfinder','gridScrollFx' ), '', true );

}

add_action('admin_enqueue_scripts', 'sgp_admin_css_js');

function sgp_admin_css_js() {
	wp_enqueue_script('custom-scripts', plugins_url('js/custom.js', __FILE__), array('jquery'), '1.0', true);
}

// load plugin textdmain
add_action('init', 'sgp_plugin_textdomain');

function sgp_plugin_textdomain() {
	load_plugin_textdomain('sgp_portfolio_textdomain', false, dirname( __FILE__).'/lang');

}

// The portfolio custom post type
add_action('init', 'sgp_custom_portfolio');

function sgp_custom_portfolio() {

	$labels = array(
		'name'               => _x( 'SGP Portfolios', 'post type general name', 'sgp_portfolio_textdomain' ),
		'singular_name'      => _x( 'SGP Portfolio', 'post type Singlular name', 'sgp_portfolio_textdomain' ),
		'menu_name'          => _x( 'SGP Portfolios', 'admin menu', 'sgp_portfolio_textdomain' ),
		'name_admin_bar'     => _x( 'SGP Portfolio', 'add new on admin bar', 'sgp_portfolio_textdomain' ),
		'add_new'            => _x( 'Add New', 'portfolio', 'sgp_portfolio_textdomain' ),
		'add_new_item'       => __( 'Add New portfolio', 'sgp_portfolio_textdomain' ),
		'new_item'           => __( 'New portfolio', 'sgp_portfolio_textdomain' ),
		'edit_item'          => __( 'Edit portfolio', 'sgp_portfolio_textdomain' ),
		'view_item'          => __( 'View portfolio', 'sgp_portfolio_textdomain' ),
		'all_items'          => __( 'All portfolios', 'sgp_portfolio_textdomain' ),
		'search_items'       => __( 'Search portfolios', 'sgp_portfolio_textdomain' ),
		'parent_item_colon'  => __( 'Parent portfolios:', 'sgp_portfolio_textdomain' ),
		'not_found'          => __( 'No portfolios found.', 'sgp_portfolio_textdomain' ),
		'not_found_in_trash' => __( 'No portfolios found in Trash.', 'sgp_portfolio_textdomain' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Description.', 'sgp_portfolio_textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 20,
		'supports'           => array( 'title', 'editor','thumbnail' )
	);


	register_post_type('sgp_portfolio', $args);


	// Registration of the taxonomy
	$labels = array(
		'name'					=> _x( 'Types', 'Taxonomy Types', 'text-domain' ),
		'singular_name'			=> _x( 'Type', 'Taxonomy Type', 'text-domain' ),
		'search_items'			=> __( 'Search Types', 'text-domain' ),
		'popular_items'			=> __( 'Popular Types', 'text-domain' ),
		'all_items'				=> __( 'All Types', 'text-domain' ),
		'parent_item'			=> __( 'Parent Type', 'text-domain' ),
		'parent_item_colon'		=> __( 'Parent Type', 'text-domain' ),
		'edit_item'				=> __( 'Edit Type', 'text-domain' ),
		'update_item'			=> __( 'Update Type', 'text-domain' ),
		'add_new_item'			=> __( 'Add New Type', 'text-domain' ),
		'new_item_name'			=> __( 'New Type Name', 'text-domain' ),
		'add_or_remove_items'	=> __( 'Add or remove Types', 'text-domain' ),
		'choose_from_most_used'	=> __( 'Choose from most used types', 'text-domain' ),
		'menu_name'				=> __( 'Type', 'text-domain' ),
	);

	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_admin_column' => false,
		'hierarchical'      => false,
		'show_tagcloud'     => true,
		'show_ui'           => true,
		'query_var'         => true,
		'rewrite'           => true,
		'query_var'         => true
	);

	register_taxonomy( 'spp-portfolio-type', array( 'sgp_portfolio' ), $args );
}

register_activation_hook(__FILE__, 'sgp_flush_url_rules');
register_deactivation_hook(__FILE__, 'sgp_flush_url_rules');

function sgp_flush_url_rules() {
	sgp_custom_portfolio();
	flush_rewrite_rules();

}

//custom template for the single post of the custom post type

add_filter('single_template', 'sgp_portfolio_single', 11, 1);

function sgp_portfolio_single( $single_template ) {
	global $post;
	if( $post->post_type == 'sgp_portfolio') {

		$single_template = dirname(__FILE__).'/single-'.$post->post_type.'.php';
	}
	return $single_template;
}

// Custom meta boxes for the portfolio post type
add_action('add_meta_boxes', 'sgp_custom_meta_boxes');
function sgp_custom_meta_boxes() {

	add_meta_box(
		'spp-meta',
		__('Widgets area', 'sgp_portfolio_textdomain'),
		'sgp_meta_callback',
		'sgp_portfolio',
		'side',
		'high'
	);
}

function sgp_meta_callback( $post ) {
	wp_nonce_field('sgp-portfolio-nonce', 'sgp-portfolio');
	?>
		<p>
			<?php 
			$db_value = get_post_meta( $post->ID, '_sgp_widgets_show', true );
			$value = isset( $db_value  ) ? $db_value : ''; ?>
			<input type="checkbox" value="1" name="_sgp_widgets_show" id="sgp_widgets_show" <?php echo $value == '1' ? 'checked' : ''; ?>><label for="sgp_widgets_show">Show widget?</label>
		</p>
		<p class="sgp_widgets_select">
			<label for="sgp_widgets">Select the Widgets</label>
			<select name="_sgp_widgets_another" id="sgp_widgets" class="regular-text">
				<?php 
					if( $GLOBALS['wp_registered_sidebars'] ) {
						$selected = get_post_meta( $post->ID, '_sgp_widgets_another', true);
						$sidebars = $GLOBALS['wp_registered_sidebars'];
						foreach( $sidebars as $sidebar ) {
							if( $selected == $sidebar['id'] ) {
								echo '<option value="'.$sidebar['id'].'" selected>'.$sidebar['name'].'</option>';
							} else {
								echo '<option value="'.$sidebar['id'].'" >'.$sidebar['name'].'</option>';
							}
						}

					}
				?>
			</select>
			
		</p>


	<?php
}

add_action('save_post', 'sgp_custom_meta_save');
function sgp_custom_meta_save( $post_id ) {

	if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

	if( !isset( $_POST['sgp-portfolio']) || !wp_verify_nonce( $_POST['sgp-portfolio'], 'sgp-portfolio-nonce') ) return;
	if( !current_user_can('manage_options') ) {
		return;
	}

	if( isset( $_REQUEST['_sgp_widgets_another'] ) ) {
		$value = sanitize_meta('_sgp_widgets_another', $_REQUEST['_sgp_widgets_another'], 'user_widget');
		update_post_meta($post_id, '_sgp_widgets_another', $value );
	}

	$old_value = isset( $_REQUEST['_sgp_widgets_show'] ) ? sanitize_text_field( $_REQUEST['_sgp_widgets_show'] ) : '';
	update_post_meta( $post_id, '_sgp_widgets_show', $old_value );
		


}

add_filter('sanitize_user_widget_meta__sgp_widgets_another', 'select_widget_sanitization');
function select_widget_sanitization( $old_value ) {
	$value = sanitize_text_field( $old_value );
	$sidebars = $GLOBALS['wp_registered_sidebars'];
	$sidebar_array = array();
	foreach( $sidebars as $sidebar ) {
		$sidebar_array[] = $sidebar['id'];
	}
	if( !in_array($value, $sidebar_array) ) {
		wp_die('Invalid user Input, go back and try agian!!');
	}
	return $value;
}

// Get the register sidebar in the theme and show the specific one
function sgp_registered_siderbar( $set, $show) {
	if( $show == 1 ):
		if( $GLOBALS['wp_registered_sidebars'] ) {
			$sidebars = $GLOBALS['wp_registered_sidebars'];
			foreach( $sidebars as $key => $sidebar_values ) {
				if( $key == $set ) {
					dynamic_sidebar( $set );
				}
			}
		}
	endif;
}


// shortcodes for the portfolio section
add_shortcode('sgp-portfolio', 'sgp_portfolio_sht_funct', 10, 2);

function sgp_portfolio_sht_funct( $attrs, $content ) {

	ob_start(); ?>
	<section class="grid-wrap">
		<ul class="grid swipe-down" id="grid">		
			<?php 
			$portfolios = new WP_Query( array(
				'post_type'			=> 'sgp_portfolio',
				'posts_per_page'	=> -1
			));

			while( $portfolios->have_posts() ): $portfolios->the_post();
			?>
			<li>
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?><h3><?php the_title(); ?></h3>
				</a>
			</li>

			<?php endwhile; wp_reset_query(); ?>
		</ul>
	</section>
	<?php return apply_filters( 'sgp_portfolio_edit', ob_get_clean() );
}


add_action('wp_head', 'sgp_custom_template_styles');
function custom_template_styles() {
	global $post;
	$type = get_post_type( $_REQUEST['post_id'] );
	if( $type == 'sgp_portfolio'):
	?>
	<style>
		select#spp_widgets {
			width: 52%;
		}
		.sgp_content_area {
		    float: none;
		    width: 100%;
		    margin-right: 0%;
		}
		.sgp_widgets {
		    float: none;
		    width: 100%;
		}
		.sgp_content_area.widget-show {
		    float: left;
		    width: 69%;
		    margin-right: 1%;
		}
		.sgp_widgets.widget-show {
		    float: left;
		    width: 30%;
		}
		header.sgp-entry-header {
		    margin-bottom: 10px;
		    font-size: 23px;
		    letter-spacing: 1px;
		    background: rgba(0, 0, 0, 0.53);
		    color: white;
		}
		header.sgp-entry-header {
			padding: 5px;
		}
	</style>

	<script>
		<?php 
		$value = get_post_meta( $post->ID, '_sgp_widgets_show', true );
		$widget_show = isset( $value ) ? $value : '';
		if( $widget_show == '1' ) : ?>
		;(function( $ ) {
			$(document).ready(function() {
				$('.sgp_content_area, .sgp_widgets').addClass('widget-show');
			});
		})(jQuery);
		<?php endif; ?>
	</script>
	<?php
	endif;
}