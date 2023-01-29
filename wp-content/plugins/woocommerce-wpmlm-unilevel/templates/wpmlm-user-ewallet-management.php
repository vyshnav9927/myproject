<?php
function wpmlm_user_ewallet_management() {
$user_id = get_current_user_id();
$user = get_user_by('id', $user_id);
$bal_amount_arr = wpmlm_getBalanceAmount($user_id);
$bal_amount = $bal_amount_arr->balance_amount;
$result2 = wpmlm_get_general_information();

?>
<!-- <div class="panel-heading">
    <h4 class="main-head"><i class="fa fa-money" aria-hidden="true"></i> E-wallet Management </h4>
</div> -->
<div id="all-reports">
    <div class="e-wallet-br">
        <div id="exTab4">
            <div id="user-e-wallet-tab">

                <ul>
                    <li><a href="#tabs-user-ewallet-details"><?php _e('E-wallet Details','woocommerce-securewpmlm-unilevel'); ?></a></li>
                    <li><a href="#tabs-user-fund-transfer"><?php _e('Fund Transfer','woocommerce-securewpmlm-unilevel'); ?></a></li>
                    <li><a href="#tabs-user-transfer-details"><?php _e('Transfer Details','woocommerce-securewpmlm-unilevel'); ?></a></li>
                    <li><a href="#tabs-payout-request"><?php _e('Payout Request','woocommerce-securewpmlm-unilevel'); ?></a></li>
                </ul>
                <div class="dashboard-tab-contents">
                    <div id="tabs-user-ewallet-details"><?php echo wpmlm_user_ewallet_details($user_id); ?></div>
                    <div id="tabs-user-fund-transfer">
                        <div class="wooCommerce-earned">
                            <div class="accordion md-accordion" id="accordionTransferDetails" role="tablist" aria-multiselectable="true">
                                <!-- Card header -->
                                <div class="card-header head" role="tab" id="headingTransferDetails">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordionTransferDetails" href="#collapseTransferDetails"
                                    aria-expanded="false" aria-controls="collapseTransferDetails">
                                        <h3 class="mb-0">
                                            <?php _e('Fund Transfer','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                                        </h3>
                                    </a>
                                </div>
                                <!-- Card body -->
                                <div id="collapseTransferDetails" class="collapse show" role="tabpanel" aria-labelledby="headingTransferDetails" data-parent="#accordionTransferDetails">
                                    <div class="card-body">

                                        <div class="form-div">
                                            <div id ="fund-transfer-form-div">
                                                <div class="submit_message"></div>
                                                <form id="fund-transfer-form" class="form-horizontal tab-form" method="post">
                                                    <div id="fund-step-1">
                                                        <div class="form-group">
                                                            <label class="control-label user-dt process-heding btn-warning"><?php _e('Step: 1 ','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-long-arrow-right" aria-hidden="true"></i></label><br>
                                                        </div>
                                                        <div class="form-group balance_amount_div">
                                                        
                                                            <div class="balance_inner_div">
                                                                <label class="control-label user-dt" for="balance_amount"><?php _e('Balance Amount','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                                <label class="control-label user-dt" style="float:left;"><?php echo $result2->company_currency; ?>&nbsp; </label> 
                                                                <label class="control-label  balance_amount" for="balance_amount"><?php echo $bal_amount; ?></label>
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label user-dt" for="ewallet_user_name_to"><?php _e('Transfer To (User Name)','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                            
                                                            <input type="text" class="form-control fund_transfer_input" name="ewallet_user_name_to" id="ewallet_user_name_to" placeholder="Enter transfer to">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label user-dt" for="fund_amount"><?php _e('Amount','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                            
                                                            <input type="number" class="form-control fund_transfer_input" name="fund_transfer_amount" id="fund_transfer_amount" placeholder="Enter Amount">
                                                            
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label user-dt" for="transaction_note"><?php _e('Transaction Note','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                            
                                                            <input type="text" class="form-control fund_transfer_input" name="transaction_note1" id="transaction_note1" placeholder="Enter Transaction Note">
                                                            
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label user-dt"><a class="forgot_trans_pass" style="color:#31afde"><?php _e('Forgot your transaction password?','woocommerce-securewpmlm-unilevel'); ?></a></label>
                                                        </div>
                                                        <div class="form-group">
                                                                <input type="hidden" name="fund_action" id="fund-action" value="">
                                                                <button id="fund-transfer-continue"  class="btn btn-primary fund-transfer-continue" > <?php _e('Continue','woocommerce-securewpmlm-unilevel'); ?></button>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    <div id="fund-step-2" style="display: none">
                                                        <div class="form-group">
                                                            <label class="control-label  col-md-4"><?php _e('Step: 2','woocommerce-securewpmlm-unilevel'); ?></label><br>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4" for="ewallet_balance"><?php _e('Ewallet Balance ','woocommerce-securewpmlm-unilevel'); ?>:</label><label class="control-label" style="float:left;"><?php echo $result2->company_currency; ?>&nbsp; </label><label class="control-label ewallet_balance"></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4" for="ewallet_user_name_to"><?php _e('Receiver','woocommerce-securewpmlm-unilevel'); ?>:</label><label class="control-label ewallet_user_name_to" ></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4" for="amount_to_transfer"> <?php _e('Amount to transfer ','woocommerce-securewpmlm-unilevel'); ?>:</label><label class="control-label" style="float:left;"><?php echo $result2->company_currency; ?>&nbsp; </label><label class="control-label amount_to_transfer"></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4" for="transaction_note"><?php _e('Transaction Note','woocommerce-securewpmlm-unilevel'); ?> :</label><label class="control-label transaction_note"></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4" for="transaction_password"><?php _e('Transaction Password','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                            <div class="col-md-6">
                                                                <input type="password" class="form-control" name="transaction_password" id="transaction_password" placeholder="<?php _e('Enter Transaction Password','woocommerce-securewpmlm-unilevel'); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-offset-4 col-sm-6">
                                                                <input type="hidden" name="ewallet_user_id" value="<?php echo $user_id; ?>">
                                                                <input type="hidden" name="ewallet_user_name" value="<?php echo $user->user_login; ?>">
                                                                <button id="fund-transfer-send" type="submit" class="btn btn-danger fund-transfer-send" > <?php _e('Send','woocommerce-securewpmlm-unilevel'); ?></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php wp_nonce_field('fund_transfer_add', 'fund_transfer_add_nonce'); ?>
                                                </form>
                                            </div>
                                            <div id="forgot-trans-pass-div" style="display:none">
                                                <div class="submit_message"></div>
                                                <p style="color: #31afde"><?php _e('Note : Transaction password will be sent to your registered Email id','woocommerce-securewpmlm-unilevel'); ?></p>
                                                <form id="forgot-tran-pass-form" class="form-horizontal tab-form" method="post">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3" for="forgot_tran_user_name"><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="forgot_tran_user_name" id="forgot_tran_user_name">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-3 col-sm-6">
                                                            <?php wp_nonce_field('forgot_tran_pass', 'forgot_tran_pass_nonce'); ?>
                                                            <button id="forgot-tran-pass-button" type="submit" class="btn btn-danger forgot-tran-pass-button" > <?php _e('Send Password','woocommerce-securewpmlm-unilevel'); ?></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tabs-user-transfer-details">
                        <div class="">
                            <div class="wooCommerce-earned">
                                <div class="accordion md-accordion" id="accordionTransferDetails" role="tablist" aria-multiselectable="true">
                                    <!-- Card header -->
                                    <div class="card-header head" role="tab" id="headingTransferDetails">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordionTransferDetails" href="#collapseTransferDetails"
                                        aria-expanded="false" aria-controls="collapseTransferDetails">
                                            <h3 class="mb-0">
                                                <?php _e('Transfer Details','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                                            </h3>
                                        </a>
                                    </div>
                                    <!-- Card body -->
                                    <div id="collapseTransferDetails" class="collapse show" role="tabpanel" aria-labelledby="headingTransferDetails" data-parent="#accordionTransferDetails">
                                        <div class="card-body">

                                            <div class="form-div">
                                                <div class="ew-transfer-section">                   
                                                    <form name="transfer-details-form tab-form" id="transfer-details-form">
                                                        <div id="transfer-date-error"></div>

                                                        <div class="form-group ewallet-date">
                                                            <div class="row">
                                                                <label class="control-label col-md-12 user-dt" for="start_date1">
                                                                    <?php _e('From Date','woocommerce-securewpmlm-unilevel'); ?>: <span class="symbol required"></span>
                                                                </label>
                                                                <div class="col-md-12">
                                                                    <div class="input-group">
                                                                        <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker transfer_input" name="start_date1" id="start_date1" type="text" tabindex="3" size="20" maxlength="10" value="">
                                                                        <label for="start_date1" class="date_label input-group-addon"> <i class="fa fa-calendar"></i> </label>
                                                                    </div>                       
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group ewallet-date" style="margin-top: 0px !important;">
                                                            <div class="row">
                                                                <label class="control-label col-md-12 col-lg-12 user-dt" for="end_date1">
                                                                    <?php _e('To Date','woocommerce-securewpmlm-unilevel'); ?>:<span class="symbol required"></span>
                                                                </label>
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="input-group">
                                                                        <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker transfer_input" name="end_date1" id="end_date1" type="text" tabindex="4" size="20" maxlength="10" value="">
                                                                        <label for="end_date1" class="date_label input-group-addon"> <i class="fa fa-calendar"></i> </label>
                                                                    </div>                        
                                                                </div>
                                                            </div>                        
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group"> 
                                                                <div class="col-sm-12 transfer-details-btn">
                                                                    <div class="reg-btn">
                                                                        <input type="hidden" name="transfer_det_user_id" value="<?php echo $user_id; ?>">
                                                                        <?php wp_nonce_field('transfer_details', 'transfer_details_nonce'); ?>
                                                                        <button tabindex="5" class="btn btn-primary " name="weekdate" type="submit" value="Submit"> <?php _e('Submit','woocommerce-securewpmlm-unilevel'); ?></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>                   
                                                </div>
                                            </div>
                                            <!-- Transfer Details Ajax Data Start-->
                                            <div class="clearfix"></div>
                                            <div class="row" style="margin-top:20px;display:none;" id="tranfer-detail-main-div">
                                                <div class="col-sm-12">
                                                    <div class="">
                                                        <div class="panel-heading">
                                                            <h4><span class="tranfer-detail-caption"></span></h4>
                                                        </div>
                                                        <div class="no-data"></div>
                                                        <div  id="profile_print_area" style="padding: 20px;" class="transfer-details-data" >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Transfer Details Ajax Data End-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tabs-payout-request">
                        <div class="">
                            <div class="wooCommerce-earned">
                                <div class="accordion md-accordion" id="accordionPayoutRequests" role="tablist" aria-multiselectable="true">
                                    <!-- Card header -->
                                    <div class="card-header head" role="tab" id="headingPayoutRequests">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordionPayoutRequests" href="#collapsePayoutRequests"
                                        aria-expanded="false" aria-controls="collapsePayoutRequests">
                                            <h3 class="mb-0">
                                                <?php _e('Payout Requests','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                                            </h3>
                                        </a>
                                    </div>
                                    <!-- Card body -->
                                    <div id="collapsePayoutRequests" class="collapse show" role="tabpanel" aria-labelledby="headingPayoutRequests" data-parent="#accordionPayoutRequests">
                                        <div class="card-body">

                                            <div class="form-div">
                                                <?php
                                                $config_res = wpmlm_get_commission_details();
                                                //$available_res = wpmlm_get_available_payout_income($user_id);
                                                ?>
                                                <form name="payout-request-form" id="payout-request-form" class="form-horizontal tab-form">
                                                    <div class="submit_message"></div>
                                                    <div class="row">
                                                        <div class="form-group col-sm-12">
                                                            <label class="control-label user-dt" for="minimum_withdraw"><?php _e('Minimum Payout Request Amount','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                            <label class="control-label user-dt"><?php echo $result2->company_currency; ?>&nbsp; </label> <label class="control-label  " ><?php echo $config_res->minimum_withdraw; ?></label>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="row">
                                                        <div class="form-group">
                                                            
                                                            <label class="control-label user-dt" for="max_withdraw">Maximum Available Withdrawal Amount:</label>
                                                            <label class="control-label user-dt"><?php //echo $result2->company_currency; ?>&nbsp; </label> <label class="control-label  " ><?php //echo $available_res; ?></label>
                                                            
                                                        </div>
                                                        </div> -->
                                                    <div class="row">
                                                        <div class="form-group col-sm-12" style="display:flex;">
                                                            <label class="control-label user-dt" for="balance_amount"><?php _e('Total Amount','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                            <label class="control-label user-dt"><?php echo $result2->company_currency; ?>&nbsp; </label> 
                                                            <div class="balance_inner_div"><label class="control-label  balance_amount" for="balance_amount" ><?php echo $bal_amount; ?></label></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-sm-12">
                                                            <label class="control-label user-dt" for="requested_amount"><?php _e('Amount to Withdraw','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                            
                                                                <input type="number" class="form-control withdrawal_input" name="requested_amount" id="requested_amount" placeholder="<?php _e('Enter Withdrawal Amount','woocommerce-securewpmlm-unilevel'); ?>">
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-sm-12">
                                                            <label class="control-label user-dt" for="withdrawal_transaction_password"><?php _e('Transaction Password','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                            
                                                                <input type="password" class="form-control withdrawal_input" name="withdrawal_transaction_password" id="withdrawal_transaction_password" placeholder="<?php _e('Enter Transaction Password','woocommerce-securewpmlm-unilevel'); ?>">
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group" style="margin-top: 10px;">
                                                            <div class="col-sm-12">
                                                                <input type="hidden" name="requested_user_id" value="<?php echo $user_id; ?>">
                                                                <?php wp_nonce_field('payout_details', 'payout_details_nonce'); ?>
                                                                <button class="btn btn btn-primary mlm-button" tabindex="5" name="withdraw_submit" type="submit" value="Submit"> <?php _e('Submit','woocommerce-securewpmlm-unilevel'); ?></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              
            </div>
            

        </div>
    </div>
</div>
<script>
jQuery("#start_date1").datepicker({
autoclose: true
});
jQuery("#end_date1").datepicker({
autoclose: true
});
jQuery(document).ready(function ($) {
$(document).on('click', '.forgot_trans_pass', function () {
$("#fund-transfer-form-div").hide();
$("#forgot-trans-pass-div").show();
});
$(".fund-transfer").click(function () {
$("#fund-transfer-form-div").show();
$("#forgot-trans-pass-div").hide();
});
// Send transaction password

$("#forgot-tran-pass-form").submit(function () {
$(".submit_message").html('');
$(".submit_message").show();
var formData = new FormData(this);
formData.append('action', 'wpmlm_ajax_transaction_password');
isValid = true;
if ($("#forgot_tran_user_name").val() == '') {
$("#forgot_tran_user_name").addClass("invalid");
isValid = false;
}
if (isValid) {
$.ajax({
type: "POST",
url: ajaxurl,
data: formData,
cache: false,
contentType: false,
processData: false,
success: function (data) {
if ($.trim(data) === "1") {
$(".submit_message").html('<div class="alert alert-info">Transaction Password Sent Successfully</div>');
setTimeout(function () {
$(".submit_message").hide();
$("#forgot-tran-pass-form")[0].reset();
}, 3000);
} else {
$(".submit_message").html('<div class="alert alert-danger">' + data + '</div>');
setTimeout(function () {
$(".submit_message").hide();
$(".submit_message").html('');
}, 3000);
}
}
});
}
return false;
});
$("#exTab4 li").click(function () {
$("#tranfer-detail-main-div").hide();
});
// Payout
$("#payout-request-form").submit(function () {
$(".submit_message").html('');
$(".submit_message").show();
var formData = new FormData(this);
isValid = true;
$(".withdrawal_input").each(function () {
var element = $(this);
if (element.val() == '') {
$(this).addClass("invalid");
isValid = false;
}
});

var formData = new FormData(this);
formData.append('action', 'wpmlm_ajax_payout_management');
if (isValid) {
$.ajax({
type: "POST",
url: ajaxurl,
data: formData,
datatype: 'json',
cache: false,
contentType: false,
processData: false,
success: function (data) {
if(data.status==1){
$(".submit_message").html('<div class="alert alert-info">' + data.message + '</div>');
setTimeout(function () {
$(".submit_message").hide();
$("#payout-request-form")[0].reset();
}, 2000);
}else{
$(".submit_message").html('<div class="alert alert-danger">' + data.message + '</div>');
setTimeout(function () {
$(".submit_message").hide();
$("#payout-request-form")[0].reset();
}, 2000);
}

}
});
}
return false;
})
$(".withdrawal_input").focus(function () {
$(this).removeClass("invalid");
})

$("#ewallet_user_name,#ewallet_user_name1,#ewallet_user_name_to").blur(function () {
var id = $(this).attr('id');
$(".err_msg").remove();
var ewallet_user_name = $(this).val();
$.ajax({
type: "POST",
url: ajaxurl,
data: {action:'wpmlm_ajax_ewallet_management',ewallet_user_name: ewallet_user_name},
beforeSend: function () {
$("#" + id).parent().append('<div class="err_msg"><img src=' + plugin_url + '/images/loader.gif></div>');
},
success: function (data) {
//alert(data);
$(".err_msg").remove();
if ($.trim(data) != "1") {
$("#" + id).parent().append('<div class="err_msg">' + data + '</div>');
}
if (id == 'ewallet_user_name1') {
$.ajax({
type: "post",
url: ajaxurl,
data: {action:'wpmlm_ajax_ewallet_management',ewallet_user_balance: ewallet_user_name},
success: function (data) {
if ($.trim(data) != "no-data") {
$(".balance_amount_div").show();
$(".balance_amount").html(data);
}
}
});
}
}
});
});
$('.fund-transfer-continue').click(function () {
isValid = true;
$(".fund_transfer_input").each(function () {
var element = $(this);
if (element.val() == '') {
$(this).addClass("invalid");
isValid = false;
}
});
var amount = parseInt($("#fund_transfer_amount").val());
var bal_amount = parseInt($(".balance_amount").html());
if (bal_amount < amount) {
$("#fund_transfer_amount").addClass("invalid");
isValid = false;
}
if (bal_amount == 0) {
$(".submit_message").html('<div class="alert alert-info">Insufficient Balance</div>');
setTimeout(function () {
$(".submit_message").hide();
}, 2000);
isValid = false;
}
if ($("#ewallet_user_name1").val() == $("#ewallet_user_name_to").val()) {
$(".submit_message").html('<div class="alert alert-info">OOPS! Wrong receiver </div>');
setTimeout(function () {
$(".submit_message").hide();
}, 2000);
isValid = false;
}
if (isValid) {
$("#fund-step-1").hide();
$("#fund-step-2").show();
$('.ewallet_balance').html($(".balance_amount").html());
$('.ewallet_user_name_to').html($("#ewallet_user_name_to").val());
$('.amount_to_transfer').html($("#fund_transfer_amount").val());
$('.transaction_note').html($("#transaction_note1").val());
}
return false;
});
$(".fund_transfer_input").focus(function () {
$(this).removeClass("invalid");
})
$('.fund-management-button').click(function () {
var action = $(this).attr("data-title");
$("#fund-action").val(action);
});

$("#fund-management-form").submit(function () {
$(".submit_message").show();
var formData = new FormData(this);
isValid = true;
$(".fund_input").each(function () {
var element = $(this);
if (element.val() == '') {
$(this).addClass("invalid");
isValid = false;
}
});

var formData = new FormData(this);
formData.append('action', 'wpmlm_ajax_ewallet_management');
if (isValid) {
$('#fund-management-add').prop('disabled', true);
$('#fund-management-deduct').prop('disabled', true);
$.ajax({
type: "POST",
url: ajaxurl,
data: formData,
cache: false,
contentType: false,
processData: false,
success: function (data) {
$(".submit_message").html('<div class="alert alert-info">' + data + '</div>');
setTimeout(function () {
$(".submit_message").hide();
$("#fund-management-form")[0].reset();
$('#fund-management-add').prop('disabled', false);
$('#fund-management-deduct').prop('disabled', false);
}, 2000);
}
});
}
return false;
})
$(".fund_input").focus(function () {
$(this).removeClass("invalid");
})

$("#fund-transfer-form").submit(function () {
$(".submit_message").show();
var formData = new FormData(this);
formData.append('action', 'wpmlm_ajax_ewallet_management');
isValid = true;
if ($("#transaction_password").val() == '') {
$("#transaction_password").addClass("invalid");
isValid = false;
}
if (isValid) {
$('#fund-transfer-send').prop('disabled', true);
$.ajax({
type: "POST",
url: ajaxurl,
data: formData,
cache: false,
contentType: false,
processData: false,
success: function (data) {
if ($.trim(data) === "0") {
$(".submit_message").html('<div class="alert alert-danger">Incorrect Transaction Password</div>');
setTimeout(function () {
$(".submit_message").hide();
}, 2000);
} else if ($.trim(data) === "1") {
$(".submit_message").html('<div class="alert alert-danger">OOPS! Wrong receiver.</div>');
setTimeout(function () {
$(".submit_message").hide();
}, 2000);
} else {
$(".submit_message").html('<div class="alert alert-info">' + data + '</div>');
setTimeout(function () {
$(".submit_message").hide();
$("#fund-transfer-form")[0].reset();
$("#fund-step-1").show();
$("#fund-step-2").hide();
$(".balance_inner_div").load(location.href + " .balance_amount");
}, 2000);
}
$('#fund-transfer-send').prop('disabled', false);
}
});
}
return false;
})
$("#transaction_password").focus(function () {
$(this).removeClass("invalid");
});
// Fund Transfer Details Ajax Function

$("#transfer-details-form").submit(function () {
$(".submit_message").show();
$("#transfer-date-error").html('');
var formData = new FormData(this);
formData.append('action', 'wpmlm_ajax_ewallet_management');
isValid = true;
$(".transfer_input").each(function () {
var element = $(this);
if (element.val() == '') {
$(this).addClass("invalid");
isValid = false;
}
});
var startDate = new Date($('#start_date1').val());
var endDate = new Date($('#end_date1').val());
if (startDate > endDate) {
$("#transfer-date-error").html('<p style="color:red">You must select an end date greater than start date</p>');
$("#tranfer-detail-main-div").hide();
return false;
}
if (isValid) {
$.ajax({
type: "POST",
url: ajaxurl,
data: formData,
cache: false,
contentType: false,
processData: false,
success: function (data) {
$("#tranfer-detail-main-div").show();
$(".tranfer-detail-caption").html('Transfer Details');
$(".transfer-details-data").html(data);
$("#transfer-details-form")[0].reset();
}
});
}
return false;
})
$(".transfer_input").focus(function () {
$(this).removeClass("invalid");
});
});
</script>
<?php
}