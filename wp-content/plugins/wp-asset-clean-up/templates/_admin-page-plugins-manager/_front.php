<?php
if (! isset($data)) {
	exit;
}
?>
<div class="wpacu-wrap" id="wpacu-plugins-load-manager-wrap">
		<?php
		$pluginsRows = array();

		foreach ($data['active_plugins'] as $pluginData) {
			$data['plugin_path'] = $pluginPath = $pluginData['path'];
			list($pluginDir) = explode('/', $pluginPath);
			ob_start();
			?>
			<tr>
				<td class="wpacu_plugin_icon" width="46">
					<?php if (isset($data['plugins_icons'][$pluginDir])) { ?>
						<img width="44" height="44" alt="" src="<?php echo esc_url($data['plugins_icons'][$pluginDir]); ?>" />
					<?php } else { ?>
						<div><span class="dashicons dashicons-admin-plugins"></span></div>
					<?php } ?>
				</td>
				<td class="wpacu_plugin_details" id="wpacu-front-manage-<?php echo esc_attr($pluginData['path']); ?>">
					<span class="wpacu_plugin_title"><?php echo esc_html($pluginData['title']); ?></span>
                    <span class="wpacu_plugin_path">&nbsp;<?php echo esc_html($pluginData['path']); ?></span>

					<?php
                    if ($pluginData['network_activated']) {
						echo '&nbsp;<span title="Network Activated" class="dashicons dashicons-admin-multisite wpacu-tooltip"></span>';
					}
                    ?>

					<div class="wpacu-clearfix"></div>

                    <!-- [Start] Unload Rules -->
					<?php
                    include '_front-areas/_unloads.php';
                    ?>
                    <!-- [End] Unload Rules -->
                </td>
            </tr>
			<?php
			$trOutput = ob_get_clean();
			$pluginsRows['always_loaded'][] = $trOutput;
		}

		if (isset($pluginsRows['always_loaded']) && ! empty($pluginsRows['always_loaded'])) {
			if (isset($pluginsRows['has_unload_rules']) && count($pluginsRows['has_unload_rules']) > 0) {
				?>
				<div style="margin-top: 35px;"></div>
				<?php
			}

			$totalAlwaysLoadedPlugins = count($pluginsRows['always_loaded']);
			?>

            <h3><span style="color: green;" class="dashicons dashicons-admin-plugins"></span> <span style="color: green;"><?php echo (int)$totalAlwaysLoadedPlugins; ?></span> active plugin<?php echo ($totalAlwaysLoadedPlugins > 1) ? 's' : ''; ?> (loaded by default)</h3>
			<table class="wp-list-table wpacu-list-table widefat plugins striped">
				<?php
				foreach ( $pluginsRows['always_loaded'] as $pluginRowOutput ) {
					echo \WpAssetCleanUp\Misc::stripIrrelevantHtmlTags($pluginRowOutput) . "\n";
				}
				?>
			</table>
			<?php
		}
		?>
        <div id="wpacu-update-button-area" style="margin-left: 0;">
            <input class="disabled" disabled="disabled" type="hidden" name="wpacu_plugins_manager_submit" value="1" />
        </div>
</div>