<?php
/**
 * Assets Handler Class
 *
 * Handles enqueueing of scripts and styles for the plugin.
 *
 * @package InfiniteBrandScroll
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class IBS_Assets
 */
class IBS_Assets {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	private static $instance = null;

	/**
	 * Track whether assets are enqueued to prevent duplicates.
	 *
	 * @var bool
	 */
	private static $assets_enqueued = false;

	/**
	 * Initialize the class.
	 */
	private function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) );
	}

	/**
	 * Get instance of this class.
	 *
	 * @return IBS_Assets
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register frontend assets (not enqueued by default).
	 * Assets will be enqueued conditionally when shortcode/widget/block is used.
	 */
	public function register_assets() {
		// Register Three.js from CDN.
		wp_register_script(
			'threejs',
			'https://cdn.skypack.dev/three',
			array(),
			null, // Version handled by CDN.
			true
		);

		// Register GSAP from CDN.
		wp_register_script(
			'gsap',
			'https://cdn.skypack.dev/gsap',
			array(),
			null,
			true
		);

		// Register GSAP ScrollTrigger.
		wp_register_script(
			'gsap-scrolltrigger',
			'https://cdn.skypack.dev/gsap/ScrollTrigger',
			array( 'gsap' ),
			null,
			true
		);

		// Register main plugin script.
		wp_register_script(
			'infinite-brand-scroll',
			INFINITE_BRAND_SCROLL_PLUGIN_URL . 'assets/js/infinite-brand-scroll.js',
			array( 'threejs', 'gsap', 'gsap-scrolltrigger' ),
			INFINITE_BRAND_SCROLL_VERSION,
			true
		);

		// Register main plugin styles.
		wp_register_style(
			'infinite-brand-scroll',
			INFINITE_BRAND_SCROLL_PLUGIN_URL . 'assets/css/infinite-brand-scroll.css',
			array(),
			INFINITE_BRAND_SCROLL_VERSION
		);

		// Add module type attribute to scripts.
		add_filter( 'script_loader_tag', array( $this, 'add_module_type' ), 10, 3 );
	}

	/**
	 * Enqueue assets for the plugin.
	 * This method is called by shortcode/widget/block when they are used.
	 *
	 * @param array $brands Array of brand data.
	 * @param array $options Plugin options.
	 */
	public static function enqueue_assets( $brands = array(), $options = array() ) {
		if ( self::$assets_enqueued ) {
			return;
		}

		wp_enqueue_script( 'infinite-brand-scroll' );
		wp_enqueue_style( 'infinite-brand-scroll' );

		// Localize script with data.
		$script_data = array(
			'brands'         => $brands,
			'animationSpeed' => isset( $options['animation_speed'] ) ? absint( $options['animation_speed'] ) : 5000,
			'pauseOnHover'   => isset( $options['pause_on_hover'] ) ? (bool) $options['pause_on_hover'] : true,
			'enable3D'       => isset( $options['enable_3d'] ) ? (bool) $options['enable_3d'] : true,
			'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
			'nonce'          => wp_create_nonce( 'ibs_nonce' ),
		);

		wp_localize_script(
			'infinite-brand-scroll',
			'ibsData',
			$script_data
		);

		self::$assets_enqueued = true;
	}

	/**
	 * Add type="module" attribute to specific scripts.
	 *
	 * @param string $tag    Script tag.
	 * @param string $handle Script handle.
	 * @param string $src    Script source.
	 * @return string
	 */
	public function add_module_type( $tag, $handle, $src ) {
		$module_scripts = array( 'infinite-brand-scroll', 'threejs', 'gsap', 'gsap-scrolltrigger' );

		if ( in_array( $handle, $module_scripts, true ) ) {
			$tag = str_replace( '<script ', '<script type="module" ', $tag );
		}

		return $tag;
	}

	/**
	 * Register admin assets.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function register_admin_assets( $hook ) {
		// Only load on our settings page.
		if ( 'settings_page_infinite-brand-scroll' !== $hook ) {
			return;
		}

		// Admin styles.
		wp_enqueue_style(
			'infinite-brand-scroll-admin',
			INFINITE_BRAND_SCROLL_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			INFINITE_BRAND_SCROLL_VERSION
		);

		// Admin scripts.
		wp_enqueue_script(
			'infinite-brand-scroll-admin',
			INFINITE_BRAND_SCROLL_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery', 'wp-color-picker' ),
			INFINITE_BRAND_SCROLL_VERSION,
			true
		);

		wp_enqueue_media();

		// Localize admin script.
		wp_localize_script(
			'infinite-brand-scroll-admin',
			'ibsAdmin',
			array(
				'nonce'           => wp_create_nonce( 'ibs_admin_nonce' ),
				'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
				'confirmDelete'   => esc_html__( 'Are you sure you want to delete this brand?', 'infinite-brand-scroll' ),
				'selectImage'     => esc_html__( 'Select Image', 'infinite-brand-scroll' ),
				'selectLogo'      => esc_html__( 'Select Logo', 'infinite-brand-scroll' ),
				'useThisImage'    => esc_html__( 'Use This Image', 'infinite-brand-scroll' ),
			)
		);

		wp_enqueue_style( 'wp-color-picker' );
	}
}
