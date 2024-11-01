<?php
defined( 'ABSPATH' ) || exit;

$active_tab = sanitize_key( $_GET['tab'] ?? 'settings' );
$prices     = get_option( 'wpcpu_prices', [] );
?>
<div class="wpclever_settings_page wrap">
    <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Price by User Role', 'wpc-price-by-user-role' ) . ' ' . esc_html( WPCPU_VERSION ) . ' ' . ( defined( 'WPCPU_PREMIUM' ) ? '<span class="premium" style="display: none">' . esc_html__( 'Premium', 'wpc-price-by-user-role' ) . '</span>' : '' ); ?></h1>
    <div class="wpclever_settings_page_desc about-text">
        <p>
			<?php printf( /* translators: stars */ esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'wpc-price-by-user-role' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
            <br/>
            <a href="<?php echo esc_url( WPCPU_REVIEWS ); ?>" target="_blank"><?php esc_html_e( 'Reviews', 'wpc-price-by-user-role' ); ?></a> |
            <a href="<?php echo esc_url( WPCPU_CHANGELOG ); ?>" target="_blank"><?php esc_html_e( 'Changelog', 'wpc-price-by-user-role' ); ?></a> |
            <a href="<?php echo esc_url( WPCPU_DISCUSSION ); ?>" target="_blank"><?php esc_html_e( 'Discussion', 'wpc-price-by-user-role' ); ?></a>
        </p>
    </div>
	<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e( 'Settings updated.', 'wpc-price-by-user-role' ); ?></p>
        </div>
	<?php } ?>
    <div class="wpclever_settings_page_nav">
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-wpcpu&tab=settings' ) ); ?>" class="<?php echo esc_attr( $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
				<?php esc_html_e( 'Settings', 'wpc-price-by-user-role' ); ?>
            </a>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-wpcpu&tab=premium' ) ); ?>" class="<?php echo esc_attr( $active_tab === 'premium' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>" style="color: #c9356e">
				<?php esc_html_e( 'Premium Version', 'wpc-price-by-user-role' ); ?>
            </a> <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-kit' ) ); ?>" class="nav-tab">
				<?php esc_html_e( 'Essential Kit', 'wpc-price-by-user-role' ); ?>
            </a>
        </h2>
    </div>
    <div class="wpclever_settings_page_content">
		<?php if ( $active_tab === 'settings' ) { ?>
            <form method="post" action="options.php">
                <table class="form-table">
                    <tr>
                        <td colspan="2">
                            <div class="wpcpu-items-wrapper">
                                <div class="wpcpu-items wpcpu-roles">
									<?php
									$i = 0;

									foreach ( $prices as $key => $price ) {
										$active = $i === 0;
										include WPCPU_DIR . 'includes/templates/role-price.php';
										$i ++;
									}
									?>
                                </div>
                            </div>
							<?php include WPCPU_DIR . 'includes/templates/add-new.php'; ?>
                        </td>
                    </tr>
                    <tr class="submit">
                        <th colspan="2">
							<?php settings_fields( 'wpcpu_settings' ); ?><?php submit_button(); ?>
                        </th>
                    </tr>
                </table>
            </form>
		<?php } elseif ( $active_tab == 'premium' ) { ?>
            <div class="wpclever_settings_page_content_text">
                <p>Get the Premium Version just $29!
                    <a href="https://wpclever.net/downloads/wpc-price-by-user-role?utm_source=pro&utm_medium=wpcpu&utm_campaign=wporg" target="_blank">https://wpclever.net/downloads/wpc-price-by-user-role</a>
                </p>
                <p><strong>Extra features for Premium Version:</strong></p>
                <ul style="margin-bottom: 0">
                    <li>- Setup price on a product basis.</li>
                    <li>- Get the lifetime update & premium support.</li>
                </ul>
            </div>
		<?php } ?>
    </div><!-- /.wpclever_settings_page_content -->
    <div class="wpclever_settings_page_suggestion">
        <div class="wpclever_settings_page_suggestion_label">
            <span class="dashicons dashicons-yes-alt"></span> Suggestion
        </div>
        <div class="wpclever_settings_page_suggestion_content">
            <div>
                To display custom engaging real-time messages on any wished positions, please install
                <a href="https://wordpress.org/plugins/wpc-smart-messages/" target="_blank">WPC Smart Messages</a> plugin. It's free!
            </div>
            <div>
                Wanna save your precious time working on variations? Try our brand-new free plugin
                <a href="https://wordpress.org/plugins/wpc-variation-bulk-editor/" target="_blank">WPC Variation Bulk Editor</a> and
                <a href="https://wordpress.org/plugins/wpc-variation-duplicator/" target="_blank">WPC Variation Duplicator</a>.
            </div>
        </div>
    </div>
</div>
