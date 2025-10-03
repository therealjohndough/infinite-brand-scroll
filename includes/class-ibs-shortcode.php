<?php
/**
 * Shortcode Handler Class
 *
 * Handles the [infinite_brand_scroll] shortcode.
 *
 * @package InfiniteBrandScroll
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class IBS_Shortcode
 */
class IBS_Shortcode {

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
		add_shortcode( 'infinite_brand_scroll', array( $this, 'render_shortcode' ) );
	}

	/**
	 * Get instance of this class.
	 *
	 * @return IBS_Shortcode
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Render the shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function render_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'speed'          => '',
				'pause_on_hover' => '',
				'enable_3d'      => '',
				'height'         => '600',
			),
			$atts,
			'infinite_brand_scroll'
		);

		// Get plugin options.
		$options = get_option( 'infinite_brand_scroll_options', array() );
		
		// Override options with shortcode attributes if provided.
		if ( ! empty( $atts['speed'] ) ) {
			$options['animation_speed'] = absint( $atts['speed'] );
		}
		if ( '' !== $atts['pause_on_hover'] ) {
			$options['pause_on_hover'] = filter_var( $atts['pause_on_hover'], FILTER_VALIDATE_BOOLEAN );
		}
		if ( '' !== $atts['enable_3d'] ) {
			$options['enable_3d'] = filter_var( $atts['enable_3d'], FILTER_VALIDATE_BOOLEAN );
		}

		// Get brands.
		$brands = isset( $options['brands'] ) ? $options['brands'] : array();

		if ( empty( $brands ) ) {
			// Return a message for logged-in admins.
			if ( current_user_can( 'manage_options' ) ) {
				return '<div class="ibs-notice" style="padding: 20px; background: #f0f0f0; border-left: 4px solid #dc3232;">' .
					esc_html__( 'No brands configured. Please add brands in the plugin settings.', 'infinite-brand-scroll' ) .
					' <a href="' . esc_url( admin_url( 'options-general.php?page=infinite-brand-scroll' ) ) . '">' .
					esc_html__( 'Go to Settings', 'infinite-brand-scroll' ) . '</a></div>';
			}
			return '';
		}

		// Enqueue assets.
		IBS_Assets::enqueue_assets( $brands, $options );

		// Generate unique ID for this instance.
		static $instance_count = 0;
		$instance_count++;
		$container_id = 'ibs-container-' . $instance_count;

		// Sanitize height.
		$height = absint( $atts['height'] );

		// Build output.
		ob_start();
		?>
		<div id="<?php echo esc_attr( $container_id ); ?>" 
			 class="infinite-brand-scroll-container" 
			 style="height: <?php echo esc_attr( $height ); ?>px;"
			 role="region"
			 aria-label="<?php echo esc_attr__( 'Brand Showcase', 'infinite-brand-scroll' ); ?>"
			 data-brands="<?php echo esc_attr( wp_json_encode( $brands ) ); ?>"
			 data-options="<?php echo esc_attr( wp_json_encode( $options ) ); ?>">
			<div class="ibs-loading" aria-live="polite" aria-busy="true">
				<?php echo esc_html__( 'Loading brand showcase...', 'infinite-brand-scroll' ); ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
