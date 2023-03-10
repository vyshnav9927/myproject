<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-(style|script)-single-row-hardcoded.php
*/
if (! isset($data)) {
	exit; // no direct access
}

$assetType = $data['row']['asset_type'];

if (isset($data['bulk_unloaded_type']) && $data['bulk_unloaded_type'] === 'post_type') {
    if ($assetType === 'styles') {
	?>
    <div class="wpacu_asset_options_wrap">
        <ul class="wpacu_asset_options">
			<?php
			switch ($data['post_type']) {
				case 'product':
					$unloadBulkText = __('Unload CSS on all WooCommerce "Product" pages', 'wp-asset-clean-up');
					break;
				case 'download':
					$unloadBulkText = __('Unload CSS on all Easy Digital Downloads "Download" pages', 'wp-asset-clean-up');
					break;
				default:
					$unloadBulkText = sprintf(__('Unload CSS on all pages of "<strong>%s</strong>" post type', 'wp-asset-clean-up'), $data['post_type']);
			}
			?>
            <li>
                <label class="wpacu-manage-hardcoded-assets-requires-pro-popup">
                    <span style="color: #ccc;" class="wpacu-manage-hardcoded-assets-requires-pro-popup dashicons dashicons-lock"></span>
					<?php echo wp_kses($unloadBulkText, array('strong' => array(), 'em' => array(), 'u' => array())); ?></label>
            </li>
        </ul>
    </div>
	<?php
    } elseif ($assetType === 'scripts') {
        ?>
        <div class="wpacu_asset_options_wrap">
            <ul class="wpacu_asset_options">
			    <?php
			    switch ($data['post_type']) {
				    case 'product':
					    $unloadBulkText = __('Unload JS on all WooCommerce "Product" pages', 'wp-asset-clean-up');
					    break;
				    case 'download':
					    $unloadBulkText = __('Unload JS on all Easy Digital Downloads "Download" pages', 'wp-asset-clean-up');
					    break;
				    default:
					    $unloadBulkText = sprintf(__('Unload JS on all pages of "<strong>%s</strong>" post type', 'wp-asset-clean-up'), $data['post_type']);
			    }
			    ?>
                <li>
                    <label class="wpacu-manage-hardcoded-assets-requires-pro-popup">
                        <span style="color: #ccc;" class="wpacu-manage-hardcoded-assets-requires-pro-popup dashicons dashicons-lock"></span>
					    <?php echo wp_kses($unloadBulkText, array('strong' => array())); ?></label>
                </li>
            </ul>
        </div>
        <?php
    }
}
