<?php 
/**
 * Plugin Name: Text columns for Elementor
 * Description: Text columns for Elementor makes easy to create columns in a text, totally responsive.
 * Plugin URI: http://arthos.fr
 * Version: 1.0.0
 * Author: Arthos
 * Author URI: http://arthos.fr
 * Text Domain: textcolumns4elementor
 */
if( ! defined( 'ABSPATH' ) ) exit;

class ElementorCustomWidget {

	private static $instance = null;

	public static function get_instance() {
		if( ! self::$instance )
			self::$instance = new self;
		return self::$instance;
	}

	public function init() {
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'widgets_registered' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_style_and_script' ] );
	}

	public function widgets_registered() {
		if( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {
			$template_file = 'plugins/elementor/text-columns-for-elementor-widget.php';
			if( ! $template_file || ! is_readable( $template_file ) ) {
				$template_file = plugin_dir_path( __FILE__ ) . 'text-columns-for-elementor-widget.php';
			}
			if( $template_file && is_readable( $template_file ) ) {
				require_once $template_file;
			}
		} 
	}

	public function enqueue_style_and_script() {
		// wp_enqueue_script( 'text-columns-for-elementor', plugins_url( 'js/text-columns-for-elementor.js', __FILE__ ), array() );
		wp_enqueue_style( 'text-columns', plugins_url( 'css/text-columns-for-elementor.min.css', __FILE__ ) );
	}

}

ElementorCustomWidget::get_instance()->init();