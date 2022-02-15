<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       Gerson
 * @since      1.0.0
 *
 * @package    Form_Register_Plugin
 * @subpackage Form_Register_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Form_Register_Plugin
 * @subpackage Form_Register_Plugin/admin
 * @author     Gerson  <gersonsanchez99@outlook.com>
 */

class Form_Register_Plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Form_Register_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Form_Register_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/form-register-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Form_Register_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Form_Register_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/form-register-plugin-admin.js', array( 'jquery' ), $this->version, false );

	}
			/**
		 *  Register the administration menu for this plugin into the WordPress Dashboard
		 * @since    1.0.0
		 */

		public function add_form_register_admin_setting() {

			/*
			* Add a settings page for this plugin to the Settings menu.
			*
			* Administration Menus: http://codex.wordpress.org/Administration_Menus
			*
			*/
			add_options_page( 'FORM REGISTER PAGE', 'TWILIO', 'manage_options', $this->plugin_name, array($this, 'display_form_register_settings_page')
			);
		}

		/**
		 * Render the settings page for this plugin.( The html file )
		 *
		 * @since    1.0.0
		 */

		public function display_form_register_settings_page() {
			include_once( 'partials/form-register-plugin-admin-display.php' );
		}
	


		/**
		 * Registers and Defines the necessary fields we need.
		 *
		 */
public function form_Register_Plugin_admin_settings_save(){

    register_setting( $this->plugin_name, $this->plugin_name, array($this, 'plugin_options_validate') );

    add_settings_section('form_register_plugin_main', 'Main Settings', array($this, 'form_Register_Plugin_section_text'), 'form-register-settings-page');

    add_settings_field('api_sid', 'API SID', array($this, 'form_Register_Plugin_setting_sid'), 'form-register-settings-page', 'form_register_plugin_main');

    add_settings_field('api_auth_token', 'API AUTH TOKEN', array($this, 'form_Register_Plugin_setting_token'), 'form-register-settings-page', 'form_register_plugin_main');

	add_settings_field('api_number', 'API NUMBER', array($this, 'form_Register_Plugin_setting_number'), 'form-register-settings-page', 'form_register_plugin_main');
}

/**
 * Displays the settings sub header
 *
 */
public function form_Register_Plugin_section_text() {
    echo '<h3>Edit api details '. $this->plugin_name.'</h3>';
} 
/**
 * Renders the number input field
 *
 */
public function form_Register_Plugin_setting_number() {

	$options = get_option($this->plugin_name);
	echo "<input id='plugin_text_string' name='$this->plugin_name[api_number]' size='40' type='text' value='{$options['api_number']}' />";
 }   
/**
 * Renders the sid input field
 *
 */
public function form_Register_Plugin_setting_sid() {

   $options = get_option($this->plugin_name);
   echo "<input id='plugin_text_string' name='$this->plugin_name[api_sid]' size='40' type='text' value='{$options['api_sid']}' />";
}   

/**
 * Renders the auth_token input field
 *
 */
public function form_Register_Plugin_setting_token() {
   $options = get_option($this->plugin_name);
   echo "<input id='plugin_text_string' name='$this->plugin_name[api_auth_token]' size='40' type='text' value='{$options['api_auth_token']}' />";
}


/**
 * Sanitises all input fields.
 *
 */
public function plugin_options_validate($input) {
    $newinput['api_sid'] = trim($input['api_sid']);
    $newinput['api_auth_token'] = trim($input['api_auth_token']);
	$newinput['api_number'] = trim($input['api_number']);

    return $newinput;
}


}
