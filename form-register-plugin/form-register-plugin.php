<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              Gerson
 * @since             1.0.0
 * @package           Form_Register_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Form Register Plugin
 * Plugin URI:        form-register-plugin
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Gerson 
 * Author URI:        Gerson
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       form-register-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
require_once( plugin_dir_path( __FILE__ ) .'/twilio-lib/src/Twilio/autoload.php');
use Twilio\Rest\Client;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FORM_REGISTER_PLUGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-form-register-plugin-activator.php
 */
function activate_form_register_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-form-register-plugin-activator.php';
	Form_Register_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-form-register-plugin-deactivator.php
 */
function deactivate_form_register_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-form-register-plugin-deactivator.php';
	Form_Register_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_form_register_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_form_register_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-form-register-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_form_register_plugin() {

	$plugin = new Form_Register_Plugin();
	$plugin->run();

}
run_form_register_plugin();


function html_form_code() {
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post" class="register-plugin-form">';
	echo '<div><p>';
	echo 'Your Number Register on Twilio (required) <br/></p>';
	echo '<input type="text" name="cf-numbers" placeholder="+2348059794251" value="' . ( isset( $_POST["cf-numbers"] ) ? esc_attr( $_POST["cf-numbers"] ) : '' ) . '" size="40"  required/>';
	echo '</div>';
	echo '<div><p>';
	echo 'Your Message (required) <br/></p>';
	echo '<textarea rows="10" cols="35" name="cf-message" placeholder="Write your message.." required>' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
	echo '</div>';
	echo '<div><input type="submit" name="cf-submitted" value="Send"></div>';
	echo '</form>';
}






function cf_shortcode() {
	$plugin_register = new Form_Register_Plugin();
	$plugin_register->cf_create_db();
	ob_start();
	$plugin_register->cf_create_db_messages();
	$plugin_register->deliver_mail();
	$plugin_register->cf_data();
	$plugin_register->send_message();
	html_form_code();
	
	return ob_get_clean();
}

add_shortcode( 'twilio_contact_form', 'cf_shortcode' );

?>
