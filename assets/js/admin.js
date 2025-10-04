/**
 * Infinite Brand Scroll - Admin Script
 * 
 * @package InfiniteBrandScroll
 */

(function($) {
	'use strict';

	$(document).ready(function() {
		// Tab navigation.
		$('.nav-tab').on('click', function(e) {
			e.preventDefault();
			const target = $(this).attr('href');
			
			$('.nav-tab').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');
			
			$('.tab-content').hide();
			$(target).show();
		});

		// Add brand.
		let brandIndex = $('.ibs-brand-item').length;

		$('#ibs-add-brand').on('click', function() {
			const template = $('#ibs-brand-template').html();
			const brandHtml = template.replace(/__INDEX__/g, brandIndex);
			$('#ibs-brands-list').append(brandHtml);
			brandIndex++;
		});

		// Save brand.
		$(document).on('click', '.ibs-save-brand', function() {
			const $item = $(this).closest('.ibs-brand-item');
			const index = $item.data('index');
			const title = $item.find('.ibs-brand-title').val();
			const tagline = $item.find('.ibs-brand-tagline').val();
			const image = $item.find('.ibs-brand-image').val();
			const logo = $item.find('.ibs-brand-logo').val();

			if (!title || !image) {
				alert('Please fill in at least the brand title and image.');
				return;
			}

			$.ajax({
				url: ibsAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'ibs_save_brand',
					nonce: ibsAdmin.nonce,
					index: index,
					title: title,
					tagline: tagline,
					image: image,
					logo: logo
				},
				success: function(response) {
					if (response.success) {
						alert(response.data.message);
						// Update the item's data-index if it was new.
						if (index === '__INDEX__') {
							$item.attr('data-index', brandIndex - 1);
						}
						// Update header title.
						$item.find('.ibs-brand-header h3').text(title);
					} else {
						alert('Error: ' + response.data.message);
					}
				},
				error: function() {
					alert('An error occurred while saving the brand.');
				}
			});
		});

		// Delete brand.
		$(document).on('click', '.ibs-delete-brand', function() {
			if (!confirm(ibsAdmin.confirmDelete)) {
				return;
			}

			const $item = $(this).closest('.ibs-brand-item');
			const index = $item.data('index');

			// If it's a new unsaved brand, just remove it.
			if (index === '__INDEX__') {
				$item.remove();
				return;
			}

			$.ajax({
				url: ibsAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'ibs_delete_brand',
					nonce: ibsAdmin.nonce,
					index: index
				},
				success: function(response) {
					if (response.success) {
						$item.fadeOut(300, function() {
							$(this).remove();
							// Re-index remaining items.
							$('.ibs-brand-item').each(function(i) {
								$(this).attr('data-index', i);
							});
						});
					} else {
						alert('Error: ' + response.data.message);
					}
				},
				error: function() {
					alert('An error occurred while deleting the brand.');
				}
			});
		});

		// Media uploader for images.
		let mediaUploader;

		$(document).on('click', '.ibs-select-image, .ibs-select-logo', function(e) {
			e.preventDefault();

			const $button = $(this);
			const $input = $button.prev('input');
			const isLogo = $button.hasClass('ibs-select-logo');

			// If the uploader object has already been created, reopen the dialog.
			if (mediaUploader) {
				mediaUploader.open();
				return;
			}

			// Create the media uploader.
			mediaUploader = wp.media({
				title: isLogo ? ibsAdmin.selectLogo : ibsAdmin.selectImage,
				button: {
					text: ibsAdmin.useThisImage
				},
				multiple: false
			});

			// When an image is selected, grab the URL.
			mediaUploader.on('select', function() {
				const attachment = mediaUploader.state().get('selection').first().toJSON();
				$input.val(attachment.url);
			});

			// Open the uploader dialog.
			mediaUploader.open();
		});

		// Color picker initialization (for future enhancements).
		$('.ibs-color-picker').wpColorPicker();
	});

})(jQuery);
