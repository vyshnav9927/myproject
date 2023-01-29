<?php
if (!defined('ABSPATH'))
    exit;

function create_wpmlm_users_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpmlm_users';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name			(
    `user_id` int(11) NOT NULL AUTO_INCREMENT,
    `user_ref_id` int(11) NOT NULL DEFAULT '0',
    `user_parent_id` int(11) NOT NULL DEFAULT '0',
    `father_id` int(11) NOT NULL DEFAULT '0',
    `position` int(11) NOT NULL DEFAULT '0',
    `user_first_name` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_second_name` varchar(500) CHARACTER SET utf8 NOT NULL DEFAULT 'NA',
    `user_address` text CHARACTER SET utf8,
    `user_address2` varchar(300) CHARACTER SET utf8 NOT NULL DEFAULT 'NA',
    `user_town` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_country` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_state` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT 'NA',
    `user_city` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT 'NA',
    `user_district` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_zip` varchar(11) CHARACTER SET utf8 NOT NULL DEFAULT 'NA',
    `user_mobile` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_network` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_land` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_email` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_dob` date NOT NULL DEFAULT '0001-01-01',
    `user_gender` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_acnumber` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_ifsc` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_nbank` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_nacct_holder` varchar(100) NOT NULL DEFAULT 'NA',
    `user_nbranch` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_level` int(11) NOT NULL DEFAULT '0',
    `join_date` datetime DEFAULT NULL,
    `package_id` int(11) NULL DEFAULT '0',
    `user_status` int(11) NOT NULL DEFAULT '0',
    `user_registration_type` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',  
    `user_photo` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT 'nophoto.jpg',
    `user_detail_facebook` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT 'http://facebook.com',
    `user_detail_twitter` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT 'http://twitter.com',
    PRIMARY KEY (`user_id`)
                          ) $charset_collate AUTO_INCREMENT=1";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta($sql);
}

function insert_wpmlm_first_user() {
    $id = get_current_user_id();
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $sql = "SELECT * FROM {$table_prefix}users WHERE `ID`='$id' ";
    $results = $wpdb->get_row($sql);


    $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM {$table_prefix}wpmlm_users");

    if ($rowcount == 0) {


        $user_details = array(
            'user_ref_id' => $results->ID,
            'user_parent_id' => 0,
            'user_first_name' => $results->user_login,
            'user_email' => $results->user_email,
            'user_level' => 0,
            'join_date' => date("Y-m-d H:i:s")
        );


        $table_name = $wpdb->prefix . "wpmlm_users";
        $result = $wpdb->insert($table_name, $user_details);



        //$tran_pass = wpmlm_getRandTransPasscode(8);
        $tran_pass = '12345678';
        $hash_tran_pass = wp_hash_password($tran_pass);
        $tran_pass_details = array(
            'user_id' => $results->ID,
            'tran_password' => $hash_tran_pass
        );
        wpmlm_insert_tran_password($tran_pass_details);
        wpmlm_insertBalanceAmount($results->ID);
    }
}

function wpmlm_delete_user_data() {
    global $wpdb;
    $table_prefix=$wpdb->prefix;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $sql = "SELECT user_ref_id FROM {$table_name} WHERE user_ref_id NOT IN (SELECT MIN(user_ref_id) FROM {$table_name}) ORDER BY user_id DESC ";

    $results = $wpdb->get_results($sql);
    $user_id = '';

    foreach ($results as $row) {
        $user_id .= $row->user_ref_id . ",";
    }
    $user_id = substr($user_id, 0, -1);

    $wpdb->query("DELETE FROM {$table_prefix}users WHERE ID IN ($user_id)");
    $wpdb->query("DELETE FROM {$table_prefix}usermeta WHERE user_id IN ($user_id)");
}


function create_wpmlm_unpaid_users_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpmlm_unpaid_users';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name			(
    `user_id` int(11) NOT NULL AUTO_INCREMENT,
    `user_ref_id` int(11) NOT NULL DEFAULT '0',
    `user_parent_id` int(11) NOT NULL DEFAULT '0',
    `father_id` int(11) NOT NULL DEFAULT '0',
    `position` int(11) NOT NULL DEFAULT '0',
    `user_first_name` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_second_name` varchar(500) CHARACTER SET utf8 NOT NULL DEFAULT 'NA',
    `user_address` text CHARACTER SET utf8,
    `user_address2` varchar(300) CHARACTER SET utf8 NOT NULL DEFAULT 'NA',
    `user_town` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_country` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_state` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT 'NA',
    `user_city` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT 'NA',
    `user_district` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_zip` varchar(11) CHARACTER SET utf8 NOT NULL DEFAULT 'NA',
    `user_mobile` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_network` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_land` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_email` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_dob` date NOT NULL DEFAULT '0001-01-01',
    `user_gender` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_acnumber` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_ifsc` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_nbank` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_nacct_holder` varchar(100) NOT NULL DEFAULT 'NA',
    `user_nbranch` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
    `user_level` int(11) NOT NULL DEFAULT '0',
    `join_date` datetime DEFAULT NULL,
    `package_id` int(11) NULL DEFAULT '0',
    `user_status` int(11) NOT NULL DEFAULT '0',
    `user_registration_type` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',  
    `user_photo` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT 'nophoto.jpg',
    `user_detail_facebook` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT 'http://facebook.com',
    `user_detail_twitter` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT 'http://twitter.com',
    PRIMARY KEY (`user_id`)
                          ) $charset_collate AUTO_INCREMENT=1";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta($sql);
}






function wpmlm_drop_tables() {
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_users");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_country");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_currency");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_level_commission");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_leg_amount");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_reg_type");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_paypal");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_registration_packages");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_general_information");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_configuration");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_fund_transfer_details
");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_ewallet_history");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_transaction_id");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_tran_password");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_user_balance_amount");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_payout_release_requests");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_amount_paid");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_products_purchased_amount");
    $wpdb->query("DROP TABLE {$table_prefix}wpmlm_unpaid_users");
    
}



function create_wpmlm_country_table() {
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE {$table_prefix}wpmlm_country
                        (
                                id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                iso CHAR(2) NOT NULL,
                                name VARCHAR(80) NOT NULL,
                                iso3 CHAR(3) DEFAULT NULL,
                                numcode SMALLINT(6) DEFAULT NULL
                        ) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    dbDelta($sql);
}

function create_wpmlm_registration_packages_table() {
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE {$table_prefix}wpmlm_registration_packages (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `package_name` varchar(200) NOT NULL,
    `package_price` double NOT NULL,
    `package_image` varchar(200) NOT NULL DEFAULT 'package_image.jpg',
    PRIMARY KEY (`id`)
    ) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

function create_wpmlm_level_table() {
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE {$table_prefix}wpmlm_level_commission (
    `id` int(12) NOT NULL AUTO_INCREMENT,
    `level_no` int(12) NOT NULL DEFAULT '0',
    `level_percentage` double NOT NULL DEFAULT '0',
    `level_achieve` double NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
    ) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

function create_wpmlm_leg_amount_table() {

    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}wpmlm_leg_amount (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL DEFAULT '0',
    `from_id` int(11) NOT NULL DEFAULT '0',
    `total_leg` int(11) NOT NULL DEFAULT '0',
    `left_leg` int(11) NOT NULL DEFAULT '0',
    `right_leg` int(11) NOT NULL DEFAULT '0',
    `total_amount` float NOT NULL DEFAULT '0',
    `leg_amount_carry` int(20) NOT NULL DEFAULT '0',
    `flush_out_pair` int(20) NOT NULL DEFAULT '0',
    `amount_payable` float NOT NULL DEFAULT '0',
    `amount_type` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT 'NA',
    `tds` float NOT NULL DEFAULT '0',
    `date_of_submission` datetime DEFAULT NULL,
    `service_charge` float NOT NULL DEFAULT '0',
    `paid_status` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT 'no',
    `payout_date` datetime DEFAULT '0001-01-01 00:00:00',
    `paid_current_date` datetime DEFAULT '0001-01-01 00:00:00',
    `user_level` int(20) DEFAULT '0',
    `released_date` date NOT NULL DEFAULT '0001-01-01',
    `paid_date` datetime NOT NULL DEFAULT '0001-01-01 00:00:00',
    `product_id` int(11) NULL DEFAULT '0',
    `pair_value` int(11) NOT NULL DEFAULT '0',
    `product_value` int(11) NULL DEFAULT '0',
    `order_id` double DEFAULT '0',
    PRIMARY KEY (`id`)
    ) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

function create_wpmlm_reg_type_table() {

    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}wpmlm_reg_type (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `reg_type` varchar(200) NOT NULL,
    PRIMARY KEY (`id`)
    ) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

function create_wpmlm_paypal_table() {

    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}wpmlm_paypal (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `paypal_api_username` text NOT NULL,
    `paypal_api_password` text NOT NULL,
    `paypal_api_signature` text NOT NULL,
    `paypal_mode` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
    ) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

function create_wpmlm_general_information_table() {

    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}wpmlm_general_information (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `company_name` varchar(300) NOT NULL,
    `company_address` varchar(500) NOT NULL,
    `company_logo` varchar(300) NOT NULL,
    `company_email` varchar(300) NOT NULL,
    `company_phone` text NOT NULL,
    `company_currency` varchar(100) NOT NULL,
    `currency_code` varchar(100) NOT NULL,
    `registration_type` VARCHAR( 100 ) NOT NULL DEFAULT  'with_package',
    `registration_amt` float NOT NULL DEFAULT '0',

    `site_logo` VARCHAR( 50 ) NOT NULL,
    PRIMARY KEY (`id`)
    ) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}


function create_wpmlm_configuration_table() {

    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}wpmlm_configuration (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `level_commission_type` varchar(100) NOT NULL,
    `level_eligibility` double NOT NULL,
    `width_ceiling` int(11) NOT NULL,
    `self_commission` double NOT NULL,
    `referral_commission` double NOT NULL,
    `minimum_withdraw` double NOT NULL,
    
    PRIMARY KEY (`id`)
    ) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}


function create_wpmlm_tran_password_table() {

    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}wpmlm_tran_password (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `tran_password` varchar(250) NOT NULL,
    PRIMARY KEY (`id`)
    ) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

function create_wpmlm_transaction_id_table() {

    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}wpmlm_transaction_id (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `transaction_id` varchar(100) NOT NULL,
    `status` varchar(30) NOT NULL DEFAULT 'yes',
    `added_date` datetime NOT NULL,
    PRIMARY KEY (`id`)
    ) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

function create_wpmlm_ewallet_history_table() {

    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}wpmlm_ewallet_history (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `from_id` int(11) NOT NULL DEFAULT '0',
    `ewallet_id` int(11) NOT NULL,
    `ewallet_type` varchar(32) NOT NULL,
    `amount` double NOT NULL,
    `amount_type` varchar(32) NOT NULL,
    `type` varchar(32) NOT NULL,
    `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `transaction_id` varchar(50) NOT NULL DEFAULT '',
    `transaction_note` varchar(100) NOT NULL DEFAULT '',
    `transaction_fee` double NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `from_id` (`from_id`)
    ) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

function create_wpmlm_fund_transfer_table() {

    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}wpmlm_fund_transfer_details (
    `id` int(20) NOT NULL AUTO_INCREMENT,
    `from_user_id` int(20) NOT NULL DEFAULT '0',
    `to_user_id` int(20) NOT NULL DEFAULT '0',
    `amount` double NOT NULL DEFAULT '0',
    `date` datetime NOT NULL DEFAULT '0001-01-01 00:00:00',
    `amount_type` varchar(20) CHARACTER SET utf8 NOT NULL,
    `transaction_concept` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
    `trans_fee` double NOT NULL DEFAULT '0',
    `transaction_id` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
    ) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}


function create_wpmlm_payout_release_requests() {

    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}wpmlm_payout_release_requests (
  `req_id` int(11) NOT NULL AUTO_INCREMENT,
  `requested_user_id` int(11) NOT NULL DEFAULT '0',
  `requested_amount` double NOT NULL DEFAULT '0',
  `requested_amount_balance` double NOT NULL DEFAULT '0',
  `requested_date` datetime NOT NULL DEFAULT '0001-01-01 00:00:00',
  `updated_date` datetime DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT 'NA',
  PRIMARY KEY (`req_id`)
) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}


function create_wpmlm_amount_paid() {

    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}wpmlm_amount_paid (
  `paid_id` int(11) NOT NULL AUTO_INCREMENT,
  `paid_user_id` int(11) NOT NULL DEFAULT '0',
  `paid_amount` double NOT NULL DEFAULT '0',
  `paid_date` datetime NOT NULL DEFAULT '0001-01-01 00:00:00',
  `paid_level` int(11) DEFAULT '0',
  `paid_type` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT 'NA',
  `transaction_id` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  PRIMARY KEY (`paid_id`)
) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}


function create_wpmlm_user_balance_amount_table() {

    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}wpmlm_user_balance_amount (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `balance_amount` double NOT NULL,
    `total_bv` double NOT NULL,
    PRIMARY KEY (`id`)
    ) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

function insert_wpmlm_reg_type() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_reg_type";
    $sql = "select count(*) from {$table_name}";
    $num = $wpdb->get_var($sql);
    
    if($num<1){
    $data = array(
        'reg_type' => 'free_join'
    );
    
    $result = $wpdb->insert($table_name, $data);
    }
}

function insert_wpmlm_configuration_information() {
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $table_name = $wpdb->prefix . "wpmlm_configuration";
    $sql = "select count(*) from {$table_name}";
    $num = $wpdb->get_var($sql);
    
    if($num<1){
        $configuration = array(
            'level_commission_type' => 'percentage'
        );        
        $result = $wpdb->insert($table_name, $configuration);
    }
}

function insert_wpmlm_general_information() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_general_information";
    $sql = "select count(*) from {$table_name}";
    $num = $wpdb->get_var($sql);

    $curr_symbol = get_woocommerce_currency_symbol();
    $currency_code = get_woocommerce_currency();
    
    if($num<1){
    
    $general_information = array(
        'company_name' => 'WP MLM',
        'company_email' => 'companyname@domain.com',
        'company_phone' => '0123456789',
        'company_address' => 'Test Address',
        'company_logo' => 'default_logo.png',
        'company_currency' => $curr_symbol,
        'currency_code' => $currency_code
    );
    
    $result = $wpdb->insert($table_name, $general_information);
    }
}

function insert_wpmlm_country_data() {
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "INSERT INTO {$table_prefix}wpmlm_country (`id`, `iso`, `name`, `iso3`, `numcode`) VALUES
				(1, 'AF', 'Afghanistan', 'AFG', 4),
				(2, 'AL', 'Albania', 'ALB', 8),
				(3, 'DZ', 'Algeria', 'DZA', 12),
				(4, 'AS', 'American Samoa', 'ASM', 16),
				(5, 'AD', 'Andorra', 'AND', 20),
				(6, 'AO', 'Angola', 'AGO', 24),
				(7, 'AI', 'Anguilla', 'AIA', 660),
				(8, 'AQ', 'Antarctica', NULL, NULL),
				(9, 'AG', 'Antigua and Barbuda', 'ATG', 28),
				(10, 'AR', 'Argentina', 'ARG', 32),
				(11, 'AM', 'Armenia', 'ARM', 51),
				(12, 'AW', 'Aruba', 'ABW', 533),
				(13, 'AU', 'Australia', 'AUS', 36),
				(14, 'AT', 'Austria', 'AUT', 40),
				(15, 'AZ', 'Azerbaijan', 'AZE', 31),
				(16, 'BS', 'Bahamas', 'BHS', 44),
				(17, 'BH', 'Bahrain', 'BHR', 48),
				(18, 'BD', 'Bangladesh', 'BGD', 50),
				(19, 'BB', 'Barbados', 'BRB', 52),
				(20, 'BY', 'Belarus', 'BLR', 112),
				(21, 'BE', 'Belgium', 'BEL', 56),
				(22, 'BZ', 'Belize', 'BLZ', 84),
				(23, 'BJ', 'Benin', 'BEN', 204),
				(24, 'BM', 'Bermuda', 'BMU', 60),
				(25, 'BT', 'Bhutan', 'BTN', 64),
				(26, 'BO', 'Bolivia', 'BOL', 68),
				(27, 'BA', 'Bosnia and Herzegovina', 'BIH', 70),
				(28, 'BW', 'Botswana', 'BWA', 72),
				(29, 'BV', 'Bouvet Island', NULL, NULL),
				(30, 'BR', 'Brazil', 'BRA', 76),
				(31, 'IO', 'British Indian Ocean Territory', NULL, NULL),
				(32, 'BN', 'Brunei Darussalam', 'BRN', 96),
				(33, 'BG', 'Bulgaria', 'BGR', 100),
				(34, 'BF', 'Burkina Faso', 'BFA', 854),
				(35, 'BI', 'Burundi', 'BDI', 108),
				(36, 'KH', 'Cambodia', 'KHM', 116),
				(37, 'CM', 'Cameroon', 'CMR', 120),
				(38, 'CA', 'Canada', 'CAN', 124),
				(39, 'CV', 'Cape Verde', 'CPV', 132),
				(40, 'KY', 'Cayman Islands', 'CYM', 136),
				(41, 'CF', 'Central African Republic', 'CAF', 140),
				(42, 'TD', 'Chad', 'TCD', 148),
				(43, 'CL', 'Chile', 'CHL', 152),
				(44, 'CN', 'China', 'CHN', 156),
				(45, 'CX', 'Christmas Island', NULL, NULL),
				(46, 'CC', 'Cocos (Keeling) Islands', NULL, NULL),
				(47, 'CO', 'Colombia', 'COL', 170),
				(48, 'KM', 'Comoros', 'COM', 174),
				(49, 'CG', 'Congo', 'COG', 178),
				(50, 'CD', 'Congo, the Democratic Republic of the', 'COD', 180),
				(51, 'CK', 'Cook Islands', 'COK', 184),
				(52, 'CR', 'Costa Rica', 'CRI', 188),
				(53, 'CI', 'Cote D''Ivoire', 'CIV', 384),
				(54, 'HR', 'Croatia', 'HRV', 191),
				(55, 'CU', 'Cuba', 'CUB', 192),
				(56, 'CY', 'Cyprus', 'CYP', 196),
				(57, 'CZ', 'Czech Republic', 'CZE', 203),
				(58, 'DK', 'Denmark', 'DNK', 208),
				(59, 'DJ', 'Djibouti', 'DJI', 262),
				(60, 'DM', 'Dominica', 'DMA', 212),
				(61, 'DO', 'Dominican Republic', 'DOM', 214),
				(62, 'EC', 'Ecuador', 'ECU', 218),
				(63, 'EG', 'Egypt', 'EGY', 818),
				(64, 'SV', 'El Salvador', 'SLV', 222),
				(65, 'GQ', 'Equatorial Guinea', 'GNQ', 226),
				(66, 'ER', 'Eritrea', 'ERI', 232),
				(67, 'EE', 'Estonia', 'EST', 233),
				(68, 'ET', 'Ethiopia', 'ETH', 231),
				(69, 'FK', 'Falkland Islands (Malvinas)', 'FLK', 238),
				(70, 'FO', 'Faroe Islands', 'FRO', 234),
				(71, 'FJ', 'Fiji', 'FJI', 242),
				(72, 'FI', 'Finland', 'FIN', 246),
				(73, 'FR', 'France', 'FRA', 250),
				(74, 'GF', 'French Guiana', 'GUF', 254),
				(75, 'PF', 'French Polynesia', 'PYF', 258),
				(76, 'TF', 'French Southern Territories', NULL, NULL),
				(77, 'GA', 'Gabon', 'GAB', 266),
				(78, 'GM', 'Gambia', 'GMB', 270),
				(79, 'GE', 'Georgia', 'GEO', 268),
				(80, 'DE', 'Germany', 'DEU', 276),
				(81, 'GH', 'Ghana', 'GHA', 288),
				(82, 'GI', 'Gibraltar', 'GIB', 292),
				(83, 'GR', 'Greece', 'GRC', 300),
				(84, 'GL', 'Greenland', 'GRL', 304),
				(85, 'GD', 'Grenada', 'GRD', 308),
				(86, 'GP', 'Guadeloupe', 'GLP', 312),
				(87, 'GU', 'Guam', 'GUM', 316),
				(88, 'GT', 'Guatemala', 'GTM', 320),
				(89, 'GN', 'Guinea', 'GIN', 324),
				(90, 'GW', 'Guinea-Bissau', 'GNB', 624),
				(91, 'GY', 'Guyana', 'GUY', 328),
				(92, 'HT', 'Haiti', 'HTI', 332),
				(93, 'HM', 'Heard Island and Mcdonald Islands', NULL, NULL),
				(94, 'VA', 'Holy See (Vatican City State)', 'VAT', 336),
				(95, 'HN', 'Honduras', 'HND', 340),
				(96, 'HK', 'Hong Kong', 'HKG', 344),
				(97, 'HU', 'Hungary', 'HUN', 348),
				(98, 'IS', 'Iceland', 'ISL', 352),
				(99, 'IN', 'India', 'IND', 356),
				(100, 'ID', 'Indonesia', 'IDN', 360),
				(101, 'IR', 'Iran, Islamic Republic of', 'IRN', 364),
				(102, 'IQ', 'Iraq', 'IRQ', 368),
				(103, 'IE', 'Ireland', 'IRL', 372),
				(104, 'IL', 'Israel', 'ISR', 376),
				(105, 'IT', 'Italy', 'ITA', 380),
				(106, 'JM', 'Jamaica', 'JAM', 388),
				(107, 'JP', 'Japan', 'JPN', 392),
				(108, 'JO', 'Jordan', 'JOR', 400),
				(109, 'KZ', 'Kazakhstan', 'KAZ', 398),
				(110, 'KE', 'Kenya', 'KEN', 404),
				(111, 'KI', 'Kiribati', 'KIR', 296),
				(112, 'KP', 'Korea, Democratic People''s Republic of', 'PRK', 408),
				(113, 'KR', 'Korea, Republic of', 'KOR', 410),
				(114, 'KW', 'Kuwait', 'KWT', 414),
				(115, 'KG', 'Kyrgyzstan', 'KGZ', 417),
				(116, 'LA', 'Lao People''s Democratic Republic', 'LAO', 418),
				(117, 'LV', 'Latvia', 'LVA', 428),
				(118, 'LB', 'Lebanon', 'LBN', 422),
				(119, 'LS', 'Lesotho', 'LSO', 426),
				(120, 'LR', 'Liberia', 'LBR', 430),
				(121, 'LY', 'Libyan Arab Jamahiriya', 'LBY', 434),
				(122, 'LI', 'Liechtenstein', 'LIE', 438),
				(123, 'LT', 'Lithuania', 'LTU', 440),
				(124, 'LU', 'Luxembourg', 'LUX', 442),
				(125, 'MO', 'Macao', 'MAC', 446),
				(126, 'MK', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807),
				(127, 'MG', 'Madagascar', 'MDG', 450),
				(128, 'MW', 'Malawi', 'MWI', 454),
				(129, 'MY', 'Malaysia', 'MYS', 458),
				(130, 'MV', 'Maldives', 'MDV', 462),
				(131, 'ML', 'Mali', 'MLI', 466),
				(132, 'MT', 'Malta', 'MLT', 470),
				(133, 'MH', 'Marshall Islands', 'MHL', 584),
				(134, 'MQ', 'Martinique', 'MTQ', 474),
				(135, 'MR', 'Mauritania', 'MRT', 478),
				(136, 'MU', 'Mauritius', 'MUS', 480),
				(137, 'YT', 'Mayotte', NULL, NULL),
				(138, 'MX', 'Mexico', 'MEX', 484),
				(139, 'FM', 'Micronesia, Federated States of', 'FSM', 583),
				(140, 'MD', 'Moldova, Republic of', 'MDA', 498),
				(141, 'MC', 'Monaco', 'MCO', 492),
				(142, 'MN', 'Mongolia', 'MNG', 496),
				(143, 'MS', 'Montserrat', 'MSR', 500),
				(144, 'MA', 'Morocco', 'MAR', 504),
				(145, 'MZ', 'Mozambique', 'MOZ', 508),
				(146, 'MM', 'Myanmar', 'MMR', 104),
				(147, 'NA', 'Namibia', 'NAM', 516),
				(148, 'NR', 'Nauru', 'NRU', 520),
				(149, 'NP', 'Nepal', 'NPL', 524),
				(150, 'NL', 'Netherlands', 'NLD', 528),
				(151, 'AN', 'Netherlands Antilles', 'ANT', 530),
				(152, 'NC', 'New Caledonia', 'NCL', 540),
				(153, 'NZ', 'New Zealand', 'NZL', 554),
				(154, 'NI', 'Nicaragua', 'NIC', 558),
				(155, 'NE', 'Niger', 'NER', 562),
				(156, 'NG', 'Nigeria', 'NGA', 566),
				(157, 'NU', 'Niue', 'NIU', 570),
				(158, 'NF', 'Norfolk Island', 'NFK', 574),
				(159, 'MP', 'Northern Mariana Islands', 'MNP', 580),
				(160, 'NO', 'Norway', 'NOR', 578),
				(161, 'OM', 'Oman', 'OMN', 512),
				(162, 'PK', 'Pakistan', 'PAK', 586),
				(163, 'PW', 'Palau', 'PLW', 585),
				(164, 'PS', 'Palestinian Territory, Occupied', NULL, NULL),
				(165, 'PA', 'Panama', 'PAN', 591),
				(166, 'PG', 'Papua New Guinea', 'PNG', 598),
				(167, 'PY', 'Paraguay', 'PRY', 600),
				(168, 'PE', 'Peru', 'PER', 604),
				(169, 'PH', 'Philippines', 'PHL', 608),
				(170, 'PN', 'Pitcairn', 'PCN', 612),
				(171, 'PL', 'Poland', 'POL', 616),
				(172, 'PT', 'Portugal', 'PRT', 620),
				(173, 'PR', 'Puerto Rico', 'PRI', 630),
				(174, 'QA', 'Qatar', 'QAT', 634),
				(175, 'RE', 'Reunion', 'REU', 638),
				(176, 'RO', 'Romania', 'ROM', 642),
				(177, 'RU', 'Russian Federation', 'RUS', 643),
				(178, 'RW', 'Rwanda', 'RWA', 646),
				(179, 'SH', 'Saint Helena', 'SHN', 654),
				(180, 'KN', 'Saint Kitts and Nevis', 'KNA', 659),
				(181, 'LC', 'Saint Lucia', 'LCA', 662),
				(182, 'PM', 'Saint Pierre and Miquelon', 'SPM', 666),
				(183, 'VC', 'Saint Vincent and the Grenadines', 'VCT', 670),
				(184, 'WS', 'Samoa', 'WSM', 882),
				(185, 'SM', 'San Marino', 'SMR', 674),
				(186, 'ST', 'Sao Tome and Principe', 'STP', 678),
				(187, 'SA', 'Saudi Arabia', 'SAU', 682),
				(188, 'SN', 'Senegal', 'SEN', 686),
				(189, 'CS', 'Serbia and Montenegro', NULL, NULL),
				(190, 'SC', 'Seychelles', 'SYC', 690),
				(191, 'SL', 'Sierra Leone', 'SLE', 694),
				(192, 'SG', 'Singapore', 'SGP', 702),
				(193, 'SK', 'Slovakia', 'SVK', 703),
				(194, 'SI', 'Slovenia', 'SVN', 705),
				(195, 'SB', 'Solomon Islands', 'SLB', 90),
				(196, 'SO', 'Somalia', 'SOM', 706),
				(197, 'ZA', 'South Africa', 'ZAF', 710),
				(198, 'GS', 'South Georgia and the South Sandwich Islands', NULL, NULL),
				(199, 'ES', 'Spain', 'ESP', 724),
				(200, 'LK', 'Sri Lanka', 'LKA', 144),
				(201, 'SD', 'Sudan', 'SDN', 736),
				(202, 'SR', 'Suriname', 'SUR', 740),
				(203, 'SJ', 'Svalbard and Jan Mayen', 'SJM', 744),
				(204, 'SZ', 'Swaziland', 'SWZ', 748),
				(205, 'SE', 'Sweden', 'SWE', 752),
				(206, 'CH', 'Switzerland', 'CHE', 756),
				(207, 'SY', 'Syrian Arab Republic', 'SYR', 760),
				(208, 'TW', 'Taiwan, Province of China', 'TWN', 158),
				(209, 'TJ', 'Tajikistan', 'TJK', 762),
				(210, 'TZ', 'Tanzania, United Republic of', 'TZA', 834),
				(211, 'TH', 'Thailand', 'THA', 764),
				(212, 'TL', 'Timor-Leste', NULL, NULL),
				(213, 'TG', 'Togo', 'TGO', 768),
				(214, 'TK', 'Tokelau', 'TKL', 772),
				(215, 'TO', 'Tonga', 'TON', 776),
				(216, 'TT', 'Trinidad and Tobago', 'TTO', 780),
				(217, 'TN', 'Tunisia', 'TUN', 788),
				(218, 'TR', 'Turkey', 'TUR', 792),
				(219, 'TM', 'Turkmenistan', 'TKM', 795),
				(220, 'TC', 'Turks and Caicos Islands', 'TCA', 796),
				(221, 'TV', 'Tuvalu', 'TUV', 798),
				(222, 'UG', 'Uganda', 'UGA', 800),
				(223, 'UA', 'Ukraine', 'UKR', 804),
				(224, 'AE', 'United Arab Emirates', 'ARE', 784),
				(225, 'GB', 'United Kingdom', 'GBR', 826),
				(226, 'US', 'United States', 'USA', 840),
				(227, 'UM', 'United States Minor Outlying Islands', NULL, NULL),
				(228, 'UY', 'Uruguay', 'URY', 858),
				(229, 'UZ', 'Uzbekistan', 'UZB', 860),
				(230, 'VU', 'Vanuatu', 'VUT', 548),
				(231, 'VE', 'Venezuela', 'VEN', 862),
				(232, 'VN', 'Viet Nam', 'VNM', 704),
				(233, 'VG', 'Virgin Islands, British', 'VGB', 92),
				(234, 'VI', 'Virgin Islands, U.s.', 'VIR', 850),
				(235, 'WF', 'Wallis and Futuna', 'WLF', 876),
				(236, 'EH', 'Western Sahara', 'ESH', 732),
				(237, 'YE', 'Yemen', 'YEM', 887),
				(238, 'ZM', 'Zambia', 'ZMB', 894),
				(239, 'ZW', 'Zimbabwe', 'ZWE', 716)";
    $wpdb->query($sql);
}


function create_wpmlm_currency_table() {

    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}wpmlm_currency (
    `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `symbol` text NOT NULL,
  PRIMARY KEY (`id`)
    ) {$charset_collate} AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}


function insert_wpmlm_currency_data() {
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "INSERT INTO {$table_prefix}wpmlm_currency (`id`, `code`, `country`, `symbol`) VALUES
                (1, 'AED', 'United Arab Emirates dirham', '&#x62f;&#x625;'),
(2, 'AFN', 'Afghan afghani', '&#x60b;'),
(3, 'ALL', 'Albanian lek', 'L'),
(4, 'AMD', 'Armenian dram', 'AMD'),
(5, 'ANG', 'Netherlands Antillean guilder', '&fnof;'),
(6, 'AOA', 'Angolan kwanza', 'Kz'),
(7, 'ARS', 'Argentine peso', '&#036;'),
(8, 'AUD', 'Australian dollar', '&#036;'),
(9, 'AWG', 'Aruban florin', 'Afl.'),
(10, 'AZN', 'Azerbaijani manat', 'AZN'),
(11, 'BAM', 'Bosnia and Herzegovina convertible mark', 'KM'),
(12, 'BBD', 'Barbadian dollar', '&#036;'),
(13, 'BDT', 'Bangladeshi taka', '&#2547;&nbsp;'),
(14, 'BGN', 'Bulgarian lev', '&#1083;&#1074;'),
(15, 'BHD', 'Bahraini dinar', '&#x62f;&#x628;'),
(16, 'BIF', 'Burundian franc', 'Fr'),
(17, 'BMD', 'Bermudian dollar', '&#036;'),
(18, 'BND', 'Brunei dollar', '&#036;'),
(19, 'BOB', 'Bolivian boliviano', 'Bs.'),
(20, 'BRL', 'Brazilian real', '&#082;&#036;'),
(21, 'BSD', 'Bahamian dollar', '&#036;'),
(22, 'BTC', 'Bitcoin', '&#3647;'),
(23, 'BTN', 'Bhutanese ngultrum', 'Nu.'),
(24, 'BWP', 'Botswana pula', 'P'),
(25, 'BYR', 'Belarusian ruble (old)', 'Br'),
(26, 'AMD', 'Armenian dram', 'AMD'),
(27, 'ANG', 'Netherlands Antillean guilder', '&fnof;'),
(28, 'AOA', 'Angolan kwanza', 'Kz'),
(29, 'ARS', 'Argentine peso', '&#036;'),
(30, 'AUD', 'Australian dollar', '&#036;'),
(31, 'AWG', 'Aruban florin', 'Afl.'),
(32, 'AZN', 'Azerbaijani manat', 'AZN'),
(33, 'BAM', 'Bosnia and Herzegovina convertible mark', 'KM'),
(34, 'BBD', 'Barbadian dollar', '&#036;'),
(35, 'BDT', 'Bangladeshi taka', '&#2547;&nbsp;'),
(36, 'BGN', 'Bulgarian lev', '&#1083;&#1074;'),
(37, 'BHD', 'Bahraini dinar', '&#x62f;&#x628;'),
(38, 'BIF', 'Burundian franc', 'Fr'),
(39, 'BMD', 'Bermudian dollar', '&#036;'),
(40, 'BND', 'Brunei dollar', '&#036;'),
(41, 'BOB', 'Bolivian boliviano', 'Bs.'),
(42, 'BRL', 'Brazilian real', '&#082;&#036;'),
(43, 'BSD', 'Bahamian dollar', '&#036;'),
(44, 'BTC', 'Bitcoin', '&#3647;'),
(45, 'BTN', 'Bhutanese ngultrum', 'Nu.'),
(46, 'BWP', 'Botswana pula', 'P'),
(47, 'BYR', 'Belarusian ruble (old)', 'Br'),
(48, 'BYN', 'Belarusian ruble', 'Br'),
(49, 'BZD', 'Belize dollar', '&#036;'),
(50, 'CAD', 'Canadian dollar', '&#036;'),
(51, 'CDF', 'Congolese franc', 'Fr'),
(52, 'CHF', 'Swiss franc', '&#067;&#072;&#070;'),
(53, 'CLP', 'Chilean peso', '&#036;'),
(54, 'CNY', 'Chinese yuan', '&yen;'),
(55, 'COP', 'Colombian peso', '&#036;'),
(56, 'CRC', 'Costa Rican col&oacute;n', '&#x20a1;'),
(57, 'CUC', 'Cuban convertible peso', '&#036;'),
(58, 'CUP', 'Cuban peso', '&#036;'),
(59, 'CVE', 'Cape Verdean escudo', '&#036;'),
(60, 'CZK', 'Czech koruna', '&#075;&#269;'),
(61, 'DJF', 'Djiboutian franc', 'Fr'),
(62, 'DKK', 'Danish krone', 'DKK'),
(63, 'DOP', 'Dominican peso', 'RD&#036;'),
(64, 'DZD', 'Algerian dinar', '&#x62f;&#x62c;'),
(65, 'EGP', 'Egyptian pound', 'EGP'),
(66, 'ERN', 'Eritrean nakfa', 'Nfk'),
(67, 'ETB', 'Ethiopian birr', 'Br'),
(68, 'EUR', 'Euro', '&euro;'),
(69, 'FJD', 'Fijian dollar', '&#036;'),
(70, 'FKP', 'Falkland Islands pound', '&pound;'),
(71, 'GBP', 'Pound sterling', '&pound;'),
(72, 'GEL', 'Georgian lari', '&#x20be;'),
(73, 'GGP', 'Guernsey pound', '&pound;'),
(74, 'GHS', 'Ghana cedi', '&#x20b5;'),
(75, 'GIP', 'Gibraltar pound', '&pound;'),
(76, 'GMD', 'Gambian dalasi', 'D'),
(77, 'GNF', 'Guinean franc', 'Fr'),
(78, 'GTQ', 'Guatemalan quetzal', 'Q'),
(79, 'GYD', 'Guyanese dollar', '&#036;'),
(80, 'HKD', 'Hong Kong dollar', '&#036;'),
(81, 'HNL', 'Honduran lempira', 'L'),
(82, 'HRK', 'Croatian kuna', 'kn'),
(83, 'HTG', 'Haitian gourde', 'G'),
(84, 'HUF', 'Hungarian forint', '&#070;&#116;'),
(85, 'IDR', 'Indonesian rupiah', 'Rp'),
(86, 'ILS', 'Israeli new shekel', '&#8362;'),
(87, 'IMP', 'Manx pound', '&pound;'),
(88, 'INR', 'Indian rupee', '&#8377;'),
(89, 'IQD', 'Iraqi dinar', '&#x639;&#x62f;'),
(90, 'IRR', 'Iranian rial', '&#xfdfc;'),
(91, 'IRT', 'Iranian toman', '&#x62A;&#x648;&#x645;&#x627;&#x646;'),
(92, 'ISK', 'Icelandic kr&oacute;na', 'kr.'),
(93, 'JEP', 'Jersey pound', '&pound;'),
(94, 'JMD', 'Jamaican dollar', '&#036;'),
(95, 'JOD', 'Jordanian dinar', '&#x62f;&#x627;'),
(96, 'JPY', 'Japanese yen', '&yen;'),
(97, 'KES', 'Kenyan shilling', 'KSh'),
(98, 'KGS', 'Kyrgyzstani som', '&#x441;&#x43e;&#x43c;'),
(99, 'KHR', 'Cambodian riel', '&#x17db;'),
(100, 'KMF', 'Comorian franc', 'Fr'),
(101, 'KPW', 'North Korean won', '&#x20a9;'),
(102, 'KRW', 'South Korean won', '&#8361;'),
(103, 'KWD', 'Kuwaiti dinar', '&#x62f;&#x643;'),
(104, 'KYD', 'Cayman Islands dollar', '&#036;'),
(105, 'KZT', 'Kazakhstani tenge', 'KZT'),
(106, 'LAK', 'Lao kip', '&#8365;'),
(107, 'LBP', 'Lebanese pound', '&#x644;&#x644;'),
(108, 'LKR', 'Sri Lankan rupee', '&#xdbb;&#xdd4;'),
(109, 'LRD', 'Liberian dollar', '&#036;'),
(110, 'LSL', 'Lesotho loti', 'L'),
(111, 'LYD', 'Libyan dinar', '&#x644;&#x62f;'),
(112, 'MAD', 'Moroccan dirham', '&#x62f;&#x645;'),
(113, 'MDL', 'Moldovan leu', 'MDL'),
(114, 'MGA', 'Malagasy ariary', 'Ar'),
(115, 'MKD', 'Macedonian denar', '&#x434;&#x435;&#x43d;'),
(116, 'MMK', 'Burmese kyat', 'Ks'),
(117, 'MNT', 'Mongolian t&ouml;gr&ouml;g', '&#x20ae;'),
(118, 'MOP', 'Macanese pataca', 'P'),
(119, 'MRO', 'Mauritanian ouguiya', 'UM'),
(120, 'MUR', 'Mauritian rupee', '&#x20a8;'),
(121, 'MVR', 'Maldivian rufiyaa', '&#x783;'),
(122, 'MWK', 'Malawian kwacha', 'MK'),
(123, 'MXN', 'Mexican peso', '&#036;'),
(124, 'MYR', 'Malaysian ringgit', '&#082;&#077;'),
(125, 'MZN', 'Mozambican metical', 'MT'),
(126, 'NAD', 'Namibian dollar', '&#036;'),
(127, 'NGN', 'Nigerian naira', '&#8358;'),
(128, 'NIO', 'Nicaraguan c&oacute;rdoba', 'C&#036;'),
(129, 'NOK', 'Norwegian krone', '&#107;&#114;'),
(130, 'NPR', 'Nepalese rupee', '&#8360;'),
(131, 'NZD', 'New Zealand dollar', '&#036;'),
(132, 'OMR', 'Omani rial', '&#x631;&#x639;'),
(133, 'PAB', 'Panamanian balboa', 'B/.'),
(134, 'PEN', 'Sol', 'S/'),
(135, 'PGK', 'Papua New Guinean kina', 'K'),
(136, 'PHP', 'Philippine peso', '&#8369;'),
(137, 'PKR', 'Pakistani rupee', '&#8360;'),
(138, 'PLN', 'Polish z&#x142;oty', '&#122;&#322;'),
(139, 'PRB', 'Transnistrian ruble', '&#x440;'),
(140, 'PYG', 'Paraguayan guaran&iacute;', '&#8370;'),
(141, 'QAR', 'Qatari riyal', '&#x631;&#x642;'),
(142, 'RON', 'Romanian leu', 'lei'),
(143, 'RSD', 'Serbian dinar', '&#x434;&#x438;&#x43d;'),
(144, 'RUB', 'Russian ruble', '&#8381;'),
(145, 'RWF', 'Rwandan franc', 'Fr'),
(146, 'SAR', 'Saudi riyal', '&#x631;&#x633;'),
(147, 'NAD', 'Namibian dollar', '&#036;'),
(148, 'NGN', 'Nigerian naira', '&#8358;'),
(149, 'NIO', 'Nicaraguan c&oacute;rdoba', 'C&#036;'),
(150, 'NOK', 'Norwegian krone', '&#107;&#114;'),
(151, 'NPR', 'Nepalese rupee', '&#8360;'),
(152, 'NZD', 'New Zealand dollar', '&#036;'),
(153, 'OMR', 'Omani rial', '&#x631;&#x639;'),
(154, 'PAB', 'Panamanian balboa', 'B/.'),
(155, 'PEN', 'Sol', 'S/'),
(156, 'PGK', 'Papua New Guinean kina', 'K'),
(157, 'PHP', 'Philippine peso', '&#8369;'),
(158, 'PKR', 'Pakistani rupee', '&#8360;'),
(159, 'PLN', 'Polish z&#x142;oty', '&#122;&#322;'),
(160, 'PRB', 'Transnistrian ruble', '&#x440;'),
(161, 'PYG', 'Paraguayan guaran&iacute;', '&#8370;'),
(162, 'QAR', 'Qatari riyal', '&#x631;&#x642;'),
(163, 'RON', 'Romanian leu', 'lei'),
(164, 'RSD', 'Serbian dinar', '&#x434;&#x438;&#x43d;'),
(165, 'RUB', 'Russian ruble', '&#8381;'),
(166, 'RWF', 'Rwandan franc', 'Fr'),
(167, 'SAR', 'Saudi riyal', '&#x631;&#x633;'),
(168, 'SBD', 'Solomon Islands dollar', '&#036;'),
(169, 'NAD', 'Namibian dollar', '&#036;'),
(170, 'NGN', 'Nigerian naira', '&#8358;'),
(171, 'NIO', 'Nicaraguan c&oacute;rdoba', 'C&#036;'),
(172, 'NOK', 'Norwegian krone', '&#107;&#114;'),
(173, 'NPR', 'Nepalese rupee', '&#8360;'),
(174, 'NZD', 'New Zealand dollar', '&#036;'),
(175, 'OMR', 'Omani rial', '&#x631;&#x639;'),
(176, 'PAB', 'Panamanian balboa', 'B/.'),
(177, 'PEN', 'Sol', 'S/'),
(178, 'PGK', 'Papua New Guinean kina', 'K'),
(179, 'PHP', 'Philippine peso', '&#8369;'),
(180, 'PKR', 'Pakistani rupee', '&#8360;'),
(181, 'PLN', 'Polish z&#x142;oty', '&#122;&#322;'),
(182, 'PRB', 'Transnistrian ruble', '&#x440;'),
(183, 'PYG', 'Paraguayan guaran&iacute;', '&#8370;'),
(184, 'QAR', 'Qatari riyal', '&#x631;&#x642;'),
(185, 'RON', 'Romanian leu', 'lei'),
(186, 'RSD', 'Serbian dinar', '&#x434;&#x438;&#x43d;'),
(187, 'RUB', 'Russian ruble', '&#8381;'),
(188, 'RWF', 'Rwandan franc', 'Fr'),
(189, 'SAR', 'Saudi riyal', '&#x631;&#x633;'),
(190, 'SBD', 'Solomon Islands dollar', '&#036;'),
(191, 'SCR', 'Seychellois rupee', '&#x20a8;'),
(192, 'SDG', 'Sudanese pound', '&#x62c;&#x633;.'),
(193, 'SEK', 'Swedish krona', '&#107;&#114;'),
(194, 'SGD', 'Singapore dollar', '&#036;'),
(195, 'SHP', 'Saint Helena pound', '&pound;'),
(196, 'SLL', 'Sierra Leonean leone', 'Le'),
(197, 'SOS', 'Somali shilling', 'Sh'),
(198, 'SRD', 'Surinamese dollar', '&#036;'),
(199, 'SSP', 'South Sudanese pound', '&pound;'),
(200, 'STD', 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'Db'),
(201, 'SYP', 'Syrian pound', '&#x644;&#x633;'),
(202, 'SZL', 'Swazi lilangeni', 'L'),
(203, 'THB', 'Thai baht', '&#3647;'),
(204, 'TJS', 'Tajikistani somoni', '&#x405;&#x41c;'),
(205, 'TMT', 'Turkmenistan manat', 'm'),
(206, 'TND', 'Tunisian dinar', '&#x62f;&#x62a;'),
(207, 'TOP', 'Tongan pa&#x2bb;anga', 'T&#036;'),
(208, 'TRY', 'Turkish lira', '&#8378;'),
(209, 'TTD', 'Trinidad and Tobago dollar', '&#036;'),
(210, 'TWD', 'New Taiwan dollar', '&#078;&#084;&#036;'),
(211, 'TZS', 'Tanzanian shilling', 'Sh'),
(212, 'UAH', 'Ukrainian hryvnia', '&#8372;'),
(213, 'UGX', 'Ugandan shilling', 'UGX'),
(214, 'USD', 'United States(US) dollar', '&#036;'),
(215, 'UYU', 'Uruguayan peso', '&#036;'),
(216, 'UZS', 'Uzbekistani som', 'UZS'),
(217, 'VEF', 'Venezuelan bol&iacute;var', 'Bs F'),
(218, 'VES', 'Bol&iacute;var soberano', 'Bs.S'),
(219, 'VND', 'Vietnamese &#x111;&#x1ed3;ng', '&#8363;'),
(220, 'VUV', 'Vanuatu vatu', 'Vt'),
(221, 'WST', 'Samoan t&#x101;l&#x101;', 'T'),
(222, 'XAF', 'Central African CFA franc', 'CFA'),
(223, 'NAD', 'Namibian dollar', '&#036;'),
(224, 'NGN', 'Nigerian naira', '&#8358;'),
(225, 'NIO', 'Nicaraguan c&oacute;rdoba', 'C&#036;'),
(226, 'NOK', 'Norwegian krone', '&#107;&#114;'),
(227, 'NPR', 'Nepalese rupee', '&#8360;'),
(228, 'NZD', 'New Zealand dollar', '&#036;'),
(229, 'OMR', 'Omani rial', '&#x631;&#x639;'),
(230, 'PAB', 'Panamanian balboa', 'B/.'),
(231, 'PEN', 'Sol', 'S/'),
(232, 'PGK', 'Papua New Guinean kina', 'K'),
(233, 'PHP', 'Philippine peso', '&#8369;'),
(234, 'PKR', 'Pakistani rupee', '&#8360;'),
(235, 'PLN', 'Polish z&#x142;oty', '&#122;&#322;'),
(236, 'PRB', 'Transnistrian ruble', '&#x440;'),
(237, 'PYG', 'Paraguayan guaran&iacute;', '&#8370;'),
(238, 'QAR', 'Qatari riyal', '&#x631;&#x642;'),
(239, 'RON', 'Romanian leu', 'lei'),
(240, 'RSD', 'Serbian dinar', '&#x434;&#x438;&#x43d;'),
(241, 'RUB', 'Russian ruble', '&#8381;'),
(242, 'RWF', 'Rwandan franc', 'Fr'),
(243, 'SAR', 'Saudi riyal', '&#x631;&#x633;'),
(244, 'SBD', 'Solomon Islands dollar', '&#036;'),
(245, 'SCR', 'Seychellois rupee', '&#x20a8;'),
(246, 'SDG', 'Sudanese pound', '&#x62c;&#x633;.'),
(247, 'SEK', 'Swedish krona', '&#107;&#114;'),
(248, 'SGD', 'Singapore dollar', '&#036;'),
(249, 'SHP', 'Saint Helena pound', '&pound;'),
(250, 'SLL', 'Sierra Leonean leone', 'Le'),
(251, 'SOS', 'Somali shilling', 'Sh'),
(252, 'SRD', 'Surinamese dollar', '&#036;'),
(253, 'SSP', 'South Sudanese pound', '&pound;'),
(254, 'STD', 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'Db'),
(255, 'SYP', 'Syrian pound', '&#x644;&#x633;'),
(256, 'SZL', 'Swazi lilangeni', 'L'),
(257, 'THB', 'Thai baht', '&#3647;'),
(258, 'TJS', 'Tajikistani somoni', '&#x405;&#x41c;'),
(259, 'TMT', 'Turkmenistan manat', 'm'),
(260, 'TND', 'Tunisian dinar', '&#x62f;&#x62a;'),
(261, 'TOP', 'Tongan pa&#x2bb;anga', 'T&#036;'),
(262, 'TRY', 'Turkish lira', '&#8378;'),
(263, 'TTD', 'Trinidad and Tobago dollar', '&#036;'),
(264, 'TWD', 'New Taiwan dollar', '&#078;&#084;&#036;'),
(265, 'TZS', 'Tanzanian shilling', 'Sh'),
(266, 'UAH', 'Ukrainian hryvnia', '&#8372;'),
(267, 'UGX', 'Ugandan shilling', 'UGX'),
(268, 'USD', 'United States(US) dollar', '&#036;'),
(269, 'UYU', 'Uruguayan peso', '&#036;'),
(270, 'UZS', 'Uzbekistani som', 'UZS'),
(271, 'VEF', 'Venezuelan bol&iacute;var', 'Bs F'),
(272, 'VES', 'Bol&iacute;var soberano', 'Bs.S'),
(273, 'VND', 'Vietnamese &#x111;&#x1ed3;ng', '&#8363;'),
(274, 'VUV', 'Vanuatu vatu', 'Vt'),
(275, 'WST', 'Samoan t&#x101;l&#x101;', 'T'),
(276, 'XAF', 'Central African CFA franc', 'CFA'),
(277, 'XCD', 'East Caribbean dollar', '&#036;'),
(278, 'XOF', 'West African CFA franc', 'CFA'),
(279, 'XPF', 'CFP franc', 'Fr'),
(280, 'YER', 'Yemeni rial', '&#xfdfc;'),
(281, 'ZAR', 'South African rand', '&#082;'),
(282, 'ZMW', 'Zambian kwacha', 'ZK')";
    $wpdb->query($sql);
}

function create_wpmlm_products_purchased_amount_table() {

    global $wpdb;
    $table_name = $wpdb->prefix . 'wpmlm_products_purchased_amount';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (

        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL DEFAULT '0',
        `order_id` double DEFAULT '0',
        `product_id` int(11) NULL DEFAULT '0',
        `product_value` int(11) NULL DEFAULT '0',
        `purchase_date` datetime NOT NULL DEFAULT '0001-01-01 00:00:00',
        PRIMARY KEY (`id`)

    )$charset_collate AUTO_INCREMENT=1";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);

}