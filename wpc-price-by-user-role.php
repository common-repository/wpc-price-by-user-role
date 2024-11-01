<?php
/*
Plugin Name: WPC Price by User Role for WooCommerce
Plugin URI: https://wpclever.net/
Description: WPC Price by User Role helps you configure discounts and adjust prices in bulk based on user roles.
Version: 2.1.7
Author: WPClever
Author URI: https://wpclever.net
Text Domain: wpc-price-by-user-role
Domain Path: /languages/
Requires Plugins: woocommerce
Requires at least: 4.0
Tested up to: 6.7
WC requires at least: 3.0
WC tested up to: 9.3
*/

! defined( 'WPCPU_VERSION' ) && define( 'WPCPU_VERSION', '2.1.7' );
! defined( 'WPCPU_LITE' ) && define( 'WPCPU_LITE', __FILE__ );
! defined( 'WPCPU_FILE' ) && define( 'WPCPU_FILE', __FILE__ );
! defined( 'WPCPU_DIR' ) && define( 'WPCPU_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'WPCPU_URI' ) && define( 'WPCPU_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WPCPU_SUPPORT' ) && define( 'WPCPU_SUPPORT', 'https://wpclever.net/support?utm_source=support&utm_medium=wpcpu&utm_campaign=wporg' );
! defined( 'WPCPU_REVIEWS' ) && define( 'WPCPU_REVIEWS', 'https://wordpress.org/support/plugin/wpc-price-by-user-role/reviews/?filter=5' );
! defined( 'WPCPU_CHANGELOG' ) && define( 'WPCPU_CHANGELOG', 'https://wordpress.org/plugins/wpc-price-by-user-role/#developers' );
! defined( 'WPCPU_DISCUSSION' ) && define( 'WPCPU_DISCUSSION', 'https://wordpress.org/support/plugin/wpc-price-by-user-role' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WPCPU_URI );

include 'includes/dashboard/wpc-dashboard.php';
include 'includes/kit/wpc-kit.php';
include 'includes/hpos.php';

if ( ! function_exists( 'wpcpu_init' ) ) {
	add_action( 'plugins_loaded', 'wpcpu_init', 11 );

	function wpcpu_init() {
		load_plugin_textdomain( 'wpc-price-by-user-role', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'wpcpu_notice_wc' );

			return null;
		}

		if ( ! class_exists( 'WPCleverWpcpu' ) && class_exists( 'WC_Product' ) ) {
			class WPCleverWpcpu {
				public function __construct() {
					require_once trailingslashit( WPCPU_DIR ) . 'includes/class-helper.php';
					require_once trailingslashit( WPCPU_DIR ) . 'includes/class-backend.php';
					require_once trailingslashit( WPCPU_DIR ) . 'includes/class-frontend.php';
				}
			}

			new WPCleverWpcpu();
		}
	}
}

if ( ! function_exists( 'wpcpu_notice_wc' ) ) {
	function wpcpu_notice_wc() {
		?>
        <div class="error">
            <p><strong>WPC Price by User Role</strong> requires WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}
