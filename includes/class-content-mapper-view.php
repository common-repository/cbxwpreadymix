<?php

	/**
	 * Class CBXWPReadymixContentMapperView
	 */
	class CBXWPReadymixContentMapperView {

		/**
		 * cbxwpreadymix_team_content_view
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		static function cbxwpreadymix_team_content_view( $atts ) {

			$atts = wp_parse_args( $atts, array(
				'column'           => 4,
				'hide_designation' => 'show',
				'hide_social'      => 'show',
				'count'            => 10,
				'meta_key'         => '',
				'order'            => 'DESC',
				'orderby'          => 'title',
			) );

			$query_args = array(
				'post_type'      => 'cbxteam',
				'post_status'    => 'publish',
				'posts_per_page' => $atts['count'],
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby']
			);

			if($atts['meta_key'] != ''){
				$query_args['meta_key'] = $atts['meta_key'];
			}

			$teams = get_posts( $query_args );

			$output = '<div class="cbxwpreadymixbootstrap cbxwpreadymix_team_wrapper">';

			$output .= '<div class="row">';
			foreach ( $teams as $team ) {
				$team_meta = get_post_meta( $team->ID, '_cbxrmteammeta', true );
				$output    .= '<div class="cbxwpreadymix_team col-md-' . esc_attr( 12 / $atts['column'] ) . '">';
				$output    .= '<div class="cbxwpreadymix_team_thumb">';
				$output    .= '<img class="img-responsive" src="' . esc_url( get_the_post_thumbnail_url( $team->ID, 'small' ) ) . '" alt="' . esc_attr( $team->post_title ) . '"/>';
				$output    .= '</div>';
				$output    .= '<div class="cbxwpreadymix_team_name">';

				$output .= '<h6>' . esc_html( $team->post_title ) . '</h6>';
				if ( $atts['hide_designation'] == 'show' ) {
					$output .= ( ! empty( $team_meta['designation'] ) ) ? '<p>' . esc_html( $team_meta['designation'] ) . '</p>' : '';
				}
				$output .= '</div>';


				if ( ! empty( $team_meta['social'] ) && $atts['hide_social'] == 'show' ) {
					$output .= '<div class="cbxwpreadymix_team_social">';
					$output .= '<ul>';
					foreach ( $team_meta['social'] as $team_social ) {
						$output .= '<li class="team_social_' . esc_attr( $team_social['type'] ) . '">';
						$output .= '<a href="' . esc_url( $team_social['url'] ) . '">';
						$output .= '<i class="fa ' . esc_attr( $team_social['icon'] ) . '"></i>';
						$output .= '</a>';
						$output .= '</li>';
					}
					$output .= '</ul>';
					$output .= '</div>';
				}

				$output .= '</div>';
			}
			$output .= '</div>';

			$output .= '</div>';

			return $output;
		}

		/**
		 * cbxwpreadymix_testimonial_content_view
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		static function cbxwpreadymix_testimonial_content_view( $atts ) {

			$atts = wp_parse_args( $atts, array(
				'count' => 10,
				'order'         => 'DESC',
				'orderby'       => 'date',
			) );

			$testimonials = get_posts( array(
				'post_type'      => 'cbxtestimonial',
				'post_status'    => 'publish',
				'posts_per_page' => $atts['count'],
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby']
			) );

			$output = '<div class="cbxwpreadymixbootstrap cbxwpreadymix_testimonial_wrapper">';
			$output .= '<div class="row">';
			$output .= '<div class="owl-carousel owl-theme cbxwpreadymix_testimonial_owl">';
			foreach ( $testimonials as $testimonial ) {
				$testimonial_meta = get_post_meta( $testimonial->ID, '_cbxrmtestimonialmeta', true );

				$output .= '<div class="cbxwpreadymix_testimonial col-md-12 text-center">';
				$output .= '<img class="img-responsive" src="' . esc_url( get_the_post_thumbnail_url( $testimonial->ID, 'small' ) ) . '" alt="' . esc_attr( $testimonial->post_title ) . '"/>';
				$output .= '<h5>' . $testimonial->post_title . '</h5>';
				$output .= '<h6>' . $testimonial_meta['company'] . '</h6>';
				$output .= '<p>' . $testimonial->post_content . '</p>';
				$output .= '</div>';
			}
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';

			return $output;
		}



		/**
		 * cbxwpreadymix_brand_content_view
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		static function cbxwpreadymix_brand_content_view( $atts ) {

			$atts = wp_parse_args( $atts, array(
				'column' => 4,
				'count'  => 10,  //count
				'order'         => 'DESC',
				'orderby'       => 'date',
			) );

			$brands = get_posts( array(
				'post_type'      => 'cbxbrand',
				'post_status'    => 'publish',
				'posts_per_page' => $atts['count'],
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby']
			) );

			$output = '<div class="cbxwpreadymixbootstrap cbxwpreadymix_brand_wrapper">';

			$output .= '<div class="row">';
			foreach ( $brands as $brand ) {
				$brand_meta = get_post_meta( $brand->ID, '_cbxrmbrandmeta', true );

				$output .= '<div class="cbxwpreadymix_brand col-md-' . esc_attr( 12 / $atts['column'] ) . '">';
				$output .= '<a href="'.esc_url($brand_meta['ext_url']).'"><img class="img-responsive" src="' . esc_url( get_the_post_thumbnail_url( $brand->ID, 'small' ) ) . '" alt="' . esc_attr( $brand->post_title ) . '"/></a>';
				$output .= '</div>';
			}
			$output .= '</div>';

			$output .= '</div>';

			return $output;
		}

		/**
		 * cbxwpreadymix_portfolio_content_view
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		static function cbxwpreadymix_portfolio_content_view( $atts ) {

			$atts = wp_parse_args( $atts, array(
				'column'        => 4,
				'count'         => - 1,
				'category_view' => 'show',
				'category'      => '',
				'order'         => 'DESC',
				'orderby'       => 'date',
			) );

			$args = array(
				'post_type'      => 'cbxportfolio',
				'post_status'    => 'publish',
				'posts_per_page' => $atts['count'],
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby'],
			);

			if ( ! empty( $atts['category'] ) && count( $atts['category'] ) > 0 ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'cbxportfoliocat',
						'field'    => 'slug',
						'terms'    => explode( ",", $atts['category'] ),
					),

				);
			}

			$portfolios = get_posts( $args );

			$portfolio_terms = get_terms( array(
				'taxonomy'   => 'cbxportfoliocat',
				'hide_empty' => false,
			) );

			$output = '<div class="cbxwpreadymixbootstrap cbxwpreadymix_portfolio_wrapper">';

			if ( $atts['category_view'] == 'show' ) {
				$output .= '<div class="row">';
				$output .= '<div class="cbxwpreadymix_portfolio_filter">';
				if ( ! empty( $portfolio_terms ) ) {
					$output .= '<button data-filter="*" class="active">' . esc_html__( 'All', 'cbxwpreadymix' ) . '</button>';
					foreach ( $portfolio_terms as $portfolio_term ) {

						$output .= '<button data-filter=".' . esc_attr( $portfolio_term->slug ) . '">' . esc_html( $portfolio_term->name ) . '</button>';

					}
				}
				$output .= '</div>';
				$output .= '</div>';
			}


			$output .= '<div class="row">';
			$output .= '<div class="cbxwpreadymix_portfolio_container">';
			foreach ( $portfolios as $portfolio ) {
				$portfolio_meta = get_post_meta( $portfolio->ID, '_cbxrmportfoliometa', true );
				$categories     = get_the_terms( $portfolio->ID, 'cbxportfoliocat' );

				$temp_cat = '';
				foreach ( $categories as $category ) {
					$temp_cat .= $category->slug . ' ';
				}

				$output .= '<div class="cbxwpreadymix_portfolio ' . esc_attr( $temp_cat ) . ' col-md-' . esc_attr( 12 / $atts['column'] ) . '">';
				$output .= '<div class="cbxwpreadymix_portfolio_thumb">';
				$output .= '<img class="img-responsive" src="' . esc_url( get_the_post_thumbnail_url( $portfolio->ID, 'small' ) ) . '" alt="' . esc_attr( $portfolio->post_title ) . '"/>';
				$output .= '<div class="cbxwpreadymix_portfolio_content">';
				$output .= '<h5>' . esc_html( $portfolio->post_title ) . '</h5>';
				$output .= '<a href="' . get_the_permalink( $portfolio->ID ) . '">' . esc_html__( 'View', 'cbxwpreadymix' ) . '</a>';
				$output .= '</div>';
				$output .= '</div>';
				$output .= '</div>';
			}
			$output .= '</div>';

			$output .= '</div>';

			$output .= '</div>';

			return $output;
		}
	}