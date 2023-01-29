<?php

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Ideapark_Elementor_Social extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'ideapark-social';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return esc_html__( 'Social', 'ideapark-luchiana' );
	}

	/**
	 * Retrieve the widget icon.
	 */
	public function get_icon() {
		return 'eicon-social-icons';
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
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_social',
			[
				'label' => __( 'Social icons', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Icons Color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .c-ip-social__link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color_hover',
			[
				'label'     => __( 'Icons Color on Hover', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .c-ip-social__link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => __( 'Icon size', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 16,
				],
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 30,
					]
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-social' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_space',
			[
				'label'      => __( 'Space', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 30,
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-social__icon' => 'margin: calc({{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .c-ip-social'       => 'margin: calc(-{{SIZE}}{{UNIT}} / 2);'
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'        => __( 'Alignment', 'ideapark-luchiana' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'    => [
						'title' => __( 'Left', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default'      => '',
			]
		);

		$this->add_control(
			'separator',
			[
				'label'     => __( 'Social links', 'ideapark-luchiana' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		foreach ( ideapark_social_networks() as $code => $name ) {
			$this->add_control(
				'soc-' . $code,
				[
					'label'       => sprintf( __( '%s url', 'ideapark-luchiana' ), $name ),
					'type'        => Controls_Manager::URL,
					'label_block' => true,
					'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
				]
			);
		}
		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings  = $this->get_settings_for_display();
		$soc_count = 0;
		ob_start();
		foreach ( $settings AS $item_index => $row ) {
			if ( strpos( $item_index, 'soc-' ) !== false && ! empty( $row['url'] ) ) {
				$soc_count ++;

				$link_key = 'link_' . $item_index;

				$this->add_link_attributes( $link_key, $row );
				$this->add_render_attribute( $link_key, 'class', 'c-ip-social__link' );

				$soc_index = str_replace( 'soc-', '', $item_index );
				?>
				<a <?php echo $this->get_render_attribute_string( $link_key ); ?>><i
						class="ip-<?php echo esc_attr( $soc_index ) ?> c-ip-social__icon c-ip-social__icon--<?php echo esc_attr( $soc_index ) ?>">
						<!-- --></i></a>
			<?php };
		}
		$content = ob_get_clean();
		echo ideapark_wrap( $content, '<div class="c-ip-social">', '</div>' );
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function content_template() {

	}
}
