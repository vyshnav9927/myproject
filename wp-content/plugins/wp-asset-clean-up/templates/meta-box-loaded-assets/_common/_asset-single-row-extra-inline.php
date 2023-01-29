<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if (! isset($data, $inlineCodeStatus)) {
	exit; // no direct access
}

$assetType  = $data['row']['asset_type'];
$assetTypeS = substr($data['row']['asset_type'], 0, -1); // "styles" to "style" & "scripts" to "script"

if ($assetTypeS === 'style' && ! empty($data['row']['extra_data_css_list'])) {
    $codeToPrint = '';
	$totalInlineCodeSize = 0;

	foreach ($data['row']['extra_data_css_list'] as $extraDataCSS) {
        $outerHtmlCode = \WpAssetCleanUp\OptimiseAssets\OptimizeCss::generateInlineAssocHtmlForHandle(
	        $data['row']['obj']->handle,
	        $extraDataCSS
        );

		$htmlInline = trim($outerHtmlCode);

		$codeToPrint .= '<small><code>'.nl2br(htmlspecialchars($htmlInline)).'</code></small><br />';

		$totalInlineCodeSize += strlen($outerHtmlCode);
	}
	?>
    <div class="wpacu-assets-inline-code-wrap">
		<?php _e('Inline styling associated with the handle:', 'wp-asset-clean-up'); ?>
        <a class="wpacu-assets-inline-code-collapsible"
			<?php if ($inlineCodeStatus !== 'contracted') { echo 'wpacu-assets-inline-code-collapsible-active'; } ?>
           href="#"><?php _e('Show / Hide', 'wp-asset-clean-up'); ?></a>
        &nbsp; / &nbsp;Size: <em><?php echo \WpAssetCleanUp\Misc::formatBytes($totalInlineCodeSize, 2, ''); ?></em>
        <div class="wpacu-assets-inline-code-collapsible-content <?php if ($inlineCodeStatus !== 'contracted') { echo 'wpacu-open'; } ?>">
            <div>
                <p style="margin-bottom: 15px; line-height: normal !important;">
					<?php echo $codeToPrint; ?>
                </p>
            </div>
        </div>
    </div>
	<?php
} elseif ($assetTypeS === 'script' && ($data['row']['extra_data_js'] || $data['row']['extra_before_js'] || $data['row']['extra_after_js'])) {
	$extraInlineKeys = array(
		'data'   => 'CDATA added via wp_localize_script()',
		'before' => 'Before the tag:',
		'after'  => 'After the tag:'
	);

    ob_start();

    $totalInlineCodeSize = 0;

	foreach ($extraInlineKeys as $extraKeyValue => $extraKeyText) {
		$keyToMatch = 'extra_'.$extraKeyValue.'_js';

		if ( ! isset($data['row'][$keyToMatch]) ) {
			continue;
		}

		$inlineScriptContent = $data['row'][$keyToMatch];

		if (is_array($inlineScriptContent) && in_array($extraKeyValue, array('before', 'after'))) {
			$inlineScriptContent = (string)ltrim(implode("\n", $inlineScriptContent));
		}

		$inlineScriptContent = trim($inlineScriptContent);

		if ($inlineScriptContent) {
			?>
            <div style="margin-bottom: 8px;">
                <div style="margin-bottom: 10px;"><strong><?php echo esc_html($extraKeyText); ?></strong></div>
                <div style="margin-top: -7px !important; line-height: normal !important;">
					<?php
                    $outerHtmlCode = \WpAssetCleanUp\OptimiseAssets\OptimizeJs::generateInlineAssocHtmlForHandle(
	                    $data['row']['obj']->handle,
	                    $extraKeyValue,
	                    $inlineScriptContent
                    );

					$htmlInline = trim($outerHtmlCode);

					echo '<small><code>' . nl2br( htmlspecialchars( $htmlInline ) ) . '</code></small>';

					$totalInlineCodeSize += strlen($outerHtmlCode);
					?>
                </div>
            </div>
			<?php
		}
	}

    $codeToPrint = ob_get_clean();

    if ($totalInlineCodeSize > 0) {
	?>
	<div class="wpacu-assets-inline-code-wrap">
		<?php _e('Inline JavaScript code associated with the handle:', 'wp-asset-clean-up'); ?>
		<a class="wpacu-assets-inline-code-collapsible"
			<?php if ($inlineCodeStatus !== 'contracted') { echo 'wpacu-assets-inline-code-collapsible-active'; } ?>
           href="#"><?php _e('Show', 'wp-asset-clean-up'); ?> / <?php _e('Hide', 'wp-asset-clean-up'); ?></a>
        &nbsp; / &nbsp;Size: <em><?php echo \WpAssetCleanUp\Misc::formatBytes($totalInlineCodeSize, 2, ''); ?></em>
		<div class="wpacu-assets-inline-code-collapsible-content <?php if ($inlineCodeStatus !== 'contracted') { echo 'wpacu-open'; } ?>">
            <?php
            echo $codeToPrint;
            ?>
		</div>
	</div>
	<?php
    }
}
