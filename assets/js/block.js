/**
 * Infinite Brand Scroll - Gutenberg Block
 * 
 * @package InfiniteBrandScroll
 */

(function(blocks, element, editor, components, i18n) {
	const { registerBlockType } = blocks;
	const { createElement: el } = element;
	const { InspectorControls } = editor;
	const { PanelBody, RangeControl, ToggleControl } = components;
	const { __ } = i18n;

	registerBlockType('infinite-brand-scroll/carousel', {
		title: __('Infinite Brand Scroll', 'infinite-brand-scroll'),
		icon: 'images-alt2',
		category: 'widgets',
		keywords: [
			__('brand', 'infinite-brand-scroll'),
			__('carousel', 'infinite-brand-scroll'),
			__('scroll', 'infinite-brand-scroll')
		],
		attributes: {
			height: {
				type: 'number',
				default: 600
			},
			speed: {
				type: 'number',
				default: 5000
			},
			pauseOnHover: {
				type: 'boolean',
				default: true
			},
			enable3D: {
				type: 'boolean',
				default: true
			}
		},

		edit: function(props) {
			const { attributes, setAttributes } = props;
			const { height, speed, pauseOnHover, enable3D } = attributes;

			return [
				el(
					InspectorControls,
					{ key: 'inspector' },
					el(
						PanelBody,
						{
							title: __('Carousel Settings', 'infinite-brand-scroll'),
							initialOpen: true
						},
						el(RangeControl, {
							label: __('Height (px)', 'infinite-brand-scroll'),
							value: height,
							onChange: function(value) {
								setAttributes({ height: value });
							},
							min: 200,
							max: 1200,
							step: 50
						}),
						el(RangeControl, {
							label: __('Animation Speed (ms)', 'infinite-brand-scroll'),
							value: speed,
							onChange: function(value) {
								setAttributes({ speed: value });
							},
							min: 1000,
							max: 20000,
							step: 100
						}),
						el(ToggleControl, {
							label: __('Pause on Hover', 'infinite-brand-scroll'),
							checked: pauseOnHover,
							onChange: function(value) {
								setAttributes({ pauseOnHover: value });
							}
						}),
						el(ToggleControl, {
							label: __('Enable 3D Effects', 'infinite-brand-scroll'),
							checked: enable3D,
							onChange: function(value) {
								setAttributes({ enable3D: value });
							}
						})
					)
				),
				el(
					'div',
					{
						key: 'preview',
						className: 'ibs-block-preview',
						style: {
							background: '#0b0b0b',
							color: '#fff',
							padding: '40px',
							textAlign: 'center',
							minHeight: height + 'px',
							display: 'flex',
							alignItems: 'center',
							justifyContent: 'center',
							flexDirection: 'column'
						}
					},
					el('h3', {
						style: { margin: '0 0 10px 0' }
					}, __('Infinite Brand Scroll', 'infinite-brand-scroll')),
					el('p', {
						style: { margin: '0', opacity: '0.7' }
					}, __('Preview available on frontend', 'infinite-brand-scroll')),
					el('p', {
						style: { margin: '10px 0 0 0', fontSize: '14px', opacity: '0.5' }
					}, __('Height: ', 'infinite-brand-scroll') + height + 'px, ' +
					   __('Speed: ', 'infinite-brand-scroll') + speed + 'ms')
				)
			];
		},

		save: function() {
			// Render via PHP callback.
			return null;
		}
	});

})(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor || window.wp.editor,
	window.wp.components,
	window.wp.i18n
);
