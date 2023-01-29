<?php

function wpmlm_user_area() {
    $current_user = wp_get_current_user();
    
    $recent_joinee = wpmlm_get_recently_joined_users('1');
	$last_jo = $recent_joinee[0];
    $date = strtotime($last_jo->user_registered);
    $last_joining_date = date('Y-m-d', $date); 
    
    $user_id = get_current_user_id();

    $user_details = wpmlm_get_user_details($user_id);
    $user = get_user_by('id', $user_id);
    $parent_id = $user_details->user_parent_id;
    $package_id = $user_details->package_id;
    $user_status = $user_details->user_status;


    if (($user_id) && (($user_status == 1) || ($user_status == 2))) {
    ?>
    <div class="panel-heading">
        <h3 class="main-head"> <?php _e('WP MLM User','woocommerce-securewpmlm-unilevel'); ?></h3>
    </div>
    <div class="user-container wc-wpmlm-main-div">
        <div class="user-details-wp">
            <div class="user-img">
                <img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/avathar.png'; ?>" style="width: 100px; height: auto;"/>
            </div>
            <div class="user-cnt">
                <div class="user-cnt1"><?php echo $current_user->user_firstname;?> <?php echo $current_user->user_lastname; ?></div>
                <div class="user-cnt2"><?php echo $current_user->user_email; ?></div>
                <div class="user-cnt3"><?php _e('Last Joined','woocommerce-securewpmlm-unilevel'); ?>: <?php echo $last_joining_date; ?></div>
            </div>
            <div class="user-affiliate">
                <label class="user-affiliate-label"><?php _e('Affiliate Link','woocommerce-securewpmlm-unilevel'); ?>: <?php echo $last_joining_date; ?></label>
                <div class="user-affiliate-link">
                    <label class="form-control" id="affiliate_link" style="height:auto;"><?php echo site_url(); ?>/<?php echo $current_user->user_login;?></label>
                    <div class="tooltip-button">
	                	<button class="btn btn-primary mlm-button affiliate_link" onclick="copyToClipboard('#affiliate_link')" onmouseout="outFunc()"><span class="tooltiptext" id="myTooltip"><?php _e('Copy to clipboard','woocommerce-securewpmlm-unilevel'); ?></span><?php _e('Copy Link','woocommerce-securewpmlm-unilevel'); ?></button>
	                </div>
                </div>
            </div>
        </div>
        <!-- User Details -->
        <div>
            <div id="main-tab" class="dashboard-tab-container">
                
                <div id="user-dashboard-tabs">

                  <ul class="dashboard-tab-user">
                    <li><a href="#tabs-dashboard"><?php _e('Dashboard','woocommerce-securewpmlm-unilevel'); ?></a></li>
                    <li><a href="#tabs-user-info"><?php _e('Account Info','woocommerce-securewpmlm-unilevel'); ?></a></li>
                    <li><a href="#tabs-genealogy-tree"><?php _e('Genealogy Tree','woocommerce-securewpmlm-unilevel'); ?></a></li>
                    <li><a href="#tabs-ewallet-management"><?php _e('E-wallet Management','woocommerce-securewpmlm-unilevel'); ?></a></li>
                    <li><a href="#tabs-income-details"><?php _e('Income Details','woocommerce-securewpmlm-unilevel'); ?></a></li>
                    <li><a href="#tabs-referral-details"><?php _e('Referral Details','woocommerce-securewpmlm-unilevel'); ?></a></li>
                  </ul>
                  <div class="dashboard-tab-contents">
                      <div id="tabs-dashboard"><?php wpmlm_user_dashboard($user_id); ?></div>
                      <div id="tabs-user-info"><?php wpmlm_user_profile_admin($user_id); ?></div>
                      <div id="tabs-genealogy-tree"><?php wpmlm_unilevel_tree($user_id,'sponsor'); ?></div>
                      <div id="tabs-ewallet-management"><?php wpmlm_user_ewallet_management(); ?></div>
                      <div id="tabs-income-details"><?php wpmlm_user_income_details($user_id); ?></div>
                      <div id="tabs-referral-details"><?php wpmlm_user_referrals($user_id); ?></div>
                  </div>
                  
                </div>

            </div>
        </div>
    </div>
    <?php
    } 
}

