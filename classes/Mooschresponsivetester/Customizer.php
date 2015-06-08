<?php
/**
 * MooschResponsiveTester Customizer Class
 *
 * This file adds the responsive icons to Customizer for testing.
 *
 * @package MooschResponsiveTester
 * @since MooschResponsiveTester 0.1.0
 */

if( ! class_exists('Mooschresponsivetester_Customizer') )
{
	class Mooschresponsivetester_Customizer {

		private static $instance;

		/**
		 * Get the instance of the class
		 *
		 * @since    0.1.0
		 * @access   public
		 */
		public static function get_instance()
		{
			if ( ! self::$instance ) {
				self::$instance = new self();
				self::$instance->init();
			}
			return self::$instance;
		}

		private function __construct() {}

		/**
		 * Initializes class
		 *
		 * @since    0.1.0
		 * @access   public
		 */
		public function init()
		{
			global $wp_customize;

			if( isset( $wp_customize ) ) {
				// Enqueue Styles
				add_action( 'customize_controls_print_footer_scripts', array( $this, 'enque_scripts' ) );
				add_action( 'customize_controls_print_styles' , array( $this, 'enque_styles' ) );

				// Build resppnsive test buttons
				add_action( 'customize_controls_print_footer_scripts' , array( $this, 'build_customizer_menu' ) );
				// Build notification bar
				add_action( 'customize_controls_print_footer_scripts' , array( $this, 'build_notifications' ) );
			}
		}

		/**
		 * Enque scripts properly
		 *
		 * @since    0.1.0
		 * @access   public
		 */
		public function enque_scripts()
		{
			wp_enqueue_script('moosch-resptest-admin-customizer', MOOSCH_RESPTEST_URI . '/customizer/js/customizer.js', array( 'customize-controls', 'wp-color-picker' ), '0.1.0', true );
		}

		/**
		 * Enque styles properly
		 *
		 * @since    0.1.0
		 * @access   public
		 */
		public function enque_styles()
		{
			wp_enqueue_style('moosch-resptest-admin-customizer', MOOSCH_RESPTEST_URI . '/customizer/css/customizer.css', array(), '0.1.0');
		}	

		/**
		 * Build Wordpress Customizer menu additions
		 *
		 * Adds our responsive test buttons to the Customizer menu
		 *
		 * @since    0.1.0
		 * @access   private
		 */
		function build_customizer_menu()
		{
			$buttons = array();

			// Defaults
			$buttons = array(
				'smartphone' => array(320, 568),
				'tablet' => array(1024, 768),
				// 'laptop' => array(1440, 720),
				// 'desktop' => array(2560, 1265)
				'desktop' => array(1440, 720)
			);

			// Restrict buttons based on current device
			if ( wp_is_mobile() ){

				if( preg_match( '/iPad/', $_SERVER['HTTP_USER_AGENT'] ) ){
					unset($buttons['tablet']);

					// Remove anything bigger
					unset($buttons['laptop']);
					unset($buttons['desktop']);
				}

				if( preg_match( '/iPod|iPhone/', $_SERVER['HTTP_USER_AGENT'] ) ){
					// Unset all
					$buttons = array();
				}

				// $is_ios = wp_is_mobile() && preg_match( '/iPad|iPod|iPhone/', $_SERVER['HTTP_USER_AGENT'] );
				// if ( $is_ios ) {}
			}

			if( !empty( $buttons ) ){

				$nav = '<div class="customize-controls-moosch-actions">'
					.'<ul class="moosch-customizer-nav">';

					foreach( $buttons as $device => $sizes ){
						$nav .= '<li><span class="customize-controls-moosch-button customize-controls-moosch-button-dashboard-main dashicons dashicons-'.$device.'" title="'.$device.'" data-width="'.$sizes[0].'" data-height="'.$sizes[1].'" data-image="'.MOOSCH_RESPTEST_URI.'svg/icon-'.$device.'.svg"></span></li>';
					}

					$nav .= '</ul>'
				.'</div>';

				echo $nav;

			}

		}

		/**
		 * Build notification box
		 *
		 * Adds a hidden notification box for when we need to present an error with JavaScript
		 *
		 * @since    0.1.0
		 * @access   private
		 */
		function build_notifications()
		{
			echo '<div class="moosch-test-notification"><span class="dashicons dashicons-flag"></span><span class="moosch-test-notification-text"></span></div>';
		}

	}

}