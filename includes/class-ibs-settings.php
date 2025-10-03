<?php
/**
 * Settings Page Class
 *
 * Handles the admin settings page for the plugin.
 *
 * @package InfiniteBrandScroll
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class IBS_Settings
 */
class IBS_Settings {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	private static $instance = null;

	/**
	 * Option name.
	 *
	 * @var string
	 */
	private $option_name = 'infinite_brand_scroll_options';

	/**
	 * Initialize the class.
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'wp_ajax_ibs_save_brand', array( $this, 'ajax_save_brand' ) );
		add_action( 'wp_ajax_ibs_delete_brand', array( $this, 'ajax_delete_brand' ) );
	}

	/**
	 * Get instance of this class.
	 *
	 * @return IBS_Settings
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Add settings page to WordPress admin.
	 */
	public function add_settings_page() {
		add_options_page(
			esc_html__( 'Infinite Brand Scroll Settings', 'infinite-brand-scroll' ),
			esc_html__( 'Brand Scroll', 'infinite-brand-scroll' ),
			'manage_options',
			'infinite-brand-scroll',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register settings.
	 */
	public function register_settings() {
		register_setting(
			'infinite_brand_scroll_group',
			$this->option_name,
			array(
				'sanitize_callback' => array( $this, 'sanitize_options' ),
			)
		);

		// General settings section.
		add_settings_section(
			'ibs_general_section',
			esc_html__( 'General Settings', 'infinite-brand-scroll' ),
			array( $this, 'render_general_section' ),
			'infinite-brand-scroll'
		);

		// Animation speed field.
		add_settings_field(
			'animation_speed',
			esc_html__( 'Animation Speed (ms)', 'infinite-brand-scroll' ),
			array( $this, 'render_animation_speed_field' ),
			'infinite-brand-scroll',
			'ibs_general_section'
		);

		// Pause on hover field.
		add_settings_field(
			'pause_on_hover',
			esc_html__( 'Pause on Hover', 'infinite-brand-scroll' ),
			array( $this, 'render_pause_on_hover_field' ),
			'infinite-brand-scroll',
			'ibs_general_section'
		);

		// Enable 3D field.
		add_settings_field(
			'enable_3d',
			esc_html__( 'Enable 3D Effects', 'infinite-brand-scroll' ),
			array( $this, 'render_enable_3d_field' ),
			'infinite-brand-scroll',
			'ibs_general_section'
		);
	}

	/**
	 * Sanitize options.
	 *
	 * @param array $input Input options.
	 * @return array
	 */
	public function sanitize_options( $input ) {
		$output = array();

		if ( isset( $input['animation_speed'] ) ) {
			$output['animation_speed'] = absint( $input['animation_speed'] );
		}

		if ( isset( $input['pause_on_hover'] ) ) {
			$output['pause_on_hover'] = (bool) $input['pause_on_hover'];
		} else {
			$output['pause_on_hover'] = false;
		}

		if ( isset( $input['enable_3d'] ) ) {
			$output['enable_3d'] = (bool) $input['enable_3d'];
		} else {
			$output['enable_3d'] = false;
		}

		// Keep existing brands data.
		$existing = get_option( $this->option_name, array() );
		if ( isset( $existing['brands'] ) ) {
			$output['brands'] = $existing['brands'];
		}

		return $output;
	}

	/**
	 * Render general section description.
	 */
	public function render_general_section() {
		echo '<p>' . esc_html__( 'Configure the general behavior of the infinite brand scroll.', 'infinite-brand-scroll' ) . '</p>';
	}

	/**
	 * Render animation speed field.
	 */
	public function render_animation_speed_field() {
		$options = get_option( $this->option_name, array() );
		$value   = isset( $options['animation_speed'] ) ? $options['animation_speed'] : 5000;
		?>
		<input type="number" 
			   name="<?php echo esc_attr( $this->option_name ); ?>[animation_speed]" 
			   value="<?php echo esc_attr( $value ); ?>" 
			   min="1000" 
			   max="20000" 
			   step="100"
			   class="regular-text" />
		<p class="description">
			<?php echo esc_html__( 'Duration of scroll animation in milliseconds (1000 = 1 second)', 'infinite-brand-scroll' ); ?>
		</p>
		<?php
	}

	/**
	 * Render pause on hover field.
	 */
	public function render_pause_on_hover_field() {
		$options = get_option( $this->option_name, array() );
		$checked = isset( $options['pause_on_hover'] ) && $options['pause_on_hover'];
		?>
		<label>
			<input type="checkbox" 
				   name="<?php echo esc_attr( $this->option_name ); ?>[pause_on_hover]" 
				   value="1" 
				   <?php checked( $checked ); ?> />
			<?php echo esc_html__( 'Pause animation when mouse hovers over the carousel', 'infinite-brand-scroll' ); ?>
		</label>
		<?php
	}

	/**
	 * Render enable 3D field.
	 */
	public function render_enable_3d_field() {
		$options = get_option( $this->option_name, array() );
		$checked = isset( $options['enable_3d'] ) && $options['enable_3d'];
		?>
		<label>
			<input type="checkbox" 
				   name="<?php echo esc_attr( $this->option_name ); ?>[enable_3d]" 
				   value="1" 
				   <?php checked( $checked ); ?> />
			<?php echo esc_html__( 'Enable 3D rendering with Three.js (disable for better performance)', 'infinite-brand-scroll' ); ?>
		</label>
		<?php
	}

	/**
	 * Render settings page.
	 */
	public function render_settings_page() {
		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'infinite-brand-scroll' ) );
		}

		$options = get_option( $this->option_name, array() );
		$brands  = isset( $options['brands'] ) ? $options['brands'] : array();
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<h2 class="nav-tab-wrapper">
				<a href="#general" class="nav-tab nav-tab-active"><?php esc_html_e( 'General', 'infinite-brand-scroll' ); ?></a>
				<a href="#brands" class="nav-tab"><?php esc_html_e( 'Brands', 'infinite-brand-scroll' ); ?></a>
				<a href="#usage" class="nav-tab"><?php esc_html_e( 'Usage', 'infinite-brand-scroll' ); ?></a>
			</h2>

			<div id="general" class="tab-content active">
				<form method="post" action="options.php">
					<?php
					settings_fields( 'infinite_brand_scroll_group' );
					do_settings_sections( 'infinite-brand-scroll' );
					submit_button();
					?>
				</form>
			</div>

			<div id="brands" class="tab-content" style="display:none;">
				<h2><?php esc_html_e( 'Manage Brands', 'infinite-brand-scroll' ); ?></h2>
				<p><?php esc_html_e( 'Add and manage the brands that will appear in your carousel.', 'infinite-brand-scroll' ); ?></p>

				<div id="ibs-brands-list">
					<?php
					if ( ! empty( $brands ) ) {
						foreach ( $brands as $index => $brand ) {
							$this->render_brand_item( $brand, $index );
						}
					}
					?>
				</div>

				<button type="button" id="ibs-add-brand" class="button button-primary">
					<?php esc_html_e( 'Add Brand', 'infinite-brand-scroll' ); ?>
				</button>
			</div>

			<div id="usage" class="tab-content" style="display:none;">
				<h2><?php esc_html_e( 'How to Use', 'infinite-brand-scroll' ); ?></h2>
				
				<h3><?php esc_html_e( 'Shortcode', 'infinite-brand-scroll' ); ?></h3>
				<p><?php esc_html_e( 'Add the following shortcode to any post or page:', 'infinite-brand-scroll' ); ?></p>
				<code>[infinite_brand_scroll]</code>
				
				<h4><?php esc_html_e( 'Shortcode Parameters', 'infinite-brand-scroll' ); ?></h4>
				<ul>
					<li><code>speed</code> - <?php esc_html_e( 'Animation speed in milliseconds (e.g., speed="3000")', 'infinite-brand-scroll' ); ?></li>
					<li><code>pause_on_hover</code> - <?php esc_html_e( 'Pause on hover (true/false)', 'infinite-brand-scroll' ); ?></li>
					<li><code>enable_3d</code> - <?php esc_html_e( 'Enable 3D effects (true/false)', 'infinite-brand-scroll' ); ?></li>
					<li><code>height</code> - <?php esc_html_e( 'Container height in pixels (default: 600)', 'infinite-brand-scroll' ); ?></li>
				</ul>

				<h3><?php esc_html_e( 'Widget', 'infinite-brand-scroll' ); ?></h3>
				<p><?php esc_html_e( 'Go to Appearance → Widgets and add the "Infinite Brand Scroll" widget to your sidebar or footer.', 'infinite-brand-scroll' ); ?></p>

				<h3><?php esc_html_e( 'Gutenberg Block', 'infinite-brand-scroll' ); ?></h3>
				<p><?php esc_html_e( 'In the block editor, search for "Infinite Brand Scroll" block and add it to your page.', 'infinite-brand-scroll' ); ?></p>

				<h3><?php esc_html_e( 'PHP Function', 'infinite-brand-scroll' ); ?></h3>
				<p><?php esc_html_e( 'Add to your theme template:', 'infinite-brand-scroll' ); ?></p>
				<code>&lt;?php echo do_shortcode('[infinite_brand_scroll]'); ?&gt;</code>
			</div>
		</div>

		<template id="ibs-brand-template">
			<?php $this->render_brand_item( array(), '__INDEX__' ); ?>
		</template>
		<?php
	}

	/**
	 * Render brand item.
	 *
	 * @param array  $brand Brand data.
	 * @param string $index Brand index.
	 */
	private function render_brand_item( $brand, $index ) {
		$title   = isset( $brand['title'] ) ? $brand['title'] : '';
		$tagline = isset( $brand['tagline'] ) ? $brand['tagline'] : '';
		$image   = isset( $brand['image'] ) ? $brand['image'] : '';
		$logo    = isset( $brand['logo'] ) ? $brand['logo'] : '';
		?>
		<div class="ibs-brand-item" data-index="<?php echo esc_attr( $index ); ?>">
			<div class="ibs-brand-header">
				<h3><?php echo esc_html( $title ? $title : __( 'New Brand', 'infinite-brand-scroll' ) ); ?></h3>
				<button type="button" class="button ibs-delete-brand"><?php esc_html_e( 'Delete', 'infinite-brand-scroll' ); ?></button>
			</div>
			<div class="ibs-brand-fields">
				<p>
					<label><?php esc_html_e( 'Brand Title:', 'infinite-brand-scroll' ); ?></label>
					<input type="text" class="ibs-brand-title widefat" value="<?php echo esc_attr( $title ); ?>" />
				</p>
				<p>
					<label><?php esc_html_e( 'Tagline:', 'infinite-brand-scroll' ); ?></label>
					<input type="text" class="ibs-brand-tagline widefat" value="<?php echo esc_attr( $tagline ); ?>" />
				</p>
				<p>
					<label><?php esc_html_e( 'Banner Image URL:', 'infinite-brand-scroll' ); ?></label>
					<input type="text" class="ibs-brand-image widefat" value="<?php echo esc_url( $image ); ?>" />
					<button type="button" class="button ibs-select-image"><?php esc_html_e( 'Select Image', 'infinite-brand-scroll' ); ?></button>
				</p>
				<p>
					<label><?php esc_html_e( 'Logo URL:', 'infinite-brand-scroll' ); ?></label>
					<input type="text" class="ibs-brand-logo widefat" value="<?php echo esc_url( $logo ); ?>" />
					<button type="button" class="button ibs-select-logo"><?php esc_html_e( 'Select Logo', 'infinite-brand-scroll' ); ?></button>
				</p>
			</div>
			<div class="ibs-brand-actions">
				<button type="button" class="button button-primary ibs-save-brand"><?php esc_html_e( 'Save Brand', 'infinite-brand-scroll' ); ?></button>
			</div>
		</div>
		<?php
	}

	/**
	 * AJAX handler for saving a brand.
	 */
	public function ajax_save_brand() {
		// Verify nonce.
		check_ajax_referer( 'ibs_admin_nonce', 'nonce' );

		// Check capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions', 'infinite-brand-scroll' ) ) );
		}

		// Get and sanitize input.
		$index   = isset( $_POST['index'] ) ? sanitize_text_field( wp_unslash( $_POST['index'] ) ) : '';
		$title   = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
		$tagline = isset( $_POST['tagline'] ) ? sanitize_text_field( wp_unslash( $_POST['tagline'] ) ) : '';
		$image   = isset( $_POST['image'] ) ? esc_url_raw( wp_unslash( $_POST['image'] ) ) : '';
		$logo    = isset( $_POST['logo'] ) ? esc_url_raw( wp_unslash( $_POST['logo'] ) ) : '';

		// Get options.
		$options = get_option( $this->option_name, array() );
		if ( ! isset( $options['brands'] ) ) {
			$options['brands'] = array();
		}

		// Create brand data.
		$brand = array(
			'title'   => $title,
			'tagline' => $tagline,
			'image'   => $image,
			'logo'    => $logo,
		);

		// Add or update brand.
		if ( is_numeric( $index ) ) {
			$options['brands'][ $index ] = $brand;
		} else {
			$options['brands'][] = $brand;
		}

		// Save options.
		update_option( $this->option_name, $options );

		wp_send_json_success( array(
			'message' => __( 'Brand saved successfully', 'infinite-brand-scroll' ),
			'brand'   => $brand,
		) );
	}

	/**
	 * AJAX handler for deleting a brand.
	 */
	public function ajax_delete_brand() {
		// Verify nonce.
		check_ajax_referer( 'ibs_admin_nonce', 'nonce' );

		// Check capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions', 'infinite-brand-scroll' ) ) );
		}

		// Get index.
		$index = isset( $_POST['index'] ) ? absint( $_POST['index'] ) : null;

		if ( null === $index ) {
			wp_send_json_error( array( 'message' => __( 'Invalid brand index', 'infinite-brand-scroll' ) ) );
		}

		// Get options.
		$options = get_option( $this->option_name, array() );
		
		if ( isset( $options['brands'][ $index ] ) ) {
			unset( $options['brands'][ $index ] );
			$options['brands'] = array_values( $options['brands'] ); // Re-index array.
			update_option( $this->option_name, $options );
		}

		wp_send_json_success( array( 'message' => __( 'Brand deleted successfully', 'infinite-brand-scroll' ) ) );
	}
}
