<?php

	/**
	 * The public-facing functionality of the plugin.
	 *
	 * @link       http:/codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    Cbxwpreadymix
	 * @subpackage Cbxwpreadymix/public
	 */

	/**
	 * The public-facing functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    Cbxwpreadymix
	 * @subpackage CBXWPReadymix/public
	 * @author     Codeboxr <info@codeboxr.com>
	 */
	class CBXWPReadymix_Public {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 *
		 * @param      string $plugin_name The name of the plugin.
		 * @param      string $version     The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;


		}


		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {


			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_register_style( 'owl-carousel', plugin_dir_url( __FILE__ ) . 'vendor/owl-carousel/assets/owl.carousel' . $suffix . '.css', array(), $this->version, 'all' );
			wp_register_style( 'owl-theme-default', plugin_dir_url( __FILE__ ) . 'vendor/owl-carousel/assets/owl.theme.default' . $suffix . '.css', array(), $this->version, 'all' );

			wp_register_style(  'cbxwpreadymix', plugin_dir_url( __FILE__ ) . 'css/cbxwpreadymix' . $suffix . '.css', array('owl-carousel', 'owl-theme-default'), $this->version, 'all' );


			wp_enqueue_style( 'owl-carousel');
			wp_enqueue_style( 'owl-theme-default');
			wp_enqueue_style( 'cbxwpreadymix');


		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			//3rd party scripts
			wp_register_script( 'owl-carousel-min', plugin_dir_url( __FILE__ ) . 'vendor/owl-carousel/owl.carousel' . $suffix . '.js', array( 'jquery' ), $this->version, false );
			wp_register_script(  'isotope-pkgd-min', plugin_dir_url( __FILE__ ) . 'vendor/isotope/isotope.pkgd.min.js', array( 'jquery' ), $this->version, false );

			//plugin's core script
			wp_register_script( 'cbxwpreadymix', plugin_dir_url( __FILE__ ) . 'js/cbxwpreadymix' . $suffix . '.js', array( 'jquery', 'owl-carousel-min', 'isotope-pkgd-min' ), $this->version, false );

			//now enqueue all js file
			//enqueue all 3rd party js file
			wp_enqueue_script( 'owl-carousel-min');
			wp_enqueue_script( 'isotope-pkgd-min');

			//enqueue core js file
			wp_enqueue_script( 'cbxwpreadymix');

		}

	}
