<?php
/**
 * Widget Class
 *
 * Provides a WordPress widget for the infinite brand scroll.
 *
 * @package InfiniteBrandScroll
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class IBS_Widget
 */
class IBS_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'ibs_widget',
			esc_html__( 'Infinite Brand Scroll', 'infinite-brand-scroll' ),
			array(
				'description' => esc_html__( 'Display an infinite scrolling carousel of brand logos', 'infinite-brand-scroll' ),
				'classname'   => 'ibs-widget',
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title  = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$height = ! empty( $instance['height'] ) ? absint( $instance['height'] ) : 600;

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $title ) ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		// Use shortcode to render.
		echo do_shortcode( '[infinite_brand_scroll height="' . esc_attr( $height ) . '"]' );

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title  = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$height = ! empty( $instance['height'] ) ? $instance['height'] : 600;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'infinite-brand-scroll' ); ?>
			</label>
			<input class="widefat" 
				   id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
				   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
				   type="text" 
				   value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>">
				<?php esc_html_e( 'Height (px):', 'infinite-brand-scroll' ); ?>
			</label>
			<input class="widefat" 
				   id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" 
				   name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" 
				   type="number" 
				   min="200" 
				   max="1200" 
				   step="50" 
				   value="<?php echo esc_attr( $height ); ?>">
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance           = array();
		$instance['title']  = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['height'] = ! empty( $new_instance['height'] ) ? absint( $new_instance['height'] ) : 600;

		return $instance;
	}
}
