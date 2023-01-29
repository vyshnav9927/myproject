<?php
function wpmlm_admin_scripts()
{
    wp_register_style('wp-mlm-excel-bootstrap', plugins_url('css/excel-bootstrap-table-filter-style.css', __FILE__));
    wp_register_style('wp-mlm-bootstrap', plugins_url('css/bootstrap.min.css', __FILE__));

    wp_register_style('wp-mlm-orgchart-css', plugins_url('css/jquery.orgchart.css', __FILE__));
    wp_register_style('wp-mlm-datepicker', plugins_url('css/datepicker.css', __FILE__));
    wp_register_style('wp-mlm-easy-responsive-tabs', plugins_url('css/easy-responsive-tabs.css', __FILE__));

    wp_register_style('wp-mlm-dataTables-jquery-ui-css', plugins_url('css/jquery-ui.css', __FILE__));
    wp_register_style('wp-mlm-dataTables-jqueryuicss', plugins_url('css/dataTables.jqueryui.min.css', __FILE__));

    wp_register_style('wp-mlm-style', plugins_url('css/style.css', __FILE__));
    wp_register_style('wp-mlm-font-awesome-css', plugins_url('css/font-awesome.css', __FILE__));


    wp_enqueue_style('wp-mlm-excel-bootstrap');
    wp_enqueue_style('wp-mlm-bootstrap');
    wp_enqueue_style('wp-mlm-orgchart-css');
    wp_enqueue_style('wp-mlm-datepicker');
    wp_enqueue_style('wp-mlm-easy-responsive-tabs');
    wp_enqueue_style('wp-mlm-dataTables-jquery-ui-css');
    wp_enqueue_style('wp-mlm-dataTables-jqueryuicss');
    wp_enqueue_style('wp-mlm-style');
    wp_enqueue_style('wp-mlm-font-awesome-css');




    wp_register_script('wp-mlm-jquery-js', plugins_url('/js/jquery.min.js', __FILE__), false, null, false);


    wp_register_script('wp-mlm-highcharts-js', plugins_url('/js/highcharts.js', __FILE__), false, null, false);
    wp_register_script('wp-mlm-exporting-js', plugins_url('/js/exporting.js', __FILE__), false, null, false);
    wp_register_script('wp-mlm-export-data-js', plugins_url('/js/export-data.js', __FILE__), false, null, false);
    wp_register_script('wp-mlm-accessibility-js', plugins_url('/js/accessibility.js', __FILE__), false, null, false);
    wp_register_script('wp-mlm-highcharts-custom-js', plugins_url('/js/highcharts-custom.js', __FILE__), false, null, true);


    wp_register_script('wp-mlm-excel-bootstrap-table-filter-bundle-js', plugins_url('/js/excel-bootstrap-table-filter-bundle.js', __FILE__), false, null, true);
    wp_register_script('wp-mlm-popper-js', plugins_url('/js/popper.min.js', __FILE__), false, null, true);
    wp_register_script('wp-mlm-jquery-ui-js', plugins_url('/js/jquery-ui.min.js', __FILE__), false, null, true);
    wp_register_script('wp-mlm-bootstrap-js', plugins_url('/js/bootstrap.min.js', __FILE__), false, null, true);
    wp_register_script('wp-mlm-jquery-validate-js', plugins_url('/js/jquery.validate.js', __FILE__), false, null, true);
    wp_register_script('wp-mlm-index-js', plugins_url('/js/index.js', __FILE__), false, null, true);
    
    wp_register_script('wp-mlm-jqueryjs-dataTables', plugins_url('/js/jquery.dataTables.min.js', __FILE__), false, null, true);
    wp_register_script('wp-mlm-jqueryuijs-dataTables', plugins_url('/js/dataTables.jqueryui.min.js', __FILE__), false, null, true);


    wp_register_script('wp-mlm-bootstrap-datepicker', plugins_url('/js/bootstrap-datepicker.js', __FILE__), array(
        'jquery'
    ));
    wp_register_script('wp-mlm-orgchart-js', plugins_url('/js/jquery.orgchart.js', __FILE__), array(
        'jquery'
    ));
    wp_register_script('wp-mlm-easyResponsiveTabs', plugins_url('/js/easyResponsiveTabs.js', __FILE__), array(
        'jquery'
    ));    


    wp_register_script('wp-mlm-my-script', plugins_url('/js/custom.js', __FILE__), array(
        'jquery'
    ));





    wp_enqueue_script('wp-mlm-jquery-js');
    if(($_GET['page'] == 'wpmlm-admin-settings')){
        wp_enqueue_script('wp-mlm-highcharts-js');
        wp_enqueue_script('wp-mlm-exporting-js');
        wp_enqueue_script('wp-mlm-export-data-js');
        wp_enqueue_script('wp-mlm-accessibility-js');
        wp_enqueue_script('wp-mlm-highcharts-custom-js');
    }
    wp_enqueue_script('wp-mlm-excel-bootstrap-table-filter-bundle-js');
    wp_enqueue_script('wp-mlm-popper-js');
    wp_enqueue_script('wp-mlm-jquery-ui-js');
    wp_enqueue_script('wp-mlm-bootstrap-js');
    wp_enqueue_script('wp-mlm-jquery-validate-js');
    wp_enqueue_script('wp-mlm-index-js');
    wp_enqueue_script('wp-mlm-jqueryjs-dataTables');
    wp_enqueue_script('wp-mlm-jqueryuijs-dataTables');
    wp_enqueue_script('wp-mlm-bootstrap-datepicker');
    wp_enqueue_script('wp-mlm-orgchart-js');
    wp_enqueue_script('wp-mlm-easyResponsiveTabs');
    wp_enqueue_script('wp-mlm-my-script');



    wp_localize_script('wp-mlm-my-script', 'path', array(
        'pluginsUrl' => plugins_url(WP_MLM_PLUGIN_NAME)
    ));
    wp_localize_script("wp-mlm-my-script", "site", array(
        "siteUrl" => site_url()
    ));
}


// Update CSS within in Admin
function admin_style() {
  wp_enqueue_style('admin-tab-style', plugins_url('css/admin-tab.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'admin_style');


function wpmlm_register_menu()
{
    if (current_user_can('subscriber') || (current_user_can('contributor'))) {
        add_action('admin_menu', 'wpmlm_admin_actions_user');
    } else {
        add_action('admin_menu', 'wpmlm_admin_actions');
    }
}

function wpmlm_admin_settings()
{
    include('wpmlm-admin.php');
}

function wpmlm_users_function()
{
    wpmlm_user_details_admin();
}
function wpmlm_genealogy_tree_function()
{
    $user_id = get_current_user_id();
    wpmlm_unilevel_tree($user_id,'sponsor'); 
}
function wpmlm_e_wallet_management_function()
{
    wpmlm_ewallet_management();
}
function wpmlm_reports_function()
{
    wpmlm_all_reports();
}
function wpmlm_change_password_function()
{
    wpmlm_password_settings();
}
function wpmlm_settings_function()
{
    wpmlm_settings();
}







function wpmlm_user_settings()
{
    include('wpmlm-user.php');
}

function wpmlm_admin_actions()
{
    $icon_url = plugins_url() . "/" . WP_MLM_PLUGIN_NAME . "/images/icon-01.png";
    //add_menu_page('WP MLM ADMIN', 'WP MLM', 1, 'wpmlm-admin-settings', 'wpmlm_admin_settings', $icon_url);

    add_menu_page('WP MLM ADMIN', 'WP MLM', 'manage_options', 'wpmlm-admin-settings', 'wpmlm_admin_settings',$icon_url);

    add_submenu_page( 'wpmlm-admin-settings', 'MLM Users', 'MLM Users', 'manage_options', 'wpmlm-users', 'wpmlm_users_function');
    add_submenu_page( 'wpmlm-admin-settings', 'Genealogy Tree', 'Genealogy Tree', 'manage_options', 'wpmlm-genealogy-tree', 'wpmlm_genealogy_tree_function');
    add_submenu_page( 'wpmlm-admin-settings', 'E-wallet Management', 'E-wallet', 'manage_options', 'wpmlm-e-wallet-management', 'wpmlm_e_wallet_management_function');
    add_submenu_page( 'wpmlm-admin-settings', 'Reports', 'Reports', 'manage_options', 'wpmlm-reports', 'wpmlm_reports_function');
    add_submenu_page( 'wpmlm-admin-settings', 'Change Password', 'Change Password', 'manage_options', 'wpmlm-change-password', 'wpmlm_change_password_function');
    add_submenu_page( 'wpmlm-admin-settings', 'Settings', 'Settings', 'manage_options', 'wpmlm-settings', 'wpmlm_settings_function');
    
}


function wpmlm_custom_loginlogo() {

    $result = wpmlm_get_general_information();
    if ($result->site_logo == 'active') {
        echo '<style type="text/css">
#login h1 a {background-image: url(' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $result->company_logo . ') !important; }
    
</style>';
    }
}

function wpmlm_admin_actions_user()
{
    $icon_url = plugins_url() . "/" . WP_MLM_PLUGIN_NAME . "/images/icon-01.png";
    add_menu_page('WP MLM ADMIN', 'WP MLM', 1, 'wpmlm-user-settings', 'wpmlm_user_settings', $icon_url);
}

add_action('admin_init', 'wpmlm_remove_menu_pages');

function wpmlm_remove_menu_pages()
{
    
    global $user_ID;
    
    if (current_user_can('contributor') || (current_user_can('subscriber'))) {
        remove_menu_page('tools.php');
    }
}


function wpmlm_admin_notice()
{
    global $pagenow;
    
    if (current_user_can('administrator')) {
        if ($pagenow == 'index.php') {
            
            echo '<div class="notice notice-info is-dismissible">
          <p>Click <a href="admin.php?page=wpmlm-admin-settings">here</a> to view the WP MLM Dashboard</p>
         </div>';
        }
    } else {
        
        if ($pagenow == 'index.php') {
            
            echo '<div class="notice notice-info is-dismissible">
          <p>Click <a href="admin.php?page=wpmlm-user-settings">here</a> to view the WP MLM Dashboard</p>
         </div>';
        }
    }
}

add_action('admin_notices', 'wpmlm_admin_notice');
add_filter('pre_option_default_role', function($default_role)
{
    return 'contributor';
});


add_action('wp_head', 'wpmlm_ajaxurl');

function wpmlm_ajaxurl()
{
    
    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

add_action('wp_ajax_wpmlm_ajax_general_settings', 'wpmlm_ajax_general_settings');
add_action('wp_ajax_wpmlm_ajax_ewallet_management', 'wpmlm_ajax_ewallet_management');
add_action('wp_ajax_wpmlm_ajax_purchase_settings', 'wpmlm_ajax_purchase_settings');
add_action('wp_ajax_wpmlm_auto_fill_user', 'wpmlm_auto_fill_user');
add_action('wp_ajax_wpmlm_level_bonus', 'wpmlm_level_bonus');
add_action('wp_ajax_wpmlm_ajax_transaction_password', 'wpmlm_ajax_transaction_password');
add_action('wp_ajax_wpmlm_ajax_payment_option', 'wpmlm_ajax_payment_option');
add_action('wp_ajax_wpmlm_ajax_package_settings', 'wpmlm_ajax_package_settings');
add_action('wp_ajax_wpmlm_ajax_profile_report', 'wpmlm_ajax_profile_report');
add_action('wp_ajax_wpmlm_ajax_joining_report', 'wpmlm_ajax_joining_report');
add_action('wp_ajax_wpmlm_ajax_bonus_report', 'wpmlm_ajax_bonus_report');
add_action('wp_ajax_wpmlm_ajax_user_details', 'wpmlm_ajax_user_details');
add_action('wp_ajax_wpmlm_ajax_user_profile', 'wpmlm_ajax_user_profile');
add_action('wp_ajax_wpmlm_ajax_session', 'wpmlm_ajax_session');
add_action('wp_ajax_wpmlm_ajax_user_check', 'wpmlm_ajax_user_check');
add_action('wp_ajax_wpmlm_sponsor_validation', 'wpmlm_ajax_sponsor_validation');
add_action('wp_ajax_wpmlm_ajax_country_change', 'wpmlm_ajax_country_change');
add_action('wp_ajax_wpmlm_ajax_user_register_from_my_account', 'wpmlm_ajax_user_register_from_my_account');
add_action('wp_ajax_wpmlm_ajax_payout_management', 'wpmlm_ajax_payout_management');
add_action('wp_ajax_wpmlm_ajax_payout_report', 'wpmlm_ajax_payout_report');

//for frontend
add_action('wp_ajax_nopriv_wpmlm_ajax_user_check', 'wpmlm_ajax_user_check');
add_action('wp_ajax_nopriv_wpmlm_ajax_sponsor_validation', 'wpmlm_ajax_sponsor_validation');
add_action('wp_ajax_nopriv_wpmlm_ajax_country_change', 'wpmlm_ajax_country_change');
add_action('wp_ajax_nopriv_wpmlm_ajax_user_register_from_my_account', 'wpmlm_ajax_user_register_from_my_account');
add_action('wp_ajax_nopriv_wpmlm_ajax_payout_management', 'wpmlm_ajax_payout_management');
add_action('wp_ajax_nopriv_wpmlm_ajax_payout_report', 'wpmlm_ajax_payout_report');

function wpmlm_frontend_script_bootstrap()
{
    $style = 'bootstrap';
    if( ( ! wp_style_is( $style, 'queue' ) ) && ( ! wp_style_is( $style, 'done' ) ) ) {
        wp_register_style('wp-mlm-bootstrap', plugins_url('css/bootstrap.min.css', __FILE__));
        wp_enqueue_style('wp-mlm-bootstrap');
    }
}

function wpmlm_frontend_script()
{

    wp_register_style('wp-mlm-excel-bootstrap', plugins_url('css/excel-bootstrap-table-filter-style.css', __FILE__));
    wp_register_style('wp-mlm-orgchart-css', plugins_url('css/jquery.orgchart.css', __FILE__));
    wp_register_style('wp-mlm-datepicker', plugins_url('css/datepicker.css', __FILE__));
    wp_register_style('wp-mlm-easy-responsive-tabs', plugins_url('/css/responsive-tabs.css', __FILE__));
    wp_register_style('wp-mlm-easy-responsive-tab-style', plugins_url('/css/responsive-tab-style.css', __FILE__));
    wp_register_style('wp-mlm-dataTables-jquery-ui-css', plugins_url('css/jquery-ui.css', __FILE__));
    wp_register_style('wp-mlm-dataTables-jqueryuicss', plugins_url('css/dataTables.jqueryui.min.css', __FILE__));
    wp_register_style('wp-mlm-style', plugins_url('css/user-style.css', __FILE__));
    wp_register_style('wp-mlm-font-awesome-css', plugins_url('css/font-awesome.css', __FILE__));

    wp_enqueue_style('wp-mlm-excel-bootstrap');
    wp_enqueue_style('wp-mlm-orgchart-css');
    wp_enqueue_style('wp-mlm-datepicker');
    wp_enqueue_style('wp-mlm-easy-responsive-tabs');
    wp_enqueue_style('wp-mlm-easy-responsive-tab-style');
    wp_enqueue_style('wp-mlm-dataTables-jquery-ui-css');
    wp_enqueue_style('wp-mlm-dataTables-jqueryuicss');
    wp_enqueue_style('wp-mlm-style');
    wp_enqueue_style('wp-mlm-font-awesome-css');


    wp_enqueue_script('wp-mlm-jquery-js', plugins_url('/js/jquery.min.js', __FILE__), false, null, false);
    wp_enqueue_script('wp-mlm-jquery-ui-1-js', plugins_url('/js/jquery-ui-1.10.1.custom.min.js', __FILE__), false, null, false);

    wp_enqueue_script('wp-mlm-highcharts-js', plugins_url('/js/highcharts.js', __FILE__), false, null, false);
    wp_enqueue_script('wp-mlm-exporting-js', plugins_url('/js/exporting.js', __FILE__), false, null, false);
    wp_enqueue_script('wp-mlm-export-data-js', plugins_url('/js/export-data.js', __FILE__), false, null, false);
    wp_enqueue_script('wp-mlm-accessibility-js', plugins_url('/js/accessibility.js', __FILE__), false, null, false);
    wp_enqueue_script('wp-mlm-highcharts-custom-js', plugins_url('/js/highcharts-custom.js', __FILE__), false, null, true);

    wp_enqueue_script('wp-mlm-excel-bootstrap-table-filter-bundle-js', plugins_url('/js/excel-bootstrap-table-filter-bundle.js', __FILE__), false, null, true);
    wp_enqueue_script('wp-mlm-popper-js', plugins_url('/js/popper.min.js', __FILE__), false, null, true);
    wp_enqueue_script('wp-mlm-jquery-ui-js', plugins_url('/js/jquery-ui.min.js', __FILE__), false, null, true);

    $style = 'bootstrap';
    if( ( ! wp_style_is( $style, 'queue' ) ) && ( ! wp_style_is( $style, 'done' ) ) ) {
        wp_enqueue_script('wp-mlm-bootstrap-js', plugins_url('/js/bootstrap.min.js', __FILE__), false, null, true);
    }

    wp_register_script('wp-mlm-jquery-validate-js', plugins_url('/js/jquery.validate.js', __FILE__), false, null, true);
    wp_enqueue_script('wp-mlm-user-index-js', plugins_url('/js/user-index.js', __FILE__), false, null, true);

    wp_enqueue_script('wp-mlm-jqueryjs-dataTables', plugins_url('/js/jquery.dataTables.min.js', __FILE__), false, null, true);
    wp_enqueue_script('wp-mlm-jqueryuijs-dataTables', plugins_url('/js/dataTables.jqueryui.min.js', __FILE__), false, null, true);

    wp_enqueue_script('wp-mlm-bootstrap-datepicker', plugins_url('/js/bootstrap-datepicker.js', __FILE__), array(
        'jquery'
    ));
    wp_enqueue_script('wp-mlm-orgchart-js', plugins_url('/js/jquery.orgchart.js', __FILE__), array(
        'jquery'
    ));
    wp_enqueue_script('wp-mlm-responsive-tabs', plugins_url('/js/jquery.responsiveTabs.js', __FILE__), array(
        'jquery'
    ));    


    wp_register_script('wp-mlm-my-script', plugins_url('/js/custom.js', __FILE__), array(
        'jquery'
    ));
    wp_enqueue_script('wp-mlm-my-script');

    wp_localize_script('wp-mlm-my-script', 'path', array(
        'pluginsUrl' => plugins_url(WP_MLM_PLUGIN_NAME)
    ));
    wp_localize_script("wp-mlm-my-script", "site", array(
        "siteUrl" => site_url()
    ));
}


/**
 * Function to handle affiliate link
 * @param  $username
 * @return boolean true or false
 */
function wpmlm_affiliate($username)
{
    
    if ($username) {
        $username = sanitize_text_field($username);
        return wpmlm_checkSponsorIsRegistered($username);
    }
}


/**
 * function used to show extra fields for registration
 * @param    $checkout 
 * @return   html data
 */
function wpmlm_extra_fields_for_registration($checkout)
{
    
    global $woocommerce;
    if (!session_id())
        session_start();
    
    $user=wp_get_current_user();
    
    
        if ( !in_array( 'seller', (array) $user->roles ) ) {
    woocommerce_form_field('register_vendor', array(
        'type' => 'checkbox',
        'label' => __('<span class="register_vendor_checkbox_span">Register As Vendor </span>'),
        'id' => 'register_vendor'
    ), $checkout->get_value('register_vendor'));
}

    
    //check a user alredy registered
    $user_status=wpmlm_check_mlm_user();
    if (is_user_logged_in() && $user_status) {
        return;
    }
    
    echo '<div class="cw_custom_class">';
    
    woocommerce_form_field('register_mlm', array(
        'type' => 'checkbox',
        'label' => __('<span class="register_mlm_checkbox_span">For MLM Registration.</span>'),
        'id' => 'register_mlm'
    ), $checkout->get_value('register_mlm'));
    
    echo '</div>';

    echo '<div class="mlm-registration-section" style="display: none;">';
    echo '<h3>Contact Information</h3>';
    if (!empty($_SESSION['sponsor'])){ $sponsor = $_SESSION['sponsor'];}else{ $sponsor='';}

    woocommerce_form_field( 'reg_billing_sponsor_name', array(
        'type'          => 'text',
        'class'         => array( 'wps-drop' ),
        'label'         => __( 'Sponsor Name' ),
        'required'      => true,
        'default'       =>$sponsor
        
    ),
    $checkout->get_value( 'reg_billing_sponsor_name' ));

        // Date of birth Field
    woocommerce_form_field( 'reg_billing_date_of_birth', array(
        'type'          => 'text',
        'class'         => array( 'wps-drop' ),
        'label'         => __( 'Date Of Birth' ),
        'required'      => true,
        
    ),
    $checkout->get_value( 'reg_billing_date_of_birth' ));  

        
        echo '</div>';
       
}
add_action('woocommerce_before_checkout_billing_form', 'wpmlm_extra_fields_for_registration');

/**
 * function used to validate custom fields
 * @return [type] [description]
 */
function wpmlm_customise_checkout_field_process()
{
    global $woocommerce;
    global $wpdb;
    if (!session_id())
        session_start();
    
    $table_name = $wpdb->prefix . 'users';
    
    if ($_POST['register_mlm']) {  
        
        
        
        
        if (!$_POST['reg_billing_sponsor_name'] && !$_SESSION['sponsor']) {
            wc_add_notice(__('<strong>Please Enter Sponsor Name</strong>'), 'error');
        } else {
            $sponsor_info     = isset($_POST['reg_billing_sponsor_name']) ? $_POST['reg_billing_sponsor_name'] : $_SESSION['sponsor'];
            $sponsor_username = sanitize_text_field($sponsor_info);
            
            if (!preg_match('/^[a-zA-Z]((_)?[a-zA-Z\d](_)?){2,50}$/i', $sponsor_username)) {
                wc_add_notice(__('<strong>Sponsor Name should be atleast 3 character, alhpanumeric</strong>'), 'error');
            } else {
                
                $sponsor = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$sponsor_username'");
                if (!$sponsor) {
                    wc_add_notice(__('<strong>Sorry! The specified sponsor is not available for registration.</strong>'), 'error');
                }
            }
        }

        
        $username = $_POST['account_username'];
        
        if (!preg_match('/^[a-zA-Z]((_)?[a-zA-Z\d](_)?){2,50}$/i', $username)) {
            wc_add_notice(__('<strong>Username should be atleast 3 character, alhpanumeric</strong>'), 'error');
        }
        
    }
}
add_action('woocommerce_checkout_process', 'wpmlm_customise_checkout_field_process', 10, 1);

/**
 * Save custom field db after validation
 * @param  [type] $customer_id [description]
 * @return [type]              [description]
 */
function wpmlm_custom_checkout_field_update_order_meta($order_id)
{
   
    
    
    $order=wc_get_order($order_id);
    $user_id=$order->get_user_id();
   

    // Billing fields
    $sponsor_username = sanitize_text_field($_POST['reg_billing_sponsor_name']);
    $date_of_birth    = sanitize_text_field($_POST['reg_billing_date_of_birth']);
    
    if (!(empty($sponsor_username) && empty($sponsor_username)))
        update_post_meta($order_id, 'reg_billing_sponsor_name', $sponsor_username);
    
    if (!(empty($date_of_birth) && empty($date_of_birth)))
        update_post_meta($order_id, 'reg_billing_date_of_birth', $date_of_birth);

      if($_POST['register_vendor']){
       
        $user_role = new WP_User( $user_id );
        $user_role->remove_role( "customer" );
        $user_role->add_role( "seller" );
     
        
        
    }
}
add_action('woocommerce_checkout_update_order_meta', 'wpmlm_custom_checkout_field_update_order_meta', 10, 2);

/**
 * Complete the registration after successfull registration
 * @param  [type] $order_id [description]
 * @return [type]           [description]
 */
 
 function wpmlm_woocommerce_registration($order_id){
    

       if (!session_id())
        session_start();
    
    if (!$order_id)
        return;
    ## HERE goes the condition to avoid the repetition
    $order_done = get_post_meta($order_id, 'order_done', true);
    if ($order_done)
        return;
    
    // Getting an instance of the order object
    $order = wc_get_order($order_id);
    $items = $order->get_items();
    foreach ($items as $item) {
        $product_id[] = $item->get_product_id();
    }    
    
    // Get the user ID from WC_Order methods
    $user_id = $order->get_user_id();
    //get the user email from the order
    $user    = $order->get_user();  


    

        
    if (get_post_meta($order_id, 'reg_billing_sponsor_name', true)) {
       
        $sponsor_username = get_post_meta($order_id, 'reg_billing_sponsor_name', true);
        $sponsor_info     = get_user_by('login', $sponsor_username);
        $user_parent_id   = $sponsor_info->ID;
        
    } else if (isset($_SESSION['sponsor'])) {
       
        $sponsor_username = $_SESSION['sponsor'];
        $sponsor_info     = get_user_by('login', $sponsor_username);
        $user_parent_id   = $sponsor_info->ID;
    } else {
       
        $sponsor_username = '';
    }
    if (get_post_meta($order_id, 'reg_billing_date_of_birth', true)) {
        $user_dob = get_post_meta($order_id, 'reg_billing_date_of_birth', true);
        
    } else {
        $user_dob = '';
    }

    if ($sponsor_username == '') {   
        //For repurchase case
        $back_user_res = wpmlm_get_user_details($user_id);
        if($order->status=='completed' && $back_user_res){
            
            $package_id=$product_id[0];
            wpmlm_insert_leg_amount($user_id, $package_id, $order_id, "woocommerce");
            wpmlm_insert_products_purchased_amount($user_id, $package_id, $order_id, "woocommerce");
            
            
        }        
        return;
    }else{

        $back_user_res = wpmlm_get_user_details($user_id);
        if (!$back_user_res) {
            $user_level             = wpmlm_get_user_level_by_parent_id($user_parent_id);
            $user_first_name        = get_post_meta($order_id, '_billing_first_name', true);
            $user_second_name       = get_post_meta($order_id, '_billing_last_name', true);
            $user_address           = get_post_meta($order_id, '_billing_address_1', true);
            $user_city              = get_post_meta($order_id, '_billing_city', true);
            $user_state             = get_post_meta($order_id, '_billing_state', true);
            $user_country           = get_post_meta($order_id, '_billing_country', true);
            $user_zip               = get_post_meta($order_id, '_billing_postcode', true);
            $user_mobile            = get_post_meta($order_id, '_billing_phone', true);
            $user_dob               = get_post_meta( $order_id, 'reg_billing_date_of_birth',  true);
            $user_email             = get_post_meta($order_id, '_billing_email', true);
            $user_registration_type = 'paid_join';
            $package_id             = $product_id[0];
    
            //converted date
            $newDate = date("Y-m-d", strtotime($user_dob));
    
            $user_details = array(
                'user_ref_id' => $user_id,
                'user_parent_id' => $user_parent_id,
                'user_first_name' => $user_first_name,
                'user_second_name' => $user_second_name,
                'user_address' => $user_address,
                'user_city' => $user_city,
                'user_state' => $user_state,
                'user_country' => $user_country,
                'user_zip' => $user_zip,
                'user_mobile' => $user_mobile,
                'user_email' => $user_email,
                'user_dob' => $newDate,
                'user_level' => $user_level,
                'user_registration_type' => $user_registration_type,
                'join_date' => date("Y-m-d H:i:s"),
                'user_status' => 1,
                'package_id' => $product_id[0]
            );
    
            if (!empty($user_details)) {
                require_once ABSPATH . 'wp-includes/class-phpass.php';
                if($order->status=='completed'){

                   
                        if (wpmlm_insert_user_registration_details($user_details)) {
                            //$tran_pass = wpmlm_getRandTransPasscode(8);
                            $tran_pass ='12345678';                
                            $hash_tran_pass    = wp_hash_password($tran_pass);
                            $tran_pass_details = array(
                                'user_id' => $user_id,
                                'tran_password' => $hash_tran_pass
                            );
                            wpmlm_insert_tran_password($tran_pass_details);
                            wpmlm_insertBalanceAmount($user_id);                    
                            unset($_SESSION['sponsor']);
                        
        
        
                            //custom email to user
                            $username = $user->user_login;
                            $user_email = $user->user_email;
                            $full_name = $user->display_name;
                            $reg_date = $user->user_registered;
                            $registration_date = date('F j, Y', strtotime($reg_date));
                            $login_link = home_url('/my-account');
                            $site_details = wpmlm_get_general_information();
                            $site_name = $site_details->company_name;
                            //$site_name = get_bloginfo('name');
                            //$to = 'vimal@teamioss.in';
                            $subject = 'Registration Success';
                            $body = '
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="center" valign="top">
                                            <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color:#ffffff;border:1px solid #dedede;border-radius:3px">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" valign="top">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#96588a;color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:roboto;border-radius:3px 3px 0 0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="padding:36px 48px;display:block">
                                                                            <h1 style="font-family:roboto;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;color:#ffffff">Registration successful..!</h1>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" valign="top">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="600">
                                                                <tbody>
                                                                    <tr>
                                                                        <td valign="top" style="background-color:#ffffff">
                                                                            <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td valign="top" style="padding:48px 48px 32px">
                                                                                            <div style="color:#636363;font-family:roboto;font-size:14px;line-height:150%;text-align:left">
                                                                                                <h3>Dear ' . $full_name . '</h3>
                                                                                                <p style="margin:0 0 16px">Thank You for registering with us..!</p>
                                                                                                <p style="margin:0 0 16px">Registration Date : <b>( ' . $registration_date . ' )</b> </p>
                                                                                                <h4>To login</h4>
                                                                                                <p>Go to <a href="' . $login_link . '" target="_blank">Login Page</a></p>
                                                                                                <div style="margin-bottom:40px">
                                                                                                    <table cellspacing="0" cellpadding="6" border="1" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:roboto">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <th scope="row" colspan="2" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px">Username:</th>
                                                                                                                <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px">' . $username . '</td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <th scope="row" colspan="2" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Password:</th>
                                                                                                                <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Your chosen password (Not provided for security reasons)</td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <th scope="row" colspan="2" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Transaction Passwod:</th>
                                                                                                                <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">12345678</td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                                <p>Thank you!</p>
                                                                                                <p>' . $site_name . '</p>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>';
        
                            $headers = array(
                                'Content-Type: text/html; charset=UTF-8'
                            );
        
                            wp_mail($user_email, $subject, $body, $headers);
                            //custom email to user
                            
        
                        } 
                        //-----------------------
                        wpmlm_insert_leg_amount($user_id, $package_id, $order_id, "woocommerce");
                        wpmlm_insert_products_purchased_amount($user_id, $package_id, $order_id, "woocommerce");
                    }   
             
              
            }

        
        }   
  
        

    } 
 }
 add_action('woocommerce_order_status_changed','wpmlm_woocommerce_registration',10);
 

/**
 * Add a custom product tab.
 */
function wpmlm_product_pv_menu_shows($tabs)
{
    $tabs['productpv'] = array(
        'label' => __('Product BV', 'woocommerce'),
        'target' => 'product_pv_options',
        'class' => array(
            'show_if_simple',
            'show_if_variable'
        )
    );
    return $tabs;
}
add_filter('woocommerce_product_data_tabs', 'wpmlm_product_pv_menu_shows');

/**
 * Contents of the gift card options product tab.
 */
function wpmlm_productpv_options_tab_content()
{
    global $post;

    echo '<div id="product_pv_options" class="panel woocommerce_options_panel">';
    echo '<div class="options_group">'; 
    woocommerce_wp_text_input(array(
        'id' => '_product_pv',
        'label' => __('Product BV', 'woocommerce'),
        'placeholder' => '',
        'desc_tip' => 'true',
        'description' => __('Enter BV '),
        'type' => 'number',
        'custom_attributes' => array(
            'step' => 'any',
            'min' => '0'
        )
    ));
    echo '</div>';
    echo '</div>';
}
add_filter('woocommerce_product_data_panels', 'wpmlm_productpv_options_tab_content'); // WC 2.6 and up

/**
 * Save the custom fields.
 */
function wpmlm_save_product_pv_option_fields($post_id)
{
    
    if (isset($_POST['_product_pv'])):
        update_post_meta($post_id, '_product_pv', absint($_POST['_product_pv']));
    endif;
    
}
add_action('woocommerce_process_product_meta_simple', 'wpmlm_save_product_pv_option_fields');
add_action('woocommerce_process_product_meta_variable', 'wpmlm_save_product_pv_option_fields');


/**
 * Account menu items
 *
 * @param arr $items
 * @return arr
 */
function wpmlm_customized_woocommerce_menu($items)
{
    $user_status = wpmlm_check_mlm_user();
    $user_data = wp_get_current_user();
    $customer_orders = wc_get_orders( array(
        
    'customer_id' => $user_data->ID
    
    ));
    
    //To check for atleast one completed order
    $has_complete_order = false;
    foreach ( $customer_orders as $order ) {
        if ( 'completed' === $order->get_status() ) {
            $has_complete_order = true;
            break;
        }
    }

    
    if ($user_status) {
        $role      = ( array ) $user_data->roles;
        if (!empty($role[0]) && $role[0] == "administrator")
            return $items;
        else {
            $items['backoffice'] = __('Backoffice', 'iconic');
            return $items;
        }
    } 
    else if(!$has_complete_order){
        return $items;
        
    }
    else{
        $items['register'] = __('Register Backoffice', 'iconic');
        return $items;
    }
}
add_filter('woocommerce_account_menu_items', 'wpmlm_customized_woocommerce_menu', 10, 1);

/**
 * [check_mlm_user description]
 * @return [type] [description]
 */
function wpmlm_check_mlm_user()
{
    $current_user = wp_get_current_user();
    $log_user_id  = $current_user->ID;
    return wpmlm_checkUserMlmRegistered($log_user_id);
}

/**
 * Add endpoint
 */
function wpmlm_iconic_add_my_account_endpoint()
{
    add_rewrite_endpoint('backoffice', EP_PAGES);
}
add_action('init', 'wpmlm_iconic_add_my_account_endpoint');

/**
 * Add endpoint
 */
function wpmlm_add_backoffice_register_endpoint()
{
    add_rewrite_endpoint('register', EP_PAGES);
}
add_action('init', 'wpmlm_add_backoffice_register_endpoint');

/**
 * Mlm Backoffice Content
 */
function wpmlm_backoffice_endpoint()
{
    wpmlm_user_area();
}
add_action('woocommerce_account_backoffice_endpoint', 'wpmlm_backoffice_endpoint');


/**
 * Information content
 */
function wpmlm_backoffice_register_content()
{
?>
    <form name="register" action="" method="post" id="backoffice-register-from">
    <!-- Sponsor name -->
    <p class="form-row form-row-wide">
    <label for="reg_billing_sponsor_name"><?php _e('Sponsor Name', 'woocommerce');?>
    <span class="required">*</span>
    </label>
    <input type="text" class="input-text" name="reg_billing_sponsor_name" id="reg_billing_sponsor_name" value="<?php if (!empty($_POST['reg_billing_sponsor_name'])) esc_attr_e($_POST['reg_billing_sponsor_name']); ?>" />
    <?php 
    if (isset($_GET['sponsor_error'])) { ?>
        <label class="error_box"><?php echo $_GET['sponsor_error'];?></label>
    <?php
    }
    ?>
    </p>

    <!-- Date Of Birth -->
    <p class="form-row form-row-wide">
    <label for="reg_billing_date_of_birth"><?php _e('Date Of Birth', 'woocommerce');?>
    <span class="required">*</span>
    </label>
    <input type="text" class="input-text" name="reg_billing_date_of_birth" id="reg_billing_date_of_birth" value="<?php if (!empty($_POST['reg_billing_date_of_birth']))
        esc_attr_e($_POST['reg_billing_date_of_birth']);?>" />
     <?php
    if (isset($_GET['dob_error'])) {
    ?>
        <lbel class="error_box"><?php echo $_GET['dob_error']; ?></label>
    <?php
    }
    ?>
    </p>

    <input type="hidden" name="action" value="contact_form">
    <?php
    wp_nonce_field('reg_to_back', 'reg_to_back_nonce');
    ?>
    <!-- Submit -->
    <p class="form-row form-row-wide">
    <input type="submit" class="input-text" name="register_to_backoffice" id="register_to_backoffice" value="Register" />
    </p>

    </form>
    <?php
}
add_action('woocommerce_account_register_endpoint', 'wpmlm_backoffice_register_content');




function nice_number($n) {
    // first strip any formatting;
    $n = (0+str_replace(",", "", $n));

    // is this a number?
    if (!is_numeric($n)) return false;

    // now filter it;
    if ($n > 1000000000000) return round(($n/1000000000000), 2).' trillion';
    elseif ($n > 1000000000) return round(($n/1000000000), 2).' billion';
    elseif ($n > 1000000) return round(($n/1000000), 2).' million';
    //elseif ($n > 1000) return round(($n/1000), 2).' thousand';

    return number_format($n);
}


function wpmlm_woocommerce_currency_change()
{

    $currency_code = get_option('woocommerce_currency');
    $curr_symbol = get_woocommerce_currency_symbol($currency_code);
    wpmlm_update_woo_currency($curr_symbol,$currency_code);
   
}
add_action( 'woocommerce_settings_saved', 'wpmlm_woocommerce_currency_change');

function session_on_first() {
    if (!session_id()){
        session_start();
    }
}
add_action('wp_loaded','session_on_first');
/* Describe what the code snippet does so you can remember later on */
add_action('wp_head', 'your_function_name');
function your_function_name(){

    if( isset($_SESSION['sponsor'])):
?>
<div class="sponsor" style="text-align:center"><span style="color:#263548;">Referring Member : <b><?php echo $_SESSION['sponsor']; ?></b></span></div>
<?php
    endif;
};