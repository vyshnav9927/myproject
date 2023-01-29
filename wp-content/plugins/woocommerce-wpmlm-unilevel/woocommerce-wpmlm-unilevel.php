<?php
/**
 * Plugin Name: WP MLM Woocommerce - Unilevel Plan
 * Plugin URI: https://wpmlmsoftware.com/
 * Description: WP MLM Plugin for Woocommerce with Unilevel Plan
 * Version: 1.3.0
 * Requires at least: 5.2
 * Tested up to: 5.4
 * Requires PHP: 7.0
 * Author: iOSS
 * Author URI: https://ioss.in/
 * Developer: WP MLM Software
 * Developer URI: https://wpmlmsoftware.com
 * Text Domain: woocommerce-securewpmlm-unilevel
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 4.3.0
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

if (!defined('ABSPATH'))
    exit;

if (!defined('WP_MLM_PLUGIN_NAME'))
    define('WP_MLM_PLUGIN_NAME', plugin_basename( dirname( __FILE__ , 1 )));

// Path and URL
if (!defined('WP_MLM_PLUGIN_DIR'))
    define('WP_MLM_PLUGIN_DIR', WP_PLUGIN_DIR . '/'.WP_MLM_PLUGIN_NAME);

require_once(WP_MLM_PLUGIN_DIR . '/wpmlm-custom-functions.php');
require_once(WP_MLM_PLUGIN_DIR . '/functions/wpmlm-core-db-functions.php');
require_once(WP_MLM_PLUGIN_DIR . '/functions/wpmlm-db-functions.php');
require_once(WP_MLM_PLUGIN_DIR . '/functions/wpmlm-ajax-functions.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-user-income-details.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-user-referrals.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-user-ewallet-details.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-user-ewallet-management.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-admin-area.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-admin-dashboard.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-user-area.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-user-dashboard.php');

require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-user-affiliate.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-level-commission-settings.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-purchase-commission-settings.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-genealogy-tree.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-user-details-admin.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-user-profile-admin.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-settings.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-password-settings.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-general-settings.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-reports.php');
require_once(WP_MLM_PLUGIN_DIR . '/templates/wpmlm-ewallet-management.php');

register_activation_hook(__FILE__, 'wpmlm_install');
register_uninstall_hook(__FILE__, 'wpmlm_uninstall');
// register_deactivation_hook(__FILE__, 'wpmlm_deactivate');


function wpmlm_install() {
    create_wpmlm_users_table();
    create_wpmlm_registration_packages_table();
    create_wpmlm_configuration_table();
    create_wpmlm_level_table();
    create_wpmlm_leg_amount_table();
    create_wpmlm_reg_type_table();
    create_wpmlm_paypal_table();
    create_wpmlm_general_information_table();
    create_wpmlm_fund_transfer_table();
    create_wpmlm_payout_release_requests();
    create_wpmlm_amount_paid();
    create_wpmlm_ewallet_history_table();
    create_wpmlm_transaction_id_table();
    create_wpmlm_tran_password_table();
    create_wpmlm_country_table();
    create_wpmlm_currency_table();
    create_wpmlm_products_purchased_amount_table();
    

    create_wpmlm_user_balance_amount_table();
    insert_wpmlm_first_user();
    insert_wpmlm_country_data();
    insert_wpmlm_currency_data();
    insert_wpmlm_general_information();
    insert_wpmlm_configuration_information();
    insert_wpmlm_reg_type();
    wpmlm_flush_permalinks();
}

function wpmlm_uninstall() {
    wpmlm_delete_user_data();
    wpmlm_drop_tables();
}

add_action('init', 'wpmlm_register_menu');

// load the scripts on only the plugin admin page 
if (isset($_GET['page']) && (($_GET['page'] == 'wpmlm-admin-settings') || ($_GET['page'] == 'wpmlm-user-settings') || ($_GET['page'] == 'wpmlm-users') || ($_GET['page'] == 'wpmlm-genealogy-tree') || ($_GET['page'] == 'wpmlm-e-wallet-management') || ($_GET['page'] == 'wpmlm-reports') || ($_GET['page'] == 'wpmlm-settings') || ($_GET['page'] == 'wpmlm-change-password'))){        
    add_action('admin_enqueue_scripts', 'wpmlm_admin_scripts');        
}

$current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//echo $current_url;

$page_name = basename($current_url);
if($page_name == 'backoffice' || $page_name == 'checkout' || $page_name == 'register'){
    //load frontend script    
    add_action('wp_enqueue_scripts', 'wpmlm_frontend_script');
}
if($page_name == 'backoffice' || $page_name == 'register'){
    add_action('wp_enqueue_scripts', 'wpmlm_frontend_script_bootstrap', 1);
}

function wpmlm_flush_permalinks(){
    global $wp_rewrite; 
    //Flush the rules and tell it to write htaccess
    $wp_rewrite->flush_rules( true );
}


/**
 * Edit .htaccess file on the plugin activation
 * @param  $rules existing .htaccess rules
 * @return append $new_rules with existing rules
 */
function wpmlm_output_htaccess( $rules ) {
$new_rules = <<<EOD
RewriteEngine On  
RewriteCond %{SCRIPT_FILENAME} !-d  
RewriteCond %{SCRIPT_FILENAME} !-f  
RewriteRule ^(\w+)$ ./index.php?id=$1


EOD;
return  $new_rules . $rules;
}
add_filter('mod_rewrite_rules', 'wpmlm_output_htaccess');

//set affiliate link
if (isset($_GET) && isset($_GET['id'])) {
    if (!session_id())
        session_start();
    $username    =    $_GET['id'];
    $check_affliate   =   wpmlm_affiliate( $username );
    if ($check_affliate) {
        
        header( 'location:'. site_url());
        exit();
    }
}



add_action('login_head', 'wpmlm_custom_loginlogo');

}
function wc_wp_mlm_install_woocommerce_admin_notice() {
    ?>
    <div class="error">
        <p><?php esc_html_e( 'WP MLM Woocommerce - Unilevel Plan is enabled but not effective. It requires WooCommerce in order to work.', 'woocommerce-securewpmlm-unilevel' ); ?></p>
    </div>
    <?php
}
function wc_wp_mlm_install() {

    if ( ! function_exists( 'WC' ) ) {
        add_action( 'admin_notices', 'wc_wp_mlm_install_woocommerce_admin_notice' );
    }
}

add_action( 'plugins_loaded', 'wc_wp_mlm_install', 11 );

if ( ! is_plugin_active( 'dokan-lite/dokan.php' ) ) {
    // Show warning message
    add_action( 'admin_notices', 'wc_wp_mlm_install_dokan_admin_notice' );
}

function wc_wp_mlm_install_dokan_admin_notice(){
    ?>
    <div class="error">
    <p><?php esc_html_e( 'WP MLM Woocommerce - Unilevel Plan is enabled but not effective. It requires Dokan plugin in order to work.', 'woocommerce-securewpmlm-unilevel' ); ?></p>
</div>
<?php
}
