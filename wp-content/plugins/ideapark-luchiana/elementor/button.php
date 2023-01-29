<?php

use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Ideapark_Elementor_Button extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'ideapark-button';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return esc_html__( 'Luchiana Button', 'ideapark-luchiana' );
	}

	/**
	 * Retrieve the widget icon.
	 */
	public function get_icon() {
		return 'eicon-button';
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
	 * Register button widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'Button', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'button_type',
			[
				'label'   => __( 'Type', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'ideapark-luchiana' ),
					'outline' => __( 'Outline', 'ideapark-luchiana' ),
				]
			]
		);


		$this->add_control(
			'text',
			[
				'label'       => __( 'Text', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Click here', 'ideapark-luchiana' ),
				'placeholder' => __( 'Click here', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
				'default'     => [
					'url' => '#',
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
			'default_bg_color',
			[
				'label'     => __( 'Background Color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .c-button--default' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					'{{WRAPPER}} .c-button--default:hover' => 'opacity: 0.6;',
				],
				'condition'    => [
					'button_type' => 'default'
				]
			]
		);

		$this->add_control(
			'default_text_color',
			[
				'label'     => __( 'Text Color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .c-button--default' => 'color: {{VALUE}};',
				],
				'condition'    => [
					'button_type' => 'default'
				]
			]
		);

		$this->add_control(
			'outline_text_color',
			[
				'label'     => __( 'Color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .c-button--outline' => 'color: {{VALUE}} !important;border-color: {{VALUE}};',
					'{{WRAPPER}} .c-button--outline:hover' => 'color: white  !important;background-color: {{VALUE}} !important;border-color: {{VALUE}} !important;',
				],
				'condition'    => [
					'button_type' => 'outline'
				]
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'       => __( 'Icon', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'     => __( 'Icon Position', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'  => __( 'Before', 'ideapark-luchiana' ),
					'right' => __( 'After', 'ideapark-luchiana' ),
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label'   => __( 'View', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'button_css_id',
			[
				'label'       => __( 'Button ID', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => '',
				'title'       => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'ideapark-luchiana' ),
				'label_block' => false,
				'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'ideapark-luchiana' ),
				'separator'   => 'before',

			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'c-ip-button__wrap' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['link'] );
		}

		$this->add_render_attribute( 'button', 'class', 'c-button c-ip-button' );
		$this->add_render_attribute( 'button', 'role', 'button' );

		if ( ! empty( $settings['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
		}

		if ( ! empty( $settings['button_type'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'c-button--' . $settings['button_type'] );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
				<?php $this->render_text(); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @since  1.5.0
	 * @access protected
	 */
	protected function render_text() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( [
			'icon-align' => [
				'class' => [
					'c-ip-button__icon',
					'c-ip-button__icon--' . $settings['icon_align'],
				],
			],
			'text'       => [
				'class' => 'c-ip-button__text',
			],
		] );

		$this->add_inline_editing_attributes( 'text', 'none' );
		if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) {
			ob_start(); ?>
			<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>><?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
			<?php
			$icon = ob_get_clean();
		} else {
			$icon = '';
		}
		?>
		<?php if ( $icon && $settings['icon_align'] == 'right' ) {
			echo ideapark_wrap( $icon );
		} ?>
		<span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['text']; ?></span>
		<?php if ( $icon && $settings['icon_align'] == 'left' ) {
			echo ideapark_wrap( $icon );
		} ?>
		<?php
	}

	/**
	 * Render icon list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
