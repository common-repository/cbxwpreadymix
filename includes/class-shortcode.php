<?php

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
 * @package    cbxwpreadymix
 * @subpackage cbxwpreadymix/includes
 * @author     Themeboxr Team <info@themeboxr.com>
 */
class CBXWPReadymixShortcode {

	/**
	 * CBXwpReadymixShortcode constructor.
	 */
	function __construct() {
		$this->settings_api    = new CBXWPReadyMixSettings( CBXWPREADYMIX_PLUGIN_NAME, CBXWPREADYMIX_PLUGIN_VERSION );



		// team shortcode
		add_shortcode( "cbxwpreadymix_team", array( $this, "cbxwpreadymix_team_shortcode" ) );


		// testimonial shortcode
		add_shortcode( "cbxwpreadymix_testimonial", array( $this, "cbxwpreadymix_testimonial_shortcode" ) );

		// portfolio shortcode
		add_shortcode( "cbxwpreadymix_portfolio", array( $this, "cbxwpreadymix_portfolio_shortcode" ) );

		// brand shortcode
		add_shortcode( "cbxwpreadymix_brand", array( $this, "cbxwpreadymix_brand_shortcode" ) );




		$enable_bsshortcode       = $this->settings_api->get_option( 'enable_bsshortcode', 'cbxwpreadymix_general', 'on' );



		//Twitter Bootstrap Specific Content shortcode
		if($enable_bsshortcode == 'on'){

			//button
			add_shortcode( "button", array( $this, "button_shortcode" ) );

			//Alert
			add_shortcode( "alert", array( $this, "alert_shortcode" ) );


			//Block Messages
			add_shortcode( "block-message", array( $this, "block_messages_shortcode" ) );

			//Blockquotes
			add_shortcode( "blockquote", array( $this, "blockquote_shortcode" ) );

			//thumbcaption
			add_shortcode( "thumbcaption", array( $this, "thumbcaption_shortcode" ) );

			//thumbnails
			add_shortcode( "thumbnail", array( $this, "thumbnails_shortcode" ) );

			//Jumbotrons
			add_shortcode( "jumbotron", array( $this, "jumbotrons_shortcode" ) );

			//Progress Bar
			add_shortcode( "progressbar", array( $this, "progressbars_shortcode" ) );

			//Pricing Table
			add_shortcode( "pricingtable", array( $this, "pricing_table_shortcode" ) ); //main shortcode
			add_shortcode( "pricingtableitem", array( $this, "pricing_table_item_shortcode" ) ); //pricing table item

			//Margin
			add_shortcode( "margin", array( $this, "margin_shortcode" ) );

			//Padding
			add_shortcode( "padding", array( $this, "padding_shortcode" ) );

			//Title
			add_shortcode( "title", array( $this, "title_shortcode" ) );

			//Content
			add_shortcode( "content", array( $this, "content_shortcode" ) );

			// Panel
			add_shortcode( "panel", array( $this, "panel_shortcode" ) );

			//Tabs
			add_shortcode( "tabs", array( $this, "tabs_shortcode" ) );

			//Tab
			add_shortcode( "tab", array( $this, "tab_shortcode" ) );

			//Accordions
			add_shortcode( "accordions", array( $this, "accordions_shortcode" ) );

			//Accordion
			add_shortcode( "accordion", array( $this, "accordion_shortcode" ) );
		}

	}

	/**
	 * cbxwpreadymix_portfolio_shortcode
	 * Example: [cbxwpreadymix_portfolio column=3 count=2 category_view='show' category="branding","travel" order="ASC" orderby="rand"]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function cbxwpreadymix_portfolio_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts( array(
			                        'column'        => 4,
			                        'count'         => 12,
			                        'category_view' => 'show',
			                        'category'      => '',
			                        'order'         => 'DESC',
			                        'orderby'       => 'date',
		                        ), $atts );

		$output = CBXWPReadymixContentMapperView::cbxwpreadymix_portfolio_content_view( $atts );

		return $output;
	}

	/**
	 * cbxwpreadymix_brand_shortcode
	 * Example: [cbxwpreadymix_brand column="3" count="2" order="ASC" orderby="date"]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function cbxwpreadymix_brand_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts( array(
			                        'column'  => 4,
			                        'count'   => 12,
			                        'order'   => 'DESC',
			                        'orderby' => 'date',
		                        ), $atts );

		$output = CBXWPReadymixContentMapperView::cbxwpreadymix_brand_content_view( $atts );

		return $output;
	}

	/**
	 * cbxwpreadymix_testimonial_shortcode
	 * Example: [cbxwpreadymix_testimonial count="2" order="ASC" orderby="date"]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function cbxwpreadymix_testimonial_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts( array(
			                        'count'   => 10,
			                        'order'   => 'DESC',
			                        'orderby' => 'date',
		                        ), $atts );

		$output = CBXWPReadymixContentMapperView::cbxwpreadymix_testimonial_content_view( $atts );

		return $output;
	}

	/**
	 * cbxwpreadymix_team_shortcode
	 * Example: [cbxwpreadymix_team column="3" hide_designation="hide" hide_social="show" count=5 order="ASC" orderby="date"]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function cbxwpreadymix_team_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts( array(
			                        'column'           => 3,
			                        'hide_designation' => 'show',
			                        'hide_social'      => 'show',
			                        'count'            => 9,
			                        'meta_key'         => '',
			                        'order'            => 'DESC',
			                        'orderby'          => 'title',

		                        ), $atts, 'cbxteam' );

		$output = CBXWPReadymixContentMapperView::cbxwpreadymix_team_content_view( $atts );

		return $output;
	}


	/**
	 * Shortcode Helper - Remove unwanted data
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public static function ShortcodeHelper( $content = null ) {
		$content = do_shortcode( shortcode_unautop( $content ) ); //Ensures that shortcodes are not wrapped in <p>...</p>
		$content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content );
		$content = preg_replace( '#<br \/>#', '', $content );

		return trim( $content );
	}


	/**
	 * getDatavalue : Add Data Attribute
	 *
	 * @param $data
	 *
	 * @return string
	 */

	function getDatavalue( $attr ) {

		$data_attr = '';

		if ( $attr ) {
			$attr = explode( '|', $attr );

			foreach ( $attr as $att ) {
				$att = explode( ',', $att );
				$data_attr .= ' data-' . $att[0] . '="' . $att[1] . '"';
			}
		} else {
			$data_attr = '';
		}

		return $data_attr;
	}


	/**
	 * Attribute_map
	 *
	 * @param      $str
	 * @param null $att
	 * We need to be able to figure out the attributes of a wrapped shortcode
	 *
	 * @return array
	 */
	function attribute_map( $str, $att = null ) {
		$res    = array();
		$return = array();
		$reg    = get_shortcode_regex(); //this function combines all registered Short Code tags into a single regular expression

		preg_match_all( '~' . $reg . '~', $str, $matches );

		foreach ( $matches[2] as $key => $name ) {

			$parsed = shortcode_parse_atts( $matches[3][ $key ] ); //Retrieve all attributes from the shortcodes tag.

			$parsed       = is_array( $parsed ) ? $parsed : array();
			$res[ $name ] = $parsed;
			$return[]     = $res;
		}

		return $return;
	}


	/**
	 * Buttons shortcode
	 * Example: [button type="primary" link="your link here" text="Cool Button" size="large"]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */

	function button_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			                         'type' => 'default', // primary, default, info, success, danger, warning, inverse
			                         'size' => 'default', // mini, small, default, large */
			                         'url'  => '',
			                         'text' => '',
		                         ), $atts ) );

		if ( $type == "default" ) {
			$type = "";
		} else {
			$type = "btn-" . $type;
		}

		if ( $size == "default" ) {
			$size = "";
		} else {
			$size = "btn-" . $size;
		}

		$url = esc_url( $url );

		$output = '<a href="' . $url . '" class="btn ' . $type . ' ' . $size . '">';
		$output .= $text;
		$output .= '</a>';

		return $output;
	}


	/**
	 * Alerts Shortcode
	 * Example: [alert type="info" text=""]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */

	function alert_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			                         'type'  => 'alert-info', //alert-info, alert-success, alert-error
			                         'close' => 'false', //display close link
			                         'text'  => '',
		                         ), $atts ) );

		$output = '<div class="fade in alert alert-' . $type . '">';
		if ( $close == 'true' ) {
			$output .= '<a class="close" data-dismiss="alert">×</a>';
		}
		$output .= $text . '</div>';

		return $output;
	}


	/**
	 * Block Messages Shortcode
	 * Example: [block-message type="info" text=""]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */

	function block_messages_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			                         'type'  => 'alert-info',// alert-info, alert-success, alert-error
			                         'close' => 'false', //display close link
			                         'text'  => '',
		                         ), $atts ) );

		$output = '<div class="fade in alert alert-block alert-' . $type . '">';
		if ( $close == 'true' ) {
			$output .= '<a class="close" data-dismiss="alert">×</a>';
		}
		$output .= '<p>' . $text . '</p></div>';

		return $output;
	}


	/**
	 * Blockquote Shortcode
	 * Example: [blockquote cite="Someone" float="right"]  Your Quote.[/blockquote]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function blockquote_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			                         'float' => '', //left, right
			                         'cite'  => '', //text for cite
		                         ), $atts ) );

		$output = '<blockquote';
		if ( $float == 'left' ) {
			$output .= ' class="pull-left"';
		} elseif ( $float == 'right' ) {
			$output .= ' class="pull-right"';
		}
		$output .= '><p>' . $content . '</p>';

		if ( $cite ) {
			$output .= '<small>' . $cite . '</small>';
		}

		$output .= '</blockquote>';

		return $output;
	}


	/**
	 * Thumb Caption Shortcode
	 * Example: [thumcaption]This is thumbnil caption[/thumcaption]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function thumbcaption_shortcode( $atts, $content = null ) {
		$atts    = shortcode_atts( array(
			                           "class" => '', // Add any class name for custom design
		                           ), $atts );

		$content = self::ShortcodeHelper( $content );

		return '<div class="caption ' . $atts['class'] . '"><p>' . $content . '</p></div>';
	}


	/**
	 * Thumbnails Shortcode
	 * Example:  [thumbnail ]<img src="" />[thumbcaption]Caption[/thumbcaption][/thumbnail]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function thumbnails_shortcode( $atts, $content = null ) {
		$atts    = shortcode_atts( array(
			                           "class" => '', // Add any class name for custom design
		                           ), $atts );
		$content = self::ShortcodeHelper( $content );

		return '<div class="thumbnail ' . $atts['class'] . '">' . $content . '</div>';
	}


	/**
	 * Jumbotron Shortcode
	 * Example: [jumbotron title=""  content="" button_text=" " button_link="#"]
	 *
	 * @param $args
	 *
	 * @return string
	 */
	function jumbotrons_shortcode( $args ) {
		extract( shortcode_atts( array(
			                         'title'       => '', // Add any text
			                         'content'     => '',// Add any text
			                         'button_text' => '',// Add any text
			                         'button_link' => '#',// Add any link

		                         ), $args ) );

		$button_link = esc_url( $button_link );
		$output      = '<div class="jumbotron">';
		$output .= '<h2>' . $title . '</h2>';
		$output .= '<p>' . $content . '</p>';
		$output .= '<p><a class="btn btn-primary btn-lg" href="' . $button_link . '" role="button">' . $button_text . '</a></p>';
		$output .= '</div>';

		return $output;
	}


	/**
	 * Progressbars Shortcode
	 * Example:[progressbar class="progress-bar-info progress-bar-striped" value="70"]
	 *
	 * @param $args
	 *
	 * @return string
	 */

	function progressbars_shortcode( $args ) {
		extract( shortcode_atts( array(
			                         'class' => 'progress-bar-success progress-bar-striped',
			                         // For more option class, please visit: http://getbootstrap.com/components/#progress
			                         'value' => 60,
			                         // Add value 0 to 100
		                         ), $args ) );

		$new_value = intval( trim( $value ) ) . '%';

		$output = '<div class="progress">';
		$output .= '<div class="progress-bar ' . $class . '" role="progressbar" aria-valuenow="' . $new_value . '" aria-valuemin="0" aria-valuemax="100%" style="width: ' . $new_value . ';">';
		$output .= $new_value;
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Pricing Table Shortcode (Parent Wrapper)
	 *
	 * Example: [pricingtable type ="type2"] [pricingtableitem] ... [/pricingtableitem][/pricingtable]
	 *
	 * @param array $attrs : type1, type2
	 * @param string $content
	 *
	 * @return string
	 */

	function pricing_table_shortcode( $attrs, $content = null ) {
		extract( shortcode_atts( array( 'type' => 'type1' ), $attrs ) );

		$type    = ( trim( $type ) === 'type1' ) ? "no-space" : "space";
		$content = self::ShortcodeHelper( $content );

		return "<div class='cbx-pricing-table clearfix row {$type}' >" . $content . '</div>';
	}


	/**
	 * Pricing Table Items(Single Item)
	 *
	 * Example: [pricingtableitem heading='' button_text='' button_link="#" price='100$' class="" per='Month'] <ul><li>your text</li><ul>[/pricingtableitem]
	 *
	 * @param array $attrs
	 * @param string $content
	 *
	 * @return string
	 */
	function pricing_table_item_shortcode( $attrs, $content = null ) {
		extract( shortcode_atts( array(
			                         'heading'     => __( 'Heading', 'cbxwpreadymix' ), // Add Your Heading
			                         'per'         => __( 'month', 'cbxwpreadymix' ),// add any text
			                         'price'       => '', // Add Price
			                         "button_link" => "#",
			                         "button_text" => __( 'Buy Now', 'cbxwpreadymix' ),
			                         "button_size" => "small", // button Size
			                         'class'       => 'col-xs-6 col-sm-4', // add your own class or grid
		                         ), $attrs ) );

		$selected = ( isset ( $attrs [0] ) && trim( $attrs [0] == 'selected' ) ) ? 'selected' : '';

		$button_link = esc_url( $button_link );

		$content = self::ShortcodeHelper( $content );
		$content = str_replace( '<ul>', '<ul class="pt-content">', $content );
		$content = str_replace( '<ol>', '<ul class="pt-content">', $content );
		$content = str_replace( '</ol>', '</ul>', $content );
		$price   = ! empty ( $price ) ? "<div class='price btn btn-info'> $price <span> $per</span> </div>" : "";

		$out = '<div class="cbx-pr-tb-col ' . $selected . ' ' . $class . '">';
		$out .= '<div class="cbx-pt-header">';
		$out .= '<div class="cbx-pt-title">';
		$out .= '<h4>' . $heading . '</h4>';
		$out .= '</div>';
		$out .= $price;
		$out .= '</div>';
		$out .= $content;
		$out .= '<div class="cbx-buy-now">';
		$out .= '<a class="btn btn-success ' . $button_size . '" href="' . $button_link . '">' . $button_text . '</a>';
		$out .= '</div>';
		$out .= '</div>';

		return $out;
	}


	/**
	 * Margin Shortcode
	 * Example: [margin]
	 *
	 * @param $atts
	 *
	 * @return string
	 */

	function margin_shortcode( $atts ) {
		extract( shortcode_atts( array(
			                         "top"    => '50', // add any number
			                         'left'   => '0',  // add any number
			                         'right'  => '0',  // add any number
			                         'bottom' => '0',  // add any number
			                         'unit'   => 'px'  // pax, rem, em, %
		                         ), $atts ) );

		$top    = floatval( $top );
		$left   = floatval( $left );
		$right  = floatval( $right );
		$bottom = floatval( $bottom );

		return '<div style="margin: ' . $top . trim( $unit ) . ' ' . $right . trim( $unit ) . ' ' . $bottom . trim( $unit ) . ' ' . $left . trim( $unit ) . '; display: block; clear:both;"> </div>';
	}

	/**
	 * Padding Shortcode
	 * Example: [padding]
	 *
	 * @param $atts
	 *
	 * @return string
	 */

	function padding_shortcode( $atts ) {
		extract( shortcode_atts( array(
			                         "top"    => '50', // add any number
			                         'left'   => '0',  // add any number
			                         'right'  => '0',  // add any number
			                         'bottom' => '0',  // add any number
			                         'unit'   => 'px'  // px, rem, em, %
		                         ), $atts ) );

		$top    = floatval( $top );
		$left   = floatval( $left );
		$right  = floatval( $right );
		$bottom = floatval( $bottom );

		return '<div style="padding: ' . $top . trim( $unit ) . ' ' . $right . trim( $unit ) . ' ' . $bottom . trim( $unit ) . ' ' . $left . trim( $unit ) . '; display: block; clear:both;"> </div>';
	}


	/**
	 * Title Shortcode
	 * Example: [title]your title[/title]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */

	function title_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			                         "tag"   => 'h2', // Add any title tag-> h1, h2, h3, h4, h5, h6
			                         "class" => '', // add any class
		                         ), $atts ) );
		$content = self::ShortcodeHelper( $content );

		return '<' . trim( $tag ) . ' class="title ' . $class . '">' . $content . '</' . trim( $tag ) . '>';
	}


	/**
	 * Content Shortcode
	 * Example: [content tag='div']your content[content]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */

	function content_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			                         "tag"   => 'p',
			                         "class" => '',
			                         "class" => '',
		                         ), $atts ) );
		$content = self::ShortcodeHelper( $content );

		$class_output = ( $class != '' ) ? ' class="' . $class . '"' : '';

		return '<' . trim( $tag ) . ' ' . $class_output . '>' . $content . '</' . trim( $tag ) . '>';
	}


	/**
	 * Panel Shortcode
	 * Example: [panel type="primary"  heading_text = '' footer_text='' ] content here [/panel]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function panel_shortcode( $atts, $content = null ) {

		extract( shortcode_atts( array(
			                         "type"         => 'default', // primary, success, info, warning, danger
			                         "heading_text" => '', // add text for heading
			                         "footer_text"  => '', // add text for footer
		                         ), $atts ) );

		$content      = self::ShortcodeHelper( $content );
		$footer_html  = ( $footer_text != '' ) ? '<div class="panel-footer">' . $footer_text . '</div>' : '';
		$heading_html = ( $heading_text != '' ) ? '<div class="panel-heading">' . $heading_text . '</div>' : '';

		$panel_html = '<div class="panel panel-' . $type . '">';
		$panel_html .= $heading_html;
		$panel_html .= '<div class="panel-body">' . $content . '</div>';
		$panel_html .= $footer_html;
		$panel_html .= '</div>';

		return $panel_html;

	}


	/**
	 *  Parent Tabs Shortcode
	 *  example:[tabs type=""] [/tabs]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */

	function tabs_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts( array(
			                        "type"  => false,
			                        //The type of nav : tabs, pills
			                        "class" => '',
			                        //Any extra classes you want to add , rename this variable to class
			                        "data"  => false
			                        //Data attribute and value pairs separated by a comma. For example: data="toggle,dropdown"
		                        ), $atts );

		$list_class_one   = 'nav';
		$list_class_two   = ( $atts['type'] ) ? ' nav-' . $atts['type'] : ' nav-tabs';
		$list_class_three = ( $atts['class'] ) ? ' ' . $atts['class'] : '';

		$list_class = $list_class_one . $list_class_two . $list_class_three;

		$content_class = 'tab-content';

		$GLOBALS['count']        = isset( $GLOBALS['count'] ) ? $GLOBALS['count'] ++ : 0;
		$GLOBALS['common_count'] = 0;

		$id = 'tabs-' . $GLOBALS['count'];

		$data_attrs = $this->getDatavalue( $atts['data'] ); // Get data attribute

		$atts_map = $this->attribute_map( $content ); //too: see what's the problem this function solves

		// Extract the tab titles for use in the tab widget.
		if ( $atts_map ) {
			$tabs = array();

			$GLOBALS['default_active'] = true;

			foreach ( $atts_map as $check ) {

				if ( ! empty( $check["tab"]["active"] ) ) {
					$GLOBALS['default_active'] = false;
				}
			}

			$i = 0;
			foreach ( $atts_map as $tab ) {

				$class = '';
				$class .= ( ! empty( $tab["tab"]["active"] ) || ( $GLOBALS['default_active'] && $i == 0 ) ) ? 'active' : '';
				$class .= ( ! empty( $tab["tab"]["class"] ) ) ? ' ' . $tab["tab"]["class"] : '';

				$tab_title = str_replace( ' ', '', $tab["tab"]["title"] );
				$tabs[]    = '<li ' . $class . ' ><a href="#' . 'tab-' . $GLOBALS['count'] . '-' . strtolower( $tab_title ) . '" data-toggle="tab">' . $tab["tab"]["title"] . '</a></li>';
				$i ++;
			}
		}

		$tabs = ( $tabs ) ? implode( $tabs ) : ''; // Array to string conversion
		/*print_r($tabs);*/

		return '<ul class="' . $list_class . '" id="' . $id . '" ' . $data_attrs . '>' . $tabs . '</ul><div class="' . $content_class . '">' . do_shortcode( $content ) . '</div>';
	}


	/**
	 * Single tab item short code
	 * Example: [tab title="Home" active="true"]...[/tab]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */

	function tab_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts( array(
			                        'title'  => false,
			                        //The title of the tab
			                        'active' => false,
			                        //Whether this tab should be "active" or selected: true, false
			                        'fade'   => false,
			                        //Whether to use the "fade" effect when showing this tab: true, false
			                        'class'  => false,
			                        //Any extra classes you want to add
			                        'data'   => false
			                        //Data attribute and value pairs separated by a comma. For example: data="toggle,dropdown"
		                        ), $atts );

		if ( $GLOBALS['default_active'] && $GLOBALS['common_count'] == 0 ) {
			$atts['active'] = true;
		}

		$GLOBALS['common_count'] ++;

		$class = 'tab-pane';
		$class .= ( $atts['fade'] == 'true' ) ? ' fade' : '';
		$class .= ( $atts['active'] == 'true' ) ? ' active' : '';
		$class .= ( $atts['active'] == 'true' && $atts['fade'] == 'true' ) ? ' in' : '';
		$class .= ( $atts['class'] ) ? ' ' . $atts['class'] : '';
		$title = str_replace( ' ', '', $atts['title'] );
		$id    = 'tab-' . $GLOBALS['count'] . '-' . strtolower( $title );

		$data_attr = $this->getDatavalue( $atts['data'] );

		return '<div class="' . $class . '" id="' . $id . '" ' . $data_attr . '>' . do_shortcode( $content ) . '</div>';
	}


	/**
	 * Accordions (Parents)
	 * Example: [accordions][/accordions]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function accordions_shortcode( $atts, $content = null ) {

		$GLOBALS['count'] = isset( $GLOBALS['count'] ) ? $GLOBALS['count'] ++ : 0;

		$atts = shortcode_atts( array(
			                        "class" => false,
			                        // extra class , if not empty will be used as extra class with the class attribute
			                        "data"  => ''
			                        // add data attribute
		                        ), $atts );

		$class = 'panel-group';
		$class .= ( $atts['class'] ) ? ' ' . $atts['class'] : '';

		$id = 'custom-collapse-' . $GLOBALS['count'];

		$data_attr = $this->getDatavalue( $atts['data'] );

		return '<div class="' . $class . '" id="' . $id . '" ' . $data_attr . '>' . do_shortcode( $content ) . '</div>';
	}


	/**
	 * Single Accordion
	 * Example:  [accordion title="Collapse 1" active="true"]This is accordion  one.[/accordion]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 */

	function accordion_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts( array(
			                        "title"  => false,
			                        "type"   => false,
			                        //The type of the panel:default, primary, success, info, warning, danger, link
			                        "active" => false,
			                        //Whether the tab is expanded at load time: true, false
			                        "class"  => false,
			                        "data"   => false
		                        ), $atts );

		$panel_class = 'panel';
		$panel_class .= ( $atts['type'] ) ? ' panel-' . $atts['type'] : ' panel-default';
		$panel_class .= ( $atts['class'] ) ? ' ' . $atts['class'] : '';

		$collapse_class = 'panel-collapse';
		$collapse_class .= ( $atts['active'] == 'true' ) ? ' in' : ' collapse';

		$a_class = '';
		$a_class .= ( $atts['active'] == 'true' ) ? '' : 'collapsed';

		$parent           = 'custom-collapse-' . $GLOBALS['count'];
		$title            = str_replace( ' ', '', $atts['title'] );
		$current_collapse = $parent . '-' . strtolower( $title );

		$data_attrs = $this->getDatavalue( $atts['data'] );


		return sprintf( '<div class="%1$s" %2$s>
		        <div class="panel-heading">
		          <h4 class="panel-title">
		            <a class="%3$s" data-toggle="collapse" data-parent="#%4$s" href="#%5$s">%6$s</a>
		          </h4>
		        </div>
		        <div id="%5$s" class="%7$s">
		          <div class="panel-body">%8$s</div>
		        </div>
		      </div>', esc_attr( $panel_class ),//1
		                ( $data_attrs ) ? ' ' . $data_attrs : '',//2
		                $a_class,//3
		                $parent,//4
		                $current_collapse,//5
		                $atts['title'],//6
		                esc_attr( $collapse_class ),//7
		                do_shortcode( $content )//8
		);
	}
}// Class