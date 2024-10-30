<?php

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * @link       http:/codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    Cbxwpreadymix
	 * @subpackage Cbxwpreadymix/admin
	 */

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    Cbxwpreadymix
	 * @subpackage Cbxwpreadymix/admin
	 * @author     Codeboxr <info@codeboxr.com>
	 */
	class CBXWPReadymix_Admin {

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
		 * @param      string $plugin_name The name of this plugin.
		 * @param      string $version     The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

			$this->plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $plugin_name . '.php' );
			$this->settings_api    = new CBXWPReadyMixSettings( $this->plugin_name, $this->version );

		}

		/**
		 * settings
		 */
		public function setting_init() {
			//set the settings
			$this->settings_api->set_sections( $this->get_settings_sections() );
			$this->settings_api->set_fields( $this->get_settings_fields() );
			//initialize settings
			$this->settings_api->admin_init();
		}

		/**
		 * Global Setting Sections
		 *
		 *
		 * @return type
		 */
		public function get_settings_sections() {
			return apply_filters( 'cbxwpreadymix_setting_sections', array(
					array(
						'id'    => 'cbxwpreadymix_general',
						'title' => esc_html__( 'General Setting', 'cbxwpreadymix' )
					)
				) );
		}

		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		public function get_settings_fields() {

			$settings_builtin_fields = array(
				'cbxwpreadymix_general' => array(
					'enable_team'        => array(
						'name'     => 'enable_team',
						'label'    => esc_html__( 'Enable Team', 'cbxform' ),
						'desc'     => esc_html__( 'Team module adds a new menu and helps to creates teams', 'cbxwpreadymix' ),
						'type'     => 'checkbox',
						'default'  => 'on',
						'desc_tip' => true,
					),
					'enable_portfolio'   => array(
						'name'     => 'enable_portfolio',
						'label'    => esc_html__( 'Enable Portfolio', 'cbxform' ),
						'desc'     => esc_html__( 'Portfolio module ads a new menu and helps to portfolio items.', 'cbxwpreadymix' ),
						'type'     => 'checkbox',
						'default'  => 'on',
						'desc_tip' => true,
					),
					'enable_testimonial' => array(
						'name'     => 'enable_testimonial',
						'label'    => esc_html__( 'Enable Testimonial', 'cbxform' ),
						'desc'     => esc_html__( 'Testimonial module adds a new menu and helps to testimonial', 'cbxwpreadymix' ),
						'type'     => 'checkbox',
						'default'  => 'on',
						'desc_tip' => true,
					),
					'enable_brand'       => array(
						'name'     => 'enable_brand',
						'label'    => esc_html__( 'Enable Brand', 'cbxform' ),
						'desc'     => esc_html__( 'Brand module adds a new menu and helps to creates brands or clients', 'cbxwpreadymix' ),
						'type'     => 'checkbox',
						'default'  => 'on',
						'desc_tip' => true,
					),
					'enable_bsshortcode'       => array(
						'name'     => 'enable_bsshortcode',
						'label'    => esc_html__( 'Enable Bootstrap(3.x) Shortcode', 'cbxform' ),
						'desc'     => esc_html__( 'Enable twitter bootstrap framework shortcode, it\'s compatible with bs3.x series and may work on 4.x series too.', 'cbxwpreadymix' ),
						'type'     => 'checkbox',
						'default'  => 'on',
						'desc_tip' => true,
					),
				),


			);


			$settings_fields = array(); //final setting array that will be passed to different filters

			$sections = $this->get_settings_sections();

			foreach ( $sections as $section ) {
				if ( ! isset( $settings_builtin_fields[ $section['id'] ] ) ) {
					$settings_builtin_fields[ $section['id'] ] = array();
				}
			}

			foreach ( $sections as $section ) {
				$settings_fields[ $section['id'] ] = apply_filters( 'cbxwpreadymix_global_' . $section['id'] . '_fields', $settings_builtin_fields[ $section['id'] ] );
			}

			$settings_fields = apply_filters( 'cbxwpreadymix_global_fields', $settings_fields ); //final filter if need

			return $settings_fields;

		}

		public function setting_menu() {
			$this->settingpage_hook = add_options_page( esc_html__( 'CBX WP Readymix Setting', 'cbxwpreadymix' ), esc_html__( 'CBX WP Readymix', 'cbxwpreadymix' ), 'manage_options', 'cbxwpreadymix', array(
				$this,
				'display_plugin_admin_settings'
			) );
		}

		/**
		 * Show cbxeventz Setting page
		 */
		public function display_plugin_admin_settings() {
			global $wpdb;
			$plugin_data = get_plugin_data( plugin_dir_path( __DIR__ ) . '/../' . $this->plugin_basename );
			include( 'partials/admin-settings-display.php' );
		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles( $hook ) {

			global $post, $post_type;;

			$allowed_post_types = apply_filters( 'cbxwpreadymix_post_types', array(
					'cbxteam',
					'cbxportfolio',
					'cbxtestimonial',
					'cbxbrand'
				) );


			wp_register_style( 'cbxwpreadymix-admin-team', plugin_dir_url( __FILE__ ) . 'css/cbxwpreadymix-admin-team.css', array(), $this->version, 'all' );
			if ( isset( $post->post_type ) && 'cbxteam' == $post->post_type ) {
				wp_enqueue_style( 'cbxwpreadymix-admin-team' );
			}

			wp_register_style( 'cbxwpreadymix-admin', plugin_dir_url( __FILE__ ) . 'css/cbxwpreadymix-admin.css', array(), $this->version, 'all' );
			if ( isset( $post_type ) && in_array( $post_type, $allowed_post_types ) ) {
				wp_enqueue_style( 'cbxwpreadymix-admin' );
			}
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts( $hook ) {


			global $post, $post_type;;

			global $post, $post_type;;

			$allowed_post_types = apply_filters( 'cbxwpreadymix_post_types', array(
					'cbxteam',
					'cbxportfolio',
					'cbxtestimonial',
					'cbxbrand'
				) );

			wp_register_script( 'cbxwpreadymix-admin', plugin_dir_url( __FILE__ ) . 'js/cbxwpreadymix-admin.js', array( 'jquery' ), $this->version, false );

			if ( isset( $post_type ) && in_array( $post_type, $allowed_post_types ) ) {
				wp_enqueue_script( 'cbxwpreadymix-admin' );
			}


			wp_register_script( 'cbxwpreadymix-admin-team', plugin_dir_url( __FILE__ ) . 'js/cbxwpreadymix-admin-team.js', array( 'jquery' ), $this->version, false );

			$item_html = '<li>
					    <select id="cbxteamsocial-type-select-##index##" value="custom" class="cbxteamsocial-type-select" name="cbxrmmetaboxteam[social][##index##][type]">
					        <option value="facebook" 	>' . __( 'Facebook', 'cbxwpreadymix' ) . '</option>
					        <option value="twitter"     >' . __( 'Twitter', 'cbxwpreadymix' ) . '</option>
					        <option value="plus"        >' . __( 'Google plus', 'cbxwpreadymix' ) . '</option>
					        <option value="delicious"   >' . __( 'Delicious', 'cbxwpreadymix' ) . '</option>
							<option value="dribbble"    >' . __( 'Dribbble', 'cbxwpreadymix' ) . '</option>
							<option value="deviantart"  >' . __( 'Deviantart', 'cbxwpreadymix' ) . '</option>
							<option value="digg"        >' . __( 'Digg', 'cbxwpreadymix' ) . '</option>
							<option value="flickr"      >' . __( 'Flickr', 'cbxwpreadymix' ) . '</option>
							<option value="weibo"       >' . __( 'Weibo', 'cbxwpreadymix' ) . '</option>
							<option value="youtube"     >' . __( 'Youtube', 'cbxwpreadymix' ) . '</option>
							<option value="google"      >' . __( 'Google', 'cbxwpreadymix' ) . '</option>
							<option value="pinterest"   >' . __( 'Pinterest', 'cbxwpreadymix' ) . '</option>
							<option value="reddit"      >' . __( 'Reddit', 'cbxwpreadymix' ) . '</option>
							<option value="yahoo"       >' . __( 'Yahoo', 'cbxwpreadymix' ) . '</option>
							<option value="vimeo"       >' . __( 'Vimeo', 'cbxwpreadymix' ) . '</option>
							<option value="stumbleupon" >' . __( 'Stumble Upon', 'cbxwpreadymix' ) . '</option>
							<option value="linkedin"    >' . __( 'Linkedin', 'cbxwpreadymix' ) . '</option>
							<option value="skype"       >' . __( 'Skype', 'cbxwpreadymix' ) . '</option>
					        <option value="tumblr"      >' . __( 'Tumblr', 'cbxwpreadymix' ) . '</option>
							<option value="instagram"   >' . __( 'Instagram', 'cbxwpreadymix' ) . '</option>
	                        <option value="cbxcustom"   >' . __( 'Custom', 'cbxwpreadymix' ) . '</option>
					    </select>
					    <input class="cbxrm-team-social-icon"     type="text" name="cbxrmmetaboxteam[social][##index##][icon]"  value="##icon##" size="30">
					    <input class="cbxrm-team-social-url"      type="url" name="cbxrmmetaboxteam[social][##index##][url]"    value="##url##" size="30">
					    <input class="cbxrm-team-social-title"    type="text" name="cbxrmmetaboxteam[social][##index##][title]"  value="##title##" size="30">

					    <span class="sort hndle cbxteam-sort ui-sortable-handle"><a class="button" href="javascript:void(0)"><div class="dashicons dashicons-editor-justify"></div></a></span>
					    <a class="cbxteam-repeatable-remove remove button" href="#"><div class="dashicons dashicons-dismiss"></div></a>
					</li>
				';

			$translation_array = array(
				'socialitemtemplate' => json_encode( $item_html ),

				'socialnetworks' => array(
					'facebook' => array(
						'icon'  => 'fa-facebook',
						'url'   => 'http://www.facebook.com/',
						'title' => __( 'Find on Facebook', 'cbxwpreadymix' ),
					),

					'twitter' => array(
						'icon'  => 'fa-twitter',
						'url'   => 'http://www.twitter.com/',
						'title' => __( 'Find on Twitter', 'cbxwpreadymix' ),
					),

					'plus' => array(
						'icon'  => 'fa-google-plus',
						'url'   => 'http://www.Google.com/',
						'title' => __( 'Find on Google Plus', 'cbxwpreadymix' ),
					),

					'delicious' => array(
						'icon'  => 'fa-delicious',
						'url'   => 'http://www.delicious.com/',
						'title' => __( 'Find on Delicious', 'cbxwpreadymix' ),
					),

					'dribbble' => array(
						'icon'  => 'fa-dribbble',
						'url'   => 'http://www.dribbble.com/',
						'title' => __( 'Find on Dribbble', 'cbxwpreadymix' ),
					),

					'deviantart' => array(
						'icon'  => 'fa-deviantart',
						'url'   => 'http://www.deviantart.com/',
						'title' => __( 'Find on Deviantart', 'cbxwpreadymix' ),
					),

					'digg' => array(
						'icon'  => 'fa-digg',
						'url'   => 'http://www.digg.com/',
						'title' => __( 'Find on Digg', 'cbxwpreadymix' ),
					),

					'flickr' => array(
						'icon'  => 'fa-digg',
						'url'   => 'http://www.flickr.com/',
						'title' => __( 'Find on Flickr', 'cbxwpreadymix' ),
					),

					'weibo' => array(
						'icon'  => 'fa-weibo',
						'url'   => 'http://www.weibo.com/',
						'title' => __( 'Find on Weibo', 'cbxwpreadymix' ),
					),

					'youtube' => array(
						'icon'  => 'fa-youtube',
						'url'   => 'http://www.youtube.com/',
						'title' => __( 'Find on Youtube', 'cbxwpreadymix' ),
					),

					'google' => array(
						'icon'  => 'fa-google',
						'url'   => 'http://www.google.com/',
						'title' => __( 'Find on Google', 'cbxwpreadymix' ),
					),

					'pinterest' => array(
						'icon'  => 'fa-pinterest',
						'url'   => 'http://www.pinterest.com/',
						'title' => __( 'Find on Pinterest', 'cbxwpreadymix' ),
					),

					'reddit' => array(
						'icon'  => 'fa-reddit',
						'url'   => 'http://www.reddit.com/',
						'title' => __( 'Find on Reddit', 'cbxwpreadymix' ),
					),

					'yahoo' => array(
						'icon'  => 'fa-yahoo',
						'url'   => 'http://www.yahoo.com/',
						'title' => __( 'Find on Yahoo', 'cbxwpreadymix' ),
					),

					'vimeo' => array(
						'icon'  => 'fa-vimeo-square',
						'url'   => 'http://www.vimeo.com/',
						'title' => __( 'Find on Vimeo', 'cbxwpreadymix' ),
					),

					'stumbleupon' => array(
						'icon'  => 'fa-stumbleupon',
						'url'   => 'http://www.stumbleupon.com/',
						'title' => __( 'Find on Stumble Upon', 'cbxwpreadymix' ),
					),

					'linkedin' => array(
						'icon'  => 'fa-linkedin',
						'url'   => 'http://www.linkedin.com/',
						'title' => __( 'Find on Linkedin', 'cbxwpreadymix' ),
					),

					'skype' => array(
						'icon'  => 'fa-skype',
						'url'   => 'http://www.skype.com/',
						'title' => __( 'Find on Skype', 'cbxwpreadymix' ),
					),

					'tumblr' => array(
						'icon'  => 'fa-tumblr',
						'url'   => 'http://www.tumblr.com/',
						'title' => __( 'Find on Tumblr', 'cbxwpreadymix' ),
					),

					'instagram' => array(
						'icon'  => 'fa-instagram',
						'url'   => 'http://www.instagram.com/',
						'title' => __( 'Find on Instagram', 'cbxwpreadymix' ),
					),

					'cbxcustom' => array(
						'icon'  => '',
						'url'   => '',
						'title' => '',
					),
				)

			);
			wp_localize_script( 'cbxwpreadymix-admin-team', 'cbxteamadmin', $translation_array );


			if ( isset( $post->post_type ) && 'cbxteam' == $post->post_type ) {
				wp_enqueue_script( 'cbxwpreadymix-admin-team' );
			}

		}


		/**
		 * Team
		 *
		 * @param $column_name
		 * @param $post_id
		 */

		public function cbxteam_column( $column_name, $post_id ) {

			$fieldValues = get_post_meta( $post_id, '_cbxrmteammeta', true );

			$designation = isset( $fieldValues['designation'] ) ? $fieldValues['designation'] : '';

			if ( $column_name == 'designation' ) {
				echo $designation;
			}

			if ( $column_name == 'thumb' ) {
				if ( false != ( $image_url = $this->get_featured_image( $post_id, 'teamthumbnail' ) ) ) {

					echo '<a href="' . $image_url . '" class="editinline"><img style="width: 40px;height: 40px;" src="' . $image_url . '" alt="thumb" /></a>';
				} else {
					//echo '<a href="#" class="editinline"><img style="width: 40px; height: 40px;" src="" alt="thumb" /></a>';
				}
			}
		}


		/**
		 * Team
		 *
		 * @param $columns
		 *
		 * @return mixed
		 */

		public function cbxteam_columns( $columns ) {

			unset( $columns['date'] );
			$columns['designation'] = __( 'Designation', 'cbxwpreadymix' );
			$columns['thumb']       = __( 'Thumb', 'cbxwpreadymix' );

			return $columns;
		}


		/**
		 *  Portfolio
		 *
		 * @param $column_name
		 * @param $post_id
		 */

		public function cbxportfolio_column( $column_name, $post_id ) {

			if ( $column_name == 'category' ) {
				$categories = get_the_terms( $post_id, 'cbxportfoliocat' );//
				foreach ( $categories as $category ) {
					echo $category->name . ', ';
				}
			}

			$fieldValues = get_post_meta( $post_id, '_cbxrmportfoliometa', true );

			$project = isset( $fieldValues['project_name'] ) ? $fieldValues['project_name'] : '';
			$client  = isset( $fieldValues['client_name'] ) ? $fieldValues['client_name'] : '';

			if ( $column_name == 'project_name' ) {
				echo $project;
			}

			if ( $column_name == 'client_name' ) {
				echo $client;
			}

			if ( $column_name == 'thumb' ) {
				if ( '' != $image_url = $this->get_featured_image( $post_id, 'teamthumbnail' ) ) {

					echo '<a href="' . $image_url . '" class="editinline"><img style="width: 40px;height: 40px;" src="' . $image_url . '" /></a>';
				} else {
					echo '<a href="#" class="editinline"><img style="width: 40px; height: 40px;" src="" /></a>';
				}
			}
		}


		/**
		 * Portfolio
		 *
		 * @param $columns
		 *
		 * @return mixed
		 */

		public function cbxportfolio_columns( $columns ) {

			unset( $columns['date'] );
			$columns['category']     = __( 'Category', 'cbxwpreadymix' );
			$columns['project_name'] = __( 'Project Name', 'cbxwpreadymix' );
			$columns['client_name']  = __( 'Client Name', 'cbxwpreadymix' );
			$columns['thumb']        = __( 'Thumbnail', 'cbxwpreadymix' );

			return $columns;
		}


		/**
		 *  Testimonial
		 *
		 * @param $column_name
		 * @param $post_id
		 */

		public function cbxtestimonial_column( $column_name, $post_id ) {

			$fieldValues = get_post_meta( $post_id, '_cbxrmtestimonialmeta', true );

			$company     = isset( $fieldValues['company'] ) ? $fieldValues['company'] : ''; //show type
			$client      = isset( $fieldValues['client_name'] ) ? $fieldValues['client_name'] : ''; //show type
			$designation = isset( $fieldValues['designation'] ) ? $fieldValues['designation'] : ''; //show type

			if ( $column_name == 'company' ) {
				echo $company;
			}

			if ( $column_name == 'client_name' ) {
				echo $client;
			}

			if ( $column_name == 'designation' ) {
				echo $designation;
			}

			if ( $column_name == 'thumb' ) {
				$image_url = $this->get_featured_image( $post_id, 'teamthumbnail' );

				if ( $image_url != '' ) {

					echo '<a href="' . $image_url . '" class="editinline"><img style="width: 40px;height: 40px;" src="' . $image_url . '" /></a>';
				} else {
					//echo '<a href="#" class="editinline"><img style="width: 40px; height: 40px;" src="" /></a>';
				}
			}
		}


		/**
		 * Testimonial
		 *
		 * @param $columns
		 *
		 * @return mixed
		 */

		public function cbxtestimonial_columns( $columns ) {

			unset( $columns['date'] );
			$columns['company']     = __( 'Company Name', 'cbxwpreadymix' );
			$columns['client_name'] = __( 'Client Name', 'cbxwpreadymix' );
			$columns['designation'] = __( 'Designation', 'cbxwpreadymix' );
			$columns['thumb']       = __( 'Thumb', 'cbxwpreadymix' );

			return $columns;
		}


		/**
		 * Get Post thumbnail for custom column
		 *
		 * @param        $post_id
		 * @param string $size
		 *
		 * @return bool | image_url
		 *
		 */
		public function get_featured_image( $post_id, $size = '' ) {

			if ( $feature_image_id = get_post_thumbnail_id( $post_id ) ) {

				$url = wp_get_attachment_image_src( $feature_image_id, $size );

				return $url[0];
			}

			return false;
		}


		/**
		 * Add metabox for custom post type
		 *
		 * @since    1.0.0
		 */
		public function add_meta_boxes_cbxrmmetabox() {

			//Team meta box to holder custom fields
			add_meta_box( 'cbxrmmetabox_team', __( 'Team Fields', 'cbxwpreadymix' ), array(
				$this,
				'cbxrmmetabox_team_display'
			), 'cbxteam', 'normal', 'high' );

			//portfolio meta box
			add_meta_box( 'cbxrmmetabox_portfolio', __( 'Portfolio Fields', 'cbxwpreadymix' ), array(
				$this,
				'cbxrmmetabox_portfolio_display'
			), 'cbxportfolio', 'normal', 'high' );

			//Testimonial meta box
			add_meta_box( 'cbxrmmetabox_testimonial', __( 'Testimonial Fields', 'cbxwpreadymix' ), array(
				$this,
				'cbxrmmetabox_testimonial_display'
			), 'cbxtestimonial', 'normal', 'high' );

			//Brands meta box
			add_meta_box( 'cbxrmmetabox_brand', __( 'Brand Fields', 'cbxwpreadymix' ), array(
				$this,
				'cbxrmmetabox_brand_display'
			), 'cbxbrand', 'normal', 'high' );
		}


		/**
		 * Register Custom Post Type
		 *
		 * @since    1.0.0
		 */
		public function create_custom_post_type() {

			$team_module        = $this->settings_api->get_option( 'enable_team', 'cbxwpreadymix_general', 'on' );
			$portfolio_module   = $this->settings_api->get_option( 'enable_portfolio', 'cbxwpreadymix_general', 'on' );
			$testimonial_module = $this->settings_api->get_option( 'enable_testimonial', 'cbxwpreadymix_general', 'on' );
			$brand_module       = $this->settings_api->get_option( 'enable_brand', 'cbxwpreadymix_general', 'on' );


			if($team_module == 'on'){
				//custom post type team (cbxteam)

				$labels_team = array(
					'name'               => _x( 'Teams', 'Post Type General Name', 'cbxwpreadymix' ),
					'singular_name'      => _x( 'Team', 'Post Type Singular Name', 'cbxwpreadymix' ),
					'menu_name'          => __( 'CBX Team', 'cbxwpreadymix' ),
					'parent_item_colon'  => __( 'Parent Item:', 'cbxwpreadymix' ),
					'all_items'          => __( 'All Team', 'cbxwpreadymix' ),
					'view_item'          => __( 'View Team', 'cbxwpreadymix' ),
					'add_new_item'       => __( 'Add New Member', 'cbxwpreadymix' ),
					'add_new'            => __( 'Add New', 'cbxwpreadymix' ),
					'edit_item'          => __( 'Edit Team', 'cbxwpreadymix' ),
					'update_item'        => __( 'Update Team', 'cbxwpreadymix' ),
					'search_items'       => __( 'Search Team', 'cbxwpreadymix' ),
					'not_found'          => __( 'Not found', 'cbxwpreadymix' ),
					'not_found_in_trash' => __( 'Not found in Trash', 'cbxwpreadymix' ),
				);

				$args_team = array(
					'label'               => __( 'Team', 'cbxwpreadymix' ),
					'description'         => __( 'Team Display', 'cbxwpreadymix' ),
					'labels'              => $labels_team,
					'supports'            => apply_filters('cbxwpreadymix_cbxteam_field_supports',  array( 'title', 'editor', 'thumbnail' )),
					'hierarchical'        => false,
					'public'              => true,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'show_in_nav_menus'   => true,
					'show_in_admin_bar'   => true,
					'can_export'          => true,
					'has_archive'         => false,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'capability_type'     => 'post',
				);
				register_post_type( 'cbxteam', $args_team );

				/*
				// Register Taxonomy For Team
				//Categories
				$cbxteam_cat_args = array(
					'hierarchical'   => true,
					'label'          => 'Categories',
					'show_ui'        => true,
					'query_var'      => true,
					'rewrite'        => array( 'slug' => 'cbxteamcat' ),
					'singular_label' => 'Categories'
				);
				register_taxonomy( 'cbxteamcat', array( 'cbxteam' ), $cbxteam_cat_args );

				//Tags
				$cbxteam_tag_args = array(
					'hierarchical'   => false,
					'label'          => 'Skill Tags',
					'show_ui'        => true,
					'query_var'      => true,
					'rewrite'        => array( 'slug' => 'cbxteamtags' ),
					'singular_label' => 'Tag'
				);
				register_taxonomy( 'cbxteamtag', array( 'cbxteam' ), $cbxteam_tag_args );
				*/
            }


            if($portfolio_module == 'on'){
	            //custom post type Portfolio (cbxportfolio)

	            $labels_portfolio = array(
		            'name'               => _x( 'Portfolio', 'Post Type General Name', 'cbxwpreadymix' ),
		            'singular_name'      => _x( 'Portfolio', 'Post Type Singular Name', 'cbxwpreadymix' ),
		            'menu_name'          => __( 'CBX Portfolio', 'cbxwpreadymix' ),
		            'parent_item_colon'  => __( 'Parent Item:', 'cbxwpreadymix' ),
		            'all_items'          => __( 'All Portfolio', 'cbxwpreadymix' ),
		            'view_item'          => __( 'View Portfolio', 'cbxwpreadymix' ),
		            'add_new_item'       => __( 'Add New Portfolio', 'cbxwpreadymix' ),
		            'add_new'            => __( 'Add New', 'cbxwpreadymix' ),
		            'edit_item'          => __( 'Edit Portfolio', 'cbxwpreadymix' ),
		            'update_item'        => __( 'Update Portfolio', 'cbxwpreadymix' ),
		            'search_items'       => __( 'Search Portfolio', 'cbxwpreadymix' ),
		            'not_found'          => __( 'Not found', 'cbxwpreadymix' ),
		            'not_found_in_trash' => __( 'Not found in Trash', 'cbxwpreadymix' ),
	            );

	            $args_portfolio = array(
		            'label'               => __( 'Portfolio', 'cbxwpreadymix' ),
		            'description'         => __( 'Portfolio Display', 'cbxwpreadymix' ),
		            'labels'              => $labels_portfolio,
		            'supports'            => apply_filters('cbxwpreadymix_cbxportfolio_field_supports',  array( 'title', 'editor', 'thumbnail' )),
		            'hierarchical'        => false,
		            'public'              => true,
		            'show_ui'             => true,
		            'show_in_menu'        => true,
		            'show_in_nav_menus'   => true,
		            'show_in_admin_bar'   => true,
		            'can_export'          => true,
		            'has_archive'         => false,
		            'publicly_queryable'  => false,
		            'exclude_from_search' => true,
		            'capability_type'     => 'post',
	            );
	            register_post_type( 'cbxportfolio', $args_portfolio );

	            // Register Taxonomy For Portfolio
	            //Categories
	            $cbxportfolio_cat_args = array(
		            'hierarchical'   => true,
		            'label'          => 'Categories',
		            'show_ui'        => true,
		            'query_var'      => true,
		            'rewrite'        => array( 'slug' => 'cbxportfoliocat' ),
		            'singular_label' => 'Categories'
	            );
	            register_taxonomy( 'cbxportfoliocat', array( 'cbxportfolio' ), $cbxportfolio_cat_args );

	            //Tags
	            $cbxportfolio_tag_args = array(
		            'hierarchical'   => false,
		            'label'          => 'Tags',
		            'show_ui'        => true,
		            'query_var'      => true,
		            'rewrite'        => array( 'slug' => 'cbxportfoliotags' ),
		            'singular_label' => 'Tag'
	            );
	            register_taxonomy( 'cbxportfoliotag', array( 'cbxportfolio' ), $cbxportfolio_tag_args );

            }


            if($testimonial_module == 'on'){
	            //custom post type testimonial (cbxtestimonial)

	            $labels_testimonial = array(
		            'name'               => _x( 'Testimonial', 'Post Type General Name', 'cbxwpreadymix' ),
		            'singular_name'      => _x( 'Testimonial', 'Post Type Singular Name', 'cbxwpreadymix' ),
		            'menu_name'          => __( 'CBX Testimonial', 'cbxwpreadymix' ),
		            'parent_item_colon'  => __( 'Parent Item:', 'cbxwpreadymix' ),
		            'all_items'          => __( 'All Testimonial', 'cbxwpreadymix' ),
		            'view_item'          => __( 'View Testimonial', 'cbxwpreadymix' ),
		            'add_new_item'       => __( 'Add New Testimonial', 'cbxwpreadymix' ),
		            'add_new'            => __( 'Add New', 'cbxwpreadymix' ),
		            'edit_item'          => __( 'Edit Testimonial', 'cbxwpreadymix' ),
		            'update_item'        => __( 'Update Testimonial', 'cbxwpreadymix' ),
		            'search_items'       => __( 'Search Testimonial', 'cbxwpreadymix' ),
		            'not_found'          => __( 'Not found', 'cbxwpreadymix' ),
		            'not_found_in_trash' => __( 'Not found in Trash', 'cbxwpreadymix' ),
	            );

	            $args_testimonial = array(
		            'label'               => __( 'Testimonial', 'cbxwpreadymix' ),
		            'description'         => __( 'Testimonial Display', 'cbxwpreadymix' ),
		            'labels'              => $labels_testimonial,
		            'supports'            => apply_filters('cbxwpreadymix_cbxtestimonial_field_supports', array( 'title', 'editor', 'thumbnail' )),
		            'hierarchical'        => false,
		            'public'              => true,
		            'show_ui'             => true,
		            'show_in_menu'        => true,
		            'show_in_nav_menus'   => true,
		            'show_in_admin_bar'   => true,
		            'can_export'          => true,
		            'has_archive'         => false,
		            'publicly_queryable'  => false,
		            'exclude_from_search' => true,
		            'capability_type'     => 'post',
	            );
	            register_post_type( 'cbxtestimonial', $args_testimonial );
            }


            if($brand_module == 'on'){

	            //custom post type brands (cbxbrand)

	            $labels_brand = array(
		            'name'               => _x( 'Brands', 'Post Type General Name', 'cbxwpreadymix' ),
		            'singular_name'      => _x( 'Brand', 'Post Type Singular Name', 'cbxwpreadymix' ),
		            'menu_name'          => __( 'CBX Brand', 'cbxwpreadymix' ),
		            'parent_item_colon'  => __( 'Parent Item:', 'cbxwpreadymix' ),
		            'all_items'          => __( 'All Brand', 'cbxwpreadymix' ),
		            'view_item'          => __( 'View Brand', 'cbxwpreadymix' ),
		            'add_new_item'       => __( 'Add New Brand', 'cbxwpreadymix' ),
		            'add_new'            => __( 'Add New', 'cbxwpreadymix' ),
		            'edit_item'          => __( 'Edit Brand', 'cbxwpreadymix' ),
		            'update_item'        => __( 'Update Brand', 'cbxwpreadymix' ),
		            'search_items'       => __( 'Search Brand', 'cbxwpreadymix' ),
		            'not_found'          => __( 'Not found', 'cbxwpreadymix' ),
		            'not_found_in_trash' => __( 'Not found in Trash', 'cbxwpreadymix' ),
	            );

	            $args_brand = array(
		            'label'               => __( 'Brand', 'cbxwpreadymix' ),
		            'description'         => __( 'Brand Display', 'cbxwpreadymix' ),
		            'labels'              => $labels_brand,
		            'supports'            => apply_filters('cbxwpreadymix_cbxbrand_field_supports', array( 'title', 'editor', 'thumbnail' )),
		            'hierarchical'        => false,
		            'public'              => false,
		            'show_ui'             => true,
		            'show_in_menu'        => true,
		            'show_in_nav_menus'   => true,
		            'show_in_admin_bar'   => true,
		            'can_export'          => true,
		            'has_archive'         => false,
		            'publicly_queryable'  => false,
		            'exclude_from_search' => true,
		            'capability_type'     => 'post',
	            );
	            register_post_type( 'cbxbrand', $args_brand );
            }

		}


		/**
		 * Render Metabox under team post type
		 *
		 * team meta field
		 *
		 * @param $post
		 *
		 * @since 1.0
		 *
		 */

		public function cbxrmmetabox_team_display( $post ) {

			$fieldValues = get_post_meta( $post->ID, '_cbxrmteammeta', true );

			wp_nonce_field( 'cbxrmmetaboxteam', 'cbxrmmetaboxteam[nonce]' );

			echo '<div id="cbxrmmetabox_team_wrapper" class="cbxmetabox-wrapper">';

			$repeated_values = isset( $fieldValues['social'] ) ? $fieldValues['social'] : '';
			$designation     = isset( $fieldValues['designation'] ) ? $fieldValues['designation'] : '';

			?>


            <table class="form-table">
                <tbody>

				<?php do_action( 'cbxrmmeta_team_fields_before_start', $fieldValues ); ?>

                <tr valign="top">
                    <td><?php _e( 'Designation', 'cbxwpreadymix' ) ?></td>
                    <td>
                        <input type="text" name="cbxrmmetaboxteam[designation]" value="<?php echo $designation; ?>"/>
                    </td>
                </tr>

                <tr valign="top">
                    <td><?php _e( 'Social Media Settings', 'cbxwpreadymix' ) ?></td>
                    <td>
						<?php
							echo '<ul id="cbxteam-custom-repeatable" class="cbxteam-custom-repeatable">';

							$i = 0;
							if ( $repeated_values != '' ) {

								foreach ( $repeated_values as $repeated_value ) {

									echo '<li>
									<select id="cbxteamsocial-type-select' . $i . '" class="cbxteamsocial-type-select" name="cbxrmmetaboxteam[social][' . $i . '][type]">
										<option value="facebook"    ' . ( ( $repeated_value['type'] == 'facebook' ) ? 'selected="selected"' : '' ) . '   >' . __( 'Facebook', 'cbxwpreadymix' ) . '</option>
										<option value="twitter"     ' . ( ( $repeated_value['type'] == 'twitter' ) ? 'selected="selected"' : '' ) . '    >' . __( 'Twitter', 'cbxwpreadymix' ) . '</option>
										<option value="plus"        ' . ( ( $repeated_value['type'] == 'plus' ) ? 'selected="selected"' : '' ) . '       >' . __( 'Google plus', 'cbxwpreadymix' ) . '</option>
										<option value="delicious"   ' . ( ( $repeated_value['type'] == 'delicious' ) ? 'selected="selected"' : '' ) . '  >' . __( 'Delicious', 'cbxwpreadymix' ) . '</option>
										<option value="dribbble"    ' . ( ( $repeated_value['type'] == 'dribbble' ) ? 'selected="selected"' : '' ) . '   >' . __( 'Dribbble', 'cbxwpreadymix' ) . '</option>
										<option value="deviantart"  ' . ( ( $repeated_value['type'] == 'deviantart' ) ? 'selected="selected"' : '' ) . ' >' . __( 'Deviantart', 'cbxwpreadymix' ) . '</option>
										<option value="digg"        ' . ( ( $repeated_value['type'] == 'digg' ) ? 'selected="selected"' : '' ) . '       >' . __( 'Digg', 'cbxwpreadymix' ) . '</option>
										<option value="flickr"      ' . ( ( $repeated_value['type'] == 'flickr' ) ? 'selected="selected"' : '' ) . '     >' . __( 'Flickr', 'cbxwpreadymix' ) . '</option>
										<option value="weibo"       ' . ( ( $repeated_value['type'] == 'weibo' ) ? 'selected="selected"' : '' ) . '      >' . __( 'Weibo', 'cbxwpreadymix' ) . '</option>
										<option value="youtube"     ' . ( ( $repeated_value['type'] == 'youtube' ) ? 'selected="selected"' : '' ) . '    >' . __( 'Youtube', 'cbxwpreadymix' ) . '</option>
										<option value="google"      ' . ( ( $repeated_value['type'] == 'google' ) ? 'selected="selected"' : '' ) . '     >' . __( 'Google', 'cbxwpreadymix' ) . '</option>
										<option value="pinterest"   ' . ( ( $repeated_value['type'] == 'pinterest' ) ? 'selected="selected"' : '' ) . '  >' . __( 'Pinterest', 'cbxwpreadymix' ) . '</option>
										<option value="reddit"      ' . ( ( $repeated_value['type'] == 'reddit' ) ? 'selected="selected"' : '' ) . '     >' . __( 'Reddit', 'cbxwpreadymix' ) . '</option>
										<option value="yahoo"       ' . ( ( $repeated_value['type'] == 'yahoo' ) ? 'selected="selected"' : '' ) . '      >' . __( 'Yahoo', 'cbxwpreadymix' ) . '</option>
										<option value="vimeo"       ' . ( ( $repeated_value['type'] == 'vimeo' ) ? 'selected="selected"' : '' ) . '      >' . __( 'Vimeo', 'cbxwpreadymix' ) . '</option>
										<option value="stumbleupon" ' . ( ( $repeated_value['type'] == 'stumbleupon' ) ? 'selected="selected"' : '' ) . '>' . __( 'Stumble Upon', 'cbxwpreadymix' ) . '</option>
										<option value="linkedin"    ' . ( ( $repeated_value['type'] == 'linkedin' ) ? 'selected="selected"' : '' ) . '   >' . __( 'Linkedin', 'cbxwpreadymix' ) . '</option>
										<option value="skype"       ' . ( ( $repeated_value['type'] == 'skype' ) ? 'selected="selected"' : '' ) . '      >' . __( 'Skype', 'cbxwpreadymix' ) . '</option>
										<option value="tumblr"      ' . ( ( $repeated_value['type'] == 'tumblr' ) ? 'selected="selected"' : '' ) . '     >' . __( 'Tumblr', 'cbxwpreadymix' ) . '</option>
										<option value="instagram"   ' . ( ( $repeated_value['type'] == 'instagram' ) ? 'selected="selected"' : '' ) . '  >' . __( 'Instagram', 'cbxwpreadymix' ) . '</option>
										<option value="cbxcustom"   ' . ( ( $repeated_value['type'] == 'cbxcustom' ) ? 'selected="selected"' : '' ) . '  >' . __( 'Custom', 'cbxwpreadymix' ) . '</option>
									</select>

									<input type="text" class="cbxrm-team-social-icon"     name="cbxrmmetaboxteam[social][' . $i . '][icon]"     value="' . $repeated_value['icon'] . '" size="30" />
									<input type="url"  class="cbxrm-team-social-url"      name="cbxrmmetaboxteam[social][' . $i . '][url]"      value="' . $repeated_value['url'] . '" size="30" />
									<input type="text" class="cbxrm-team-social-title"    name="cbxrmmetaboxteam[social][' . $i . '][title]"    value="' . $repeated_value['title'] . '" size="30" />

		                            <span class="sort hndle cbxteam-sort"><a class="button" href="javascript:void(0)"><div class="dashicons dashicons-editor-justify"></div></a></span>
		                            <a class="cbxteam-repeatable-remove button" href="#"><div class="dashicons dashicons-dismiss"></div></a>
	                         </li>
							';
									$i ++;
								}
							}
							echo '</ul>';

							echo '<select  id="#cbxsocial-repeatable-add" class="button button-primary button-large repeatable-add cbxsocial-repeatable-add">
								<option value=""  selected="selected" >' . __( 'Add New Social Network', 'cbxwpreadymix' ) . '</option>
								<option value="facebook"    >' . __( 'Add Facebook', 'cbxwpreadymix' ) . '</option>
								<option value="twitter"     >' . __( 'Add Twitter', 'cbxwpreadymix' ) . '</option>
								<option value="plus"        >' . __( 'Add Google Plus', 'cbxwpreadymix' ) . '</option>
								<option value="delicious"   >' . __( 'Add Delicious', 'cbxwpreadymix' ) . '</option>
								<option value="dribbble"    >' . __( 'Add Dribbble', 'cbxwpreadymix' ) . '</option>
								<option value="deviantart"  >' . __( 'Add Deviantart', 'cbxwpreadymix' ) . '</option>
								<option value="digg"        >' . __( 'Add Digg', 'cbxwpreadymix' ) . '</option>
								<option value="flickr"      >' . __( 'Add Flickr', 'cbxwpreadymix' ) . '</option>
								<option value="weibo"       >' . __( 'Add Weibo', 'cbxwpreadymix' ) . '</option>
								<option value="youtube"     >' . __( 'Add Youtube', 'cbxwpreadymix' ) . '</option>
								<option value="google"      >' . __( 'Add Google', 'cbxwpreadymix' ) . '</option>
								<option value="pinterest"   >' . __( 'Add Pinterest', 'cbxwpreadymix' ) . '</option>
								<option value="reddit"      >' . __( 'Add Reddit', 'cbxwpreadymix' ) . '</option>
								<option value="yahoo"       >' . __( 'Add Yahoo', 'cbxwpreadymix' ) . '</option>
								<option value="vimeo"       >' . __( 'Add Vimeo', 'cbxwpreadymix' ) . '</option>
								<option value="stumbleupon" >' . __( 'Add Stumble Upon', 'cbxwpreadymix' ) . '</option>
								<option value="linkedin"    >' . __( 'Add Linkedin', 'cbxwpreadymix' ) . '</option>
								<option value="skype"       >' . __( 'Add Skype', 'cbxwpreadymix' ) . '</option>
								<option value="tumblr"      >' . __( 'Add Tumblr', 'cbxwpreadymix' ) . '</option>
								<option value="instagram"   >' . __( 'Add Instagram', 'cbxwpreadymix' ) . '</option>
								<option value="cbxcustom"   >' . __( 'Add Custom', 'cbxwpreadymix' ) . '</option>
							</select>';
						?>
                    </td>
                </tr>

				<?php
					//allow others to show more custom fields at end
					do_action( 'cbxrmmeta_team_fields_after_start', $fieldValues );
				?>

                </tbody>
            </table>

			<?php
			echo '</div>';

		}//end team metabox field


		/**
		 * Render Metabox under Portfolio
		 *
		 * portfolio meta field
		 *
		 * @param $post
		 *
		 * @since 1.0
		 *
		 */

		public function cbxrmmetabox_portfolio_display( $post ) {

			$fieldValues = get_post_meta( $post->ID, '_cbxrmportfoliometa', true );

			wp_nonce_field( 'cbxrmmetaboxportfolio', 'cbxrmmetaboxportfolio[nonce]' );

			echo '<div id="cbxrmmetabox_portfolio_wrapper">';

			$ext_url      = isset( $fieldValues['ext_url'] ) ? $fieldValues['ext_url'] : '';
			$client_name  = isset( $fieldValues['client_name'] ) ? $fieldValues['client_name'] : '';
			$project_name = isset( $fieldValues['project_name'] ) ? $fieldValues['project_name'] : '';

			?>


            <table class="form-table">
                <tbody>

				<?php do_action( 'cbxrmmeta_portfolio_fields_before_start', $fieldValues ); ?>

                <tr valign="top">
                    <td><?php _e( 'Project Name', 'cbxwpreadymix' ) ?></td>
                    <td>
                        <input type="text" name="cbxrmmetaboxportfolio[project_name]"
                               value="<?php echo $project_name; ?>"/>
                        <p class="description"><?php _e( 'Portfolio Project Name', 'cbxwpreadymix' ); ?></p>
                    </td>
                </tr>

                <tr valign="top">
                    <td><?php _e( 'Client Name', 'cbxwpreadymix' ) ?></td>
                    <td>
                        <input type="text" name="cbxrmmetaboxportfolio[client_name]"
                               value="<?php echo $client_name; ?>"/>
                        <p class="description"><?php _e( 'Portfolio Client Name', 'cbxwpreadymix' ); ?></p>
                    </td>
                </tr>

                <tr valign="top">
                    <td><?php _e( 'External Url', 'cbxwpreadymix' ) ?></td>
                    <td>
                        <input type="url" name="cbxrmmetaboxportfolio[ext_url]" value="<?php echo $ext_url; ?>"/>
                        <p class="description"><?php _e( 'Portfolio External Url', 'cbxwpreadymix' ); ?></p>
                    </td>
                </tr>


				<?php
					//allow others to show more custom fields at end
					do_action( 'cbxrmmeta_portfolio_fields_after_start', $fieldValues );
				?>

                </tbody>
            </table>

			<?php
			echo '</div>';


		}//end  metabox field


		/**
		 * Render Metabox under custom post
		 *
		 * testimonial meta field
		 *
		 * @param $post
		 *
		 * @since 1.0
		 *
		 */

		public function cbxrmmetabox_testimonial_display( $post ) {

			$fieldValues = get_post_meta( $post->ID, '_cbxrmtestimonialmeta', true );


			wp_nonce_field( 'cbxrmmetaboxtestimonial', 'cbxrmmetaboxtestimonial[nonce]' );

			echo '<div id="cbxrmmetabox_testimonial_wrapper">';

			$company     = isset( $fieldValues['company'] ) ? $fieldValues['company'] : '';
			$designation = isset( $fieldValues['designation'] ) ? $fieldValues['designation'] : '';
			$client_name = isset( $fieldValues['client_name'] ) ? $fieldValues['client_name'] : '';
			$ext_url     = isset( $fieldValues['ext_url'] ) ? $fieldValues['ext_url'] : '';

			?>


            <table class="form-table">
                <tbody>

				<?php do_action( 'cbxrmmeta_testimonial_fields_before_start', $fieldValues ); ?>

                <tr valign="top">
                    <td><?php _e( 'Company Name', 'cbxwpreadymix' ) ?></td>
                    <td>
                        <input type="text" name="cbxrmmetaboxtestimonial[company]" value="<?php echo $company; ?>"/>
                        <p class="description"><?php _e( 'Client Company Name', 'cbxwpreadymix' ); ?></p>
                    </td>
                </tr>

                <tr valign="top">
                    <td><?php _e( 'Client Name', 'cbxwpreadymix' ) ?></td>
                    <td>
                        <input type="text" name="cbxrmmetaboxtestimonial[client_name]"
                               value="<?php echo $client_name; ?>"/>
                        <p class="description"><?php _e( 'Client Name', 'cbxwpreadymix' ); ?></p>
                    </td>
                </tr>

                <tr valign="top">
                    <td><?php _e( 'Designation', 'cbxwpreadymix' ) ?></td>
                    <td>
                        <input type="text" name="cbxrmmetaboxtestimonial[designation]"
                               value="<?php echo $designation; ?>"/>
                        <p class="description"><?php _e( 'Client Designation', 'cbxwpreadymix' ); ?></p>
                    </td>
                </tr>

                <tr valign="top">
                    <td><?php _e( 'External Url', 'cbxwpreadymix' ) ?></td>
                    <td>
                        <input type="url" name="cbxrmmetaboxtestimonial[ext_url]" value="<?php echo $ext_url; ?>"/>
                        <p class="description"><?php _e( 'Testimonial External Url', 'cbxwpreadymix' ); ?></p>
                    </td>
                </tr>

				<?php
					//allow others to show more custom fields at end
					do_action( 'cbxrmmeta_testimonial_fields_after_start', $fieldValues );
				?>

                </tbody>
            </table>

			<?php
			echo '</div>';


		}//end  metabox field


		/**
		 * Render Metabox under custom post
		 *
		 * brand meta field
		 *
		 * @param $post
		 *
		 * @since 1.0
		 *
		 */

		public function cbxrmmetabox_brand_display( $post ) {

			$fieldValues = get_post_meta( $post->ID, '_cbxrmbrandmeta', true );


			wp_nonce_field( 'cbxrmmetaboxbrand', 'cbxrmmetaboxbrand[nonce]' );

			echo '<div id="cbxrmmetabox_brand_wrapper">';
			$ext_url = isset( $fieldValues['ext_url'] ) ? $fieldValues['ext_url'] : '';
			?>

            <table class="form-table">
                <tbody>

				<?php do_action( 'cbxrmmeta_brand_fields_before_start', $fieldValues ); ?>


                <tr valign="top">
                    <td><?php _e( 'Brand External Url', 'cbxwpreadymix' ) ?></td>
                    <td>
                        <input type="url" name="cbxrmmetaboxbrand[ext_url]" value="<?php echo $ext_url; ?>"/>
                        <p class="description"><?php _e( 'External Url', 'cbxwpreadymix' ); ?></p>
                    </td>
                </tr>

				<?php
					//allow others to show more custom fields at end
					do_action( 'cbxrmmeta_brand_fields_after_start', $fieldValues );
				?>

                </tbody>
            </table>

			<?php
			echo '</div>';


		}//end  metabox field


		/**
		 * Determines whether or not the current user has the ability to save meta data associated with this post.
		 *
		 * Save team Meta Field
		 *
		 * @param        int $post_id //The ID of the post being save
		 * @param            bool     //Whether or not the user has the ability to save this post.
		 */
		public function save_post_cbxrmmetabox_team( $post_id, $post ) {

			$post_type = 'cbxteam';

			// If this isn't a 'book' post, don't update it.
			if ( $post_type != $post->post_type ) {
				return;
			}

			if ( ! empty( $_POST['cbxrmmetaboxteam'] ) ) {

				$postData = $_POST['cbxrmmetaboxteam'];

				$repeated_data_all = ( empty( $postData['social'] ) ) ? array() : $postData['social'];

				$saveableData = array();

				if ( $this->user_can_save( $post_id, 'cbxrmmetaboxteam', $postData['nonce'] ) ) {


					$saveableData['designation'] = sanitize_text_field( $postData['designation'] );

					$i = 0;

					foreach ( $repeated_data_all as $repeated_data ) {

						$saveableData['social'][ $i ]['type']   = sanitize_text_field( $repeated_data['type'] );
						$saveableData['social'][ $i ]['icon']   = sanitize_text_field( $repeated_data['icon'] );
						$saveableData['social'][ $i ]['url']    = esc_url( $repeated_data['url'] );
						$saveableData['social'][ $i ] ['title'] = sanitize_text_field( $repeated_data['title'] );
						$i ++;
					}

					update_post_meta( $post_id, '_cbxrmteammeta', $saveableData );
				}


			}
		}// End  Meta Save


		/**
		 * Determines whether or not the current user has the ability to save meta data associated with this post.
		 *
		 * Save portfolio Meta Field
		 *
		 * @param        int $post_id //The ID of the post being save
		 * @param            bool     //Whether or not the user has the ability to save this post.
		 */
		public function save_post_cbxrmmetabox_portfolio( $post_id, $post ) {

			$post_type = 'cbxportfolio';

			// If this isn't a 'book' post, don't update it.
			if ( $post_type != $post->post_type ) {
				return;
			}

			if ( ! empty( $_POST['cbxrmmetaboxportfolio'] ) ) {

				$postData = $_POST['cbxrmmetaboxportfolio'];

				$saveableData = array();

				if ( $this->user_can_save( $post_id, 'cbxrmmetaboxportfolio', $postData['nonce'] ) ) {

					$saveableData['ext_url']      = esc_url( $postData['ext_url'] );
					$saveableData['client_name']  = sanitize_text_field( $postData['client_name'] );
					$saveableData['project_name'] = sanitize_text_field( $postData['project_name'] );

					update_post_meta( $post_id, '_cbxrmportfoliometa', $saveableData );
				}
			}
		}// End  Meta Save


		/**
		 * Determines whether or not the current user has the ability to save meta data associated with this post.
		 *
		 * Save testimonial Meta Field
		 *
		 * @param        int $post_id //The ID of the post being save
		 * @param            bool     //Whether or not the user has the ability to save this post.
		 */
		public function save_post_cbxrmmetabox_testimonial( $post_id, $post ) {

			$post_type = 'cbxtestimonial';

			// If this isn't a 'book' post, don't update it.
			if ( $post_type != $post->post_type ) {
				return;
			}

			if ( ! empty( $_POST['cbxrmmetaboxtestimonial'] ) ) {

				$postData = $_POST['cbxrmmetaboxtestimonial'];

				$saveableData = array();

				if ( $this->user_can_save( $post_id, 'cbxrmmetaboxtestimonial', $postData['nonce'] ) ) {

					$saveableData['company']     = sanitize_text_field( $postData['company'] );
					$saveableData['designation'] = sanitize_text_field( $postData['designation'] );
					$saveableData['client_name'] = sanitize_text_field( $postData['client_name'] );
					$saveableData['ext_url']     = esc_url( $postData['ext_url'] );

					update_post_meta( $post_id, '_cbxrmtestimonialmeta', $saveableData );
				}
			}

		}// End  Meta Save


		/**
		 * Determines whether or not the current user has the ability to save meta data associated with this post.
		 *
		 * Save brand Meta Field
		 *
		 * @param        int $post_id //The ID of the post being save
		 * @param            bool     //Whether or not the user has the ability to save this post.
		 */
		public function save_post_cbxrmmetabox_brand( $post_id, $post ) {

			$post_type = 'cbxbrand';

			// If this isn't a 'book' post, don't update it.
			if ( $post_type != $post->post_type ) {
				return;
			}

			if ( ! empty( $_POST['cbxrmmetaboxbrand'] ) ) {

				$postData = $_POST['cbxrmmetaboxbrand'];

				$saveableData = array();

				if ( $this->user_can_save( $post_id, 'cbxrmmetaboxbrand', $postData['nonce'] ) ) {

					$saveableData['ext_url'] = esc_url( $postData['ext_url'] );

					update_post_meta( $post_id, '_cbxrmbrandmeta', $saveableData );
				}
			}
		}// End  Meta Save


		/**
		 * Determines whether or not the current user has the ability to save meta data associated with this post.
		 *
		 * user_can_save
		 *
		 * @param        int $post_id // The ID of the post being save
		 * @param            bool     /Whether or not the user has the ability to save this post.
		 *
		 * @since 1.0
		 */
		public function user_can_save( $post_id, $action, $nonce ) {

			$is_autosave    = wp_is_post_autosave( $post_id );
			$is_revision    = wp_is_post_revision( $post_id );
			$is_valid_nonce = ( isset( $nonce ) && wp_verify_nonce( $nonce, $action ) );

			// Return true if the user is able to save; otherwise, false.
			return ! ( $is_autosave || $is_revision ) && $is_valid_nonce;

		}

	}
