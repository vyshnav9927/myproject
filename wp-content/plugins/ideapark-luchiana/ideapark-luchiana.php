<?php
/*
 * Plugin Name: Luchiana Core
 * Version: 4.4.1
 * Description: Core plugin for Luchiana theme.
 * Author: parkofideas.com
 * Author URI: http://parkofideas.com
 * Text Domain: ideapark-luchiana
 * Domain Path: /lang/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'IDEAPARK_LUCHIANA_FUNC_VERSION', '4.4.1' );

define( 'IDEAPARK_LUCHIANA_FUNC_IS_AJAX', function_exists( 'wp_doing_ajax' ) ? wp_doing_ajax() : ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) );
define( 'IDEAPARK_LUCHIANA_FUNC_IS_AJAX_HEARTBEAT', IDEAPARK_LUCHIANA_FUNC_IS_AJAX && ! empty( $_POST['action'] ) && ( $_POST['action'] == 'heartbeat' ) );

$theme_obj = wp_get_theme();

if ( empty( $theme_obj ) || strtolower( $theme_obj->get( 'TextDomain' ) ) != 'luchiana' && strtolower( $theme_obj->get( 'TextDomain' ) ) != 'luchiana-child' ) {

	add_filter( 'plugin_row_meta', function ( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ) {
			$row_meta = [
				'warning' => '<b style="vertical-align:middle;display:inline-flex;align-items:center;border:solid 2px #dc3545;padding: 2px 10px;color: #dc3545"><span class="dashicons dashicons-warning" style="margin-right: 5px;"></span>' . esc_html__( 'The Luchiana theme is not activated! This plugin works only with Luchiana theme', 'ideapark-luchiana' ) . '</b>',
			];

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}, 10, 2 );

	return;
}

if ( ! empty( $theme_obj ) && version_compare( IDEAPARK_LUCHIANA_FUNC_VERSION, $theme_obj->parent() ? $theme_obj->parent()->get( 'Version' ) : $theme_obj->get( 'Version' ), '!=' ) ) {

	add_filter( 'plugin_row_meta', function ( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ) {
			$row_meta = [
				'warning' => '<b style="vertical-align:middle;display:inline-flex;align-items:center;border:solid 2px #dc3545;padding: 2px 10px;color: #dc3545;"><span class="dashicons dashicons-warning" style="margin-right: 5px;"></span>' . sprintf( esc_html__( 'The Luchiana theme version and the theme core plugin version must be the same. Please update the plugin to version %s', 'ideapark-luchiana' ), IDEAPARK_VERSION ) . '</b>',
			];

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}, 10, 2 );
}

$ip_dir = dirname( __FILE__ );

require_once( $ip_dir . '/elementor/elementor.php' );
require_once( $ip_dir . '/importer/importer.php' );
require_once( $ip_dir . '/includes/class-ideapark.php' );
require_once( $ip_dir . '/includes/svg-support.php' );
require_once( $ip_dir . '/includes/class-ideapark-post-type.php' );
require_once( $ip_dir . '/includes/class-ideapark-taxonomy.php' );
require_once( $ip_dir . '/includes/mb-settings-page/mb-settings-page.php' );
require_once( $ip_dir . '/includes/meta-box-group/meta-box-group.php' );
require_once( $ip_dir . '/includes/class-ideapark-custom-fonts.php' );
require_once( $ip_dir . '/includes/variation-gallery/class-ideapark-variation-gallery.php' );

function Ideapark_Luchiana_Elementor() {
	$instance = Ideapark_Elementor::instance( __FILE__, IDEAPARK_LUCHIANA_FUNC_VERSION );

	return $instance;
}

Ideapark_Luchiana_Elementor();

function Ideapark_Luchiana() {
	$instance = Ideapark_Luchiana::instance( __FILE__, IDEAPARK_LUCHIANA_FUNC_VERSION );

	return $instance;
}

Ideapark_Luchiana();

function Ideapark_Luchiana_Importer() {
	$instance = Ideapark_Importer::instance( __FILE__, IDEAPARK_LUCHIANA_FUNC_VERSION );

	return $instance;
}

Ideapark_Luchiana_Importer();

if ( ! function_exists( 'ideapark_woocommerce_on' ) ) {
	function ideapark_woocommerce_on() {
		return class_exists( 'WooCommerce' );
	}
}

if ( ! function_exists( 'ideapark_swatches_plugin_on' ) ) {
	function ideapark_swatches_plugin_on() {
		return function_exists( 'woo_variation_swatches' ) && defined( 'WOO_VARIATION_SWATCHES_PLUGIN_VERSION' ) ? 1 : 0;
	}
}

if ( ! function_exists( 'ideapark_check_plugin_version' ) ) {
	function ideapark_check_plugin_version() {
		global $wpdb;
		$current_version = get_option( 'ideapark_luchiana_plugin_version', '' );
		if ( ! defined( 'IFRAME_REQUEST' ) && ! IDEAPARK_LUCHIANA_FUNC_IS_AJAX_HEARTBEAT && ( version_compare( $current_version, IDEAPARK_LUCHIANA_FUNC_VERSION, '<' ) ) ) {

			// Check if we are not already running this routine.
			if ( 'yes' === get_transient( 'ideapark_luchiana_installing' ) ) {
				return;
			}
			set_transient( 'ideapark_luchiana_installing', 'yes', MINUTE_IN_SECONDS * 10 );


			delete_transient( 'ideapark_luchiana_installing' );
			update_option( 'ideapark_luchiana_plugin_version', IDEAPARK_LUCHIANA_FUNC_VERSION );
		}
	}

	add_action( 'init', 'ideapark_check_plugin_version', 998 );
}

if ( ! function_exists( 'ideapark_plugin_widgets_init' ) ) {
	function ideapark_plugin_widgets_init() {
		$ip_dir = dirname( __FILE__ );
		include_once( $ip_dir . "/widgets/latest-posts-widget.php" );
		if ( ideapark_woocommerce_on() && class_exists( 'WC_Widget' ) ) {
			include_once( $ip_dir . "/widgets/wc-color-filter-widget.php" );
		}
	}

	add_action( 'widgets_init', 'ideapark_plugin_widgets_init' );
}

if ( ! function_exists( 'ideapark_init_custom_post_types' ) ) {
	function ideapark_init_custom_post_types() {

		Ideapark_Luchiana()->register_post_type(
			'html_block',
			esc_html__( 'HTML Blocks', 'ideapark-luchiana' ),
			esc_html__( 'HTML Block', 'ideapark-luchiana' ),
			esc_html__( 'Static HTML blocks for using in widgets and in templates', 'ideapark-luchiana' ),
			[
				'menu_icon'           => 'dashicons-media-code',
				'public'              => true,
				'hierarchical'        => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => true,
				'menu_position'       => 5,
				'capability_type'     => 'post',
				'supports'            => [
					'title',
					'editor',
				],
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
			]
		);
	}

	ideapark_init_custom_post_types();

	add_action( 'save_post_html_block', function () { //todo-me disable when Elementor fix this bug
		static $is_cleared;
		if ( empty( $is_cleared ) && ideapark_is_elementor() ) {
			$elementor_instance = Elementor\Plugin::instance();
			$elementor_instance->files_manager->clear_cache();
			$is_cleared = true;
		}
	}, 99 );
}

if ( ! function_exists( 'ideapark_set_details_transient' ) ) {
	function ideapark_set_details_transient() {
		$details_list = [];
		$terms        = get_terms( [
			'taxonomy'   => 'detail',
			'hide_empty' => false,
		] );
		foreach ( $terms as $term ) {
			$meta = get_term_meta( $term->term_id );

			$image = null;
			if ( ! empty( $meta['image'][0] ) ) {
				if ( get_post_mime_type( $meta['image'][0] ) == 'image/svg+xml' ) {
					$image = ideapark_get_inline_svg( $meta['image'][0], 'c-item-details__svg' );
				} else {
					$image_srcset = wp_get_attachment_image_srcset( $meta['image'][0], 'thumbnail' );
					$attr         = [];
					if ( ideapark_mod( 'lazyload' ) ) {
						$attr['class']       = 'c-item-details__image lazyload';
						$attr['data-srcset'] = $image_srcset;
						$attr['src']         = ideapark_empty_gif();
					} else {
						$attr['class']  = 'c-item-details__image';
						$attr['srcset'] = $image_srcset;
					}

					$attachment = get_post( $meta['image'][0] );
					if ( ! $attachment ) {
						return '';
					}

					$attr['alt'] = get_post_meta( $meta['image'][0], '_wp_attachment_image_alt', true );
					if ( ! $attr['alt'] ) {
						$attr['alt'] = $attachment->post_excerpt;
						if ( ! $attr['alt'] ) {
							$attr['alt'] = $attachment->post_title;
						}
					}
					$attr['alt']   = trim( strip_tags( $attr['alt'] ) );
					$attr['title'] = get_the_title( $meta['image'][0] );

					$image = '<img ' . ideapark_html_attributes( $attr ) . '>';
				}
			}

			$args = [
				'name'  => $term->name,
				'icon'  => ! empty( $meta['font-icon'][0] ) ? '<i class="c-item-details__icon ' . esc_attr( $meta['font-icon'][0] ) . '"></i>' : null,
				'image' => $image
			];
			if ( ! empty( $meta['show_in_list'][0] ) ) {
				$details_list[ $term->slug ] = $args;
			}
		}

		set_transient( 'ideapark_details_list', $details_list );

		return $details_list;
	}
}

if ( ! function_exists( 'ideapark_plugin_custom_meta_boxes' ) ) {
	function ideapark_plugin_custom_meta_boxes( $meta_boxes ) {

		// Product Metaboxes

		$meta_boxes[] = [
			'title'      => __( 'Product Video', 'ideapark-luchiana' ),
			'post_types' => 'product',
			'priority'   => 'low',
			'context'    => 'side',
			'fields'     => [
				[
					'name' => __( 'Video URL', 'ideapark-luchiana' ),
					'desc' => __( 'Enter the url to product video (Youtube, Vimeo etc.).', 'ideapark-luchiana' ),
					'id'   => '_ip_product_video_url',
					'type' => 'text',
				],
				[
					'name'             => __( 'Video Thumbnail', 'ideapark-luchiana' ),
					'id'               => '_ip_product_video_thumb',
					'desc'             => __( 'Leave blank to use the standard thumbnail from youtube.', 'ideapark-luchiana' ),
					'type'             => 'image_advanced',
					'max_file_uploads' => 1,
					'force_delete'     => false,
					'max_status'       => false,
				],
			],
			'validation' => [
				'rules' => [
					'_ip_product_video_url' => [
						'url' => true,
					],
				],
			]
		];


		// Post Metaboxes

		$meta_boxes[] = [
			'title'      => __( 'Youtube Video URL', 'ideapark-luchiana' ),
			'post_types' => 'post',
			'priority'   => 'high',
			'fields'     => [
				[
					'name' => '',
					'id'   => 'post_video_url',
					'type' => 'text',
				],
			],
			'validation' => [
				'rules' => [
					'post_video_url' => [
						'url' => true,
					],
				],
			]
		];

		$meta_boxes[] = [
			'title'      => __( 'Image Gallery', 'ideapark-luchiana' ),
			'post_types' => 'post',
//			'context'    => 'side',
			'priority'   => 'high',
			'fields'     => [
				[
					'name'         => '',
					'id'           => 'post_image_gallery',
					'type'         => 'image_advanced',
					'force_delete' => false,
					'max_status'   => false,
					'image_size'   => 'thumbnail',
				],
			]
		];

		// Customizer Metaboxes

		$fonts_info = get_option( 'ideapark_fonts_info' );
		$icons      = [];

		if ( ! empty( $fonts_info['fonts'] ) ) {

			foreach ( $fonts_info['fonts'] as $_font_name => $_font ) {
				foreach ( $_font['unicodes'] as $class_name => $code ) {
					$icons[ $class_name ] = $class_name;
				}
			}
		}

		$meta_boxes[] = [
			'id'     => 'ideapark_section_woocommerce',
			'title'  => __( 'Feature', 'ideapark-luchiana' ),
			'panel'  => '',
			'fields' => [
				[
					'id'         => 'product_feature',
					'type'       => 'group',
					'clone'      => true,
					'sort_clone' => false,
					'fields'     => [
						[
							'name'            => __( 'Icon', 'ideapark-luchiana' ),
							'id'              => 'font-icon',
							'type'            => 'select_advanced',
							'options'         => $icons,
							'multiple'        => false,
							'select_all_none' => false,
							'js_options'      => [
								'templateResult' => 'ideaparkSelectWithIcons',
							]
						],
						[
							'name' => __( 'Name', 'ideapark-luchiana' ),
							'id'   => 'name',
							'type' => 'text',
						],
						[
							'name' => __( 'Description', 'ideapark-luchiana' ),
							'id'   => 'description',
							'type' => 'text',
						],
					],
				],
			],
		];

		return $meta_boxes;
	}

	add_filter( 'rwmb_meta_boxes', 'ideapark_plugin_custom_meta_boxes', 99 );
}

if ( ! function_exists( 'ideapark_manage_extra_column' ) ) {
	function ideapark_manage_extra_column( $column_name, $post_id ) {
		if ( $column_name == 'img' ) {
			echo get_the_post_thumbnail( $post_id, 'thumbnail' );
		}
	}

	add_filter( 'manage_posts_custom_column', 'ideapark_manage_extra_column', 10, 2 );
}

if ( ! function_exists( 'ideapark_contact_methods' ) ) {
	function ideapark_contact_methods( $contactmethods ) {

		if ( function_exists( 'ideapark_social_networks' ) ) {
			foreach ( ideapark_social_networks() as $code => $name ) {
				$contactmethods[ $code ] = $name;
			}
		}

		return $contactmethods;
	}

	add_filter( 'user_contactmethods', 'ideapark_contact_methods', 10, 1 );
}

if ( ! function_exists( 'ideapark_admin_enqueue_scripts' ) ) {
	function ideapark_admin_enqueue_scripts( $hook ) {
		if (
			( $hook == 'post.php' || $hook == 'post-new.php' )
		) {
			$assets_url = esc_url( trailingslashit( plugins_url( '/assets/', __FILE__ ) ) );

			$screen = get_current_screen();
			if ( is_object( $screen ) && $screen->post_type == 'post' ) {
				wp_enqueue_style( 'ideapark-luchiana-post-format', esc_url( $assets_url ) . 'css/post-format.css', [], IDEAPARK_LUCHIANA_FUNC_VERSION );
				wp_enqueue_script( 'ideapark-luchiana-post-format', esc_url( $assets_url ) . 'js/post-format.js', [
					'jquery',
					'wp-editor'
				], IDEAPARK_LUCHIANA_FUNC_VERSION, true );
			}
		}
	}

	add_action( 'admin_enqueue_scripts', 'ideapark_admin_enqueue_scripts', 10, 1 );
}

if ( ! function_exists( 'ideapark_get_cookie_params' ) ) {
	function ideapark_get_cookie_params() {
		static $params;
		if ( empty( $params ) ) {
			$params['sort']     = ! empty( $_COOKIE[ 'sort_' . COOKIEHASH ] ) ? $_COOKIE[ 'sort_' . COOKIEHASH ] : 'newest';
			$params['limit']    = ! empty( $_COOKIE[ 'limit_' . COOKIEHASH ] ) ? abs( $_COOKIE[ 'limit_' . COOKIEHASH ] ) : 0;
			$params['map_mode'] = ( ! empty( $_COOKIE[ 'map-mode_' . COOKIEHASH ] ) && $_COOKIE[ 'map-mode_' . COOKIEHASH ] == 'on' );
			if ( ! in_array( $params['sort'], [ 'newest', 'low-price', 'high-price' ] ) ) {
				$params['sort'] = 'newest';
			}
			if ( ! in_array( $params['limit'], [ 12, 60 ] ) ) {
				$params['limit'] = 12;
			}

			if ( $params['reset_page'] = ! empty( $_COOKIE[ 'reset_page_' . COOKIEHASH ] ) ) {
				setcookie( 'reset_page_' . COOKIEHASH, '', time() - 3600 * 24, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN );
			}
		}

		return $params;
	}
}

if ( ! function_exists( 'ideapark_404_html_block' ) ) {
	function ideapark_404_html_block() {
		global $wp_query;

		$is_preview_mode = ideapark_is_elementor_preview_mode();

		if ( is_singular( 'html_block' ) && ! $is_preview_mode ) {
			$wp_query->set_404();
			status_header( 404 );
			nocache_headers();
			include( get_query_template( '404' ) );
			die();
		}
	}

	add_action( 'wp', 'ideapark_404_html_block' );
}

if ( ! function_exists( 'ideapark_plugin_generator_tag' ) ) {
	function ideapark_plugin_generator_tag( $gen, $type ) {
		switch ( $type ) {
			case 'html':
				$gen .= "\n" . '<meta name="generator" content="Theme Plugin ' . IDEAPARK_LUCHIANA_FUNC_VERSION . '">';
				break;
			case 'xhtml':
				$gen .= "\n" . '<meta name="generator" content="Theme Plugin ' . IDEAPARK_LUCHIANA_FUNC_VERSION . '" />';
				break;
		}

		return $gen;
	}

	add_filter( 'get_the_generator_html', 'ideapark_plugin_generator_tag', 10, 2 );
	add_filter( 'get_the_generator_xhtml', 'ideapark_plugin_generator_tag', 10, 2 );
}

if ( ! function_exists( 'ideapark_elementor_post_type' ) ) {
	function ideapark_elementor_post_type() {
		$cpt_support = get_option( 'elementor_cpt_support' );
		if ( ! $cpt_support ) {
			$cpt_support = [ 'page', 'post', 'html_block' ];
			update_option( 'elementor_cpt_support', $cpt_support );
		} else if ( ! in_array( 'html_block', $cpt_support ) ) {
			$cpt_support[] = 'html_block';
			update_option( 'elementor_cpt_support', $cpt_support );
		}
	}

	add_action( 'after_setup_theme', 'ideapark_elementor_post_type' );
}

if ( ! function_exists( 'ideapark_ajax_item_images' ) ) {
	function ideapark_ajax_item_images() {
		ob_start();
		if ( isset( $_REQUEST['product_id'] ) && ( $product_id = absint( $_REQUEST['product_id'] ) ) ) {

			$images         = [];
			$attachment_ids = get_post_meta( $product_id, 'image_gallery', false );

			if ( ! is_array( $attachment_ids ) ) {
				$attachment_ids = [];
			}

			if ( has_post_thumbnail( $product_id ) ) {
				array_unshift( $attachment_ids, get_post_thumbnail_id( $product_id ) );
			}

			if ( $attachment_ids ) {
				foreach ( $attachment_ids as $attachment_id ) {
					$image    = wp_get_attachment_image_src( $attachment_id, 'full' );
					$images[] = [
						'src' => $image[0],
						'w'   => $image[1],
						'h'   => $image[2],
					];
				}
			}

			if ( $video_url = get_post_meta( $product_id, 'video_url', true ) ) {
				$images[] = [
					'html' => ideapark_wrap( wp_oembed_get( $video_url ), '<div class="pswp__video-wrap">', '</div>' )
				];
			}
			ob_end_clean();
			wp_send_json( [ 'images' => $images ] );
		}
		ob_end_clean();
	}

	add_action( 'wp_ajax_ideapark_item_images', 'ideapark_ajax_item_images' );
	add_action( 'wp_ajax_nopriv_ideapark_item_images', 'ideapark_ajax_item_images' );

}

if ( ! function_exists( 'ideapark_fix_woocommerce_activation' ) ) {
	function ideapark_fix_woocommerce_activation() {
		if ( is_admin() && wp_doing_ajax() && ! empty( $_POST['action'] ) && $_POST['action'] == 'ideapark_about_ajax' && empty( $_POST['is_additional'] ) && empty( $_POST['is_core_update'] ) && empty( $_POST['plugins'] ) ) {

			if ( ! defined( 'DOING_CRON' ) ) {
				define( 'DOING_CRON', true );
			}
		}
	}

	add_action( 'plugins_loaded', 'ideapark_fix_woocommerce_activation', 1 );
}

if ( ! function_exists( 'ideapark_customize_loaded_components' ) ) {
	function ideapark_customize_loaded_components( $components ) {

		foreach ( [ 'nav_menus', 'widgets' ] as $key ) {
			$i = array_search( $key, $components );
			if ( false !== $i ) {
				unset( $components[ $i ] );
			}
		}

		return $components;
	}

	add_filter( 'customize_loaded_components', 'ideapark_customize_loaded_components' );
}

if ( ! function_exists( 'ideapark_is_network_activated' ) ) {
	function ideapark_is_network_activated() {
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		return is_multisite() && is_plugin_active_for_network( 'ideapark-luchiana/ideapark-luchiana.php' );
	}
}

if ( ! function_exists( 'ideapark_theme_notice' ) ) {
	function ideapark_theme_notice() {

		$screen = get_current_screen();
		if ( in_array( $screen->id, [ 'appearance_page_ideapark_about' ], true ) ) {
			return;
		}

		if ( ( $code = ideapark_get_purchase_code() ) && ( $code !== IDEAPARK_SKIP_REGISTER ) ) {
			return;
		}

		$message          = __( 'You have not registered the theme yet! Please <a href="%1$s">enter your purchase code</a> or <a href="%2$s" target="_blank">get a new license here</a>.', 'ideapark-luchiana' );
		$theme_about_page = admin_url( 'themes.php?page=ideapark_about' );

		echo '<div id="ideapark-notification" class="notice notice-warning is-dismissible"><p><span class="dashicons dashicons-warning" style="color: #f56e28"></span> ', ideapark_wp_kses( sprintf( $message, $theme_about_page, preg_replace( '~#.*$~', '', IDEAPARK_CHANGELOG ) ) ), '</p></div>';

	}

	$admin_notices_hook = ideapark_is_network_activated() ? 'network_admin_notices' : 'admin_notices';
	add_action( $admin_notices_hook, 'ideapark_theme_notice' );
}

add_action( 'admin_init', function () {
	global $rs_admin;
	if ( function_exists( 'ideapark_ra' ) && isset( $rs_admin ) ) {
		ideapark_ra( 'admin_notices', [ $rs_admin, 'add_plugins_page_notices' ] );
	}
} );

add_shortcode( 'ip-button', function ( $atts ) {

	$default_atts = [
		'size'           => 'medium', // small, medium, large
		'type'           => 'primary', // primary, accent, outline, outline-white,  outline-black
		'icon'           => '',
		'text'           => '',
		'link'           => '#',
		'target'         => '_self',
		'text_transform' => '',
		'margin'         => '',
		'custom_class'   => '',
		'html_type'      => 'anchor', // anchor, button
	];

	$params = shortcode_atts( $default_atts, $atts );

	$styles = [];

	if ( ! empty( $params['text_transform'] ) ) {
		$styles[] = 'text-transform: ' . $params['text_transform'];
	}

	if ( $params['margin'] !== '' ) {
		$styles[] = 'margin: ' . $params['margin'];
	}

	ob_start();
	?>
	<?php if ( $params['type'] == 'button' ) { ?>
		<button type="button"
				class="c-button c-button--<?php echo esc_html( $params['type'] ); ?> c-button--<?php echo esc_html( $params['size'] ); ?> <?php if ( $params['custom_class'] ) {
			        esc_attr( $params['custom_class'] );
		        } ?>" <?php if ( $styles ) { ?>style="<?php echo esc_attr( implode( ';', $styles ) ); ?>"<?php } ?>>
			<?php if ( $params['icon'] ) { ?>
				<i class="c-button__icon c-button__icon--left <?php echo esc_attr( $params['icon'] ); ?>"><!-- --></i>
			<?php } ?>
			<span class="c-button__text"><?php echo esc_html( $params['text'] ); ?></span>
		</button>
	<?php } else { ?>
		<a href="<?php echo esc_url( $params['link'] ); ?>"
		   target="<?php echo esc_attr( $params['target'] ); ?>"
		   class="c-button c-button--<?php echo esc_html( $params['type'] ); ?> c-button--<?php echo esc_html( $params['size'] ); ?> <?php if ( $params['custom_class'] ) {
			   esc_attr( $params['custom_class'] );
		   } ?>" <?php if ( $styles ) { ?>style="<?php echo esc_attr( implode( ';', $styles ) ); ?>"<?php } ?>>
			<?php if ( $params['icon'] ) { ?>
				<i class="c-button__icon c-button__icon--left <?php echo esc_attr( $params['icon'] ); ?>"><!-- --></i>
			<?php } ?>
			<span class="c-button__text"><?php echo esc_html( $params['text'] ); ?></span>
		</a>
	<?php } ?>
	<?php

	return preg_replace( '~[\r\n]~', '', ob_get_clean() );
} );

add_shortcode( 'ip-post-share', function ( $atts ) {

	global $post;

	$esc_permalink = esc_url( get_permalink() );
	$product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), false, '' );

	$share_links = [
		'<a class="c-post-share__link" target="_blank" href="//www.facebook.com/sharer.php?u=' . $esc_permalink . '" title="' . esc_html__( 'Share on Facebook', 'ideapark-luchiana' ) . '"><i class="ip-facebook c-post-share__icon c-post-share__icon--facebook"></i></a>',
		'<a class="c-post-share__link" target="_blank" href="//twitter.com/share?url=' . $esc_permalink . '" title="' . esc_html__( 'Share on Twitter', 'ideapark-luchiana' ) . '"><i class="ip-twitter c-post-share__icon c-post-share__icon--twitter"></i></a>',
		'<a class="c-post-share__link" target="_blank" href="//pinterest.com/pin/create/button/?url=' . $esc_permalink . ( $product_image ? '&amp;media=' . esc_url( $product_image[0] ) : '' ) . '&amp;description=' . urlencode( get_the_title() ) . '" title="' . esc_html__( 'Pin on Pinterest', 'ideapark-luchiana' ) . '"><i class="ip-pinterest c-post-share__icon c-post-share__icon--pinterest"></i></a>',
		'<a class="c-post-share__link" target="_blank" href="//wa.me/?text=' . $esc_permalink . '" title="' . esc_html__( 'Share on Whatsapp', 'ideapark-luchiana' ) . '"><i class="ip-whatsapp c-post-share__icon c-post-share__icon--whatsapp"></i></a>'
	];

	ob_start();
	?>

	<?php
	foreach ( $share_links as $link ) {
		echo ideapark_wrap( $link );
	}
	?>
	<?php

	$content = ob_get_clean();

	return ideapark_wrap( $content, '<div class="c-post-share">', '</div>' );
} );