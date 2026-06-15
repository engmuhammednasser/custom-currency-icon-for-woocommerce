/**
 * Admin JavaScript
 * معالجة upload الصور والمعاينة المباشرة
 *
 * @package CCFW
 */

(function($) {
	'use strict';

	// Wait for DOM to be ready
	$(document).ready(function() {
		const CCFW = {
			frame: null,
			currentCurrency: '',
			currentImageId: 0,

			init: function() {
				console.log('CCFW Admin initialized');
				this.bindEvents();
				// Load initial currency image after DOM is ready
				setTimeout(function() {
					CCFW.loadCurrencyImage();
				}, 100);
			},

			bindEvents: function() {
				const self = this;

				// Upload button
				$('#ccfw-upload-image-btn').on('click', function(e) {
					e.preventDefault();
					console.log('Upload button clicked');

					const currency = $('#ccfw-currency-select').val();
					if (!currency) {
						alert('Please select a currency first');
						return;
					}

					// Check if wp.media exists
					if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
						console.error('wp.media is not available');
						alert('Media Library is not available. Please reload the page.');
						return;
					}

					self.openMediaFrame();
				});

				// Remove button
				$('#ccfw-remove-image-btn').on('click', function(e) {
					e.preventDefault();
					console.log('Remove button clicked');
					self.removeImage();
				});

				// Currency select change
				$('#ccfw-currency-select').on('change', function() {
					self.currentCurrency = $(this).val();
					if (self.currentCurrency) {
						localStorage.setItem('ccfw_last_currency', self.currentCurrency);
					} else {
						localStorage.removeItem('ccfw_last_currency');
					}
					console.log('Currency changed to: ' + self.currentCurrency);
					self.loadCurrencyImage();
				});

				// Load last selected currency from localStorage
				const lastCurrency = localStorage.getItem('ccfw_last_currency');
				if (lastCurrency && $('#ccfw-currency-select option[value="' + lastCurrency + '"]').length > 0) {
					$('#ccfw-currency-select').val(lastCurrency);
					self.currentCurrency = lastCurrency;
				}

				// Settings change - update preview
				$('input[id^="ccfw_"], select[id^="ccfw_"]').on('change', function() {
					console.log('Settings changed');
					self.updatePreview();
				});

				// Initial preview update
				this.updatePreview();
			},

			/**
			 * Open media frame
			 */
			openMediaFrame: function() {
				const self = this;
				const currency = $('#ccfw-currency-select').val();

				if (!currency) {
					alert('Please select a currency first');
					return;
				}

				// Create frame
				this.frame = wp.media({
					title: 'Select Currency Icon',
					button: { text: 'Select Image' },
					multiple: false,
					library: { type: ['image/jpeg', 'image/png', 'image/webp'] }
				});

				// Handle selection
				this.frame.on('select', function() {
					const attachment = self.frame.state().get('selection').first().toJSON();
					self.handleImageSelected(attachment);
				});

				this.frame.open();
			},

			/**
			 * Handle selected image
			 */
			handleImageSelected: function(attachment) {
				const self = this;
				const currency = $('#ccfw-currency-select').val();

				if (!currency) {
					return;
				}

				// Show preview
				this.showImagePreview(attachment);

				// Update preview
				this.updatePreview();

				// Show remove button
				$('#ccfw-remove-image-btn').show();

				// Save to database via AJAX
				$.ajax({
					url: ccfwData.ajaxurl,
					type: 'POST',
					data: {
						action: 'ccfw_upload_image',
						nonce: ccfwData.nonce,
						currency: currency,
						image_id: attachment.id
					},
					success: function(response) {
						if (response.success) {
							self.currentImageId = response.data.attachment_id || response.data.image_id;
							self.currentImageUrl = response.data.url;
							console.log('Image saved successfully');
						} else {
							alert('Error saving image: ' + ((response.data && response.data.message) || 'Unknown error'));
						}
					},
					error: function() {
						alert('AJAX request failed');
					}
				});
			},

			/**
			 * Show image preview
			 */
			showImagePreview: function(attachment) {
				const previewContainer = $('#ccfw-image-preview');
				previewContainer.empty();

				if (attachment.url) {
					$('<img>')
						.attr('src', attachment.url)
						.attr('alt', attachment.title || 'Currency Icon')
						.css({
							'max-width': '150px',
							'max-height': '150px',
							'border': '1px solid #ddd',
							'border-radius': '4px',
							'padding': '5px'
						})
						.appendTo(previewContainer);
				}
			},

			/**
			 * Load current currency image
			 */
			loadCurrencyImage: function() {
				const self = this;
				const currency = $('#ccfw-currency-select').val();
				const removeBtn = $('#ccfw-remove-image-btn');
				const previewContainer = $('#ccfw-image-preview');

				previewContainer.empty();
				removeBtn.hide();
				this.currentImageId = 0;

				if (!currency) {
					return;
				}

				// Load saved image via AJAX
				$.ajax({
					url: ccfwData.ajaxurl,
					type: 'POST',
					data: {
						action: 'ccfw_get_currency_image',
						nonce: ccfwData.nonce,
						currency: currency
					},
					success: function(response) {
						if (response.success && response.data.image_id) {
							self.currentImageId = response.data.image_id;
							self.currentImageUrl = response.data.url;

							// Show preview
							$('<img>')
								.attr('src', response.data.url)
								.attr('alt', 'Currency Icon')
								.css({
									'max-width': '150px',
									'max-height': '150px',
									'border': '1px solid #ddd',
									'border-radius': '4px',
									'padding': '5px'
								})
								.appendTo(previewContainer);

							// Show remove button
							removeBtn.show();
						}
						self.updatePreview();
					},
					error: function() {
						self.updatePreview();
					}
				});
			},

			/**
			 * Remove image
			 */
			removeImage: function() {
				const self = this;
				const currency = $('#ccfw-currency-select').val();

				if (!currency || !confirm('Are you sure you want to remove this image?')) {
					return;
				}

				$.ajax({
					url: ccfwData.ajaxurl,
					type: 'POST',
					data: {
						action: 'ccfw_remove_image',
						nonce: ccfwData.nonce,
						currency: currency,
						image_id: this.currentImageId
					},
					success: function(response) {
						if (response.success) {
							$('#ccfw-image-preview').empty();
							$('#ccfw-remove-image-btn').hide();
							self.currentImageId = 0;
							self.currentImageUrl = '';
							self.updatePreview();
						} else {
							alert('Error removing image: ' + ((response.data && response.data.message) || 'Unknown error'));
						}
					},
					error: function() {
						alert('AJAX request failed');
					}
				});
			},

			/**
			 * Update live preview
			 */
			updatePreview: function() {
				const previewData = {
					currency: $('#ccfw-currency-select').val() || 'SAR',
					display_method: $('#ccfw_display_method').val() || 'image_before_price',
					width: $('#ccfw_width').val() || 24,
					height: $('#ccfw_height').val() || 24,
					margin: $('#ccfw_margin').val() || 4,
					alignment: $('#ccfw_alignment').val() || 'middle',
					amount: 100
				};

				$.ajax({
					url: ccfwData.ajaxurl,
					type: 'POST',
					data: {
						action: 'ccfw_update_preview',
						nonce: ccfwData.nonce,
						preview_data: previewData
					},
					success: function(response) {
						if (response.success) {
							$('#ccfw-preview-content').html(response.data.preview || '<p>Preview</p>');
						} else {
							$('#ccfw-preview-content').html('<p>Preview error</p>');
						}
					},
					error: function() {
						$('#ccfw-preview-content').html('<p>Connection failed</p>');
					}
				});
			}
		};

		// Initialize
		CCFW.init();
	});

})(jQuery);
