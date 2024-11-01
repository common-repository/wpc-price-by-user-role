<?php
defined( 'ABSPATH' ) || exit;

global $wp_roles;
?>
<div class="wpcpu-items-new">
    <select id="wpcpu-item-new-role">
        <option value="guest"><?php esc_html_e( 'Guest (not logged in)', 'wpc-price-by-user-role' ); ?></option>
		<?php foreach ( $wp_roles->roles as $role => $details ) {
			echo '<option value="' . esc_attr( $role ) . '">' . esc_html( $details['name'] ) . '</option>';
		} ?>
    </select>
    <input type="button" class="button wpcpu-item-new" value="<?php esc_attr_e( '+ Setup for role', 'wpc-price-by-user-role' ); ?>">
</div>
