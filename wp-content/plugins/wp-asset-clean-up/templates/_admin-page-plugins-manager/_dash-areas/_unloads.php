<?php
if (! isset($data)) {
	exit;
}
?>
<div data-wpacu-plugin-path="<?php echo esc_attr($data['plugin_path']); ?>"
     class="wpacu_plugin_unload_rules_options_wrap">
	<div class="wpacu_plugin_rules_wrap">
		<fieldset>
			<legend><strong>Unload this plugin</strong> within the Dashboard:</legend>
			<ul class="wpacu_plugin_rules">
				<li>
					<label for="wpacu_global_unload_plugin_<?php echo esc_attr($data['plugin_path']); ?>">
						<input data-wpacu-plugin-path="<?php echo esc_attr($data['plugin_path']); ?>"
						       disabled="disabled"
						       class="disabled wpacu_plugin_unload_site_wide wpacu_plugin_unload_rule_input"
						       id="wpacu_global_unload_plugin_<?php echo esc_attr($data['plugin_path']); ?>"
						       type="checkbox"
						       value="unload_site_wide" />
						<a class="go-pro-link-no-style"
						   target="_blank"
						   href="<?php echo apply_filters('wpacu_go_pro_affiliate_link', WPACU_PLUGIN_GO_PRO_URL . '?utm_source=manage_asset&utm_medium=unload_plugin_all_pages_in_admin'); ?>"><span class="wpacu-tooltip" style="width: 200px; margin-left: -108px;">This feature is locked for Pro users<br />Click here to upgrade!</span><img width="20" height="20" src="<?php echo esc_url(WPACU_PLUGIN_URL); ?>/assets/icons/icon-lock.svg" valign="top" alt="" /></a>&nbsp; Unload on all admin pages</label>
				</li>
				<li>
					<label for="wpacu_unload_it_regex_option_<?php echo esc_attr($data['plugin_path']); ?>"
					       style="margin-right: 0;">
						<input data-wpacu-plugin-path="<?php echo esc_attr($data['plugin_path']); ?>"
						       disabled="disabled"
						       id="wpacu_unload_it_regex_option_<?php echo esc_attr($data['plugin_path']); ?>"
						       class="disabled wpacu_plugin_unload_regex_option wpacu_plugin_unload_rule_input"
						       type="checkbox"
						       value="unload_via_regex">
						<a class="go-pro-link-no-style"
						   target="_blank"
						   href="<?php echo apply_filters('wpacu_go_pro_affiliate_link', WPACU_PLUGIN_GO_PRO_URL . '?utm_source=manage_asset&utm_medium=unload_plugin_via_regex_in_admin'); ?>"><span class="wpacu-tooltip" style="width: 200px; margin-left: -108px;">This feature is locked for Pro users<br />Click here to upgrade!</span><img width="20" height="20" src="<?php echo esc_url(WPACU_PLUGIN_URL); ?>/assets/icons/icon-lock.svg" valign="top" alt="" /></a> &nbsp;<span>Unload it for admin URLs with request URI matching the RegEx(es):</span></label>
					<a class="help_link unload_it_regex"
					   target="_blank"
					   href="https://assetcleanup.com/docs/?p=372#wpacu-unload-plugins-via-regex"><span style="color: #74777b;" class="dashicons dashicons-editor-help"></span></a>
				</li>
			</ul>
		</fieldset>
	</div>
	<div class="wpacu-clearfix"></div>
</div>
<!-- [End] Unload Rules -->