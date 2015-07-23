<?php
/**
 * MooschResponsiveTester plugin file
 * @link              http://wp.mooschmedia.com/plugins/moosch-responsive-tester/
 * @since             0.1.0
 * @package           MooschResponsiveTester
 * 
 * @wordpress-plugin
 * Plugin Name:       Moosch Responsive Tester
 * Plugin URI:        http://wp.mooschmedia.com/plugins/moosch-responsive-tester/
 * Description:       The plugin adds additional buttons to the Wordpress Customizer Menu. These are used to test your website on various screen sizes.
 * Version:           0.1.0
 * Author:            Moosch Media Limited
 * Author URI:        http://wp.mooschmedia.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       moosch-responsive-tester
 */


/**
 * Defined constants
 *
 * @since    0.1.0
 */
define( 'MOOSCH_RESPTEST_NAME', 'moosch-responsive-tester' );
define( 'MOOSCH_RESPTEST_URL', dirname (__FILE__) . '/' );
define( 'MOOSCH_RESPTEST_URI', plugins_url() . '/'.MOOSCH_RESPTEST_NAME.'/' );


/**
 * Autoloader
 *
 * Loads all class files required
 *
 * @since    0.1.0
 */
spl_autoload_register( 'moosch_responsive_tester_autoloader' ); // Register autoloader
function moosch_responsive_tester_autoloader( $class_name ) {
	if ( false !== strpos( $class_name, 'Mooschresponsivetester' ) ) {
		$classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
		$class_file = str_replace( '_', DIRECTORY_SEPARATOR, $class_name ) . '.php';
		require_once $classes_dir . $class_file;
	}
}

/**
 * Initializes the classes
 *
 * @since    0.1.0
 */
function moosch_responsive_tester_customizer_init(){
	global $wp_customize;
	if( $wp_customize ){
		$moosch_layers_widget = Mooschresponsivetester_Customizer::get_instance();
	}
}
add_action( 'customize_register' , 'moosch_responsive_tester_customizer_init' , 50 );
// add_action( 'init' , 'moosch_responsive_tester_customizer_init');

