<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Ideapark_WC_Color_Filter_Widget extends WC_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_layered_nav woocommerce-widget-layered-nav';
		$this->widget_description = __( 'Display a list of attributes to filter products in your store.', 'ideapark-luchiana' );
		$this->widget_id          = 'ip_attribute_filter';
		$this->widget_name        = __( 'Luchiana Filter by Attribute', 'ideapark-luchiana' );
		parent::__construct();
	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * @param array $new_instance New Instance.
	 * @param array $old_instance Old Instance.
	 *
	 * @return array
	 * @see WP_Widget->update
	 *
	 */
	public function update( $new_instance, $old_instance ) {
		$this->init_settings();

		return parent::update( $new_instance, $old_instance );
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @param array $instance Instance.
	 *
	 * @see WP_Widget->form
	 *
	 */
	public function form( $instance ) {
		if ( ideapark_woocommerce_on() ) {
			$this->init_settings();
			parent::form( $instance );
		} else {
			esc_html_e( 'You need to activate and configure the "Woocommerce" and "Variation Swatches for WooCommerce" plugins', 'ideapark-luchiana' );
		}
	}

	/**
	 * Init settings after post types are registered.
	 */
	public function init_settings() {
		$attribute_array      = [];
		$std_attribute        = '';
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $tax ) {
				if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
					$attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
				}
			}
			$std_attribute = current( $attribute_array );
		}

		$this->settings = [
			'title'      => [
				'type'  => 'text',
				'std'   => __( 'Filter by', 'ideapark-luchiana' ),
				'label' => __( 'Title', 'ideapark-luchiana' ),
			],
			'attribute'  => [
				'type'    => 'select',
				'std'     => $std_attribute,
				'label'   => __( 'Attribute', 'ideapark-luchiana' ),
				'options' => $attribute_array,
			],
			'count'      => [
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show product counts', 'ideapark-luchiana' ),
			],
			'query_type' => [
				'type'    => 'select',
				'std'     => 'and',
				'label'   => __( 'Query type', 'ideapark-luchiana' ),
				'options' => [
					'and' => __( 'AND', 'ideapark-luchiana' ),
					'or'  => __( 'OR', 'ideapark-luchiana' ),
				],
			],
			'image_size' => [
				'type'  => 'number',
				'step'  => 1,
				'min'   => 20,
				'max'   => 100,
				'std'   => 50,
				'label' => __( 'Image size', 'ideapark-luchiana' ),
			],
		];
	}

	/**
	 * Get this widgets taxonomy.
	 *
	 * @param array $instance Array of instance options.
	 *
	 * @return string
	 */
	protected function get_instance_taxonomy( $instance ) {
		if ( isset( $instance['attribute'] ) ) {
			return wc_attribute_taxonomy_name( $instance['attribute'] );
		}

		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $tax ) {
				if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
					return wc_attribute_taxonomy_name( $tax->attribute_name );
				}
			}
		}

		return '';
	}

	protected function get_instance_query_type( $instance ) {
		return isset( $instance['query_type'] ) ? $instance['query_type'] : 'and';
	}

	protected function get_instance_count( $instance ) {
		return isset( $instance['count'] ) ? $instance['count'] : false;
	}

	protected function get_instance_image_size( $instance ) {
		return isset( $instance['image_size'] ) && (int) $instance['image_size'] >= 20 ? (int) $instance['image_size'] : 50;
	}

	public function widget( $args, $instance ) {
		if ( ! ideapark_woocommerce_on() ) {
			return;
		}

		if ( ! is_shop() && ! is_product_taxonomy() ) {
			return;
		}

		$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
		$taxonomy           = $this->get_instance_taxonomy( $instance );
		$query_type         = $this->get_instance_query_type( $instance );
		$count              = $this->get_instance_count( $instance );
		$image_size         = $this->get_instance_image_size( $instance );

		if ( ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		$terms = get_terms( $taxonomy, [ 'hide_empty' => '1' ] );

		if ( 0 === count( $terms ) ) {
			return;
		}

		ob_start();

		$this->widget_start( $args, $instance );

		$found = $this->layered_nav_list( $terms, $taxonomy, $query_type, $count, $image_size );

		$this->widget_end( $args );

		// Force found when option is selected - do not force found on taxonomy attributes.
		if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
			$found = true;
		}

		if ( ! $found ) {
			ob_end_clean();
		} else {
			echo ob_get_clean(); // @codingStandardsIgnoreLine
		}
	}

	/**
	 * Return the currently viewed taxonomy name.
	 *
	 * @return string
	 */
	protected function get_current_taxonomy() {
		return is_tax() ? get_queried_object()->taxonomy : '';
	}

	/**
	 * Return the currently viewed term ID.
	 *
	 * @return int
	 */
	protected function get_current_term_id() {
		return absint( is_tax() ? get_queried_object()->term_id : 0 );
	}

	/**
	 * Return the currently viewed term slug.
	 *
	 * @return int
	 */
	protected function get_current_term_slug() {
		return absint( is_tax() ? get_queried_object()->slug : 0 );
	}

	/**
	 * Count products within certain terms, taking the main WP query into consideration.
	 *
	 * This query allows counts to be generated based on the viewed products, not all products.
	 *
	 * @param array  $term_ids   Term IDs.
	 * @param string $taxonomy   Taxonomy.
	 * @param string $query_type Query Type.
	 *
	 * @return array
	 */
	protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
		return wc_get_container()->get( Automattic\WooCommerce\Internal\ProductAttributesLookup\Filterer::class )->get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type );
	}

	/**
	 * Wrapper for WC_Query::get_main_tax_query() to ease unit testing.
	 *
	 * @return array
	 * @since 4.4.0
	 */
	protected function get_main_tax_query() {
		return WC_Query::get_main_tax_query();
	}

	/**
	 * Wrapper for WC_Query::get_main_search_query_sql() to ease unit testing.
	 *
	 * @return string
	 * @since 4.4.0
	 */
	protected function get_main_search_query_sql() {
		return WC_Query::get_main_search_query_sql();
	}

	/**
	 * Wrapper for WC_Query::get_main_search_queryget_main_meta_query to ease unit testing.
	 *
	 * @return array
	 * @since 4.4.0
	 */
	protected function get_main_meta_query() {
		return WC_Query::get_main_meta_query();
	}

	/**
	 * Show list based layered nav.
	 *
	 * @param array  $terms      Terms.
	 * @param string $taxonomy   Taxonomy.
	 * @param string $query_type Query Type.
	 *
	 * @return bool   Will nav display?
	 */
	protected function layered_nav_list( $terms, $taxonomy, $query_type, $with_count, $image_size ) {
		// List display.
		echo '<ul class="c-ip-attribute-filter__list">';

		$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
		$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
		$found              = false;
		$base_link          = $this->get_current_page_url();
		$type               = ideapark_get_taxonomy_type( $taxonomy );

		foreach ( $terms as $term ) {
			$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : [];
			$option_is_set  = in_array( $term->slug, $current_values, true );
			$count          = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

			// Skip the term for the current archive.
			if ( $this->get_current_term_id() === $term->term_id ) {
				continue;
			}

			// Only show options with count > 0.
			if ( 0 < $count ) {
				$found = true;
			} elseif ( 0 === $count && ! $option_is_set ) {
				continue;
			}

			$filter_name = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : [];
			$current_filter = array_map( 'sanitize_title', $current_filter );

			if ( ! in_array( $term->slug, $current_filter, true ) ) {
				$current_filter[] = $term->slug;
			}

			$link = remove_query_arg( $filter_name, $base_link );

			// Add current filters to URL.
			foreach ( $current_filter as $key => $value ) {
				// Exclude query arg for current term archive term.
				if ( $value === $this->get_current_term_slug() ) {
					unset( $current_filter[ $key ] );
				}

				// Exclude self so filter can be unset on click.
				if ( $option_is_set && $value === $term->slug ) {
					unset( $current_filter[ $key ] );
				}
			}

			if ( ! empty( $current_filter ) ) {
				asort( $current_filter );
				$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

				// Add Query type Arg to URL.
				if ( 'or' === $query_type && ! ( 1 === count( $current_filter ) && $option_is_set ) ) {
					$link = add_query_arg( 'query_type_' . wc_attribute_taxonomy_slug( $taxonomy ), 'or', $link );
				}
				$link = str_replace( '%2C', ',', $link );
			}

			$swatches = '';

			if ( $count > 0 || $option_is_set ) {

				if ( $type ) {
					switch ( $type ) {
						case 'color':
							$color    = sanitize_hex_color( ideapark_get_product_attribute_color( $term ) );
							$swatches = sprintf( '<span class="c-ip-attribute-filter__sw c-ip-attribute-filter__sw--color" style="background-color:%s;"></span>', esc_attr( $color ) );
							break;

						case 'image':
							$attachment_id = absint( ideapark_get_product_attribute_image( $term ) );
							$image         = wp_get_attachment_image_src( $attachment_id, ideapark_get_wvs_get_option( 'attribute_image_size' ) );
							if ( $image ) {
								$swatches = sprintf( '<span class="c-ip-attribute-filter__sw c-ip-attribute-filter__sw--image variable-item" style="width:' . esc_attr( $image_size ) . 'px;height:' . esc_attr( $image_size ) . 'px;"><img class="c-ip-attribute-filter__thumb" aria-hidden="true" alt="%s" src="%s" width="%d" height="%d" /></span>', esc_attr( $term->name ), esc_url( $image[0] ), esc_attr( $image[1] ), esc_attr( $image[2] ) );
							} else {
								$type = '';
							}
							break;

						case 'button':
							$swatches = sprintf( '<span class="c-ip-attribute-filter__sw c-ip-attribute-filter__sw--button">%s</span>', esc_html( $term->name ) . ( $with_count ? ' ' . apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term ) : '' ) );
							break;

						default:
							$type = '';
							break;
					}
				}

				if ( ! $type ) {
					$swatches = '<span class="c-ip-attribute-filter__sw c-ip-attribute-filter__sw--checkbox"></span>';
				}

				$link = apply_filters( 'woocommerce_layered_nav_link', $link, $term, $taxonomy );
				if ( in_array( $type, [ 'button' ] ) ) {
					$term_html = '<a rel="nofollow" href="' . esc_url( $link ) . '">' . $swatches . '</a>';
				} else {
					$term_html = '<a rel="nofollow" href="' . esc_url( $link ) . '">' . $swatches . esc_html( $term->name ) . '</a>';
					if ( $with_count ) {
						$term_html .= ' ' . apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term );
					}
				}
			} else {
				$link      = false;
				$term_html = '<span>' . esc_html( $term->name ) . '</span>';
				if ( $with_count ) {
					$term_html .= ' ' . apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term );
				}
			}

			// woocommerce-widget-layered-nav-list__item
			echo '<li class="c-ip-attribute-filter__item c-ip-attribute-filter__item--' . esc_attr( $type ) . ' ' . ( $option_is_set ? 'c-ip-attribute-filter--chosen' : '' ) . '">';
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.EscapeOutput.OutputNotEscaped
			echo apply_filters( 'woocommerce_layered_nav_term_html', $term_html, $term, $link, $count );
			echo '</li>';
		}

		echo '</ul>';

		return $found;
	}
}

register_widget( 'Ideapark_WC_Color_Filter_Widget' );