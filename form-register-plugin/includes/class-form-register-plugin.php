<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       Gerson
 * @since      1.0.0
 *
 * @package    Form_Register_Plugin
 * @subpackage Form_Register_Plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Form_Register_Plugin
 * @subpackage Form_Register_Plugin/includes
 * @author     Gerson  <gersonsanchez99@outlook.com>
 */

require_once( plugin_dir_path( __FILE__ ) .'/../twilio-lib/src/Twilio/autoload.php');
use Twilio\Rest\Client;

class Form_Register_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Form_Register_Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'FORM_REGISTER_PLUGIN_VERSION' ) ) {
			$this->version = FORM_REGISTER_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'form-register-plugin';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}
	

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Form_Register_Plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Form_Register_Plugin_i18n. Defines internationalization functionality.
	 * - Form_Register_Plugin_Admin. Defines all hooks for the admin area.
	 * - Form_Register_Plugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-form-register-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-form-register-plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-form-register-plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-form-register-plugin-public.php';

		$this->loader = new Form_Register_Plugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Form_Register_Plugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Form_Register_Plugin_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Form_Register_Plugin_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// Add setting menu item 
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_form_register_admin_setting' );

		// Saves and update settings
		$this->loader->add_action( 'admin_init', $plugin_admin, 'form_Register_Plugin_admin_settings_save' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Form_Register_Plugin_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Form_Register_Plugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	//add data to the DataBase 
	public function deliver_mail() {
		// if the submit button is clicked, send the email
		if ( isset( $_POST['cf-submitted'] ) ) {
			global $wpdb;
			$tablename = $wpdb->prefix.'form_message_content';

		$wpdb->insert( $tablename, array(
			'time' => date('Y-m-d H:i:s'),
			'phone' => $_POST["cf-numbers"], 
			'message' => $_POST["cf-message"] ),
			array( '%s', '%s') 
		);
		     
		}
	}

	/**
	 * Show data from Previous Messages.
	 *
	 * @since    1.0.0
	 */
	public function cf_data(){
		global $wpdb;
		$table_name = $wpdb->prefix . "form_message_content";
		$user = $wpdb->get_results( "SELECT * FROM $table_name" );

		?> 
		<table id="tableData" class="form-registration-plugin">
			<tr>
				<th onclick="sortTable(0)">Data Time </th>
				<th onclick="sortTable(1)">Phone</th>
				<th onclick="sortTable(2)" >Messages</th>
			</tr>
				<?php 
					foreach ($user as $row){ 
						?>
						<tr>
						<td>
						<?php
						echo $row->time;
						?>
						</td>
						<?php
							?>
							<td>
							<?php
							echo $row->phone;
							?>
							</td>
							<td>
							<?php
							echo $row->message;
							?>
							</td>
							</tr>
							<?php
					} 
				?>
		</table>
		<?php
	
	}

	/**
	 * Create DB for Message Content 
	 *
	 * @since    1.0.0
	 */
	public function cf_create_db(){

		global $wpdb;

		$table_name = $wpdb->prefix . "form_message_content";
		$charset_collate = $wpdb->get_charset_collate();
	
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		phone text NOT NULL,
		message text NOT NULL,
		url varchar(55) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
		) $charset_collate;";
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	
	}
	/**
	 * Crate DB for Content from Twilio.
	 *
	 * @since    1.0.0
	 */
	public function cf_create_db_messages(){
	
		global $wpdb;

		$table_name = $wpdb->prefix . "form_message_sent_content";
		$charset_collate = $wpdb->get_charset_collate();
	
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		id_messages mediumint(9) NOT NULL ,
		sent_time datetime DEFAULT '0000-00-00 00:00:00',
		confirmation text NOT NULL,
		PRIMARY KEY  (id),
		FOREIGN KEY (id_messages) REFERENCES wp_form_message_content(id)
		) $charset_collate;";
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	
	}

	/**
	 * Send Message Function.
	 *
	 * @since    1.0.0
	 */

	public function send_message(){
		global $wpdb;

		if (!isset($_POST["cf-submitted"])) {
			return;
		}

		$to        = (isset($_POST["cf-numbers"])) ? $_POST["cf-numbers"] : "";
		$message   = (isset($_POST["cf-message"])) ? $_POST["cf-message"] : "";

		$table_name = $wpdb->prefix . "form_message_content";

		$last_data = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC LIMIT 1" );


		//gets our api details from the database.
			$api_details = get_option("form-register-plugin");
			if (is_array($api_details) and count($api_details) != 0) {
				$sid = $api_details["api_sid"];
				$TWILIO_TOKEN = $api_details["api_auth_token"];
				$TWILIO_NUMBER = $api_details["api_number"];
			}

			$client = new Client($sid, $TWILIO_TOKEN);
			$response = $client->messages->create(
				$to,
				["body" => $message, "from" => $TWILIO_NUMBER]
			);
			$confirmation = $response->status;
			$datefor = $response->dateUpdated;
			$fortmatedDate = $datefor->format('Y-m-d H:i:s');

		 	global $wpdb;

		 	$tablename = $wpdb->prefix.'form_message_sent_content';

			$wpdb->insert( $tablename, array(
				'id_messages' => $last_data[0]->id,
				'sent_time' => $fortmatedDate,
				'confirmation' => $confirmation),
			array(  '%s', '%s') 
	 			);
				 reset($_POST);
		}
    /**
     * Designs for displaying Notices
     *
     * @since    1.0.0
     * @access   private
     * @var $message - String - The message we are displaying
     * @var $status   - Boolean - its either true or false
     */
    public static function adminNotice($message, $status = true) {
        $class =  ($status) ? "notice notice-success" : "notice notice-error";
        $message = __( $message, "sample-text-domain" );

        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
    }

    /**
     * Displays Error Notices
     *
     * @since    1.0.0
     * @access   private
     */
    public static function DisplayError($message = "Aww!, there was an error.") {
        add_action( 'adminNotices', function() use($message) {
            self::adminNotice($message, false);
        });
    }

    /**
     * Displays Success Notices
     *
     * @since    1.0.0
     * @access   private
     */
    public static function DisplaySuccess($message = "Successful!") {
        add_action( 'adminNotices', function() use($message) {
            self::adminNotice($message, true);
        });
    }


}
