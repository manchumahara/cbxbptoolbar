<?php

	/**
	 * The public-facing functionality of the plugin.
	 *
	 * @link       https://codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    CBXBPToolbar
	 * @subpackage CBXBPToolbar/public
	 */

	/**
	 * The public-facing functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the public-facing stylesheet and JavaScript.
	 *
	 * @package    CBXBPToolbar
	 * @subpackage CBXBPToolbar/public
	 * @author     Codeboxr <info@codeboxr.com>
	 */
	class CBXBPToolbar_Public {

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
			wp_register_style( 'cbxbptoolbar-public', plugin_dir_url( __FILE__ ) . '../assets/css/cbxbptoolbar-public.css', array(), $this->version, 'all' );

		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
			wp_register_script( 'cbxbptoolbar-public', plugin_dir_url( __FILE__ ) . 'js/cbxbptoolbar-public.js', array( 'jquery' ), $this->version, true );
		}

		/**
		 * add global toolbar nav
		 *
		 * @param $retval
		 *
		 * @return string
		 */
		public function display_bp_toolbar_wrapper( $retval ) {

			wp_enqueue_style( 'cbxbptoolbar-public' );

			$template_pack = bp_get_option( '_bp_theme_package_id', 'legacy' );
			if ( $template_pack == 'nouveau' ) {
				if ( false !== strpos( $retval, '<nav class="bp-navs cbxbptoolbar-navs' ) ) {
					return $retval;
				}
			} else {
				if ( false !== strpos( $retval, '<div class="item-list-tabs cbxbptoolbar-navs' ) ) {
					return $retval;
				}
			}


			$nav_html = $this->global_bp_toolbar( $template_pack );

			// Add our 'buddypress' div wrapper.
			/*return sprintf(
				'<div id="buddypress" class="%1$s">%2$s</div><!-- #buddypress -->%3$s',
				esc_attr( bp_nouveau_get_container_classes() ),
				$retval,  // Constructed HTML.
				"\n"
			);*/

			return $nav_html . $retval;
		}

		/**
		 * This method displays the toolbar
		 *
		 * @param string $template_pack
		 *
		 * @return string
		 */
		public function global_bp_toolbar( $template_pack = 'legacy' ) {
			ob_start();
			$bp_pages = bp_core_get_directory_page_ids( 'all' );
			/*echo '<pre>';
			print_r($bp_pages);
			echo '</pre>';*/
			//var_dump($bp_pages);

			//bp_before_directory_activity - activity
			//bp_before_directory_blogs_page - blogs
			//bp_before_directory_groups_page - groups
			//bp_before_directory_members_page - members

			$nav_wrapper = ( $template_pack == 'nouveau' ) ? 'nav' : 'div';

			$show_toolbar      = true;
			$show_toolbar_home = true;
			//$show_toolbar_blogs = true;
			$show_toolbar_groups  = true;
			$show_toolbar_members = true;

			if ( $show_toolbar ) {
				do_action( 'cbxbptoolbar_nav_before' );
				echo '<' . $nav_wrapper . ' class="' . apply_filters( 'cbxbptoolbar_nav_wrapper_class', 'bp-navs cbxbptoolbar-navs' ) . '">';
				echo '<ul class="' . apply_filters( 'cbxbptoolbar_nav_ul_class', 'cbxbptoolbar-navs-ul' ) . '">';
				do_action( 'cbxbptoolbar_nav_ul_li_start' );
				echo '<li class="' . $this->isMenuSelected( intval( $bp_pages['activity'] ) ) . '"><a href="' . esc_url( get_permalink( intval( $bp_pages['activity'] ) ) ) . '">' . esc_html__( 'Home', 'cbxbptoolbar' ) . '</a></li>';
				echo '<li class="' . $this->isMenuSelected( intval( $bp_pages['groups'] ) ) . '"><a href="' . esc_url( get_permalink( intval( $bp_pages['groups'] ) ) ) . '">' . esc_html__( 'Groups', 'cbxbptoolbar' ) . '</a></li>';
				echo '<li class="' . $this->isMenuSelected( intval( $bp_pages['members'] ) ) . '"><a href="' . esc_url( get_permalink( intval( $bp_pages['members'] ) ) ) . '">' . esc_html__( 'Members', 'cbxbptoolbar' ) . '</a></li>';

				do_action( 'cbxbptoolbar_nav_ul_li_end' );
				if ( ! is_user_logged_in() ) {
					echo '<li class="' . $this->isMenuSelected( intval( $bp_pages['register'] ) ) . '"><a href="' . esc_url( get_permalink( intval( $bp_pages['register'] ) ) ) . '">' . esc_html__( 'Register', 'cbxbptoolbar' ) . '</a></li>';
				}
				echo '</ul>';
				echo '</' . $nav_wrapper . '>';
				do_action( 'cbxbptoolbar_nav_after' );
			}

			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		}//end method display_bp_toolbar

		/**
		 * If the current menu is active
		 *
		 * @param $page_id
		 *
		 * @return string
		 */
		private function isMenuSelected( $page_id ) {
			global $wp;
			$post      = get_post( $page_id );
			$post_name = $post->post_name;

			if ( is_page( $page_id ) ) {
				return 'selected';
			} else if ( preg_match( '#^' . $post_name . '(/.+)?$#', $wp->request ) ) {
				return 'selected';
			}

			return '';
		}

	}//end method CBXBPToolbar_Public
