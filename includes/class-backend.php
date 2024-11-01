<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Wpcpu_Backend' ) ) {
	class Wpcpu_Backend {
		protected static $instance = null;

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

			// Settings
			add_action( 'admin_init', [ $this, 'register_settings' ] );
			add_action( 'admin_menu', [ $this, 'admin_menu' ] );

			// Links
			add_filter( 'plugin_action_links', [ $this, 'action_links' ], 10, 2 );
			add_filter( 'plugin_row_meta', [ $this, 'row_meta' ], 10, 2 );

			// Single Product
			add_filter( 'woocommerce_product_data_tabs', [ $this, 'product_data_tabs' ] );
			add_action( 'woocommerce_product_data_panels', [ $this, 'product_data_panels' ] );
			add_action( 'woocommerce_process_product_meta', [ $this, 'process_product_meta' ] );

			// Product columns
			add_filter( 'manage_edit-product_columns', [ $this, 'product_columns' ] );
			add_action( 'manage_product_posts_custom_column', [ $this, 'custom_column' ], 10, 2 );

			// AJAX
			add_action( 'wp_ajax_wpcpu_add_role_price', [ $this, 'ajax_add_role_price' ] );
			add_action( 'wp_ajax_wpcpu_overview', [ $this, 'ajax_overview' ] );
			add_action( 'wp_ajax_wpcpu_search_term', [ $this, 'ajax_search_term' ] );

			// Export
			add_filter( 'woocommerce_product_export_meta_value', [ $this, 'export_process' ], 10, 3 );

			// Import
			add_filter( 'woocommerce_product_import_pre_insert_product_object', [ $this, 'import_process' ], 10, 2 );
		}

		public function product_data_tabs( $tabs ) {
			$tabs['wpcpu'] = [
				'label'  => esc_html__( 'Price by User Role', 'wpc-price-by-user-role' ),
				'target' => 'wpcpu_settings'
			];

			return $tabs;
		}

		public function product_data_panels() {
			global $post, $thepostid, $product_object;

			if ( $product_object instanceof WC_Product ) {
				$product_id = $product_object->get_id();
			} elseif ( is_numeric( $thepostid ) ) {
				$product_id = $thepostid;
			} elseif ( $post instanceof WP_Post ) {
				$product_id = $post->ID;
			} else {
				$product_id = 0;
			}

			if ( ! $product_id ) {
				?>
                <div id='wpcpu_settings' class='panel woocommerce_options_panel wpcpu_settings'>
                    <p style="padding: 0 12px; color: #c9356e"><?php esc_html_e( 'Product wasn\'t returned.', 'wpc-price-by-user-role' ); ?></p>
                </div>
				<?php
				return;
			}

			$enable = get_post_meta( $product_id, 'wpcpu_enable', true ) ?: 'global';
			?>
            <div id='wpcpu_settings' class='panel woocommerce_options_panel wpcpu_settings'>
                <div class="options_group">
                    <p class="form-field">
                        <label for="wpcpu-select-enable"><?php esc_html_e( 'Price by User Role', 'wpc-price-by-user-role' ); ?></label>
                        <select name="wpcpu_enable" id="wpcpu-select-enable">
                            <option value="global" <?php selected( $enable, 'global' ); ?>><?php esc_html_e( 'Global', 'wpc-price-by-user-role' ); ?></option>
                            <option value="disable" <?php selected( $enable, 'disable' ); ?>><?php esc_html_e( 'Disable', 'wpc-price-by-user-role' ); ?></option>
                            <option value="override" <?php selected( $enable, 'override' ); ?> disabled><?php esc_html_e( 'Override', 'wpc-price-by-user-role' ); ?></option>
                        </select>
                    </p>
                    <div style="color: #c9356e; padding-left: 12px; padding-right: 12px;">
                        Settings at a product basis only available on the Premium Version.
                        <a href="https://wpclever.net/downloads/wpc-price-by-user-role/?utm_source=pro&utm_medium=wpcpu&utm_campaign=wporg" target="_blank">Click here</a> to buy, just $29!
                    </div>
                </div>
            </div>
			<?php
		}

		public function process_product_meta( $post_id ) {
			if ( isset( $_POST['wpcpu_prices'] ) ) {
				update_post_meta( $post_id, 'wpcpu_prices', Wpcpu_Helper()::sanitize_array( $_POST['wpcpu_prices'] ) );
			}

			if ( isset( $_POST['wpcpu_enable'] ) ) {
				update_post_meta( $post_id, 'wpcpu_enable', sanitize_text_field( $_POST['wpcpu_enable'] ) );
			}
		}

		function register_settings() {
			// settings
			register_setting( 'wpcpu_settings', 'wpcpu_prices' );
		}

		public function admin_menu() {
			add_submenu_page( 'wpclever', 'WPC Price by User Role', 'Price by User Role', 'manage_options', 'wpclever-wpcpu', [
				$this,
				'admin_menu_content'
			] );
		}

		public function admin_menu_content() {
			include WPCPU_DIR . 'includes/templates/settings.php';
		}

		function action_links( $links, $file ) {
			static $plugin;

			if ( ! isset( $plugin ) ) {
				$plugin = plugin_basename( WPCPU_FILE );
			}

			if ( $plugin === $file ) {
				$settings             = '<a href="' . esc_url( admin_url( 'admin.php?page=wpclever-wpcpu&tab=settings' ) ) . '">' . esc_html__( 'Settings', 'wpc-price-by-user-role' ) . '</a>';
				$links['wpc-premium'] = '<a href="' . esc_url( admin_url( 'admin.php?page=wpclever-wpcpu&tab=premium' ) ) . '" style="color: #c9356e">' . esc_html__( 'Premium Version', 'wpc-price-by-user-role' ) . '</a>';
				array_unshift( $links, $settings );
			}

			return (array) $links;
		}

		function row_meta( $links, $file ) {
			static $plugin;

			if ( ! isset( $plugin ) ) {
				$plugin = plugin_basename( WPCPU_FILE );
			}

			if ( $plugin === $file ) {
				$row_meta = [
					'support' => '<a href="' . esc_url( WPCPU_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'wpc-price-by-user-role' ) . '</a>',
				];

				return array_merge( $links, $row_meta );
			}

			return (array) $links;
		}

		public function ajax_add_role_price() {
			$key        = sanitize_text_field( $_POST['role'] ?? 'guest' );
			$apply      = sanitize_text_field( $_POST['apply'] ?? 'all' );
			$apply_val  = Wpcpu_Helper()::sanitize_array( $_POST['apply_val'] ?? [] );
			$hide_price = sanitize_text_field( $_POST['hide_price'] ?? '' );
			$price_text = sanitize_text_field( $_POST['price_text'] ?? '' );
			$regular    = sanitize_text_field( $_POST['regular'] ?? '' );
			$sale       = sanitize_text_field( $_POST['sale'] ?? '' );

			if ( ! empty( $key ) ) {
				$active = true;
				$price  = [
					'role'       => $key,
					'apply'      => $apply,
					'apply_val'  => $apply_val,
					'hide_price' => $hide_price,
					'price_text' => $price_text,
					'regular'    => $regular,
					'sale'       => $sale
				];
				include WPCPU_DIR . 'includes/templates/role-price.php';
			}

			wp_die();
		}

		public function enqueue_scripts() {
			wp_enqueue_style( 'hint', WPCPU_URI . 'assets/css/hint.css' );
			wp_enqueue_style( 'wpcpu-backend', WPCPU_URI . 'assets/css/backend.css', [ 'woocommerce_admin_styles' ], WPCPU_VERSION );
			wp_enqueue_script( 'wpcpu-backend', WPCPU_URI . 'assets/js/backend.js', [
				'jquery',
				'jquery-ui-dialog',
				'jquery-ui-sortable',
				'wc-enhanced-select',
				'selectWoo'
			], WPCPU_VERSION, true );
		}

		function product_columns( $columns ) {
			$columns['wpcpu'] = esc_html__( 'Price by User Role', 'wpc-price-by-user-role' );

			return $columns;
		}

		function custom_column( $column, $postid ) {
			if ( $column === 'wpcpu' ) {
				$enable = get_post_meta( $postid, 'wpcpu_enable', true );

				if ( empty( $enable ) || $enable === 'global' ) {
					echo '<a href="#" class="wpcpu_overview" data-pid="' . esc_attr( $postid ) . '" data-name="' . esc_attr( get_the_title( $postid ) ) . '" data-type="global"><span class="dashicons dashicons-groups"></span></a>';
				}

				if ( $enable === 'disable' ) {
					echo '<span class="wpcpu_overview_disable"><span class="dashicons dashicons-groups"></span> ' . esc_html__( 'Disable', 'wpc-price-by-user-role' ) . '</span>';
				}

				if ( $enable === 'override' ) {
					echo '<a href="#" class="wpcpu_overview" data-pid="' . esc_attr( $postid ) . '" data-name="' . esc_attr( get_the_title( $postid ) ) . '" data-type="override"><span class="dashicons dashicons-groups"></span> ' . esc_html__( 'Override', 'wpc-price-by-user-role' ) . '</a>';
				}
			}
		}

		function ajax_overview() {
			$pid     = absint( sanitize_text_field( $_POST['pid'] ) );
			$type    = sanitize_text_field( $_POST['type'] );
			$product = wc_get_product( $pid );

			if ( $product ) {
				global $wp_roles;
				$prices                = [];
				$product_regular_price = $product->get_regular_price( 'edit' );
				$product_sale_price    = $product->get_sale_price( 'edit' );

				if ( empty( $product_sale_price ) ) {
					$product_sale_price = $product_regular_price;
				}

				echo '<ul>';

				if ( ! empty( $product_regular_price ) ) {
					echo '<li>' . esc_html__( 'Default price:', 'wpc-price-by-user-role' ) . ' ' . wc_format_sale_price( $product_regular_price, $product_sale_price ) . '</li>';
				} else {
					echo '<li>' . esc_html__( 'Default price:', 'wpc-price-by-user-role' ) . ' ' . esc_html__( 'Not set', 'wpc-price-by-user-role' ) . '</li>';
				}

				if ( $type === 'global' ) {
					$prices = (array) get_option( 'wpcpu_prices', [] );
				}

				if ( $type === 'override' && $pid ) {
					$prices = get_post_meta( $pid, 'wpcpu_prices', true ) ?: [];
				}

				if ( ! empty( $prices ) ) {
					foreach ( $prices as $key => $price ) {
						if ( ! str_contains( $key, '__' ) ) {
							$role = $key;
						} else {
							$key_arr = explode( '__', $key );
							$role    = ! empty( $key_arr[1] ) ? $key_arr[1] : 'guest';
						}

						$role_name = isset( $wp_roles->roles[ $role ] ) ? $wp_roles->roles[ $role ]['name'] : esc_html__( 'Guest (not logged in)', 'wpc-price-by-user-role' );
						$price     = array_merge( [
							'apply'      => 'all',
							'apply_val'  => [],
							'hide_price' => '',
							'price_text' => '',
							'regular'    => '',
							'sale'       => '',
						], $price );

						if ( empty( $price['regular'] ) ) {
							$price['regular'] = '100%';
						}

						if ( empty( $price['sale'] ) ) {
							$price['sale'] = '100%';
						}

						$regular_price = Wpcpu_Helper()::format_price( $price['regular'], $product_regular_price );
						$sale_price    = Wpcpu_Helper()::format_price( $price['sale'], $product_sale_price );

						echo '<li>';

						echo esc_html__( 'User role:', 'wpc-price-by-user-role' ) . ' <strong>' . $role_name . '</strong><br/>';

						if ( $type === 'global' ) {
							if ( $price['apply'] === 'all' ) {
								echo esc_html__( 'Apply for:', 'wpc-price-by-user-role' ) . ' ' . esc_html__( 'all products', 'wpc-price-by-user-role' ) . '<br/>';
							} else {
								echo esc_html__( 'Apply for:', 'wpc-price-by-user-role' ) . ' ' . $price['apply'] . ' (' . implode( ',', (array) $price['apply_val'] ) . ')<br/>';
							}
						}

						if ( ! empty( $price['hide_price'] ) ) {
							echo esc_html__( 'Price', 'wpc-price-by-user-role' ) . ': ' . esc_html__( 'Hide price', 'wpc-price-by-user-role' ) . ( ! empty( $price['price_text'] ) ? ' (' . esc_html( $price['price_text'] ) . ')' : '' );
						} else {
							echo esc_html__( 'Price', 'wpc-price-by-user-role' ) . ': ' . wc_format_sale_price( $regular_price, $sale_price );
						}

						echo '</li>';
					}
				}

				echo '</ul>';
			}

			wp_die();
		}

		function ajax_search_term() {
			$return = [];

			$args = [
				'taxonomy'   => sanitize_text_field( $_REQUEST['taxonomy'] ),
				'orderby'    => 'id',
				'order'      => 'ASC',
				'hide_empty' => false,
				'fields'     => 'all',
				'name__like' => sanitize_text_field( $_REQUEST['q'] ),
			];

			$terms = get_terms( $args );

			if ( count( $terms ) ) {
				foreach ( $terms as $term ) {
					$return[] = [ $term->slug, $term->name ];
				}
			}

			wp_send_json( $return );
		}

		function export_process( $value, $meta, $product ) {
			if ( $meta->key === 'wpcpu_prices' ) {
				$ids = get_post_meta( $product->get_id(), 'wpcpu_prices', true );

				if ( ! empty( $ids ) && is_array( $ids ) ) {
					return json_encode( $ids );
				}
			}

			return $value;
		}

		function import_process( $object, $data ) {
			if ( isset( $data['meta_data'] ) ) {
				foreach ( $data['meta_data'] as $meta ) {
					if ( $meta['key'] === 'wpcpu_prices' ) {
						$object->update_meta_data( 'wpcpu_prices', json_decode( $meta['value'], true ) );
						break;
					}
				}
			}

			return $object;
		}
	}

	function Wpcpu_Backend() {
		return Wpcpu_Backend::instance();
	}

	Wpcpu_Backend();
}