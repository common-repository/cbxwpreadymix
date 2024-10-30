<?php

	/**
	 * Define the internationalization functionality
	 *
	 * Loads and defines the internationalization files for this plugin
	 * so that it is ready for translation.
	 *
	 * @link       http:/codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    CBXWPReadymix
	 * @subpackage CBXWPReadymix/includes
	 */

	/**
	 * Define the internationalization functionality.
	 *
	 * Loads and defines the internationalization files for this plugin
	 * so that it is ready for translation.
	 *
	 * @since      1.0.0
	 * @package    CBXWPReadymix
	 * @subpackage CBXWPReadymix/includes
	 * @author     Codeboxr <info@codeboxr.com>
	 */
	class CBXWPReadymix_i18n {


		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    1.0.0
		 */
		public function load_plugin_textdomain() {

			load_plugin_textdomain( 'cbxwpreadymix', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );

		}


	}
