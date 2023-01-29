<?php

function wpmlm_ajax_payout_management(){

    header('Content-Type: application/json'); 
    global $wpdb;
    if (isset($_POST['payout_details_nonce']) && wp_verify_nonce($_POST['payout_details_nonce'], 'payout_details')) {
    
    $transaction_password = sanitize_text_field($_POST['withdrawal_transaction_password']);
    $requested_amount = sanitize_text_field($_POST['requested_amount']);
    $requested_user_id = sanitize_text_field($_POST['requested_user_id']);

    //$requested_amount_balance = sanitize_text_field($_POST['requested_amount_balance']);
    $requested_amount_balance =0;
    $status = sanitize_text_field($_POST['status']);
    $date = date('Y-m-d H:i:s');


    $tran_pass_arr = wpmlm_getUserPasscode($requested_user_id);
    $tran_password = $tran_pass_arr->tran_password;

    $bal_amount_arr = wpmlm_getBalanceAmount($requested_user_id);
    $bal_amount = $bal_amount_arr->balance_amount;

    $msg = array();


    $config_res = wpmlm_get_commission_details();
    $available_balance = wpmlm_get_available_payout_income($requested_user_id);


    if($requested_amount > $available_balance){
        $msg['message'] =  'Sorry! Please check your available withdrawal amount';
        $msg['status'] =  0;
        echo json_encode($msg);
        exit();
    }

    if($requested_amount < $config_res->minimum_withdraw){
        $msg['message'] =  'Sorry! minimum withdrawal amount for request is '.$config_res->minimum_withdraw;
        $msg['status'] =  0;
        echo json_encode($msg);
        exit();
    }
    $requested_amount_balance = $bal_amount - $requested_amount;

    require_once ABSPATH . 'wp-includes/class-phpass.php';
    $wp_hasher = new PasswordHash(8, true);

    if ($wp_hasher->CheckPassword($transaction_password, $tran_password)) {

        $request_details = array(
                'requested_user_id' => $requested_user_id,
                'requested_amount' => $requested_amount,
                'requested_amount_balance' => $requested_amount_balance,
                'requested_date' => $date,
                'updated_date' => $date,
                'status' => 'pending'
            );

        $result1 = wpmlm_insert_payout_request($request_details);

        $res_parent = wpmlm_getAdminDetailsByParent(0);
        $from_user_id = $res_parent->user_ref_id;

        $fund_details = array(
            'from_user_id' => $from_user_id,
            'to_user_id' => $requested_user_id,
            'amount' => $requested_amount,
            'date' => $date,
            'amount_type' => 'admin_debit',
            'transaction_concept' => 'Deducted for payout request'
        );

        wpmlm_insert_fund_transfer_details($fund_details);
        $lastid = $wpdb->insert_id;
        
        $ewallet_details = array(
                    'from_id' => $from_user_id,
                    'user_id' => $requested_user_id,
                    'ewallet_id' => $lastid,
                    'ewallet_type' => 'payout_request',
                    'amount' => $requested_amount,
                    'amount_type' => 'admin_debit',
                    'type' => 'debit',
                    'date_added' => $date,
                    'transaction_note' => 'Deducted for payout request'
                );

                
    wpmlm_addEwalletHistory($ewallet_details);
    $result2 = wpmlm_updateBalanceAmountDetailsFrom($requested_user_id, $requested_amount);

        if($result1){
            $msg['message'] =  'Successfully Requested';
            $msg['status'] =  1;
                echo json_encode($msg);
                exit();
            }else{
                $msg['message'] =  'Request Failed! Please try again';
                $msg['status'] =  0;
                echo json_encode($msg);
                exit();

            }

        


    }else{
        $msg['message'] =  'Incorrect Transaction Password';
        $msg['status'] =  0;
        echo json_encode($msg);
        exit();
    }



}


if(isset($_POST['payout_accept_id'])){
    $payout_id = $_POST['payout_accept_id'];
    $result = wpmlm_update_payout_request_status($payout_id,'accepted');
    if($result){
        $msg['message'] =  'Payout Accepted';
        $msg['status'] =  1;
        
    }else{
        $msg['message'] =  'Error!';
        $msg['status'] =  0;
    }
    echo json_encode($msg);
    exit();

}

if(isset($_POST['payout_reject_id'])){
    global $wpdb;
    $date = date('Y-m-d H:i:s');
    $payout_id = $_POST['payout_reject_id'];
    


    $res_parent = wpmlm_getAdminDetailsByParent(0);
    $from_user_id = $res_parent->user_ref_id;

    $payout_res = wpmlm_get_payout_release_requests_by_id($payout_id);

        $fund_details = array(
            'from_user_id' => $from_user_id,
            'to_user_id' => $payout_res->requested_user_id,
            'amount' => $payout_res->requested_amount,
            'date' => $date,
            'amount_type' => 'admin_credit',
            'transaction_concept' => 'Credited for payout request delete'
        );

        wpmlm_insert_fund_transfer_details($fund_details);
        $lastid = $wpdb->insert_id;
        
        $ewallet_details = array(
                    'from_id' => $from_user_id,
                    'user_id' => $payout_res->requested_user_id,
                    'ewallet_id' => $lastid,
                    'ewallet_type' => 'payout_request',
                    'amount' => $payout_res->requested_amount,
                    'amount_type' => 'admin_credit',
                    'type' => 'credit',
                    'date_added' => $date,
                    'transaction_note' => 'Credited for payout request delete'
                );

                
    wpmlm_addEwalletHistory($ewallet_details);
    $result2 = wpmlm_updateBalanceAmountDetailsTo($payout_res->requested_user_id, $payout_res->requested_amount);



    $result = wpmlm_update_payout_request_status($payout_id,'rejected');

    if($result){
        $msg['message'] =  'Payout Rejected';
        $msg['status'] =  1;
        
    }else{
        $msg['message'] =  'Error!';
        $msg['status'] =  0;
    }
    echo json_encode($msg);
    exit();

}


if(isset($_POST['payout_confirm_id'])){
    global $wpdb;
    $date = date('Y-m-d H:i:s');
    $payout_id = $_POST['payout_confirm_id'];

    $res_parent = wpmlm_getAdminDetailsByParent(0);
    $from_user_id = $res_parent->user_ref_id;

    $payout_res = wpmlm_get_payout_release_requests_by_id($payout_id);

    
    $transaction_id = $curl_result->transactionid;

        $fund_details = array(
            'from_user_id' => $from_user_id,
            'to_user_id' => $payout_res->requested_user_id,
            'amount' => 0,
            'date' => $date,
            'amount_type' => 'NA',
            'transaction_concept' => 'Payout released by request, Released amount: '.$payout_res->requested_amount
        );

        wpmlm_insert_fund_transfer_details($fund_details);
        $lastid = $wpdb->insert_id;
        
        $ewallet_details = array(
                    'from_id' => $from_user_id,
                    'user_id' => $payout_res->requested_user_id,
                    'ewallet_id' => $lastid,
                    'ewallet_type' => 'payout_request',
                    'amount' => 0,
                    'amount_type' => 'NA',
                    'type' => 'NA',
                    'date_added' => $date,
                    'transaction_note' => 'Payout released by request, Released amount: '.$payout_res->requested_amount
                );

        
        $paid_details = array(
                    'paid_user_id' => $payout_res->requested_user_id,
                    'paid_amount' => $payout_res->requested_amount,
                    'paid_date' => $date,
                    'paid_level' => '',
                    'paid_type' => 'Bank Transfer',
                    'transaction_id' => $transaction_id
                );


                
        wpmlm_addEwalletHistory($ewallet_details);
        wpmlm_insert_paid_amount($paid_details);


        $result = wpmlm_update_payout_request_status($payout_id,'confirmed');

        if($result){
            $msg['message'] =  'Payout Confirmed';
            $msg['status'] =  1;
            
        }else{
            $msg['message'] =  'Error!';
            $msg['status'] =  0;
        }
        echo json_encode($msg);
        exit();

    }


}

function wpmlm_ajax_user_profile() {
    
    global $wpdb;
    
    $table_name = $wpdb->prefix . "wpmlm_users";
    $table_name1 = $wpdb->prefix . "users";
    $user_id = intval($_POST['user_id']);



    if (isset($_POST['user_form2_nonce']) && wp_verify_nonce($_POST['user_form2_nonce'], 'user_form2')) {
        
        $fname = sanitize_text_field($_POST['fname']);
        $lname = sanitize_text_field($_POST['lname']);

        $user_details = array(
            'user_first_name' => $fname,
            'user_second_name' => $lname
        );
        $condition = array('user_ref_id' => $user_id);
        $wpdb->update($table_name, $user_details, $condition);
        echo 'Updated Successfully';
        exit();
    }


    
    if (isset($_POST['user_form3_nonce']) && wp_verify_nonce($_POST['user_form3_nonce'], 'user_form3')) {

        $user_address = sanitize_text_field($_POST['address1']);
        $user_email = sanitize_email($_POST['user_email']);
        $user_dob = sanitize_text_field($_POST['dob']);
        $user_city = sanitize_text_field($_POST['city']);
        $user_state = sanitize_text_field($_POST['state']);
        $user_country = sanitize_text_field($_POST['country']);
        $user_zip = sanitize_text_field($_POST['zip']);
        $user_mobile = sanitize_text_field($_POST['contact_no']);

        $user_details = array(
            'user_address' => $user_address,
            'user_email' => $user_email,
            'user_dob' => $user_dob,
            'user_city' => $user_city,
            'user_state' => $user_state,
            'user_country' => $user_country,
            'user_zip' => $user_zip,
            'user_mobile' => $user_mobile
        );
        $condition = array('user_ref_id' => $user_id);
        $wpdb->update($table_name, $user_details, $condition);

        $user_details1 = array(
            'user_email' => $user_email
        );
        $condition1 = array('ID' => $user_id);
        $result = $wpdb->update($table_name1, $user_details1, $condition1);
        
        update_usermeta( $user_id, "billing_address_1", $user_address ); 
        update_usermeta( $user_id, "billing_email", $user_email ); 
        update_usermeta( $user_id, "billing_phone", $user_mobile );
        update_usermeta( $user_id, "billing_city", $user_city );
        //update_usermeta( $user_id, "billing_state", $user_state );
        //update_usermeta( $user_id, "billing_country", $user_country );
        update_usermeta( $user_id, "billing_postcode", $user_zip );
        
        $user_post_id   =   getUserPostId($user_id);
        if(isset($user_post_id->post_id)){
            update_post_meta( $user_post_id->post_id, "_billing_address_1", $user_address);
            update_post_meta( $user_post_id->post_id, "_billing_email", $user_email ); 
            update_post_meta( $user_post_id->post_id, "_billing_phone", $user_mobile );
            update_post_meta( $user_post_id->post_id, "_billing_city", $user_city );
            //update_post_meta( $user_post_id->post_id, "_billing_state", $user_state );
            //update_post_meta( $user_post_id->post_id, "_billing_country", $user_country );
            update_post_meta( $user_post_id->post_id, "_billing_postcode", $user_zip );
        }
        //re assign new session
        //wp_session_regenerate_id();
        echo 'Updated Successfully';
        exit();
    }


    if (isset($_POST['user_form4_admin_nonce']) && wp_verify_nonce($_POST['user_form4_admin_nonce'], 'user_form4_admin')) {

        $newpassword = $_POST['password_admin'];
        wp_set_password($newpassword, $user_id);
        echo 'Password Updated Successfully';
        exit();
    }


    if (isset($_POST['user_form5_nonce']) && wp_verify_nonce($_POST['user_form5_nonce'], 'user_form5')) {
        
        $bank_name = sanitize_text_field($_POST['bank_name']);
        $branch_name = sanitize_text_field($_POST['branch_name']);
        $account_holder = sanitize_text_field($_POST['account_holder']);
        $account_number = sanitize_text_field($_POST['account_number']);
        $ifsc_code = sanitize_text_field($_POST['ifsc_code']);

        $user_details = array(
            'user_nbank' => $bank_name,
            'user_nbranch' => $branch_name,
            'user_nacct_holder' => $account_holder,
            'user_acnumber' => $account_number,
            'user_ifsc' => $ifsc_code
        );
        $condition = array('user_ref_id' => $user_id);
        $wpdb->update($table_name, $user_details, $condition);
        echo 'Updated Successfully';
        exit();
    }
}
function getUserPostId($user_id=''){
    global $wpdb;
    $table_name     =   $wpdb->prefix . "postmeta";
    $meta_key       =   "_customer_user";
    if(isset($user_id)){
        $sql        =   "SELECT `post_id` FROM {$table_name} where `meta_key`='$meta_key' AND  `meta_value`='$user_id'";
        return $wpdb->get_row($sql);
    }
    return FALSE;
}

//Ajax function for General settings
function wpmlm_ajax_general_settings() {
    global $wpdb;
    $msg = '';
    if (isset($_POST['general_add_nonce']) && wp_verify_nonce($_POST['general_add_nonce'], 'general_add')) {


        $company_name = sanitize_text_field($_POST['company_name']);
        $company_address = sanitize_text_field($_POST['company_address']);
        $company_email = sanitize_email($_POST['company_email']);
        $company_phone = sanitize_text_field($_POST['company_phone']);
        $site_logo = sanitize_text_field($_POST['site_logo']);
        $currency = sanitize_text_field($_POST['company_currency']);

        $currency_arr = explode(",",$currency);

        $company_currency = $currency_arr[0];
        $currency_code = $currency_arr[1];

        if (!empty($_FILES['company_logo']['name'])) {
            $uploaddir = WP_MLM_PLUGIN_DIR . '/uploads/';
            $file = $uploaddir . basename($_FILES['company_logo']['name']);


            if (file_exists($file)) {
                $duplicate_filename = TRUE;
                $i = 0;
                while ($duplicate_filename) {
                    $filename_data = explode(".", $_FILES['company_logo']['name']);
                    $new_filename = $filename_data[0] . "_" . $i . "." . $filename_data[1];
                    $_FILES['company_logo']['name'] = $new_filename;
                    $file = $uploaddir . basename($_FILES['company_logo']['name']);
                    if (file_exists($file)) {
                        $i++;
                    } else {
                        $duplicate_filename = FALSE;
                    }
                }
            }

            $company_logo = $_FILES['company_logo']['name'];
            if (move_uploaded_file($_FILES['company_logo']['tmp_name'], $file)) {
                $msg .='';
            } else {
                $msg .="Image Uploading Error";
            }
        } else {
            $company_logo = sanitize_text_field($_POST['image']);
        }
        if ($company_logo == '') {
            $company_logo = 'default_logo.png';
        }


        if (empty($msg)) {
            $company = array(
                'company_name' => $company_name,
                'company_address' => $company_address,
                'company_logo' => $company_logo,
                'company_email' => $company_email,
                'company_phone' => $company_phone,
                'site_logo' => $site_logo,
                'company_currency' => $company_currency,
                'currency_code' => $currency_code
            );
            $table_name = $wpdb->prefix . "wpmlm_general_information";

            $condition = array('id' => 1);
            $wpdb->update($table_name, $company, $condition);
            $msg = 'Successfully Updated';

            $company_settings_style = '';
            if (!empty($msg)) {
                echo nl2br($msg);
                exit();
            } else {
                echo $msg;
                exit();
            }
        }
    }

    if (isset($_POST['reg_type'])) {
        $reg_type = $_POST['reg_type'];
        $company = array(
            'registration_type' => $reg_type
        );
        $table_name = $wpdb->prefix . "wpmlm_general_information";
        $condition = array('id' => 1);
        $result = $wpdb->update($table_name, $company, $condition);

        echo $result;
        exit();
    }

    if (isset($_POST['act_inact'])) {
        $act_inact = sanitize_text_field($_POST['act_inact']);
        $company = array(
            'site_logo' => $act_inact
        );
        $table_name = $wpdb->prefix . "wpmlm_general_information";
        $condition = array('id' => 1);
        $result = $wpdb->update($table_name, $company, $condition);
        echo $result;
        exit();
    }
}

function wpmlm_ajax_ewallet_management() {
    $msg = '';
    global $wpdb;
    $table_name = $wpdb->prefix . 'users';
    if (isset($_POST['fund_management_add_nonce']) && wp_verify_nonce($_POST['fund_management_add_nonce'], 'fund_management_add')) {


        $ewallet_user_name = sanitize_text_field($_POST['ewallet_user_name']);
        $ewallet_user_name = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$ewallet_user_name'");
        if (!$ewallet_user_name) {
            echo 'Sorry! The specified user is not available.';
            exit();
        }

        $fund_amount = sanitize_text_field($_POST['fund_amount']);
        $fund_action = sanitize_text_field($_POST['fund_action']);
        $transaction_note = sanitize_text_field($_POST['transaction_note']);

        $from_user_id = get_current_user_id();
        $the_user = get_user_by('login', $ewallet_user_name);
        $to_user_id = $the_user->ID;
        $transaction_id = wpmlm_getUniqueTransactionId();
        $date = date('Y-m-d H:i:s');
        $fund_details = array(
            'from_user_id' => $from_user_id,
            'to_user_id' => $to_user_id,
            'amount' => $fund_amount,
            'date' => $date,
            'amount_type' => $fund_action,
            'transaction_concept' => $transaction_note,
            'transaction_id' => $transaction_id
        );

        $bal_amount_arr = wpmlm_getBalanceAmount($to_user_id);
        $bal_amount = $bal_amount_arr->balance_amount;



        if ($fund_action == 'admin_debit') {

            if (is_numeric($fund_amount) && $fund_amount > 0 && $bal_amount >= $fund_amount) {
                wpmlm_insert_fund_transfer_details($fund_details);
                $lastid = $wpdb->insert_id;

                $ewallet_details = array(
                    'from_id' => $from_user_id,
                    'user_id' => $to_user_id,
                    'ewallet_id' => $lastid,
                    'ewallet_type' => 'fund_transfer',
                    'amount' => $fund_amount,
                    'amount_type' => $fund_action,
                    'type' => ($fund_action == 'admin_debit') ? 'debit' : 'credit',
                    'date_added' => $date,
                    'transaction_note' => $transaction_note,
                    'transaction_id' => $transaction_id
                );

                wpmlm_updateBalanceAmountDetailsFrom($to_user_id, $fund_amount);
                $res = wpmlm_addEwalletHistory($ewallet_details);
            } else {
                echo 'Sorry! Insufficient Balance';
                exit();
            }
        }


        if ($fund_action == 'admin_credit') {

            if (is_numeric($fund_amount) && $fund_amount > 0) {
                wpmlm_insert_fund_transfer_details($fund_details);
                $lastid = $wpdb->insert_id;

                $ewallet_details = array(
                    'from_id' => $from_user_id,
                    'user_id' => $to_user_id,
                    'ewallet_id' => $lastid,
                    'ewallet_type' => 'fund_transfer',
                    'amount' => $fund_amount,
                    'amount_type' => $fund_action,
                    'type' => ($fund_action == 'admin_debit') ? 'debit' : 'credit',
                    'date_added' => $date,
                    'transaction_note' => $transaction_note,
                    'transaction_id' => $transaction_id
                );

                wpmlm_updateBalanceAmountDetailsTo($to_user_id, $fund_amount);
                $res = wpmlm_addEwalletHistory($ewallet_details);
            } else {
                echo 'Sorry! Insufficient Balance';
                exit();
            }
        }

        if ($res) {
            echo 'Transaction Completed Successfully';
            exit();
        } else {
            echo 'Transaction Failed';
            exit();
        }
    }


    if (isset($_POST['fund_transfer_add_nonce']) && wp_verify_nonce($_POST['fund_transfer_add_nonce'], 'fund_transfer_add')) {

        $transaction_password = $_POST['transaction_password'];
        $ewallet_user_name = sanitize_text_field($_POST['ewallet_user_name']);
        $ewallet_user_name_to = sanitize_text_field($_POST['ewallet_user_name_to']);
        $fund_transfer_amount = sanitize_text_field($_POST['fund_transfer_amount']);
        $transaction_note = sanitize_text_field($_POST['transaction_note1']);
        $date = date('Y-m-d H:i:s');


        if ($ewallet_user_name == $ewallet_user_name_to) {
            echo '1';
            exit();
        }

        $ewallet_user_name = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$ewallet_user_name'");
        if (!$ewallet_user_name) {
            echo 'Sorry! The specified user is not available.';
            exit();
        }




        $ewallet_user_name_to = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$ewallet_user_name_to'");
        if (!$ewallet_user_name_to) {
            echo 'Sorry! The specified transfer to user is not available.';
            exit();
        }


        $the_user = get_user_by('login', $ewallet_user_name);
        $from_user_id = $the_user->ID;


        $bal_amount_arr = wpmlm_getBalanceAmount($from_user_id);
        $bal_amount = $bal_amount_arr->balance_amount;

        if ($fund_transfer_amount > $bal_amount) {
            echo 'Sorry! Insufficient Balance';
            exit();
        }


        if (isset($_POST['ewallet_user_id'])) {
            $from_user_id = intval($_POST['ewallet_user_id']);
        }

        $the_user1 = get_user_by('login', $ewallet_user_name_to);
        $to_user_id = $the_user1->ID;

        $tran_pass_arr = wpmlm_getUserPasscode($from_user_id);
        $tran_password = $tran_pass_arr->tran_password;


        require_once ABSPATH . 'wp-includes/class-phpass.php';
        $wp_hasher = new PasswordHash(8, true);

        if ($wp_hasher->CheckPassword($transaction_password, $tran_password)) {
            $transaction_id = wpmlm_getUniqueTransactionId();
            $date = date('Y-m-d H:i:s');

            $fund_details = array(
                'from_user_id' => $from_user_id,
                'to_user_id' => $to_user_id,
                'amount' => $fund_transfer_amount,
                'date' => $date,
                'amount_type' => 'user_credit',
                'transaction_concept' => $transaction_note,
                'transaction_id' => $transaction_id
            );

            wpmlm_insert_fund_transfer_details($fund_details);
            $lastid = $wpdb->insert_id;


            $ewallet_details = array(
                'from_id' => $from_user_id,
                'user_id' => $to_user_id,
                'ewallet_id' => $lastid,
                'ewallet_type' => 'fund_transfer',
                'amount' => $fund_transfer_amount,
                'amount_type' => 'user_credit',
                'type' => 'credit',
                'date_added' => $date,
                'transaction_note' => $transaction_note,
                'transaction_id' => $transaction_id
            );

            wpmlm_updateBalanceAmountDetailsTo($to_user_id, $fund_transfer_amount);
            wpmlm_addEwalletHistory($ewallet_details);



            $fund_details = array(
                'from_user_id' => $to_user_id,
                'to_user_id' => $from_user_id,
                'amount' => $fund_transfer_amount,
                'date' => $date,
                'amount_type' => 'user_debit',
                'transaction_concept' => $transaction_note,
                'transaction_id' => $transaction_id
            );

            wpmlm_insert_fund_transfer_details($fund_details);
            $lastid = $wpdb->insert_id;


            $ewallet_details = array(
                'from_id' => $to_user_id,
                'user_id' => $from_user_id,
                'ewallet_id' => $lastid,
                'ewallet_type' => 'fund_transfer',
                'amount' => $fund_transfer_amount,
                'amount_type' => 'user_debit',
                'type' => 'debit',
                'date_added' => $date,
                'transaction_note' => $transaction_note,
                'transaction_id' => $transaction_id
            );

            wpmlm_updateBalanceAmountDetailsFrom($from_user_id, $fund_transfer_amount);
            $res = wpmlm_addEwalletHistory($ewallet_details);
            if ($res) {
                echo 'Transaction Completed Successfully';
                exit();
            } else {
                echo 'Transaction Failed';
                exit();
            }
        } else {
            echo '0';
            exit();
        }
    }


    if (isset($_POST['transfer_details_nonce']) && wp_verify_nonce($_POST['transfer_details_nonce'], 'transfer_details')) {

        $start_date = sanitize_text_field($_POST['start_date1']);
        $end_date = sanitize_text_field($_POST['end_date1']);
        $start_date_1 = $start_date . " 00:00:00";
        $end_date_1 = $end_date . " 23:59:59";

        if (isset($_POST['transfer_det_user_id'])) {
            $user_id = intval($_POST['transfer_det_user_id']);
        } else {

            $search = sanitize_text_field($_POST['search1']);
            $user = get_userdatabylogin($search);
            $user_id = $user->ID;
        }
        $results = wpmlm_getTransferDetails($user_id, $start_date_1, $end_date_1);

        $result2 = wpmlm_get_general_information();



        if (count($results) > 0) {
            ?>
            <table id="transaction_details_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <!-- <th>#</th> -->
                        <th>Username</th>
                        <th>Transaction Id</th>                                    
                        <th>Amount</th>
                        <th>Transfer Type</th>
                        <th>Transfer Note</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($results as $res) {
                        $count++;

                        $from_id = $res->from_user_id;
                        $the_user = get_user_by('ID', $from_id);
                        $username = $the_user->user_login;

                        // <td>' . $count . '</td>
                        echo '<tr>
                        <td>' . $username . '</td>
                        <td>' . $res->transaction_id . '</td>
                        <td>' . $result2->company_currency . ' ' . $res->amount . '</td>
                        <td>' . ucwords(str_replace("_", " ", $res->amount_type)) . '</td>
                        <td>' . $res->transaction_concept . '</td>  
                        <td>' . date("Y/m/d", strtotime($res->date)) . '</td>
                        </tr>';
                    }
                    ?>
                </tbody> 
            </table>

            <script>
                jQuery(document).ready(function ($) {
                    $('#transaction_details_table').DataTable({
                        "pageLength": 10
                    });
                });

            </script>
            <?php
        } else {
            echo '<div class="no-data"> No Data</div>';
        }
        exit();
    }


    if (isset($_POST['ewallet_user_name'])) {
        $user_name = sanitize_text_field($_POST['ewallet_user_name']);
        $user_name = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$user_name'");
        if (!$user_name) {
            echo 'Sorry! The specified user is not available.';
        } else {
            echo '1';
        }
        exit();
    }
    if (isset($_POST['ewallet_user_name_to'])) {
        $user_name = sanitize_text_field($_POST['ewallet_user_name_to']);
        $user_name = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$user_name'");
        if (!$user_name) {
            echo 'Sorry! The specified to user is not available.';
        } else {
            echo '1';
        }
        exit();
    }


    if (isset($_POST['ewallet_user_balance'])) {
        $general = wpmlm_get_general_information();
        $user_name = sanitize_text_field($_POST['ewallet_user_balance']);
        $the_user = get_user_by('login', $user_name);
        $user_id = $the_user->ID;
        $bal_amount_arr = wpmlm_getBalanceAmount($user_id);
        $bal_amount = $general->company_currency . $bal_amount_arr->balance_amount;

        if ($bal_amount_arr) {
            echo $bal_amount;
            exit();
        } else {
            echo 'no-data';
            exit();
        }
    }
}

function wpmlm_level_bonus() {
    global $wpdb;    

if (isset($_POST['level_width_depth_nonce']) && wp_verify_nonce($_POST['level_width_depth_nonce'], 'level_width_depth')) {
    
    //if (isset($_POST['depth'])) {
        $depth = wpmlm_get_level_depth();
        $depth_new = intval($_POST['depth']);
        if ($depth_new != $depth) {
            wpmlm_setLevel($depth_new);
        }

        $width = intval($_POST['width_ceiling']);
        $data = array(
                'width_ceiling' => $width
            );
        $table_name = $wpdb->prefix . "wpmlm_configuration";
        $condition = array('id' => 1);
        $result = $wpdb->update($table_name, $data, $condition);

        echo 'Level Width and Depth Updated';
        exit();
    //}
}

if (isset($_POST['level_commission_nonce']) && wp_verify_nonce($_POST['level_commission_nonce'], 'level_commission')) {
    $level_commission = $_POST['level_commission'];
    $level_type = sanitize_text_field($_POST['level_type']);

    wpmlm_update_level_commission($level_commission);
    wpmlm_update_level_commission_type($level_type);
    echo 'Level Bonus Updated';
    exit();
}
}

function wpmlm_ajax_transaction_password() {
    global $wpdb;
    if (isset($_POST['change_tran_pass_nonce']) && wp_verify_nonce($_POST['change_tran_pass_nonce'], 'change_tran_pass')) {


        $current_tran_pass = $_POST['current_tran_pass'];
        $new_tran_pass = $_POST['new_tran_pass'];
        $confirm_tran_pass = $_POST['confirm_tran_pass'];
        $user_id = get_current_user_id();
        $pass = wpmlm_getUserPasscode($user_id);

        require_once ABSPATH . 'wp-includes/class-phpass.php';
        $wp_hasher = new PasswordHash(8, true);
        $err = '';
        if (!$wp_hasher->CheckPassword($current_tran_pass, $pass->tran_password)) {
            $err.='<p>Your current transaction password is incorrect';
        }

        if (strlen($new_tran_pass) < 8) {
            $err.='<p>New Password should be at least 8 characters in length</p>';
        }
        if ($new_tran_pass != $confirm_tran_pass) {
            $err.='<p>New and confirm password miss match</p>';
        }

        if (empty($err)) {

            $new_pass = wp_hash_password($new_tran_pass);
            $update = wpmlm_update_tran_password($new_pass, $user_id);

            if ($update) {
                echo '1';
                exit();
            } else {
                $err.='<p>Password updation failed</p>';
                echo $err;
                exit();
            }
        } else {
            echo $err;
            exit();
        }
    }


    if (isset($_POST['change_user_tran_pass_nonce']) && wp_verify_nonce($_POST['change_user_tran_pass_nonce'], 'change_user_tran_pass')) {


        $username = sanitize_text_field($_POST['change_tran_pass_user']);
        $new_tran_pass = $_POST['new_user_tran_pass'];
        $confirm_tran_pass = $_POST['confirm_user_tran_pass'];

        $the_user = get_user_by('login', $username);
        $user_id = $the_user->ID;
        $err = '';


        if (!$user_id) {
            $err.='<p>Sorry! Username not exist</p>';
        }
        require_once ABSPATH . 'wp-includes/class-phpass.php';
        $wp_hasher = new PasswordHash(8, true);
        if (strlen($new_tran_pass) < 8) {
            $err.='<p>New Password should be at least 8 characters in length</p>';
        }
        if ($new_tran_pass != $confirm_tran_pass) {
            $err.='<p>New and confirm password miss match</p>';
        }
        if (empty($err)) {
            $new_pass = wp_hash_password($new_tran_pass);
            $update = wpmlm_update_tran_password($new_pass, $user_id);
            if ($update) {
                echo '1';
                exit();
            } else {
                $err.='<p>Password updation failed</p>';
                echo $err;
                exit();
            }
        } else {
            echo $err;
            exit();
        }
    }




    //New user transaction password change 
    if (isset($_POST['change_user_tran_pass_by_user_nonce']) && wp_verify_nonce($_POST['change_user_tran_pass_by_user_nonce'], 'change_user_tran_pass_by_user')) {


        $new_tran_pass = $_POST['new_trans_password_user'];
        $confirm_tran_pass = $_POST['confirm_trans_password_user'];


        $user_id = get_current_user_id();
        
        $current_tran_pass = $_POST['current_trans_password_user'];
        $pass = wpmlm_getUserPasscode($user_id);
        
        $err = '';
        
        require_once ABSPATH . 'wp-includes/class-phpass.php';
        $wp_hasher = new PasswordHash(8, true);
        
        if (!$wp_hasher->CheckPassword($current_tran_pass, $pass->tran_password)) {
            $err.='<p>Your current transaction password is incorrect';
        }
        if (strlen($new_tran_pass) < 8) {
            $err.='<p>New Password should be at least 8 characters in length</p>';
        }
        if ($new_tran_pass != $confirm_tran_pass) {
            $err.='<p>New and confirm password miss match</p>';
        }
        if (empty($err)) {
            $new_pass = wp_hash_password($new_tran_pass);
            $update = wpmlm_update_tran_password($new_pass, $user_id);
            if ($update) {
                echo '1';
                exit();
            } else {
                $err.='<p>Password updation failed</p>';
                echo $err;
                exit();
            }
        } else {
            echo $err;
            exit();
        }
    }
    //New user transaction password change


    

    if (isset($_POST['send_tran_pass_nonce']) && wp_verify_nonce($_POST['send_tran_pass_nonce'], 'send_tran_pass')) {
        $username = sanitize_text_field($_POST['tran_user_name']);
        $user_tran_password = sanitize_text_field($_POST['user_tran_password']);
        $confirm_user_tran_password = sanitize_text_field($_POST['confirm_user_tran_password']);


        


        $the_user = get_user_by('login', $username);
        $user_id = $the_user->ID;
        $err = '';


        if (!$user_id) {
            $err.='<p>Sorry! Username not exist</p>';
            echo $err;
            exit();
        }else if($user_tran_password!=$confirm_user_tran_password){

           $err.='<p>Error! Password mismatch</p>';
            echo $err;
            exit(); 
        }
         else {

            //$new_tran_pass = wpmlm_getRandStrPassword();
            $new_pass = wp_hash_password($user_tran_password);
            $update = wpmlm_update_tran_password($new_pass, $user_id);
            if ($update) {
                echo '1';
                exit();
            }

            // if ($update) {

            //     $to = $the_user->user_email;
            //     $mail = wpmlm_sendMailTransactionPass($to, $new_tran_pass);

            //     if ($mail) {
            //         echo '1';
            //         exit();
            //     } else {
            //         $err.='<p>Mail sending failed</p>';
            //         echo $err;
            //         exit();
            //     }
            // } else {
            //     $err.='<p>Password sending failed</p>';
            //     echo $err;
            //     exit();
            // }
        }
    }



    if (isset($_POST['forgot_tran_pass_nonce']) && wp_verify_nonce($_POST['forgot_tran_pass_nonce'], 'forgot_tran_pass')) {
        $username = sanitize_text_field($_POST['forgot_tran_user_name']);
        $the_user = get_user_by('login', $username);
        $user_id = $the_user->ID;
        $err = '';

        if (!$user_id) {
            $err.='<p>Sorry! Username not exist</p>';
            echo $err;
            exit();
        } else {

            $new_tran_pass = wpmlm_getRandStrPassword();
            $new_pass = wp_hash_password($new_tran_pass);
            $update = wpmlm_update_tran_password($new_pass, $user_id);
            if ($update) {

                $to = $the_user->user_email;
                $mail = wpmlm_sendMailTransactionPass($to, $new_tran_pass);

                if ($mail) {
                    echo '1';
                    exit();
                } else {
                    $err.='<p>Mail sending failed</p>';
                    echo $err;
                    exit();
                }
            } else {
                $err.='<p>Password sending failed</p>';
                echo $err;
                exit();
            }
        }
    }


    if (isset($_POST['user_password_admin_nonce']) && wp_verify_nonce($_POST['user_password_admin_nonce'], 'user_password_admin')) {
        $newpassword = $_POST['password_user'];
        $username = $_POST['username_pwd'];
        $the_user = get_user_by('login', $username);
        
        
        $user_id = $the_user->ID;

        if (!$user_id) {
            echo 'Sorry! Username not exist';
            exit();
        }
        wp_set_password($newpassword, $user_id);
        echo 'Password Updated Successfully';
        exit();
    }
}

function wpmlm_ajax_payment_option() {
    global $wpdb;

    if (isset($_POST['payment_submit']) && wp_verify_nonce($_POST['payment_submit'], 'payment_action')) {


        $paypal_username = sanitize_text_field($_POST['paypal_username']);
        $paypal_password = $_POST['paypal_password'];
        $paypal_signature = $_POST['paypal_signature'];
        $paypal_mode = sanitize_text_field($_POST['paypal_mode']);

        $paypal = array(
            'paypal_api_username' => $paypal_username,
            'paypal_api_password' => $paypal_password,
            'paypal_api_signature' => $paypal_signature,
            'paypal_mode' => $paypal_mode
        );

        global $wpdb;
        $table_name = $wpdb->prefix . "wpmlm_paypal";

        $sql1 = "TRUNCATE TABLE {$table_name} ";
        $wpdb->query($sql1);

        $result = $wpdb->insert($table_name, $paypal);
        if ($result) {
            echo 'Successfully Updated';
            exit();
        } else {
            echo 'Error in database insert';
            exit();
        }
    }

    if (isset($_POST['reg_submit']) && wp_verify_nonce($_POST['reg_submit'], 'register_action')) {
        $type = array_map( 'sanitize_text_field', wp_unslash( $_POST['reg_type'] ) );
        wpmlm_insert_reg_type($type);
        echo 'Successfully Updated';
        exit();
    }
}

function wpmlm_ajax_package_settings() {

    $msg = '';
    if (isset($_POST['package_add_nonce']) && wp_verify_nonce($_POST['package_add_nonce'], 'package_add')) {

        $package_name = sanitize_text_field($_POST['package_name']);
        $package_price = sanitize_text_field($_POST['package_price']);

        if (inputFieldBlankCheck($package_name))
            $msg .= "<p>Please enter registration package name</p>";
        if (inputFieldBlankCheck($package_price))
            $msg .= "<p>Please enter your registration package price</p>";



        if (empty($msg)) {
            $package = array(
                'package_name' => $package_name,
                'package_price' => $package_price
            );
            global $wpdb;
            $table_name = $wpdb->prefix . "wpmlm_registration_packages";

            if ($_POST['submit-action'] == '') {

                $count = wpmlm_package_name_check($package_name);


                if ($count > 0) {

                    $msg .="Package name already exists";
                } else {

                    $result = $wpdb->insert($table_name, $package);
                    if ($result) {
                        $msg = '1';
                    } else {
                        $msg .="Error in database insert";
                    }
                }
            } else {
                $package_id = intval($_POST['package_id']);
                $condition = array('id' => $package_id);
                $wpdb->update($table_name, $package, $condition);
                $msg = '2';
            }
            $package_settings_style = '';
            if (!empty($msg)) {
                echo nl2br($msg);
                exit();
            } else {
                echo $msg;
                exit();
            }
        }
    } else if (isset($_POST['package_id'])) {
        $package_id = intval($_POST['package_id']);
        $result = wpmlm_select_package_by_id($package_id);
        echo json_encode($result);
        exit();
    } else if (isset($_POST['package_delete_id'])) {

        $package_id = intval($_POST['package_delete_id']);
        $result = wpmlm_delete_package_by_id($package_id);
        if ($result) {
            echo "1";
        }
        exit();
    }



    if (isset($_POST['reg_amt_add_nonce']) && wp_verify_nonce($_POST['reg_amt_add_nonce'], 'reg_amt_add')) {

        $reg_amt = sanitize_text_field($_POST['reg_amt']);
        $data = array(
            'registration_amt' => $reg_amt
        );
        global $wpdb;


        $general = wpmlm_get_general_information();
        $reg_amt_old = $general->registration_amt;
        if ($reg_amt_old != $reg_amt) {

            $table_name = $wpdb->prefix . "wpmlm_general_information";

            $condition = array('id' => 1);
            echo $result = $wpdb->update($table_name, $data, $condition);
            exit();
        } else {
            echo '2';
            exit();
        }
    }
}

function wpmlm_ajax_profile_report() {

    if (isset($_POST['default_profile']) && ($_POST['default_profile'] == 'profile_report_all')) {

        $result = wpmlm_get_all_user_details_join();


        $res = wpmlm_get_general_information();
        if (count($result) > 0) {

            $data = '
            <div class="table-report">
                <div class="row row-bottom">

                    <div class="col-sm-8">
                        <div class="logo-area">
                            <img class="print-logo" src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $res->company_logo . ' align="left"  >
                        </div>
                        <div class="wp-mlm">
                            <span>
                                <font face="Arial, Helvetica, sans-serif">
                                    <h3 style="margin-top: 0px; margin-bottom: 0px;"> ' . $res->company_name . '</h3>
                                </font>
                            </span>
                            <span><b><font >Address:</font>' . $res->company_address . '</b></span>
                            <span><b><font >Phone:</font> ' . $res->company_phone . '</b></span>
                            <span><b><font >Email:</font> ' . $res->company_email . '</b></span>
                        </div>
                    </div>
                    <div class="col-sm-4 page-center">
                        <div class="date-right pull-right">
                            <h4><b>Date: ' . date("Y-m-d") . '</b></h4>
                        </div>
                    </div>
                </div>
        
                <h2 style="text-align: center;margin: 50px 0px 10px 0px;">Profile Report</h2>
                <div class="table-responsive">
                    <table id="profile_search_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">                                               

                                                
                        <thead>
                            <tr>
                                <!--<th>#</th>-->
                                <th>Name</th>              
                                <th>Username</th>
                                <th>Sponsor Name</th>
                                <th>Date of Birth</th>
                                <th>Address</th>
                                <th>Zip Code</th>
                                <th>Mobile No</th>        
                                <th>Email</th>
                                <th>Date of Joining</th>
                            </tr>
                        </thead>
                        <tbody>';

                            $count = 1;
                                    // <td>' . $count++ . '</td>
                            foreach ($result as $res) {
                                $sponsor_id = $res->user_parent_id;
                                $res1 = wpmlm_get_user_details_by_id($sponsor_id);
                                $data.='
                                <tr>
                                    <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
                                    <td>' . $res->user_login . '</td>
                                    <td>' . ($res1->user_first_name ? "$res1->user_first_name $res1->user_second_name" : "NA") . '</td>
                                    <td>' . ($res->user_dob ? "$res->user_dob" : "NA") . '</td>
                                    <td>' . ($res->user_address ? "$res->user_address" : "NA") . '</td>
                                    <td>' . ($res->user_zip ? "$res->user_zip" : "NA") . '</td>
                                    <td>' . ($res->user_mobile ? "$res->user_mobile" : "NA") . '</td>
                                    <td>' . $res->user_email . '</td>
                                    <td>' . date("Y/m/d", strtotime($res->join_date)) . '</td>
                                </tr>';
                            }

                        '<tbody>
                    </table>
                </div>

            </div>';

        echo $data;
        exit();
        ?>

        <?php
        } else {
            echo 0;
            exit();
        }
    }


    if (isset($_POST['search']) || isset($_POST['search_type'])) {
        $search = sanitize_text_field($_POST['search']);
        $search_type = sanitize_text_field($_POST['search_type']);
        if ($search_type == 'all') {
            $result = wpmlm_get_all_user_details_join();
        } else {
            if (username_exists($search)) {
                $user = get_userdatabylogin($search);
                $user_id = $user->ID;

                $result = wpmlm_get_user_details_by_id_join($user_id);
            } else {
                echo 'no-user';
                exit;
            }
        }
        $res = wpmlm_get_general_information();
        if (count($result) > 0) {

            $data = '
            <div class="table-report">
                <div class="row row-bottom">

                    <div class="col-sm-8">
                        <div class="logo-area">
                            <img class="print-logo" src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $res->company_logo . ' align="left"  >
                        </div>
                        <div class="wp-mlm">
                            <span>
                                <font face="Arial, Helvetica, sans-serif">
                                    <h3 style="margin-top: 0px; margin-bottom: 0px;"> ' . $res->company_name . '</h3>
                                </font>
                            </span>
                            <span><b><font >Address:</font>' . $res->company_address . '</b></span>
                            <span><b><font >Phone:</font> ' . $res->company_phone . '</b></span>
                            <span><b><font >Email:</font> ' . $res->company_email . '</b></span>
                        </div>
                    </div>
                    <div class="col-sm-4 page-center">
                        <div class="date-right pull-right">
                            <h4><b>Date: ' . date("Y-m-d") . '</b></h4>
                        </div>
                    </div>
                </div>
        
                <h2 style="text-align: center;margin: 50px 0px 10px 0px;">Profile Report</h2>
                <div class="table-responsive">
                    <table id="profile_search_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">                                               
                        <thead>
                            <tr>
                                <!--<th>#</th>-->
                                <th>Name</th>              
                                <th>Username</th>
                                <th>Sponsor Name</th>
                                <th>Date of Birth</th>
                                <th>Address</th>
                                <th>Zip Code</th>
                                <th>Mobile No</th>        
                                <th>Email</th>
                                <th>Date of Joining</th>
                            </tr>
                        </thead>
                        <tbody>';

                            $count = 1;
                                    // <td>' . $count++ . '</td>
                            foreach ($result as $res) {
                                $sponsor_id = $res->user_parent_id;
                                $res1 = wpmlm_get_user_details_by_id($sponsor_id);
                                $data.='
                                <tr>
                                    <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
                                    <td>' . $res->user_login . '</td>
                                    <td>' . ($res1->user_first_name ? "$res1->user_first_name $res1->user_second_name" : "NA") . '</td>
                                    <td>' . ($res->user_dob ? "$res->user_dob" : "NA") . '</td>
                                    <td>' . ($res->user_address ? "$res->user_address" : "NA") . '</td>
                                    <td>' . ($res->user_zip ? "$res->user_zip" : "NA") . '</td>
                                    <td>' . ($res->user_mobile ? "$res->user_mobile" : "NA") . '</td>
                                    <td>' . $res->user_email . '</td>
                                    <td>' . date("Y/m/d", strtotime($res->join_date)) . '</td>
                                </tr>';
                            }

                        '</tbody>
                    </table>
                </div>

            </div>';
            echo $data;
            exit();
            ?>

        <?php
        } else {
            echo 0;
            exit();
        }
    }
}


function wpmlm_ajax_payout_report() {
    if (isset($_POST['payout_report_nonce']) && wp_verify_nonce($_POST['payout_report_nonce'], 'payout_report')) {


        $payout_status = $_POST['payout_status'];
        $start_date = sanitize_text_field($_POST['payout_start_date']);
        $end_date = sanitize_text_field($_POST['payout_end_date']);
        $start_date_1 = $start_date . " 00:00:00";
        $end_date_1 = $end_date . " 23:59:59";
        $result = wpmlm_get_payout_release_requests_by_date($start_date_1, $end_date_1,$payout_status);

        
        if($payout_status=='select_all'){
            $result = wpmlm_get_payout_release_requests_all_by_date($start_date_1, $end_date_1);
        }else{
            $result = wpmlm_get_payout_release_requests_by_date($start_date_1, $end_date_1,$payout_status);
        }
        //echo $result;
        //exit();


        $res = wpmlm_get_general_information();
        if (count($result) > 0) {


            $data = '
            <div class="table-report">
                <div class="row row-bottom">

                    <div class="col-sm-8">
                        <div class="logo-area">
                            <img class="print-logo" src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $res->company_logo . ' align="left"  >
                        </div>
                        <div class="wp-mlm">
                            <span>
                                <font face="Arial, Helvetica, sans-serif">
                                    <h3 style="margin-top: 0px; margin-bottom: 0px;"> ' . $res->company_name . '</h3>
                                </font>
                            </span>
                            <span><b><font >Address:</font>' . $res->company_address . '</b></span>
                            <span><b><font >Phone:</font> ' . $res->company_phone . '</b></span>
                            <span><b><font >Email:</font> ' . $res->company_email . '</b></span>
                        </div>
                    </div>
                    <div class="col-sm-4 page-center">
                        <div class="date-right pull-right">
                            <h4><b>Date: ' . date("Y-m-d") . '</b></h4>
                        </div>
                    </div>
                </div>
        
                <h2 style="text-align: center;margin: 50px 0px 10px 0px;">Payout Report</h2>
                <h6 style="text-align: center;">' . $start_date . ' to ' . $end_date . '</h6>
                <div class="table-responsive">
                    <table id="profile_search_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">                                               
                        <thead>
                            <tr>
                                <!--<th>#</th>-->
                                <th>Username</th>
                                <th>Fullname</th>
                                <th>Payout Amount</th>
                                <th>Balance Amount</th>
                            </tr>
                        </thead>
                        <tbody>';
                            $count = 1;
                            foreach ($result as $res1) {
                                $sponsor_id = $res->user_parent_id;

                                    // <td>' . $count++ . '</td>
                                $data.='
                                <tr>
                                    <td>' . $res1->user_login . '</td>
                                    <td>' . $res1->user_first_name . ' ' . $res1->user_second_name . '</td>
                                    <td>' . $res->company_currency.$res1->requested_amount . '</td>
                                    <td>' . $res->company_currency.$res1->requested_amount_balance . '</td>
                                </tr>';
                            }

                        '</tbody>
                    </table>
                </div>

            </div>';

            echo $data;
            exit();
        } else {
            echo 0;
            exit();
        }
    }


    if (isset($_POST['default_payout']) && ($_POST['default_payout'] == 'payout_report_all')) {

        $result = wpmlm_get_payout_release_requests_all();
        // print_r($result);
        $res = wpmlm_get_general_information();
        if (count($result) > 0) {

            $data = '
            <div class="table-report">
                <div class="row row-bottom">

                    <div class="col-sm-8">
                        <div class="logo-area">
                            <img class="print-logo" src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $res->company_logo . ' align="left"  >
                        </div>
                        <div class="wp-mlm">
                            <span>
                                <font face="Arial, Helvetica, sans-serif">
                                    <h3 style="margin-top: 0px; margin-bottom: 0px;"> ' . $res->company_name . '</h3>
                                </font>
                            </span>
                            <span><b><font >Address:</font>' . $res->company_address . '</b></span>
                            <span><b><font >Phone:</font> ' . $res->company_phone . '</b></span>
                            <span><b><font >Email:</font> ' . $res->company_email . '</b></span>
                        </div>
                    </div>
                    <div class="col-sm-4 page-center">
                        <div class="date-right pull-right">
                            <h4><b>Date: ' . date("Y-m-d") . '</b></h4>
                        </div>
                    </div>
                </div>
        
                <h2 style="text-align: center;margin: 50px 0px 10px 0px;">Payout Report</h2>
                <div class="table-responsive">
                    <table id="profile_search_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">                                               

                        <thead>
                            <tr>
                                <!--<th>#</th>-->
                                <th>Username</th>
                                <th>Fullname</th>
                                <th>Payout Amount</th>
                                <th>Balance Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>';

                            $count = 1;
                            foreach ($result as $res1) {
                                $sponsor_id = $res->user_parent_id;

                                    // <td>' . $count++ . '</td>
                                $data.='<tr>
                                    <td>' . $res1->user_login . '</td>
                                    <td>' . $res1->user_first_name . ' ' . $res1->user_second_name . '</td>
                                    <td>' . $res->company_currency.$res1->requested_amount . '</td>
                                    <td>' . $res->company_currency.$res1->requested_amount_balance . '</td>
                                    <td>' . $res1->status . '</td>
                                </tr>';
                            }

                        '</tbody>
                    </table>
                </div>

            </div>';

            echo $data;
            exit();
        } else {
            echo 0;
            exit();
        }
    }


}

function wpmlm_ajax_joining_report() {
    if (isset($_POST['joining_report_nonce']) && wp_verify_nonce($_POST['joining_report_nonce'], 'joining_report')) {

        $start_date = sanitize_text_field($_POST['start_date']);
        $end_date = sanitize_text_field($_POST['end_date']);
        $start_date_1 = $start_date . " 00:00:00";
        $end_date_1 = $end_date . " 23:59:59";
        $result = wpmlm_get_all_user_details_by_date_join($start_date_1, $end_date_1);

        $res = wpmlm_get_general_information();
        if (count($result) > 0) {

            $data = '
            <div class="table-report">
                <div class="row row-bottom">

                    <div class="col-sm-8">
                        <div class="logo-area">
                            <img class="print-logo" src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $res->company_logo . ' align="left"  >
                        </div>
                        <div class="wp-mlm">
                            <span>
                                <font face="Arial, Helvetica, sans-serif">
                                    <h3 style="margin-top: 0px; margin-bottom: 0px;"> ' . $res->company_name . '</h3>
                                </font>
                            </span>
                            <span><b><font >Address:</font>' . $res->company_address . '</b></span>
                            <span><b><font >Phone:</font> ' . $res->company_phone . '</b></span>
                            <span><b><font >Email:</font> ' . $res->company_email . '</b></span>
                        </div>
                    </div>
                    <div class="col-sm-4 page-center">
                        <div class="date-right pull-right">
                            <h4><b>Date: ' . date("Y-m-d") . '</b></h4>
                        </div>
                    </div>
                </div>
        
                <h2 style="text-align: center;margin: 50px 0px 10px 0px;">Joining Report</h2>
                <h6 style="text-align: center;">' . $start_date . ' to ' . $end_date . '</h6>
                <div class="table-responsive">
                    <table id="profile_search_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">                                               
                        <thead>
                            <tr>
                                <!--<th>#</th>-->
                                <th>Name</th>              
                                <th>Username</th>
                                <th>Sponsor Name</th>
                                <th>Date of Birth</th>           
                                <th>Email</th>
                                <th>Date of Joining</th>
                            </tr>
                        </thead>
                        <tbody>';

                            $count = 1;
                            foreach ($result as $res) {
                                $sponsor_id = $res->user_parent_id;
                                $res1 = wpmlm_get_user_details_by_id($sponsor_id);


                                    // <td>' . $count++ . '</td>
                                $data.='<tr>
                                    <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
                                    <td>' . $res->user_login . '</td>
                                    <td>' . ($res1->user_first_name ? "$res1->user_first_name $res1->user_second_name" : "NA") . '</td>
                                    <td>' . ($res->user_dob ? "$res->user_dob" : "NA") . '</td>       
                                    <td>' . $res->user_email . '</td>
                                    <td>' . date("Y/m/d", strtotime($res->join_date)) . '</td>
                                </tr>';
                            }

                        '</tbody>
                    </table>
                </div>

            </div>';
            echo $data;
            exit();

        } else {
            echo 0;
            exit();
        }

    }



    if (isset($_POST['default_joining']) && ($_POST['default_joining'] == 'joining_report_all')) {

        $result = wpmlm_get_all_user_details_join();
        $res = wpmlm_get_general_information();
        if (count($result) > 0) {

            $data = '
            <div class="table-report">
                <div class="row row-bottom">

                    <div class="col-sm-8">
                        <div class="logo-area">
                            <img class="print-logo" src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $res->company_logo . ' align="left"  >
                        </div>
                        <div class="wp-mlm">
                            <span>
                                <font face="Arial, Helvetica, sans-serif">
                                    <h3 style="margin-top: 0px; margin-bottom: 0px;"> ' . $res->company_name . '</h3>
                                </font>
                            </span>
                            <span><b><font >Address:</font>' . $res->company_address . '</b></span>
                            <span><b><font >Phone:</font> ' . $res->company_phone . '</b></span>
                            <span><b><font >Email:</font> ' . $res->company_email . '</b></span>
                        </div>
                    </div>
                    <div class="col-sm-4 page-center">
                        <div class="date-right pull-right">
                            <h4><b>Date: ' . date("Y-m-d") . '</b></h4>
                        </div>
                    </div>
                </div>
        
                <h2 style="text-align: center;margin: 50px 0px 10px 0px;">Joining Report</h2>
                <div class="table-responsive">
                    <table id="profile_search_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">                                               

                                                
                        <thead>
                            <tr>
                                <!--<th>#</th>-->
                                <th>Name</th>              
                                <th>Username</th>
                                <th>Sponsor Name</th>
                                <th>Date of Birth</th>           
                                <th>Email</th>
                                <th>Date of Joining</th>
                            </tr>
                        </thead>
                        <tbody>';

                            $count = 1;
                            foreach ($result as $res) {
                                $sponsor_id = $res->user_parent_id;
                                $res1 = wpmlm_get_user_details_by_id($sponsor_id);


                                    // <td>' . $count++ . '</td>
                                $data.='<tr>
                                    <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
                                    <td>' . $res->user_login . '</td>
                                    <td>' . ($res1->user_first_name ? "$res1->user_first_name $res1->user_second_name" : "NA") . '</td>
                                    <td>' . ($res->user_dob ? "$res->user_dob" : "NA") . '</td>       
                                    <td>' . $res->user_email . '</td>
                                    <td>' . date("Y/m/d", strtotime($res->join_date)) . '</td>
                                </tr>';
                            }

                        '</tbody>
                    </table>
                </div>

            </div>';
            echo $data;
            exit();
        } else {
            echo 0;
            exit();
        }
    }
}

function wpmlm_ajax_bonus_report() {
    if (isset($_POST['commission_report_nonce']) && wp_verify_nonce($_POST['commission_report_nonce'], 'commission_report')) {

        $start_date = sanitize_text_field($_POST['commission_start_date']);
        $end_date = sanitize_text_field($_POST['commission_end_date']);
        $start_date_1 = $start_date . " 00:00:00";
        $end_date_1 = $end_date . " 23:59:59";
        $result = wpmlm_get_leg_amount_details($start_date_1, $end_date_1);
        $result1 = wpmlm_get_total_leg_amount($start_date_1, $end_date_1);
        $result2 = wpmlm_get_general_information();

        $res = wpmlm_get_general_information();
        if (count($result) > 0) {

            $data = '
            <div class="table-report">
                <div class="row row-bottom">

                    <div class="col-sm-8">
                        <div class="logo-area">
                            <img class="print-logo" src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $res->company_logo . ' align="left"  >
                        </div>
                        <div class="wp-mlm">
                            <span>
                                <font face="Arial, Helvetica, sans-serif">
                                    <h3 style="margin-top: 0px; margin-bottom: 0px;"> ' . $res->company_name . '</h3>
                                </font>
                            </span>
                            <span><b><font >Address:</font>' . $res->company_address . '</b></span>
                            <span><b><font >Phone:</font> ' . $res->company_phone . '</b></span>
                            <span><b><font >Email:</font> ' . $res->company_email . '</b></span>
                        </div>
                    </div>
                    <div class="col-sm-4 page-center">
                        <div class="date-right pull-right">
                            <h4><b>Date: ' . date("Y-m-d") . '</b></h4>
                        </div>
                    </div>
                </div>
        
                <h2 style="text-align: center;margin: 50px 0px 10px 0px;">Bonus Report</h2>
                <h6 style="text-align: center;">' . $start_date . ' to ' . $end_date . '</h6>
                <div class="table-responsive">
                    <table id="profile_search_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">                                               
                        <thead>                        
                            <tr>
                                <!--<th>#</th>-->
                                <th>Username</th>              
                                <th>Fullname</th>
                                
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>';
                            $count = 1;
                                    // <td>' . $count++ . '</td>
                            foreach ($result as $res) {
                                $data.='
                                <tr>
                                    <td>' . $res->user_login . '</td>
                                    <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>        
                                          
                                    <td>' . $result2->company_currency . ' ' . $res->total_amount . '</td>
                                </tr>';
                            }
                            $data.='
                            <tfoot>
                                <tr>
                                    <th colspan="2" style="text-align: right;">Total Amount :</th>
                                    <th style="display: none"></th>
                                    <th style="display: none"></th>
                                    <th>' . $result2->company_currency . ' ' . $result1->total_amount . '</th>
                                </tr>
                            </tfoot>';

                        '</tbody>
                    </table>
                </div>

            </div>';

            echo $data;
            exit();
        } else {
            echo 0;
            exit();
        }
    }


    if (isset($_POST['default_commission']) && ($_POST['default_commission'] == 'commission_report_all')) {

        $result = wpmlm_get_leg_amount_details_all();
        $result1 = wpmlm_get_total_leg_amount_all();
        $result2 = wpmlm_get_general_information();

        $res = wpmlm_get_general_information();
        if (count($result) > 0) {

            $data = '
            <div class="table-report">
                <div class="row row-bottom">

                    <div class="col-sm-8">
                        <div class="logo-area">
                            <img class="print-logo" src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $res->company_logo . ' align="left"  >
                        </div>
                        <div class="wp-mlm">
                            <span>
                                <font face="Arial, Helvetica, sans-serif">
                                    <h3 style="margin-top: 0px; margin-bottom: 0px;"> ' . $res->company_name . '</h3>
                                </font>
                            </span>
                            <span><b><font >Address:</font>' . $res->company_address . '</b></span>
                            <span><b><font >Phone:</font> ' . $res->company_phone . '</b></span>
                            <span><b><font >Email:</font> ' . $res->company_email . '</b></span>
                        </div>
                    </div>
                    <div class="col-sm-4 page-center">
                        <div class="date-right pull-right">
                            <h4><b>Date: ' . date("Y-m-d") . '</b></h4>
                        </div>
                    </div>
                </div>
        
                <h2 style="text-align: center;margin: 50px 0px 10px 0px;">Bonus Report</h2>
                <div class="table-responsive">
                    <table id="profile_search_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">                                               
                                                
                        <thead>                        
                            <tr>
                                <!--<th>#</th>-->
                                <th>Username</th>              
                                <th>Fullname</th>
                                
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>';
                            $count = 1;
                                    // <td>' . $count++ . '</td>
                            foreach ($result as $res) {
                                $data.='
                                <tr>
                                    <td>' . $res->user_login . '</td>
                                    <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>        
                                         
                                    <td>' . $result2->company_currency . ' ' . $res->total_amount . '</td>
                                </tr>';
                            }
                            $data.='
                            <tfoot>
                                <tr>
                                    <th colspan="2" style="text-align: right;">Total Amount :</th>
                                    <th>' . $result2->company_currency . ' ' . $result1->total_amount . '</th>
                                </tr>
                            </tfoot>';

                        '</tbody>
                    </table>
                </div>

            </div>';

            echo $data;
            exit();
        } else {
            echo 0;
            exit();
        }
    }
}


function wpmlm_ajax_user_details() {

    $user_id = $_GET['user_id'];
    $user_details = wpmlm_get_user_details($user_id);
    $user = get_user_by('id', $user_id);
    $parent_id = $user_details->user_parent_id;
    ?>
    <section class="tile-area">
        <div class="container-fluid" id="exTab5">
            <div class="card-columns user-profile-tab">
                <div class="tile-single">
                    <?php echo wpmlm_user_profile_admin($user_id); ?>
                </div>
                <div class="tile-single">
                    <?php echo wpmlm_user_ewallet_details($user_id); ?>
                </div>
                <div class="tile-single">
                    <?php echo wpmlm_user_income_details($user_id); ?>  
                </div>
                <div class="tile-single">
                    <?php echo wpmlm_user_referrals($user_id); ?>
                </div>
            </div>
        </div>
    </section>
    <script>
        jQuery(".user-details-tab").click(function ($) {
            jQuery("#user-div").show();
            jQuery("#exTab5").hide();
            jQuery(".user-details").hide();
        });
    </script>
    <?php
    exit();
}

function wpmlm_ajax_session() {
    session_start();
    if (isset($_POST['session_pkg_id'])) {
        $session_pkg_id = intval($_POST['session_pkg_id']);
        $pkg = wpmlm_select_package_by_id($session_pkg_id);
        $_SESSION['package_name'] = $package->package_name;
        $_SESSION['session_pkg_id'] = $session_pkg_id;
        $package['package_name'] = $pkg->package_name;
        $package['package_price'] = $pkg->package_price;
        echo json_encode($package);
        exit();
    }
}

function wpmlm_ajax_user_check() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'users';
    $table_prefix = $wpdb->prefix;
    if (isset($_POST['username'])) {
        $username = sanitize_text_field($_POST['username']);
        $username = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$username'");
        if ($username) {
            echo 'Sorry! Username already taken';
        } else {
            echo '1';
        }
        exit();
    }
    
    if (isset($_POST['sponsor'])) {
        $sponsor = sanitize_text_field($_POST['sponsor']);        
        $sponsor = $wpdb->get_var("SELECT a.*,b.* FROM {$table_prefix}users a INNER JOIN {$table_prefix}wpmlm_users b ON a.ID=b.user_ref_id AND a.user_login = '" . $sponsor . "'");
       $user_id = get_current_user_id();
        $the_user = get_user_by('login', $sponsor);
        
        
        if (!$sponsor || ($user_id==$the_user->ID)) {
            echo 'Sorry! The specified sponsor is not available for registration.';
        } else {
            echo '1';
        }
        exit();
    }


    if (isset($_POST['email'])) {
        $user_email = sanitize_email($_POST['email']);
        if (email_exists($user_email)) {
            echo '0';
        } else {
            echo '1';
        }
        exit();
    }


    if (isset($_POST['user_email'])) {

        $user_id = intval($_POST['user_id']);
        $the_user = get_user_by('ID', $user_id);
        $email = $the_user->user_email;

        $user_email = sanitize_email($_POST['user_email']);


        if ($email == $user_email) {
            echo '1';
            exit();
        } else {
            if (email_exists($user_email)) {
                echo '0';
                exit();
            } else {
                echo '1';
                exit();
            }
        }
    }

    if (isset($_POST['sponsor_woocommerce'])) {
        
        //$sponsor = sanitize_text_field($_POST['sponsor_woocommerce']);
        // $sponsor = $wpdb->get_var("SELECT user_login FROM {$table_name} WHERE user_login = '$sponsor'");
        // if (!$sponsor) {
        //     echo 'Sorry! The specified sponsor is not available for registration.';
        // } else {
        //     echo '1';
        // }

        $sponsor = sanitize_text_field($_POST['sponsor_woocommerce']);
        $sponsor = $wpdb->get_var("SELECT a.*,b.* FROM {$table_prefix}users a INNER JOIN {$table_prefix}wpmlm_users b ON a.ID=b.user_ref_id AND a.user_login = '" . $sponsor . "'");        
        if (!$sponsor) {
            echo 'Sorry! The specified sponsor is not available for registration.';
        } else {
            echo '1';
        }
        exit();
    }
}

function wpmlm_auto_fill_user() {
    global $wpdb;
    if ((isset($_POST['query'])) && (isset($_POST['sponsor']) )) {


        $keyword = sanitize_text_field($_POST['query']);
        $sponsor = sanitize_text_field($_POST['sponsor']);
        $result = wpmlm_get_all_user_like_except_current($keyword, $sponsor);
        if (count($result) > 0) {
            foreach ($result as $res) {
                $users[] = $res->user_login;
            }
            echo json_encode($users);
            exit();
        }
    } else {


        $keyword = sanitize_text_field($_POST['query']);
        $result = wpmlm_get_all_user_like($keyword);
        if (count($result) > 0) {
            foreach ($result as $res) {
                $users[] = $res->user_login;
            }
            echo json_encode($users);
            exit();
        }
    }
}

function wpmlm_ajax_user_register_from_my_account()
{ 

    parse_str($_POST['data'], $post);

    if (isset($post['reg_to_back_nonce']) && wp_verify_nonce($post['reg_to_back_nonce'], 'reg_to_back') && $_POST['action'] == 'wpmlm_ajax_user_register_from_my_account'){

        $current_user       =   wp_get_current_user();
        $log_user_id        =   $current_user->ID;

        $back_user_res = wpmlm_get_user_details($log_user_id);

        if (!$back_user_res) {

        if (!$post['reg_billing_sponsor_name']) {
            $Response  =  [
                        'status_code' =>  502,
                        'message'     =>  "Sponsor Name is Required" 
                    ]; 
            echo json_encode($Response); exit();
        }
        else if (!$post['reg_billing_date_of_birth']) {
            $Response  =  [
                        'status_code' =>  502,
                        'message'     =>  "Date Of Birth is Required" 
                    ]; 
            echo json_encode($Response); exit();
        }else if (!$post['reg_billing_sponsor_name'] && !$post['reg_billing_date_of_birth']) {
            $Response  =  [
                        'status_code' =>  502,
                        'message'     =>  "Sponsor Name And Date of Birth is Required" 
                    ];
            echo json_encode($Response); exit(); 
        }
        else{
            $sponsor_username   =   sanitize_text_field($post['reg_billing_sponsor_name']);
            $date_of_birth      =   sanitize_text_field($post['reg_billing_date_of_birth']);
        }
        $sponsor_info       =   get_user_by('login', $sponsor_username);
        $user_parent_id     =   $sponsor_info->ID;
        $billing_address_1  =   $billing_address_2  =  '';
        $user_details       =   [];

        if ($user_parent_id) {
            $user_level         =   wpmlm_get_user_level_by_parent_id($user_parent_id);
            $user_first_name    =   get_user_meta( $log_user_id, 'first_name',  true);
            $user_second_name   =   get_user_meta( $log_user_id, 'last_name', true );
            $billing_address_1  =   get_user_meta( $log_user_id, 'billing_address_1', true );
            $billing_address_2  =   get_user_meta( $log_user_id, 'billing_address_2', true );
            $user_address       =   $billing_address_1 .  $billing_address_2;
            $user_city          =   get_user_meta( $log_user_id, 'billing_city', true );
            $user_state         =   get_user_meta( $log_user_id, 'billing_state', true );
            $user_country       =   get_user_meta( $log_user_id, 'billing_country', true );
            $user_zip           =   get_user_meta( $log_user_id, 'billing_postcode', true );
            $user_mobile        =   get_user_meta( $log_user_id, 'billing_phone', true);
            $user_email         =   get_user_meta( $log_user_id, 'billing_email', true );
            $registration_type  =   'join_from_woocommerce_myaccount';
            $package_id         =   "";

            $placement_details = wpmlm_getPlacementMatrix($user_parent_id);            
            $father_id = $placement_details['id'];
            $position = $placement_details['position'];

            $user_details = array(
                'user_ref_id'       =>  $log_user_id,
                'user_parent_id'    =>  $user_parent_id,
                'father_id'         =>  $father_id,
                'position'          =>  $position,
                'user_first_name'   =>  $user_first_name,
                'user_second_name'  =>  $user_second_name,
                'user_address'      =>  $user_address,
                'user_city'         =>  $user_city,
                'user_state'        =>  $user_state,
                'user_country'      =>  $user_country,
                'user_zip'          =>  $user_zip,
                'user_mobile'       =>  $user_mobile,
                'user_email'        =>  $user_email,
                'user_dob'          =>  $date_of_birth,
                'user_level'        =>  $user_level,
                'user_registration_type' => $registration_type,
                'join_date'         =>  date("Y-m-d H:i:s"),
                'user_status'       =>  1,
                'package_id'        =>  $package_id
            );

            if(wpmlm_checkUserMlmRegistered( $log_user_id )){
                $Response =  [
                        'status_code' =>  505,
                        'message'     =>  "User is Already Registered" 
                    ];
                echo json_encode($Response); exit();
            }

           
            if (!empty($user_details)) {
                require_once ABSPATH . 'wp-includes/class-phpass.php';
                if(wpmlm_insert_user_registration_details($user_details)){
                    $tran_pass          =   wpmlm_getRandTransPasscode(8);
                    $hash_tran_pass     =   wp_hash_password($tran_pass);
                    $tran_pass_details  =   array(
                        'user_id' => $log_user_id,
                        'tran_password' => $hash_tran_pass
                    );
                    wpmlm_insert_tran_password($tran_pass_details);
                    wpmlm_insertBalanceAmount($log_user_id);
                    //sendMailRegistration($user_email, $username, $password, $user_first_name, $user_second_name);
                    //sendMailTransactionPass($user_email, $tran_pass);
                    $Response  =  [
                        'status_code' =>  200,
                        'message'     =>  "Registration Completed Successfully" 
                    ];
                }
                else{
                    $Response  =  [
                        'status_code' =>  501,
                        'message'     =>  "User registration failed" 
                    ];
                }
            }else{
                $Response  =  [
                        'status_code' =>  502,
                        'message'     =>  "Information Not Completed" 
                    ]; 
            }
        }else{
            $Response  =  [
                'status_code' =>  500,
                'message'     =>  "Sponsor Name Not available" 
            ];
        }
        
        echo json_encode($Response);
    }
    }
    exit();

}



//Ajax function for purchase settings
function wpmlm_ajax_purchase_settings() {
    global $wpdb;

    if (isset($_POST['purchase_add_nonce']) && wp_verify_nonce($_POST['purchase_add_nonce'], 'purchase_add')) {

        $level_eligibility = sanitize_text_field($_POST['level_eligibility']);
        $self_commission = sanitize_text_field($_POST['self_commission']);
        $minimum_withdraw = sanitize_text_field($_POST['minimum_withdraw']);
        $referral_commission = sanitize_text_field($_POST['referral_commission']);
        
            $puchase = array(
                'level_eligibility' => $level_eligibility,
                'self_commission' => $self_commission,
                'minimum_withdraw' => $minimum_withdraw,
                'referral_commission' => $referral_commission
            );
            $table_name = $wpdb->prefix . "wpmlm_configuration";

            $condition = array('id' => 1);
            $result = $wpdb->update($table_name, $puchase, $condition);
            if($result){
                echo $msg = 'Successfully Updated';
                exit();
            }else{
                echo $msg = 'Already Updated';
                exit();
            }            
        
    }    
}


add_action( 'wp_ajax_wpmlm_all_users_table', 'wpmlm_all_users_table' );
add_action( 'wp_ajax_nopriv_wpmlm_all_users_table', 'wpmlm_all_users_table' );
function wpmlm_all_users_table() {

  global $wpdb;

  header("Content-Type: application/json");

  $request= $_GET;

  $return_json = array();

  $results = wpmlm_get_all_user_details_join();
  $totalData = count($results);

  if($totalData){
      foreach ($results as $res) {
        $row = array(
          $res->user_login,
          $res->user_first_name . ' ' . $res->user_second_name,
          date("Y/m/d", strtotime($res->join_date)),
          '<button type="button" class="btn btn-default btn-sm mlm-button user_view" edit-id="' . $res->ID . '">View details</button>',
        );
        $return_json[] = $row;
      }

      echo '{ "data": ';
      echo json_encode($return_json);
      echo '}';

  } else {

    $json_data = array(
      "data" => array()
    );

    echo json_encode($json_data);
  }

  wp_die();
}


add_action( 'wp_ajax_wpmlm_ewallet_users_table', 'wpmlm_ewallet_users_table' );
add_action( 'wp_ajax_nopriv_wpmlm_ewallet_users_table', 'wpmlm_ewallet_users_table' );
function wpmlm_ewallet_users_table() {

  global $wpdb;

  header("Content-Type: application/json");

  $request= $_GET;

  $return_json = array();

  $balamountresults = wpmlm_getBalanceAmountAll();
  $totalData = count($balamountresults);

  $general = wpmlm_get_general_information();

  if($totalData){
      foreach ($balamountresults as $balres) {
        $row = array(
          $balres->user_login,
          $balres->display_name,
          $general->company_currency.$balres->balance_amount,
        );
        $return_json[] = $row;
      }

      echo '{ "data": ';
      echo json_encode($return_json);
      echo '}';

  } else {

    $json_data = array(
      "data" => array()
    );

    echo json_encode($json_data);
  }

  wp_die();
}

function wpmlm_ajax_ewallet_report(){
    
    
    if (isset($_POST['ewallet_report_nonce']) && wp_verify_nonce($_POST['ewallet_report_nonce'], 'ewallet_report')) {

       $start_date = sanitize_text_field($_POST['ewallet_start_date']);
       $end_date = sanitize_text_field($_POST['ewallet_end_date']);
       $start_date_1 = $start_date . " 00:00:00";
       $end_date_1 = $end_date . " 23:59:59";
       
       $result2=get_all_ewallet_transactions_by_date($start_date_1,$end_date_1);
       $gen_res = wpmlm_get_general_information();
       
       if (count($result2) > 0) {

           $data = '
           <div class="table-report">
               <div class="row row-bottom">

                   <div class="col-sm-8">
                       <div class="logo-area">
                           <img class="print-logo" src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $res->company_logo . ' align="left"  >
                       </div>
                       <div class="wp-mlm">
                           <span>
                               <font face="Arial, Helvetica, sans-serif">
                                   <h3 style="margin-top: 0px; margin-bottom: 0px;"> ' . $gen_res->company_name . '</h3>
                               </font>
                           </span>
                           <span><b><font >Address:</font>' . $gen_res->company_address . '</b></span>
                           <span><b><font >Phone:</font> ' . $gen_res->company_phone . '</b></span>
                           <span><b><font >Email:</font> ' . $gen_res->company_email . '</b></span>
                       </div>
                   </div>
                   <div class="col-sm-4 page-center">
                       <div class="date-right pull-right">
                           <h4><b>Date: ' . date("Y-m-d") . '</b></h4>
                       </div>
                   </div>
               </div>
       
               <h2 style="text-align: center;margin: 50px 0px 10px 0px;">Bonus Report</h2>
               <h6 style="text-align: center;">' . $start_date . ' to ' . $end_date . '</h6>
               <div class="table-responsive">
                   <table id="ewallet_search_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">                                               
                       <thead>                        
                           <tr>
                               <!--<th>#</th>-->
                               <th>S No.</th>
                               <th>Username</th>
                               <th>Ewallet Type</th>
                               <th>Transaction Type</th>
                               <th>Amount Type</th>
                               <th>Amount</th>
                               <th>Date of Transaction</th>
                           </tr>
                       </thead>
                       <tbody>';
                           $count = 1;
                                   
                           foreach ($result2 as $res) {
                               
                               $type=explode("/",$res->amount_type);
                               
                               switch($type[0]){
                                   case "level_bonus":
                                       $res->amount_type="Level Bonus";
                                       break;
                                   case "referral_bonus":
                                       $res->amount_type="Referral Bonus";
                                       break;
                                   case "user_credit":
                                       $res->amount_type="User Credit";
                                       break;
                                   case "user_debit":
                                       $res->amount_type="User Debit";
                                       break;
                                   case "self_bonus":
                                       $res->amount_type="Self Bonus";
                                       break;
                                   case "admin_credit":
                                       $res->amount_type="Admin Credit";
                                       break;
                                   case "admin_debit":
                                       $res->amount_type="Admin Debit";
                                       break;
                                //    case "rank_commission":
                                //        $res->amount_type="Rank Commission/".$type[1];
                                //        break;
                                   
                               }
                             
                               $data.='
                               <tr>
                                   <td>' . $count . '</td>
                                   <td>' . $res->user_login . '</td>
                                   <td>' . $res->ewallet_type. '</td>         
                                   <td>' . $res->type . '</td>
                                   <td>' . $res->amount_type . '</td>
                                   <td>' . $gen_res->company_currency. $res->amount . '</td>
                                   <td>' . $res->date_added . '</td>
                               </tr>';
                               $count++;
                           }
                           $data.='</tbody>
                           <tfoot></tfoot>
                   </table>
               </div>
           </div>';

           echo $data;
           exit();
       } else {
           echo 0;
           exit();
       }
   }


   if (isset($_POST['default_ewallet']) && ($_POST['default_ewallet'] == 'ewallet_report_all')) {

       
       $result1=get_all_ewallet_transactions();
       
       $gen_res = wpmlm_get_general_information();
       if (count($result1) > 0) {

           $data = '
           <div class="table-report">
               <div class="row row-bottom">

                   <div class="col-sm-8">
                       <div class="logo-area">
                           <img class="print-logo" src=' . plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $gen_res->company_logo . ' align="left"  >
                       </div>
                       <div class="wp-mlm">
                           <span>
                               <font face="Arial, Helvetica, sans-serif">
                                   <h3 style="margin-top: 0px; margin-bottom: 0px;"> ' . $gen_res->company_name . '</h3>
                               </font>
                           </span>
                           <span><b><font >Address:</font>' . $gen_res->company_address . '</b></span>
                           <span><b><font >Phone:</font> ' . $gen_res->company_phone . '</b></span>
                           <span><b><font >Email:</font> ' . $gen_res->company_email . '</b></span>
                       </div>
                   </div>
                   <div class="col-sm-4 page-center">
                       <div class="date-right pull-right">
                           <h4><b>Date: ' . date("Y-m-d") . '</b></h4>
                       </div>
                   </div>
               </div>
       
               <h2 style="text-align: center;margin: 50px 0px 10px 0px;">Ewallet Report</h2>
               <div class="table-responsive">
                   <table id="ewallet_search_table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px">                                               
                                               
                       <thead>                        
                           <tr>
                               <!--<th>#</th>-->
                               <th>S No.</th>
                               <th>Username</th>
                               <th>Ewallet Type</th>
                               <th>Transaction Type</th>
                               <th>Amount Type</th>
                               <th>Amount</th>
                               <th>Date of Transaction</th>
                           </tr>
                       </thead>
                       <tbody>';
                           
                                  $count = 1;
                           foreach ($result1 as $res1) {
                               
                               $type=explode("/",$res1->amount_type);
                               
                               switch($type[0]){
                                   case "level_bonus":
                                       $res1->amount_type="Level Bonus";
                                       break;
                                   case "referral_bonus":
                                       $res1->amount_type="Referral Bonus";
                                       break;
                                   case "user_credit":
                                       $res1->amount_type="User Credit";
                                       break;
                                   case "user_debit":
                                       $res1->amount_type="User Debit";
                                       break;
                                   case "self_bonus":
                                       $res1->amount_type="Self Bonus";
                                       break;
                                   case "admin_credit":
                                       $res1->amount_type="Admin Credit";
                                       break;
                                   case "admin_debit":
                                       $res1->amount_type="Admin Debit";
                                       break;
                                   case "rank_commission":
                                       $res1->amount_type="Rank Commission/".$type[1];
                                       break;
                                   
                               }
                               
                               $data.='
                                <tr>
                                   <td>' . $count . '</td>
                                   <td>' . $res1->user_login . '</td>
                                   <td>' . $res1->ewallet_type. '</td>         
                                   <td>' . $res1->type . '</td>
                                   <td>' . $res1->amount_type . '</td>
                                   <td>' . $gen_res->company_currency.$res1->amount . '</td>
                                   <td>' . $res1->date_added . '</td>
                               </tr>';
                               $count++;
                           }
                          

                     $data.='</tbody>
                       <tfoot></tfoot>
                   </table>
               </div>

           </div>';

           echo $data;
           exit();
       } else {
           echo 0;
           exit();
       }
   }
   
   
}

add_action('wp_ajax_wpmlm_ajax_ewallet_report','wpmlm_ajax_ewallet_report');
