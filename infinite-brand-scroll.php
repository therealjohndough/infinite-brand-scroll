<?php
/**
 * Plugin Name: Infinite Brand Scroll
 * Plugin URI: https://github.com/therealjohndough/infinite-brand-scroll
 * Description: Display scrolling/carousel of brand logos with infinite loop using Three.js and GSAP. Includes shortcode, widget, and Gutenberg block support.
 * Version: 1.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Case Study Labs
 * Author URI: https://github.com/therealjohndough
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: infinite-brand-scroll
 * Domain Path: /languages
 *
 * @package InfiniteBrandScroll
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin constants.
define( 'INFINITE_BRAND_SCROLL_VERSION', '1.0.0' );
define( 'INFINITE_BRAND_SCROLL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'INFINITE_BRAND_SCROLL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'INFINITE_BRAND_SCROLL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main plugin class.
 */
class Infinite_Brand_Scroll {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	private static $instance = null;

	/**
	 * Initialize the plugin.
	 */
	private function __construct() {
		// Load plugin text domain.
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		// Register activation and deactivation hooks.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// Initialize plugin components.
		$this->load_dependencies();
		$this->init_hooks();
	}

	/**
	 * Get instance of this class.
	 *
	 * @return Infinite_Brand_Scroll
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Load plugin textdomain for translations.
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'infinite-brand-scroll',
			false,
			dirname( INFINITE_BRAND_SCROLL_PLUGIN_BASENAME ) . '/languages'
		);
	}

	/**
	 * Load plugin dependencies.
	 */
	private function load_dependencies() {
		require_once INFINITE_BRAND_SCROLL_PLUGIN_DIR . 'includes/class-ibs-assets.php';
		require_once INFINITE_BRAND_SCROLL_PLUGIN_DIR . 'includes/class-ibs-shortcode.php';
		require_once INFINITE_BRAND_SCROLL_PLUGIN_DIR . 'includes/class-ibs-widget.php';
		require_once INFINITE_BRAND_SCROLL_PLUGIN_DIR . 'includes/class-ibs-settings.php';
		
		// Load Gutenberg block if WordPress version supports it.
		if ( function_exists( 'register_block_type' ) ) {
			require_once INFINITE_BRAND_SCROLL_PLUGIN_DIR . 'includes/class-ibs-block.php';
		}
	}

	/**
	 * Initialize WordPress hooks.
	 */
	private function init_hooks() {
		// Initialize assets handler.
		IBS_Assets::get_instance();

		// Initialize shortcode.
		IBS_Shortcode::get_instance();

		// Register widget.
		add_action( 'widgets_init', array( $this, 'register_widget' ) );

		// Initialize settings page.
		if ( is_admin() ) {
			IBS_Settings::get_instance();
		}

		// Initialize Gutenberg block.
		if ( function_exists( 'register_block_type' ) ) {
			IBS_Block::get_instance();
		}

		// Add settings link on plugins page.
		add_filter( 'plugin_action_links_' . INFINITE_BRAND_SCROLL_PLUGIN_BASENAME, array( $this, 'add_action_links' ) );
	}

	/**
	 * Register widget.
	 */
	public function register_widget() {
		register_widget( 'IBS_Widget' );
	}

	/**
	 * Add settings link to plugin actions.
	 *
	 * @param array $links Plugin action links.
	 * @return array
	 */
	public function add_action_links( $links ) {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'options-general.php?page=infinite-brand-scroll' ) ),
			esc_html__( 'Settings', 'infinite-brand-scroll' )
		);
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Plugin activation.
	 */
	public function activate() {
		// Set default options.
		$default_options = array(
			'animation_speed' => 5000,
			'pause_on_hover' => true,
			'enable_3d'       => true,
			'brands'          => array(),
		);

		if ( ! get_option( 'infinite_brand_scroll_options' ) ) {
			add_option( 'infinite_brand_scroll_options', $default_options );
		}

		// Flush rewrite rules.
		flush_rewrite_rules();
	}

	/**
	 * Plugin deactivation.
	 */
	public function deactivate() {
		// Flush rewrite rules.
		flush_rewrite_rules();
	}
}

/**
 * Initialize the plugin.
 */
function infinite_brand_scroll_init() {
	return Infinite_Brand_Scroll::get_instance();
}

// Start the plugin.
infinite_brand_scroll_init();
