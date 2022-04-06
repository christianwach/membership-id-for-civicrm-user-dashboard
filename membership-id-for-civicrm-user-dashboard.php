<?php
/**
 * -----------------------------------------------------------------------------
 * Plugin Name: Membership ID for CiviCRM User Dashboard
 * Plugin URI: https://develop.tadpole.cc/plugins/membership-id-for-civicrm-user-dashboard
 * Description: Adds the Membership ID to the Membership section of the CiviCRM User Dashboard.
 * Author: Christian Wach
 * Version: 0.1
 * Author URI: https://haystack.co.uk
 * Text Domain: membership-id-for-civicrm-user-dashboard
 * Domain Path: /languages
 * -----------------------------------------------------------------------------
 *
 * @package Membership_ID_For_CiviCRM_User_Dashboard
 */

// Set plugin version here.
define( 'MIDCUD_VERSION', '0.1' );

// Store reference to this file.
if ( ! defined( 'MIDCUD_FILE' ) ) {
	define( 'MIDCUD_FILE', __FILE__ );
}

// Store URL to this plugin's directory.
if ( ! defined( 'MIDCUD_URL' ) ) {
	define( 'MIDCUD_URL', plugin_dir_url( MIDCUD_FILE ) );
}

// Store PATH to this plugin's directory.
if ( ! defined( 'MIDCUD_PATH' ) ) {
	define( 'MIDCUD_PATH', plugin_dir_path( MIDCUD_FILE ) );
}

/**
 * Membership ID for CiviCRM User Dashboard Class.
 *
 * A class that encapsulates this plugin's functionality.
 *
 * @since 0.1
 */
class Membership_ID_For_CiviCRM_User_Dashboard {

	/**
	 * Initialises this object.
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// Clear cache on activation & deactivation.
		add_action( 'midcud/activated', [ $this, 'clear_civicrm_cache' ] );
		add_action( 'midcud/deactivated', [ $this, 'clear_civicrm_cache' ] );

		// Initialise when CiviCRM loads.
		add_action( 'civicrm_instance_loaded', [ $this, 'initialise' ] );

	}

	/**
	 * Initialise this plugin.
	 *
	 * @since 0.1
	 */
	public function initialise() {

		// Only do this once.
		static $done;
		if ( isset( $done ) && $done === true ) {
			return;
		}

		// Bootstrap plugin.
		$this->register_hooks();

		/**
		 * Broadcast that this plugin is loaded.
		 *
		 * @since 0.1
		 */
		do_action( 'midcud/loaded' );

		// We're done.
		$done = true;

	}

	/**
	 * Register hooks.
	 *
	 * @since 0.1
	 */
	public function register_hooks() {

		// Register template directory.
		add_action( 'civicrm_config', [ $this, 'register_template_directory' ] );

	}

	/**
	 * Register directory that CiviCRM searches in for our template file.
	 *
	 * @since 0.1
	 *
	 * @param object $config The CiviCRM config object.
	 */
	public function register_template_directory( &$config ) {

		// Get template instance.
		$template = CRM_Core_Smarty::singleton();

		// Define our custom path.
		$custom_path = MIDCUD_PATH . 'assets/templates/civicrm';

		// Add our custom template directory.
		$template->addTemplateDir( $custom_path );

		// Register template directory.
		$template_include_path = $custom_path . PATH_SEPARATOR . get_include_path();
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_set_include_path
		set_include_path( $template_include_path );

	}

	/**
	 * Test if CiviCRM is initialised.
	 *
	 * @since 0.1
	 *
	 * @return bool True if CiviCRM initialised, false otherwise.
	 */
	public function is_civicrm_initialised() {

		// Init only when CiviCRM is fully installed.
		if ( ! defined( 'CIVICRM_INSTALLED' ) ) {
			return false;
		}
		if ( ! CIVICRM_INSTALLED ) {
			return false;
		}

		// Bail if no CiviCRM init function.
		if ( ! function_exists( 'civi_wp' ) ) {
			return false;
		}

		// Try and init CiviCRM.
		return civi_wp()->initialize();

	}

	/**
	 * Clear CiviCRM cache.
	 *
	 * @since 0.1
	 */
	public function clear_civicrm_cache() {

		// Bail if no CiviCRM.
		if ( ! $this->is_civicrm_initialised() ) {
			return;
		}

		// Access config object.
		$config = CRM_Core_Config::singleton();

		// Clear database cache.
		$config->clearDBCache();

		// Cleanup the "templates_c" directory.
		$config->cleanup( 1, true );

		// Cleanup the session object.
		$session = CRM_Core_Session::singleton();
		$session->reset( 1 );

		// Call system flush.
		CRM_Utils_System::flushCache();

	}

}

/**
 * Load plugin if not yet loaded and return reference.
 *
 * @since 0.1
 *
 * @return Submission_Logs_For_ACFE $plugin The plugin reference.
 */
function midcud() {

	static $plugin;

	// Instantiate plugin if not yet instantiated.
	if ( ! isset( $plugin ) ) {
		$plugin = new Membership_ID_For_CiviCRM_User_Dashboard();
	}

	// --<
	return $plugin;

}

// Load immediately.
midcud();

/**
 * Performs plugin activation tasks.
 *
 * @since 0.1
 */
function midcud_activate() {

	/**
	 * Broadcast that this plugin has been activated.
	 *
	 * @since 0.1
	 */
	do_action( 'midcud/activated' );

}

// Activation.
register_activation_hook( __FILE__, 'midcud_activate' );

/**
 * Performs plugin deactivation tasks.
 *
 * @since 0.1
 */
function midcud_deactivated() {

	/**
	 * Broadcast that this plugin has been deactivated.
	 *
	 * @since 0.1
	 */
	do_action( 'midcud/deactivated' );

}

// Deactivation.
register_deactivation_hook( __FILE__, 'midcud_deactivated' );

/**
 * Performs plugin deletion tasks.
 *
 * @since 0.1
 */
function midcud_deleted() {

	/**
	 * Broadcast that this plugin has been deleted.
	 *
	 * @since 0.1
	 */
	do_action( 'midcud/deleted' );

}

// Deletion.
register_uninstall_hook( __FILE__, 'midcud_deleted' );

