<?php

	/**
	 * Add CBXWPReadymixBrand_Widget widget.
	 */
	class CBXWPReadymixPortfolio_Widget extends WP_Widget {

		protected $widget_slug = 'cbxportfolio';

	    /**
		 * Register widget with WordPress.
		 */
		function __construct() {

			parent::__construct( 'cbxwpreadymixportfolio_widget', // Base ID
				esc_html__( 'CBX Portfolio', 'cbxwpreadymix' ), // Name
				array(
                    'classname'  => 'widget-'.$this->get_widget_slug(),
					'description' => esc_html__( 'Show Portofolio', 'cbxwpreadymix' ),
				) // Args
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

			ob_start();

			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			echo CBXWPReadymixContentMapperView::cbxwpreadymix_portfolio_content_view( $instance );

			echo $args['after_widget'];

			$output = ob_get_clean();

			$cache[ $args['widget_id'] ] = $output;
			wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );
			print $output;

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
				'title'         => '',
				'column'        => 1,
				'count'         => 10,
				'category_view' => 'show',
				'category'      => '',
				'order'         => 'DESC',
				'orderby'       => 'date',
			) );

			$portfolio_terms = get_terms( array(
				'taxonomy'   => 'cbxportfoliocat',
				'hide_empty' => false,
			) );

			?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'cbxwpreadymix' ); ?></label>
                <input id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat title"
                       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                       value="<?php echo $instance['title']; ?>" />
            </p>
			<p>
                <label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php esc_html_e( 'No. of Portfolio to show:', 'cbxwpreadymix' ); ?></label>
                <input id="<?php echo $this->get_field_id( 'count' ); ?>" class="tiny-text"
                       name="<?php echo $this->get_field_name( 'count' ); ?>" type="number"
                       value="<?php echo $instance['count']; ?>">
            </p>        <p>
                <label for="<?php echo $this->get_field_id( 'column' ); ?>"><?php esc_html_e( 'No. of Column to show:', 'cbxwpreadymix' ); ?></label>
                <input id="<?php echo $this->get_field_id( 'column' ); ?>" class="tiny-text"
                       name="<?php echo $this->get_field_name( 'column' ); ?>" type="number"
                       value="<?php echo $instance['column']; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'category' ); ?>">Select Category Name</label> <select
                        id="<?php echo $this->get_field_id( 'category' ); ?>"
                        name="<?php echo $this->get_field_name( 'category' ); ?>">
                    <option value="<?php echo esc_attr( '', 'cbxwpreadymix' ); ?>" <?php selected( $instance['category'], '' ); ?>><?php echo esc_html__( 'All', 'cbxwpreadymix' ); ?></option>
					<?php foreach ( $portfolio_terms as $portfolio_term ) { ?>
                        <option value="<?php echo esc_attr( $portfolio_term->slug ); ?>" <?php selected( $instance['category'], $portfolio_term->slug ); ?>><?php echo esc_html( $portfolio_term->name ); ?></option>
					<?php } ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'category_view' ); ?>">Show Category Link</label> <select
                        id="<?php echo $this->get_field_id( 'category_view' ); ?>"
                        name="<?php echo $this->get_field_name( 'category_view' ); ?>">
                    <option value="show" <?php selected( $instance['category_view'], 'show' ); ?>>Show</option>
                    <option value="hide" <?php selected( $instance['category_view'], 'hide' ); ?>>Hide</option>
                </select>

            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'order' ); ?>">Order</label> <select
                        id="<?php echo $this->get_field_id( 'order' ); ?>"
                        name="<?php echo $this->get_field_name( 'order' ); ?>">
                    <option value="ASC" <?php selected( $instance['order'], 'ASC' ); ?>>ASC</option>
                    <option value="DESC" <?php selected( $instance['order'], 'DESC' ); ?>>DESC</option>
                </select>

            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'orderby' ); ?>">Order By</label> <select
                        id="<?php echo $this->get_field_id( 'orderby' ); ?>"
                        name="<?php echo $this->get_field_name( 'orderby' ); ?>">
                    <option value="date" <?php selected( $instance['orderby'], 'date' ); ?>>Date</option>
                    <option value="ID" <?php selected( $instance['orderby'], 'ID' ); ?>>ID</option>
                    <option value="rand" <?php selected( $instance['orderby'], 'rand' ); ?>>RAND</option>
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

			$instance                  = $old_instance;
			$instance['title']         = $new_instance['title'];
			$instance['count']         = $new_instance['count'];
			$instance['column']        = $new_instance['column'];
			$instance['category_view'] = $new_instance['category_view'];
			$instance['order']         = $new_instance['order'];
			$instance['orderby']       = $new_instance['orderby'];
			$instance['category']      = $new_instance['category'];

			return $instance;

		}

	} // class Popular_Store

// register storeinformation_widget
	function cbxwpreadymixportfolio_widget() {
		register_widget( 'CBXwpReadymixPortfolio_Widget' );
	}

	add_action( 'widgets_init', 'cbxwpreadymixportfolio_widget' );