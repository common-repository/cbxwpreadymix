<?php

	/**
	 * The plugin bootstrap file
	 *
	 * This file is read by WordPress to generate the plugin information in the plugin
	 * admin area. This file also includes all of the dependencies used by the plugin,
	 * registers the activation and deactivation functions, and defines a function
	 * that starts the plugin.
	 *
	 * @link              http:/codeboxr.com
	 * @since             1.0.0
	 * @package           Cbxwpreadymix
	 *
	 * @wordpress-plugin
	 * Plugin Name:       CBX Ready Mix
	 * Plugin URI:        https://codeboxr.com/product/cbx-wordpress-ready-mix/
	 * Description:       Team, Testimonial, Portfolio, Brand, Bootstrap Shortcode and many more
	 * Version:           1.0.5
	 * Author:            Codeboxr
	 * Author URI:        http:/codeboxr.com
	 * License:           GPL-2.0+
	 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
	 * Text Domain:       cbxwpreadymix
	 * Domain Path:       /languages
	 */

// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}


	defined( 'CBXWPREADYMIX_PLUGIN_NAME' ) or define( 'CBXWPREADYMIX_PLUGIN_NAME', 'cbxwpreadymix' );
	defined( 'CBXWPREADYMIX_PLUGIN_VERSION' ) or define( 'CBXWPREADYMIX_PLUGIN_VERSION', '1.0.5' );
	defined( 'CBXWPREADYMIX_BASE_NAME' ) or define( 'CBXWPREADYMIX_BASE_NAME', plugin_basename( __FILE__ ) );
	defined( 'CBXWPREADYMIX_ROOT_PATH' ) or define( 'CBXWPREADYMIX_ROOT_PATH', plugin_dir_path( __FILE__ ) );
	defined( 'CBXWPREADYMIX_ROOT_URL' ) or define( 'CBXWPREADYMIX_ROOT_URL', plugin_dir_url( __FILE__ ) );


	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-cbxwpreadymix-activator.php
	 */
	function activate_cbxwpreadymix() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxwpreadymix-activator.php';
		Cbxwpreadymix_Activator::activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-cbxwpreadymix-deactivator.php
	 */
	function deactivate_cbxwpreadymix() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxwpreadymix-deactivator.php';
		Cbxwpreadymix_Deactivator::deactivate();
	}

	register_activation_hook( __FILE__, 'activate_cbxwpreadymix' );
	register_deactivation_hook( __FILE__, 'deactivate_cbxwpreadymix' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-cbxwpreadymix.php';

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_cbxwpreadymix() {

		$plugin = new Cbxwpreadymix();
		$plugin->run();

	}

	run_cbxwpreadymix();
