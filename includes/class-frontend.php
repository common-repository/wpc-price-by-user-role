<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Wpcpu_Frontend' ) ) {
	class Wpcpu_Frontend {
		protected static $instance = null;

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			add_filter( 'woocommerce_product_get_regular_price', [ $this, 'get_regular_price' ], 998, 2 );
			add_filter( 'woocommerce_product_get_sale_price', [ $this, 'get_sale_price' ], 998, 2 );
			add_filter( 'woocommerce_product_get_price', [ $this, 'get_price' ], 998, 2 );

			// Variation
			add_filter( 'woocommerce_product_variation_get_regular_price', [ $this, 'get_regular_price' ], 998, 2 );
			add_filter( 'woocommerce_product_variation_get_sale_price', [ $this, 'get_sale_price' ], 998, 2 );
			add_filter( 'woocommerce_product_variation_get_price', [ $this, 'get_price' ], 998, 2 );
			add_filter( 'woocommerce_variation_prices_regular_price', [ $this, 'get_regular_price' ], 998, 2 );
			add_filter( 'woocommerce_variation_prices_sale_price', [ $this, 'get_sale_price' ], 998, 2 );
			add_filter( 'woocommerce_variation_prices_price', [ $this, 'get_price' ], 998, 2 );
			add_filter( 'woocommerce_get_variation_prices_hash', [ $this, 'variation_prices_hash' ], 998 );

			// Add to cart link
			add_filter( 'woocommerce_get_price_html', [ $this, 'get_price_html' ], 998, 2 );
			add_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'loop_add_to_cart_link' ], 998, 2 );
		}

		public static function get_regular_price( $regular_price, $product ) {
			if ( apply_filters( 'wpcpu_ignore', false, $product, 'regular_price' ) ) {
				return $regular_price;
			}

			$price_role = Wpcpu_Helper()::get_price_role( $product );

			if ( ! empty( $price_role ) && ! empty( $price_role['hide_price'] ) ) {
				return '';
			}

			if ( ! empty( $price_role ) && ( $price_role['regular'] !== '' ) && ( $price_role['regular'] !== '100%' ) ) {
				$regular_price = Wpcpu_Helper()::format_price( $price_role['regular'], $regular_price );
			}

			return apply_filters( 'wpcpu_get_regular_price', $regular_price, $product );
		}

		public static function get_sale_price( $sale_price, $product ) {
			if ( apply_filters( 'wpcpu_ignore', false, $product, 'sale_price' ) ) {
				return $sale_price;
			}

			$price_role = Wpcpu_Helper()::get_price_role( $product );

			if ( ! empty( $price_role ) && ! empty( $price_role['hide_price'] ) ) {
				return '';
			}

			if ( ! empty( $price_role ) && ( $price_role['sale'] !== '' ) && ( $price_role['sale'] !== '100%' ) ) {
				if ( $sale_price === '' ) {
					$sale_price = $product->get_regular_price( 'edit' );
				}

				$sale_price = Wpcpu_Helper()::format_price( $price_role['sale'], $sale_price );
			}

			return apply_filters( 'wpcpu_get_sale_price', $sale_price, $product );
		}

		public static function get_price( $price, $product ) {
			if ( apply_filters( 'wpcpu_ignore', false, $product, 'price' ) ) {
				return $price;
			}

			$price_role = Wpcpu_Helper()::get_price_role( $product );

			if ( ! empty( $price_role ) && ! empty( $price_role['hide_price'] ) ) {
				return '';
			}

			if ( ! empty( $price_role ) && ( $price_role['regular'] !== '' || $price_role['sale'] !== '' ) ) {
				if ( $product->get_sale_price() !== '' ) {
					$price = $product->get_sale_price();
				} else {
					$price = $product->get_regular_price();
				}
			}

			return apply_filters( 'wpcpu_get_price', $price, $product );
		}

		function variation_prices_hash( $hash ) {
			$hash[] = get_current_user_id();

			return $hash;
		}

		public static function get_price_html( $price_html, $product ) {
			if ( apply_filters( 'wpcpu_ignore', false, $product, 'price_html' ) ) {
				return $price_html;
			}

			$price_role = Wpcpu_Helper()::get_price_role( $product );

			if ( ! empty( $price_role ) && ! empty( $price_role['hide_price'] ) ) {
				if ( ! empty( $price_role['price_text'] ) ) {
					return esc_html( trim( $price_role['price_text'] ) );
				} else {
					return '';
				}
			}

			return $price_html;
		}

		public static function loop_add_to_cart_link( $link, $product ) {
			if ( apply_filters( 'wpcpu_ignore', false, $product, 'add_to_cart_link' ) ) {
				return $link;
			}

			$price_role = Wpcpu_Helper()::get_price_role( $product );

			if ( ! empty( $price_role ) && ! empty( $price_role['hide_price'] ) ) {
				return '';
			}

			return $link;
		}
	}

	function Wpcpu_Frontend() {
		return Wpcpu_Frontend::instance();
	}

	Wpcpu_Frontend();
}