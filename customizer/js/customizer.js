/**
* MooschResponsiveTester
*
* This file contains global widget functions
*
* @package MooschResponsiveTester
* @since MooschResponsiveTester 0.1.0
*
* Author: Moosch Media
* Author URI: http://wp.mooschmedia.com.com/
* License: GNU General Public License v2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

+( function( exports, $ ) {

	"use strict";

	// Check if customizer exists
	if ( ! wp || ! wp.customize ) return;

	// WordPress Stuff
	var	Previewer;

	var self,
		$nav = $('.moosch-customizer-nav'),
		$preview = $('#customize-preview'),
		$notification = $('.moosch-test-notification');

	wp.customize.MDeviceTestCustomizerPreviewer = {

		init: function () {
			self = this;
			self.moveNav();
			self.bindEvents();
			self.update_customizer_interface();
			wp.customize.previewer.bind('url', self.handle_customizer_talkback);
		},
		handle_customizer_talkback: function() {
			self.resetTestEnvironment();
			self.update_customizer_interface();
		},
		update_customizer_interface: function() {
			//Update preview to match the selected size
			self.resetTestEnvironment();
		},
		resetTestEnvironment: function(){
			if( $nav.find('.active')[0] ){
				self.resetPreview();
				self.clearAllActive();
			}
		},
		moveNav: function(){

			$('#customize-header-actions').append( $('.customize-controls-moosch-actions') );
			$('.customize-controls-moosch-actions').css({ 'display':'block', 'visibility':'visible' });

		},
		resetPreview: function(){
			$preview.children('iframe').css({
				'width': '',
				'height': ''
			});
		},
		clearAllActive: function(){
			$nav.find('.active').removeClass('active');
		},
		showNotifications: function( str ){
			$notification.addClass('show').find('.moosch-test-notification-text').text(str);
		},
		hideNotifications: function(){
			$notification.removeClass('show');
		},
		bindDocClick: function(){
			setTimeout(function(){
				$(document).bind('click.notificationAction', self.watchUnBindDocClick);
			},500);
		},
		watchUnBindDocClick: function(){
			$(document).unbind('.notificationAction');
			self.hideNotifications();
		},
		bindEvents: function(){

			$nav.find('span').on('click', function(e){
				e.preventDefault();

				var $btn = $(this),
					w = $btn.attr('data-width'),
					h = $btn.attr('data-height');

				// If is already active, turn all off
				if( $btn.parent('li').hasClass('active') ){
					self.resetPreview();
					self.clearAllActive();
					return false;
				}

				// Clear any active nav
				self.clearAllActive();

				// Make active
				$btn.parent().addClass('active');

				$preview.addClass('test-active');

				// Check sizes
				if( $preview.width() > w ){
					$preview.children('iframe').css({
						'width': w+'px',
						'height': h+'px'
					});
				} else {
					// Show error
					self.showNotifications('Your browser width is smaller than the selected test size.');
					// Clear any active nav
					self.clearAllActive();
					self.bindDocClick();
				}
			});

		}
	};

	// Cache Preview
	Previewer = wp.customize.Previewer;
	wp.customize.Previewer = Previewer.extend({
		initialize: function( params, options ) {

			// cache the Preview
			wp.customize.MDeviceTestCustomizerPreviewer.preview = this;

			// call the Previewer's initialize function
			Previewer.prototype.initialize.call( this, params, options );
		}
	} );

	// On document ready
	$( function() {

		// Initialize Previewer
		wp.customize.MDeviceTestCustomizerPreviewer.init();
	} );

})(wp, jQuery);