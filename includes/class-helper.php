<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Wpcpu_Helper' ) ) {
	class Wpcpu_Helper {
		protected static $instance = null;

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public static function sanitize_array( $arr ) {
			foreach ( (array) $arr as $k => $v ) {
				if ( is_array( $v ) ) {
					$arr[ $k ] = self::sanitize_array( $v );
				} else {
					$arr[ $k ] = sanitize_text_field( $v );
				}
			}

			return $arr;
		}

		public static function generate_key() {
			$key         = '';
			$key_str     = apply_filters( 'wpcpu_key_characters', 'abcdefghijklmnopqrstuvwxyz0123456789' );
			$key_str_len = strlen( $key_str );

			for ( $i = 0; $i < apply_filters( 'wpcpu_key_length', 4 ); $i ++ ) {
				$key .= $key_str[ random_int( 0, $key_str_len - 1 ) ];
			}

			if ( is_numeric( $key ) ) {
				$key = self::generate_key();
			}

			return apply_filters( 'wpcpu_generate_key', $key );
		}

		public static function get_current_roles() {
			if ( isset( $_REQUEST['action'], $_REQUEST['order_id'] ) && ( $_REQUEST['action'] == 'woocommerce_add_order_item' ) ) {
				$order = wc_get_order( $_REQUEST['order_id'] );

				if ( $customer = $order->get_user() ) {
					return $customer->roles;
				}
			}

			if ( is_user_logged_in() ) {
				$user = wp_get_current_user();

				return $user->roles;
			}

			return [];
		}

		public static function get_price_role( $product ) {
			$product_id = $product->get_id();

			if ( $product->is_type( 'variation' ) ) {
				$product_id = $product->get_parent_id();
			}

			$enable     = get_post_meta( $product_id, 'wpcpu_enable', true ) ?: 'global';
			$roles      = self::get_current_roles();
			$prices     = [];
			$price_role = [];

			if ( $enable === 'global' ) {
				$prices = (array) get_option( 'wpcpu_prices', [] );
			}

			if ( $enable === 'override' ) {
				$prices = get_post_meta( $product_id, 'wpcpu_prices', true ) ?: [];
			}

			if ( ! empty( $prices ) ) {
				foreach ( $prices as $key => $price ) {
					if ( ! empty( $price ) ) {
						// check apply
						$apply     = ! empty( $price['apply'] ) ? $price['apply'] : 'all';
						$apply_val = ! empty( $price['apply_val'] ) ? (array) $price['apply_val'] : [];

						if ( $apply !== 'all' && ! has_term( $apply_val, $apply, $product_id ) ) {
							// doesn't apply for current product
							continue;
						}

						// check role
						if ( ! empty( $price['role'] ) ) {
							$role = $price['role'];
						} else {
							if ( ! str_contains( $key, '__' ) ) {
								$role = $key;
							} else {
								$key_arr = explode( '__', $key );
								$role    = ! empty( $key_arr[1] ) ? $key_arr[1] : 'guest';
							}
						}

						if ( in_array( $role, $roles ) || ( ( $role === 'guest' ) && empty( $roles ) ) ) {
							$price_role = $price;
							break;
						}
					}
				}
			}

			return apply_filters( 'wpcpu_get_price_role', $price_role, $product );
		}

		public static function format_price( $price, $current_price ) {
			preg_match( '/[\d+\.{0,1}%{0,1}]+/', $price, $matches, PREG_OFFSET_CAPTURE, 0 );

			if ( count( $matches ) > 0 ) {
				$price = $matches[0][0];
			}

			if ( preg_match( '/%$/', $price ) ) {
				$price = floatval( $current_price ) * floatval( preg_replace( '/%$/', '', $price ) ) / 100;
			}

			return (float) $price;
		}
	}

	function Wpcpu_Helper() {
		return Wpcpu_Helper::instance();
	}

	Wpcpu_Helper();
}