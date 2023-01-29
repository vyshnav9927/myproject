<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Tabs widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_Tabs extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Tabs widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-tabs';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Tabs widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Luchiana Tabs', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Tabs widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eicon-tabs';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 */
	public function get_categories() {
		return [ 'ideapark-elements' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since  2.1.0
	 * @access public
	 *
	 */
	public function get_keywords() {
		return [ 'Tabs', 'list' ];
	}

	/**
	 * Register Tabs widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Tabs', 'ideapark-luchiana' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'label'       => __( 'Title', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'List Item', 'ideapark-luchiana' ),
				'placeholder' => __( 'Enter title', 'ideapark-luchiana' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$args    = [
			'numberposts'      => - 1,
			'post_type'        => 'html_block',
			'orderby'          => 'title',
		];
		$options = [];
		$posts   = get_posts( $args );
		foreach ( $posts as $post ) {
			$options[ $post->ID ] = $post->post_title;
		}

		$repeater->add_control(
			'page_id',
			[
				'label'       => __( 'Content', 'ideapark-luchiana' ),
				'description' => sprintf( __( 'Select a %s HTML block %s', 'ideapark-luchiana' ), '<a href="' . esc_url( admin_url( 'edit.php?post_type=html_block' ) ) . '">', '</a>' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $options
			]
		);

		$this->add_control(
			'tabs',
			[
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Tabs widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings           = $this->get_settings_for_display();
		$elementor_instance = Elementor\Plugin::instance();
		?>
		<div class="c-ip-tabs js-ip-tabs">
			<?php if ( sizeof( $settings['tabs'] ) > 1 ) { ?>
				<div class="c-ip-tabs__wrap js-ip-tabs-wrap">
					<div
						class="c-ip-tabs__menu js-ip-tabs-list h-carousel h-carousel--small h-carousel--hover h-carousel--mobile-arrows h-carousel--dots-hide">
						<?php
						foreach ( $settings['tabs'] as $index => $item ) { ?>
							<div
								class="c-ip-tabs__menu-item js-ip-tabs-menu-item <?php if ( ! $index ) { ?>active<?php } ?>">
								<a class="c-ip-tabs__menu-link js-ip-tabs-link"
								   href="#tab-<?php echo esc_attr( $this->get_id() . '-' . ( $index + 1 ) ); ?>"
								   data-index="<?php echo esc_attr( $index ); ?>"
								   onclick="return false;"><?php echo $item['title']; ?></a>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
			<div class="c-ip-tabs__list">
				<?php
				foreach ( $settings['tabs'] as $index => $item ) { ?>
					<div class="c-ip-tabs__item <?php if ( ! $index ) { ?>visible active<?php } ?>"
						 id="tab-<?php echo esc_attr( $this->get_id() . '-' . ( $index + 1 ) ); ?>">
						<?php
						$page_id = $item['page_id'];
						global $post;
						if ( ! $page_id ) {
							$page_content = '';
						} elseif ( ideapark_is_elementor_page( $page_id ) ) {
							$page_content = $elementor_instance->frontend->get_builder_content_for_display( $page_id );
						} elseif ( $post = get_post( $page_id ) ) {
							$page_content = apply_filters( 'the_content', $post->post_content );
							$page_content = str_replace( ']]>', ']]&gt;', $page_content );
							$page_content = ideapark_wrap( $page_content, '<div class="entry-content">', '</div>' );
							wp_reset_postdata();
						} else {
							$page_content = '';
						}
						?>
						<?php echo ideapark_wrap( $page_content, '<div class="c-ip-tabs__content">', '</div>' ); ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Tabs widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
