<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-(script|style)-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}

$assetType  = $data['row']['asset_type'];
$assetTypeS = substr($data['row']['asset_type'], 0, -1); // "styles" to "style" & "scripts" to "script"

// Only show it if "Unload site-wide" is NOT enabled
// Otherwise, there's no point to use an unload regex if the asset is unloaded site-wide
if (! $data['row']['global_unloaded']) {
?>
	<div data-<?php echo $assetTypeS; ?>-handle="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>" class="wpacu_asset_options_wrap wpacu_unload_regex_area_wrap">
		<ul class="wpacu_asset_options">
			<li>
				<label for="wpacu_unload_it_regex_option_<?php echo $assetTypeS; ?>_<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>">
					<input data-handle="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
					       data-handle-for="<?php echo $assetTypeS; ?>"
					       id="wpacu_unload_it_regex_option_<?php echo $assetTypeS; ?>_<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
					       class="wpacu_unload_it_regex_checkbox wpacu_unload_rule_input wpacu_bulk_unload"
					       type="checkbox"
					       name="wpacu_handle_unload_regex[<?php echo $assetType; ?>][<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>][enable]"
					       disabled="disabled"
                           value="1"/>&nbsp;<span>Unload it for URLs with request URI matching this RegEx(es):</span></label>

					<a class="go-pro-link-no-style"
					   href="<?php echo apply_filters('wpacu_go_pro_affiliate_link', WPACU_PLUGIN_GO_PRO_URL.'?utm_source=manage_asset&utm_medium=unload_'.$assetTypeS.'_by_regex'); ?>"><span
							class="wpacu-tooltip wpacu-larger" style="left: -26px;"><?php echo str_replace('the premium', 'the<br />premium', wp_kses(__('This feature is available in the premium version of the plugin.',
								'wp-asset-clean-up' ), array('br' => array()))); ?><br/> <?php _e( 'Click here to upgrade to Pro',
								'wp-asset-clean-up' ); ?>!</span><img width="20" height="20"
					                                                  src="<?php echo esc_url(WPACU_PLUGIN_URL); ?>/assets/icons/icon-lock.svg"
					                                                  valign="top" alt=""/></a>
				<a style="text-decoration: none; color: inherit; vertical-align: middle;" target="_blank"
				   href="https://assetcleanup.com/docs/?p=313#wpacu-unload-by-regex"><span
						class="dashicons dashicons-editor-help"></span></a>
			</li>
		</ul>
	</div>
	<?php
}
