<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

$baseNamePageType = str_replace('.php', '', basename(__FILE__));
$baseNamePageType = trim($baseNamePageType, '_');
?>
<div style="margin: 25px 0 0;">
    <p><?php echo wp_kses(
		    str_replace('[wpacu_chosen_page_type]', $baseNamePageType, $data['locked_for_pro']),
		    array('span' => array('class' => array()), 'a' => array('href' => array()))
	    ); ?></p>
    <hr />

    <p>Shows all posts belonging to a specific author (e.g. https://yourwebsite.com/author/yourname/). The assets can be unloaded <strong>only in the front-end view</strong> (<em>"Manage in the Front-end?" from "Settings" tab has to be enabled</em>).</p>
    <p><strong>Example:</strong> <code>//www.yoursite.com/blog/author/john-doe/</code></p>
    <hr />

    <strong>How to retrieve the loaded styles &amp; scripts?</strong>

    <p style="margin-bottom: 0;"><span class="dashicons dashicons-yes" style="color: green;"></span> If "Manage in the Front-end?" is enabled, and you're logged in:</p>
    <p style="margin-top: 0;">Go to the author archive page and scroll to the bottom of the page where you will see the list.</p>
</div>