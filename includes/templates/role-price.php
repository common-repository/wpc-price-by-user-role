<?php
/**
 * @var $key
 * @var $price
 * @var $active
 */

defined( 'ABSPATH' ) || exit;

if ( strpos( $key, '__' ) === false ) {
	$role = $key;
	$key  = Wpcpu_Helper()::generate_key() . '__' . $role;
} else {
	$key_arr = explode( '__', $key );
	$role    = ! empty( $key_arr[1] ) ? $key_arr[1] : 'guest';
}

global $wp_roles;
$role_name = isset( $wp_roles->roles[ $role ] ) ? $wp_roles->roles[ $role ]['name'] : esc_html__( 'Guest (not logged in)', 'wpc-price-by-user-role' );

$price = array_merge( [
	'role'       => 'guest',
	'apply'      => 'all',
	'apply_val'  => [],
	'hide_price' => '',
	'price_text' => '',
	'regular'    => '',
	'sale'       => '',
], $price );
?>
<div class="<?php echo esc_attr( $active ? 'wpcpu-item active' : 'wpcpu-item' ); ?>">
    <div class="wpcpu-item-header">
        <span class="wpcpu-item-move ui-sortable-handle"><?php esc_html_e( 'move', 'wpc-price-by-user-role' ); ?></span>
        <span class="wpcpu-item-name"><span class="wpcpu-item-name-role"><?php echo esc_html( $role_name ); ?></span><span class="wpcpu-item-name-apply"><?php echo esc_html( $price['apply'] === 'all' ? 'all' : $price['apply'] . ': ' . implode( ',', (array) $price['apply_val'] ) ); ?></span></span>
        <span class="wpcpu-item-duplicate"><?php esc_html_e( 'duplicate', 'wpc-price-by-user-role' ); ?></span>
        <span class="wpcpu-item-remove" data-role-value="<?php echo esc_attr( $role ); ?>" data-role-name="<?php echo esc_attr( $role_name ); ?>"><?php esc_html_e( 'remove', 'wpc-price-by-user-role' ); ?></span>
    </div>
    <div class="wpcpu-item-content">
        <input type="hidden" name="wpcpu_prices[<?php echo esc_attr( $key ); ?>][role]" class="wpcpu_role" value="<?php echo esc_attr( $role ); ?>"/>
        <div class="wpcpu-item-line wpcpu-item-apply">
            <div class="wpcpu-item-label">
				<?php esc_html_e( 'Apply for', 'wpc-price-by-user-role' ); ?>
            </div>
            <div class="wpcpu-item-input">
                <select class="wpcpu_apply" name="wpcpu_prices[<?php echo esc_attr( $key ); ?>][apply]">
                    <option value="all" <?php selected( $price['apply'], 'all' ); ?>><?php esc_attr_e( 'All products', 'wpc-price-by-user-role' ); ?></option>
					<?php
					$taxonomies = get_object_taxonomies( 'product', 'objects' ); //$taxonomies = get_taxonomies( [ 'object_type' => [ 'product' ] ], 'objects' );

					foreach ( $taxonomies as $taxonomy ) {
						echo '<option value="' . $taxonomy->name . '" ' . selected( $price['apply'], $taxonomy->name, false ) . '>' . $taxonomy->label . '</option>';
					}
					?>
                </select>
                <div class="hide_if_apply_all">
                    <select class="wpcpu_terms wpcpu_apply_val" multiple="multiple" name="wpcpu_prices[<?php echo esc_attr( $key ); ?>][apply_val][]" data-<?php echo esc_attr( $price['apply'] ); ?>="<?php echo esc_attr( implode( ',', (array) $price['apply_val'] ) ); ?>">
						<?php if ( is_array( $price['apply_val'] ) && ! empty( $price['apply_val'] ) ) {
							foreach ( $price['apply_val'] as $t ) {
								if ( $term = get_term_by( 'slug', $t, $price['apply'] ) ) {
									echo '<option value="' . esc_attr( $t ) . '" selected>' . esc_html( $term->name ) . '</option>';
								}
							}
						} ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="wpcpu-item-line">
            <div class="wpcpu-item-label">
				<?php esc_html_e( 'Hide price', 'wpc-price-by-user-role' ); ?>
            </div>
            <div class="wpcpu-item-input">
                <input type="checkbox" value="1" class="wpcpu-hide-price wpcpu_hide_price" name="wpcpu_prices[<?php echo esc_attr( $key ); ?>][hide_price]" <?php checked( $price['hide_price'], '1' ); ?>/>
            </div>
        </div>
        <div class="wpcpu-item-line show-if-hide-price">
            <div class="wpcpu-item-label">
				<?php esc_html_e( 'Custom price text', 'wpc-price-by-user-role' ); ?>
            </div>
            <div class="wpcpu-item-input">
                <input type="text" value="<?php echo esc_attr( $price['price_text'] ); ?>" class="wpcpu_price_text" name="wpcpu_prices[<?php echo esc_attr( $key ); ?>][price_text]"/>
            </div>
        </div>
		<?php if ( $role !== 'guest' ) { ?>
            <div class="wpcpu-item-line hide-if-hide-price">
                <div class="wpcpu-item-label">
					<?php esc_html_e( 'Regular price', 'wpc-price-by-user-role' ); ?>
                </div>
                <div class="wpcpu-item-input">
                <span class="hint--right" aria-label="<?php esc_attr_e( 'Set a price using a number (eg. "10") or percentage (eg. "90%" of product regular price)', 'wpc-price-by-user-role' ); ?>">
                <input type="text" value="<?php echo esc_attr( $price['regular'] ); ?>" class="wpcpu_regular" name="wpcpu_prices[<?php echo esc_attr( $key ); ?>][regular]"/>
                </span>
                </div>
            </div>
            <div class="wpcpu-item-line hide-if-hide-price">
                <div class="wpcpu-item-label">
					<?php esc_html_e( 'Sale price', 'wpc-price-by-user-role' ); ?>
                </div>
                <div class="wpcpu-item-input">
                <span class="hint--right" aria-label="<?php esc_attr_e( 'Set a price using a number (eg. "10") or percentage (eg. "90%" of product sale price)', 'wpc-price-by-user-role' ); ?>">
                <input type="text" value="<?php echo esc_attr( $price['sale'] ); ?>" class="wpcpu_sale" name="wpcpu_prices[<?php echo esc_attr( $key ); ?>][sale]"/>
                </span>
                </div>
            </div>
		<?php } ?>
    </div>
</div>
