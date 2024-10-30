<?php

	/**
	 * The file that defines the core plugin class
	 *
	 * A class definition that includes attributes and functions used across both the
	 * public-facing side of the site and the admin area.
	 *
	 * @link       http:/codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    Cbxwpreadymix
	 * @subpackage Cbxwpreadymix/includes
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
	 * @package    Cbxwpreadymix
	 * @subpackage Cbxwpreadymix/includes
	 * @author     Codeboxr <info@codeboxr.com>
	 */
	class CBXWPReadymix {

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      Cbxwpreadymix_Loader $loader Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $plugin_name The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $version The current version of the plugin.
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

			$this->plugin_name = CBXWPREADYMIX_PLUGIN_NAME;
			$this->version     = CBXWPREADYMIX_PLUGIN_VERSION;

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
		 * - Cbxwpreadymix_Loader. Orchestrates the hooks of the plugin.
		 * - Cbxwpreadymix_i18n. Defines internationalization functionality.
		 * - Cbxwpreadymix_Admin. Defines all hooks for the admin area.
		 * - Cbxwpreadymix_Public. Defines all hooks for the public side of the site.
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
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxwpreadymix-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxwpreadymix-i18n.php';

			/**
			 * The class responsible for defining settings functionality of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-settingsapi.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cbxwpreadymix-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cbxwpreadymix-public.php';

			$this->loader = new CBXWPReadymix_Loader();

			//load shortcode class
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-shortcode.php';

			//Initialize Shortcode
			new CBXWPReadymixShortcode();

			//load content mapper view class
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-content-mapper-view.php';

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widget/cbxwpreadymixteam_widget.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widget/cbxwpreadymixtestimonial_widget.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widget/cbxwpreadymixbrand_widget.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widget/cbxwpreadymixportfolio_widget.php';

		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the Cbxwpreadymix_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale() {

			$plugin_i18n = new CBXWPReadymix_i18n();

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

			$plugin_admin = new CBXWPReadymix_Admin( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

			//Add All Post Type for core Plugin


			//adding the setting action
			$this->loader->add_action( 'admin_init', $plugin_admin, 'setting_init' );

			$this->loader->add_action( 'init', $plugin_admin, 'create_custom_post_type' );

			//create opverview menu page
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'setting_menu' );

			// Admin Init
			//$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init', 1 );

			//Team  Custom Column
			$this->loader->add_filter( 'manage_cbxteam_posts_columns', $plugin_admin, 'cbxteam_columns', 10, 1 );
			$this->loader->add_action( 'manage_cbxteam_posts_custom_column', $plugin_admin, 'cbxteam_column', 10, 2 );

			// Portfolio Custom Column
			$this->loader->add_filter( 'manage_cbxportfolio_posts_columns', $plugin_admin, 'cbxportfolio_columns', 10, 1 );
			$this->loader->add_action( 'manage_cbxportfolio_posts_custom_column', $plugin_admin, 'cbxportfolio_column', 10, 2 );

			//Testimonial Custom Column
			$this->loader->add_filter( 'manage_cbxtestimonial_posts_columns', $plugin_admin, 'cbxtestimonial_columns', 10, 1 );
			$this->loader->add_action( 'manage_cbxtestimonial_posts_custom_column', $plugin_admin, 'cbxtestimonial_column', 10, 2 );


			//add metabox for custom post type
			$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_boxes_cbxrmmetabox' );

			//team save post
			$this->loader->add_action( 'save_post', $plugin_admin, 'save_post_cbxrmmetabox_team', 10, 2 );

			//portfolio save post
			$this->loader->add_action( 'save_post', $plugin_admin, 'save_post_cbxrmmetabox_portfolio', 10, 2 );

			//testimonial save post
			$this->loader->add_action( 'save_post', $plugin_admin, 'save_post_cbxrmmetabox_testimonial', 10, 2 );

			//brand save post
			$this->loader->add_action( 'save_post', $plugin_admin, 'save_post_cbxrmmetabox_brand', 10, 2 );


		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks() {

			$plugin_public = new CBXWPReadymix_Public( $this->get_plugin_name(), $this->get_version() );

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
		 * @return    CBXWPReadymix_Loader    Orchestrates the hooks of the plugin.
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

	}
