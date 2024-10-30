<?php

	/**
	 * Add CBXwpReadymix_Team_Widget widget.
	 */
	class CBXWPReadymixTeam_Widget extends WP_Widget {

	    protected $widget_slug = 'cbxteam';

		/**
		 * Register widget with WordPress.
		 */
		function __construct() {

			parent::__construct(
				'cbxwpreadymixteam_widget',
				esc_html__( 'CBX Team', 'cbxwpreadymix' ),
				array(
					'classname'  => 'widget-'.$this->get_widget_slug(),
					'description' => __( 'Display team members', 'cbxwpreadymix' )
				)
			);

			// Refreshing the widget's cached output with each new post
			add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
			add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
			add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

		}
		/**
		 * Return the widget slug.
		 *
		 * @since    1.0.0
		 *
		 * @return    Plugin slug variable.
		 */
		public function get_widget_slug() {
			return $this->widget_slug;
		}

		public function flush_widget_cache()
		{
			wp_cache_delete( $this->get_widget_slug(), 'widget' );
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			// Check if there is a cached output
			$cache = wp_cache_get( $this->get_widget_slug(), 'widget' );
			if ( !is_array( $cache ) )
				$cache = array();

			if ( ! isset ( $args['widget_id'] ) )
				$args['widget_id'] = $this->id;


			if ( isset ( $cache[ $args['widget_id'] ] ) )
				return print $cache[ $args['widget_id'] ];


			$widget_string = $args['before_widget'];
			ob_start();
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			echo  CBXWPReadymixContentMapperView::cbxwpreadymix_team_content_view( $instance );
			$widget_string .= ob_get_clean();

			$widget_string .= $args['after_widget'];
			$cache[ $args['widget_id'] ] = $widget_string;
			wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );
			print $widget_string;

		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {

			$instance = wp_parse_args( $instance, array(
				'title'            => '',
				'column'           => '1',
				'hide_designation' => 'show',
				'hide_social'      => 'show',
				'count'            =>  10,
				'meta_key'          => '',
				'order'             => 'DESC',
				'orderby'           => 'title',
			) );

			?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'cbxwpreadymix' ); ?></label>
                <input id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat title"
                       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                       value="<?php echo $instance['title']; ?>">
            </p><p>
                <label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php esc_html_e( 'No. of Team Member to show:', 'cbxwpreadymix' ); ?></label>
                <input id="<?php echo $this->get_field_id( 'count' ); ?>" class="tiny-text"
                       name="<?php echo $this->get_field_name( 'count' ); ?>" type="number"
                       value="<?php echo $instance['count']; ?>">
            </p><p>
                <label for="<?php echo $this->get_field_id( 'column' ); ?>"><?php esc_html_e( 'No. of Column to show:', 'cbxwpreadymix' ); ?></label>
                <input id="<?php echo $this->get_field_id( 'column' ); ?>" class="tiny-text"
                       name="<?php echo $this->get_field_name( 'column' ); ?>" type="number"
                       value="<?php echo $instance['column']; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'hide_designation' ); ?>"><?php esc_html_e( 'Show Designation:', 'cbxwpreadymix' ); ?></label>
                <select
                        id="<?php echo $this->get_field_id( 'hide_designation' ); ?>"
                        name="<?php echo $this->get_field_name( 'hide_designation' ); ?>">
                    <option value="show" <?php selected( $instance['hide_designation'], 'show' ); ?>><?php esc_html_e('Show', 'cbxwpreadymix'); ?></option>
                    <option value="hide" <?php selected( $instance['hide_designation'], 'hide' ); ?>><?php esc_html_e('Hide','cbxwpreadymix') ?></option>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'hide_social' ); ?>"><?php esc_html_e('Show Social Links','cbxwpreadymix') ?></label>
                <select
                        id="<?php echo $this->get_field_id( 'hide_social' ); ?>"
                        name="<?php echo $this->get_field_name( 'hide_social' ); ?>">
                    <option value="show" <?php selected( $instance['hide_social'], 'show' ); ?> ><?php esc_html_e('Show', 'cbxwpreadymix'); ?></option>
                    <option value="hide" <?php selected( $instance['hide_social'], 'hide' ); ?> ><?php esc_html_e('Hide','cbxwpreadymix') ?></option>
                </select>

            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php esc_html_e('Sort Order','cbxwpreadymix') ?></label>
                <select
                        id="<?php echo $this->get_field_id( 'order' ); ?>"
                        name="<?php echo $this->get_field_name( 'order' ); ?>">
                    <option value="DESC" <?php selected( $instance['order'], 'DESC' ); ?>><?php esc_html_e('DESC', 'cbxwpreadymix'); ?></option>
                    <option value="ASC" <?php selected( $instance['order'], 'ASC' ); ?>><?php esc_html_e('ASC', 'cbxwpreadymix'); ?></option>
                </select>

            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php esc_html_e('Sort Order By','cbxwpreadymix') ?></label>
                <select
                        id="<?php echo $this->get_field_id( 'orderby' ); ?>"
                        name="<?php echo $this->get_field_name( 'orderby' ); ?>">
                    <option value="title" <?php selected( $instance['orderby'], 'DESC' ); ?>><?php esc_html_e('Title', 'cbxwpreadymix'); ?></option>
                    <option value="ID" <?php selected( $instance['orderby'], 'ID' ); ?>><?php esc_html_e('ID', 'cbxwpreadymix'); ?></option>
                    <option value="name" <?php selected( $instance['orderby'], 'name' ); ?>><?php esc_html_e('Post Slug', 'cbxwpreadymix'); ?></option>
                    <option value="date" <?php selected( $instance['orderby'], 'date' ); ?>><?php esc_html_e('Date', 'cbxwpreadymix'); ?></option>
                    <option value="modified" <?php selected( $instance['orderby'], 'modified' ); ?>><?php esc_html_e('Modified Date', 'cbxwpreadymix'); ?></option>
                    <option value="rand" <?php selected( $instance['orderby'], 'rand' ); ?>><?php esc_html_e('Random', 'cbxwpreadymix'); ?></option>
                </select>

            </p>
			<?php
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {

			$instance                     = $old_instance;
			$instance['title']            = $new_instance['title'];
			$instance['column']           = $new_instance['column'];
			$instance['hide_designation'] = $new_instance['hide_designation'];
			$instance['hide_social']      = $new_instance['hide_social'];
			$instance['count']            = $new_instance['count'];
			$instance['meta_key']         = $new_instance['meta_key'];
			$instance['order']            = $new_instance['order'];
			$instance['orderby']          = $new_instance['orderby'];

			return $instance;

		}

	} // class Popular_Store

// register storeinformation_widget
	function cbxwpreadymixteam_widget() {
		register_widget( 'CBXwpReadymixTeam_Widget' );
	}

	add_action( 'widgets_init', 'cbxwpreadymixteam_widget' );