<?php 
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

if( ! defined( 'ABSPATH' ) ) exit;

class Text_Columns_For_Elementor extends Widget_Base {

	public $hash = null;

	public function get_hash() {
		if( null === $this->hash ):
			$this->hash = $this->get_random();
		// else:
		// 	$this->hash = $this->hash;
		endif;

		return $this->hash;
	}

	public function get_name() {
		return 'text-columns-for-elementor';
	}

	public function get_title() {
		return __( 'Text columns for Elementor', 'textcolumns4elementor' );
	}

	public function get_icon() {
		return 'fa fa-bars';
	}

	public function get_random() {
		return rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label'		=> __( 'Text Columns', 'textcolumns4elementor' ),
			]
		);

		$this->add_control(
			'text_columns',
			[
				'label'			=> __( 'Contenu à afficher', 'textcolumns4elementor' ),
				'type'			=> Controls_Manager::WYSIWYG,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label'			=> __( 'Typographie', 'textcolumns4elementor' ),
				'tab'			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'columns_number',
			[
				'label'			=> __( 'Nombre de colonnes', 'textcolumns4elementor' ),
				'type'			=> Controls_Manager::SLIDER,
				'default'		=> [
					'size'		=> 4,
				],
				'size_units'	=> [ 'size' ],
				'range'			=> [
					'px'	=> [
						'min'		=> 1,
						'max'		=> 8,
						'step'		=> 1,
					],
				],
				'selectors'		=> [
					'#textcolumns-container-' . $this->get_hash() => 'column-count: {{SIZE}};',
				]
			]
		);

		$this->add_control(
			'columns_gap',
			[
				'label'			=> __( 'Écart entre les colonnes (en PX)', 'textcolumns4elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 10,
				'min'			=> 0,
				'max'			=> 100,
				'step'			=> 1,
				'selectors'		=> [
					'#textcolumns-container-' . $this->get_hash() => 'column-gap: {{SIZE}}px;'
				]
			]
		);

		$this->add_control(
			'color',
			[
				'label' 	=> __( 'Couleur du texte', 'textcolumns4elementor' ),
				'type'		=> Controls_Manager::COLOR,
				'scheme'	=> [
					'type'	=> Scheme_Color::get_type(),
					'value'	=> Scheme_Color::COLOR_1
				],
				'selectors'	=> [
					'#textcolumns-container-' . $this->get_hash() => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'		=> 'typography',
				'scheme'	=> Scheme_Typography::TYPOGRAPHY_1,
				'selectors'	=> '#textcolumns-container-' . $this->get_hash()
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings 			= $this->get_settings();
		if( empty( $settings['text_columns'] ) ) return;
		$container_id 		= $this->get_hash();
		$texte 				= $settings['text_columns'];
		printf(
			'<div id="textcolumns-container-%1$s" class="textcolumns4elementor" style="">%2$s</div><!-- .textcolumns4elementor -->',
			$container_id,
			$texte
		);

		add_action( 'wp_footer', [ $this, 'textcolumns_for_elementor_styles' ] );

	}

	public function textcolumns_for_elementor_styles() {
		$settings 			= $this->get_settings();
		$container_id 		= $this->get_hash();
		$columns_number 	= $settings['columns_number'];
		$columns_gap 		= $settings['columns_gap'];
		$color 				= $settings['color'];
		$font_family 		= ( '' != $settings['typography_font_family'] ) ? "'" . $settings['typography_font_family'] . "'" : "inherit";
		$font_weight 		= ( '' != $settings['typography_font_weight'] ) ? $settings['typography_font_weight'] : '400';
		$text_transform		= ( '' != $settings['typography_text_transform'] ) ? $settings['typography_text_transform'] : 'inherit';
		$prop_css 			= '#textcolumns-container-' . $container_id . '{column-count:' . $columns_number['size'] . ';column-gap:' . $columns_gap . 'px;}';

		echo '<style>' . $prop_css . '</style>';

	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Text_Columns_For_Elementor() );
