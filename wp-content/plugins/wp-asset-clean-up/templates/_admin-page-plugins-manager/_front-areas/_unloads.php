<?php
if (! isset($data)) {
	exit;
}
?>
<div data-wpacu-plugin-path="<?php echo esc_attr($data['plugin_path']); ?>"
     class="wpacu_plugin_unload_rules_options_wrap">
	<div class="wpacu_plugin_rules_wrap">
		<fieldset>
			<legend><strong>Unload this plugin</strong> in the front-end:</legend>
			<ul class="wpacu_plugin_rules">
				<li>
					<label for="wpacu_global_unload_plugin_<?php echo esc_attr($data['plugin_path']); ?>">
						<input data-wpacu-plugin-path="<?php echo esc_attr($data['plugin_path']); ?>"
						       style="margin-right: 0;"
						       disabled="disabled"
						       class="disabled wpacu_plugin_unload_site_wide wpacu_plugin_unload_rule_input"
						       id="wpacu_global_unload_plugin_<?php echo esc_attr($data['plugin_path']); ?>"
						       type="checkbox"
						       value="unload_site_wide" />
						<a class="go-pro-link-no-style"
						   href="<?php echo apply_filters('wpacu_go_pro_affiliate_link', WPACU_PLUGIN_GO_PRO_URL . '?utm_source=manage_asset&utm_medium=unload_plugin_site_wide'); ?>"><span class="wpacu-tooltip" style="width: 200px; margin-left: -108px;">This feature is locked for Pro users<br />Click here to upgrade!</span><img width="20" height="20" style="vertical-align: text-bottom;" src="<?php echo esc_url(WPACU_PLUGIN_URL); ?>/assets/icons/icon-lock.svg" alt="" /></a>&nbsp;
						<span>On all pages</span></label>
				</li>
				<li>
					<label for="wpacu_home_page_unload_plugin_<?php echo esc_attr($data['plugin_path']); ?>">
					    <input data-wpacu-plugin-path="<?php echo esc_attr($data['plugin_path']); ?>"
                               style="margin-right: 0;"
                               disabled="disabled"
                               class="disabled wpacu_plugin_unload_home_page wpacu_plugin_unload_rule_input"
                               id="wpacu_home_page_unload_plugin_<?php echo esc_attr($data['plugin_path']); ?>"
                               type="checkbox"
                               name="wpacu_plugins[<?php echo esc_attr($data['plugin_path']); ?>][status][]"
                               value="unload_home_page" />
                        <a class="go-pro-link-no-style"
                           href="<?php echo apply_filters('wpacu_go_pro_affiliate_link', WPACU_PLUGIN_GO_PRO_URL . '?utm_source=manage_asset&utm_medium=unload_plugin_on_home_page'); ?>"><span class="wpacu-tooltip" style="width: 200px; margin-left: -108px;">This feature is locked for Pro users<br />Click here to upgrade!</span><img width="20" height="20" style="vertical-align: text-bottom;" src="<?php echo esc_url(WPACU_PLUGIN_URL); ?>/assets/icons/icon-lock.svg" alt="" /></a>&nbsp;
					<span>On the homepage</span></label>
				</li>
                <li>
                    <label for="wpacu_via_post_type_unload_plugin_<?php echo esc_attr($data['plugin_path']); ?>">
                        <input data-wpacu-plugin-path="<?php echo esc_attr($data['plugin_path']); ?>"
                               style="margin-right: 0;"
                               disabled="disabled"
                               class="disabled wpacu_plugin_unload_via_post_type wpacu_plugin_unload_rule_input"
                               id="wpacu_via_post_type_unload_plugin_<?php echo esc_attr($data['plugin_path']); ?>"
                               type="checkbox"
                               name="wpacu_plugins[<?php echo esc_attr($data['plugin_path']); ?>][status][]"
                               value="unload_via_post_type" />
                        <a class="go-pro-link-no-style"
                           href="<?php echo apply_filters('wpacu_go_pro_affiliate_link', WPACU_PLUGIN_GO_PRO_URL . '?utm_source=manage_asset&utm_medium=unload_plugin_on_pages_of_post_types'); ?>"><span class="wpacu-tooltip" style="width: 200px; margin-left: -108px;">This feature is locked for Pro users<br />Click here to upgrade!</span><img width="20" height="20" style="vertical-align: text-bottom;" src="<?php echo esc_url(WPACU_PLUGIN_URL); ?>/assets/icons/icon-lock.svg" alt="" /></a>&nbsp;
                        <span>On pages of the following post types:</span>
                    </label>
                </li>
                <li>
                    <label for="wpacu_via_page_type_unload_plugin_<?php echo esc_attr($data['plugin_path']); ?>">
                        <input data-wpacu-plugin-path="<?php echo esc_attr($data['plugin_path']); ?>"
                               style="margin-right: 0;"
                               disabled="disabled"
                               class="disabled wpacu_plugin_unload_via_tax wpacu_plugin_unload_rule_input"
                               id="wpacu_via_page_type_unload_plugin_<?php echo esc_attr($data['plugin_path']); ?>"
                               type="checkbox"
                               name="wpacu_plugins[<?php echo esc_attr($data['plugin_path']); ?>][status][]"
                               value="unload_via_tax" />
                        <a class="go-pro-link-no-style"
                           href="<?php echo apply_filters('wpacu_go_pro_affiliate_link', WPACU_PLUGIN_GO_PRO_URL . '?utm_source=manage_asset&utm_medium=unload_plugin_on_taxonomy_pages'); ?>"><span class="wpacu-tooltip" style="width: 200px; margin-left: -108px;">This feature is locked for Pro users<br />Click here to upgrade!</span><img width="20" height="20" style="vertical-align: text-bottom;" src="<?php echo esc_url(WPACU_PLUGIN_URL); ?>/assets/icons/icon-lock.svg" alt="" /></a>&nbsp;
                        <span>On the following taxonomy pages:</span></label>
                </li>
				<li>
					<label for="wpacu_unload_it_regex_option_<?php echo esc_attr($data['plugin_path']); ?>"
					       style="margin-right: 0;">
						<input data-wpacu-plugin-path="<?php echo esc_attr($data['plugin_path']); ?>"
						       style="margin-right: 0;"
						       disabled="disabled"
						       id="wpacu_unload_it_regex_option_<?php echo esc_attr($data['plugin_path']); ?>"
						       class="disabled wpacu_plugin_unload_regex_radio wpacu_plugin_unload_rule_input"
						       type="checkbox"
						       value="unload_via_regex">
						<a class="go-pro-link-no-style"
						   href="<?php echo apply_filters('wpacu_go_pro_affiliate_link', WPACU_PLUGIN_GO_PRO_URL . '?utm_source=manage_asset&utm_medium=unload_plugin_via_regex'); ?>"><span class="wpacu-tooltip" style="width: 200px; margin-left: -108px;">This feature is locked for Pro users<br />Click here to upgrade!</span><img width="20" height="20" style="vertical-align: text-bottom;" src="<?php echo esc_url(WPACU_PLUGIN_URL); ?>/assets/icons/icon-lock.svg" alt="" /></a>&nbsp;
                        <span>If request URI matches these RegEx(es):</span>
                    </label>
					<a class="help_link unload_it_regex"
					   target="_blank"
					   href="https://assetcleanup.com/docs/?p=372#wpacu-unload-plugins-via-regex"><span style="color: #74777b;" class="dashicons dashicons-editor-help"></span></a>
				</li>
				<li>
					<label for="wpacu_unload_it_logged_in_plugin_<?php echo esc_attr($data['plugin_path']); ?>" style="margin-right: 0;">
						<input data-wpacu-plugin-path="<?php echo esc_attr($data['plugin_path']); ?>"
						       style="margin-right: 0;"
						       disabled="disabled"
						       id="wpacu_unload_it_logged_in_plugin_<?php echo esc_attr($data['plugin_path']); ?>"
						       class="disabled wpacu_plugin_unload_logged_in"
						       type="checkbox"
						       name="wpacu_plugins[<?php echo esc_attr($data['plugin_path']); ?>][status][]"
						       value="unload_logged_in" />
						<a class="go-pro-link-no-style"
						   href="<?php echo apply_filters('wpacu_go_pro_affiliate_link', WPACU_PLUGIN_GO_PRO_URL . '?utm_source=manage_asset&utm_medium=unload_plugin_if_logged_in'); ?>"><span class="wpacu-tooltip" style="width: 200px; margin-left: -132px;">This feature is locked for Pro users<br />Click here to upgrade!</span><img width="20" height="20" style="vertical-align: text-bottom;" src="<?php echo esc_url(WPACU_PLUGIN_URL); ?>/assets/icons/icon-lock.svg" alt="" /></a>&nbsp;
					    <span>If the user is logged in</span>
                    </label>
				</li>
			</ul>
			<div class="wpacu-clearfix"></div>
		</fieldset>
	</div>
</div>