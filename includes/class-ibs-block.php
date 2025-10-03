<?php
/**
 * Gutenberg Block Class
 *
 * Registers a Gutenberg block for the infinite brand scroll.
 *
 * @package InfiniteBrandScroll
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class IBS_Block
 */
class IBS_Block {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	private static $instance = null;

	/**
	 * Initialize the class.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );
	}

	/**
	 * Get instance of this class.
	 *
	 * @return IBS_Block
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register the Gutenberg block.
	 */
	public function register_block() {
		// Register block script.
		wp_register_script(
			'infinite-brand-scroll-block',
			INFINITE_BRAND_SCROLL_PLUGIN_URL . 'assets/js/block.js',
			array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n' ),
			INFINITE_BRAND_SCROLL_VERSION,
			true
		);

		// Register block styles.
		wp_register_style(
			'infinite-brand-scroll-block-editor',
			INFINITE_BRAND_SCROLL_PLUGIN_URL . 'assets/css/block-editor.css',
			array( 'wp-edit-blocks' ),
			INFINITE_BRAND_SCROLL_VERSION
		);

		// Register the block.
		register_block_type(
			'infinite-brand-scroll/carousel',
			array(
				'editor_script'   => 'infinite-brand-scroll-block',
				'editor_style'    => 'infinite-brand-scroll-block-editor',
				'render_callback' => array( $this, 'render_block' ),
				'attributes'      => array(
					'height' => array(
						'type'    => 'number',
						'default' => 600,
					),
					'speed' => array(
						'type'    => 'number',
						'default' => 5000,
					),
					'pauseOnHover' => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'enable3D' => array(
						'type'    => 'boolean',
						'default' => true,
					),
				),
			)
		);
	}

	/**
	 * Render the block on the frontend.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public function render_block( $attributes ) {
		$height = isset( $attributes['height'] ) ? absint( $attributes['height'] ) : 600;
		$speed  = isset( $attributes['speed'] ) ? absint( $attributes['speed'] ) : 5000;
		$pause  = isset( $attributes['pauseOnHover'] ) ? ( $attributes['pauseOnHover'] ? 'true' : 'false' ) : 'true';
		$enable = isset( $attributes['enable3D'] ) ? ( $attributes['enable3D'] ? 'true' : 'false' ) : 'true';

		return do_shortcode( 
			sprintf(
				'[infinite_brand_scroll height="%d" speed="%d" pause_on_hover="%s" enable_3d="%s"]',
				$height,
				$speed,
				$pause,
				$enable
			)
		);
	}
}
