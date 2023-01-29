<?php

function wpmlm_ewallet_management() {

    ?>
    <div class="panel-heading">
        <h4 class="main-head"><i class="fa fa-suitcase" aria-hidden="true"></i><?php _e('E-Wallet Management','woocommerce-securewpmlm-unilevel'); ?></h4>
    </div>
    <div id="all-reports">
        <div class="reports-tab" >
        
            <div id="E-wallet-tab" class="dashboard-tab-container tab-cnt-hed">
                <section class="tile-area">
                    <div class="container-fluid">
                        <div class="card-columns">

                            <div class="tile-single">
                                <div class="wooCommerce-earned">
                                    <div class="accordion md-accordion" id="accordionEwallet" role="tablist" aria-multiselectable="true">
                                        <!-- Card header -->
                                        <div class="card-header head" role="tab" id="headingEwallet">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordionEwallet" href="#collapseEwallet"
                                            aria-expanded="false" aria-controls="collapseEwallet">
                                            <h3 class="mb-0">
                                                <?php _e('E-Wallet Amount','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                                            </h3>
                                        </a>
                                        </div>
                                        <!-- Card body -->
                                        <div id="collapseEwallet" class="collapse show" role="tabpanel" aria-labelledby="headingEwallet"
                                        data-parent="#accordionEwallet">
                                            <div class="card-body">
                                                <div class="panel panel-primary">
                                                    <table id="eWalletTableInside" class="table table-bordered table-responsive-lg table-intel">
                                                        <thead>
                                                            <tr class="filters">
                                                                <!-- <th>Sl.NO</th> -->
                                                                <th><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?></th>
                                                                <th><?php _e('Full Name','woocommerce-securewpmlm-unilevel'); ?></th>
                                                                <th><?php _e('Amount','woocommerce-securewpmlm-unilevel'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            // $balamountresults = wpmlm_getBalanceAmountAll();
                                                            // $p_count = 0;
                                                            // // <td>' . $p_count . '</td>
                                                            // foreach ($balamountresults as $balres) {
                                                            //     $p_count++;
                                                            //     echo '<tr>
                                                            //     <td>' . $balres->user_login . '</td>
                                                            //     <td>' . $balres->display_name . '</td>
                                                            //     <td>' . $balres->balance_amount . '</td>
                                                            //     </tr>';
                                                            // }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tile-single">
                                <div class="wooCommerce-earned">
                                    <div class="accordion md-accordion" id="accordionFundManagement" role="tablist" aria-multiselectable="true">
                                        <!-- Card header -->
                                        <div class="card-header head" role="tab" id="headingFundManagement">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordionFundManagement" href="#collapseFundManagement"
                                            aria-expanded="false" aria-controls="collapseFundManagement">
                                                <h3 class="mb-0">
                                                    <?php _e('Fund Management','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                                                </h3>
                                            </a>
                                        </div>
                                        <!-- Card body -->
                                        <div id="collapseFundManagement" class="collapse show" role="tabpanel" aria-labelledby="headingFundManagement" data-parent="#accordionFundManagement">
                                            <div class="card-body">

                                                <div class="form-div">
                                                    <div class="submit_message_fund_management"></div>
                                                    <form id="fund-management-form" class="form-horizontal tab-form" method="post">
                                                        <div class="form-row">
                                                            <div class="col-sm-6">
                                                                <label class="control-label user-dt" for="ewallet_user_name"><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?>:</label>                                
                                                                <input type="text" class="form-control fund_input" name="ewallet_user_name" id="ewallet_user_name" placeholder="Enter User Name" autocomplete="off">
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label class="control-label user-dt" for="fund_amount"><?php _e('Amount','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                                <input type="number" class="form-control fund_input" name="fund_amount" id="fund_amount" placeholder="Enter Amount">
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <label class="control-label user-dt" for="transaction_note"> <?php _e('Transaction Note','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                                <input type="text" class="form-control fund_input" name="transaction_note" id="transaction_note" placeholder="Enter Transaction Note">
                                                            </div>


                                                            <div class="col-sm-12 "> 
                                                                <div class="reg-btn">
                                                                    <?php wp_nonce_field('fund_management_add', 'fund_management_add_nonce'); ?>
                                                                    <input type="hidden" name="fund_action" class="fund-action" value="">
                                                                    <button id="fund-management-add" type="submit" class="btn btn-primary fund-management-button" data-title="admin_credit"> <?php _e('Add','woocommerce-securewpmlm-unilevel'); ?></button>
                                                                    <button id="fund-management-deduct" type="submit" class="btn btn-primary fund-management-button" data-title="admin_debit"> <?php _e('Deduct','woocommerce-securewpmlm-unilevel'); ?></button>
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
                            
                            <div class="tile-single">
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

                                                <div class="">
                                                    <div id="payout_requests_div" class="">

                                                        <?php 

                                                        $payout_res = wpmlm_get_payout_release_requests('pending');
                                                        $result2 = wpmlm_get_general_information();

                                                        if (count($payout_res) > 0) {
                                                        ?>


                                                        <table id="payout_request_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                       <!--  <th>#</th> -->
                                                                        <th><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?></th>
                                                                        <th><?php _e('Full name','woocommerce-securewpmlm-unilevel'); ?></th>                                    
                                                                        <th><?php _e('Balance Amount','woocommerce-securewpmlm-unilevel'); ?></th>
                                                                        <th><?php _e('Payout Amount','woocommerce-securewpmlm-unilevel'); ?></th>
                                                                        <th><?php _e('Action','woocommerce-securewpmlm-unilevel'); ?></th>
                                                                    </tr>
                                                                </thead>
                                                                
                                                                <tbody>

                                                            
                                                      
                                                                <?php
                                                                $pcount=0;
                                                                foreach($payout_res as $payout){
                                                                    $pcount++;

                                                                        // <td>' . $pcount . '</td>

                                                                    echo '<tr>
                                                                        <td>' . $payout->user_login . '</td>
                                                                        <td>' . $payout->user_first_name.' '.$payout->user_second_name. '</td>            
                                                                        <td>' . $result2->company_currency.$payout->requested_amount_balance . '</td>
                                                                        <td>' . $result2->company_currency.$payout->requested_amount . '</td>
                                                                        <td>
                                                                            <div class="table-btn">
                                                                                <button type="button" class="payout_accept" accept-id="'. $payout->req_id .'"><i class="fa fa-check"></i>    </button>
                                                                                <button type="button" class="payout_reject" delete-id="'. $payout->req_id .'"><i class="fa fa-trash-o"></i></button>
                                                                            </div>
                                                                        </td>                             
                                                                    </tr>';
                                                               
                                                               } ?>
                                                            </tbody> 
                                                        </table>

                                                        <?php 
                                                        }else{
                                                            _e('<div> No Request</div>');
                                                            //echo '<div> No Request</div>';
                                                        }
                                                        ?>
                                                        
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tile-single">
                                <div class="wooCommerce-earned">
                                    <div class="accordion md-accordion" id="accordionFundTransfer" role="tablist" aria-multiselectable="true">
                                        <!-- Card header -->
                                        <div class="card-header head" role="tab" id="headingFundTransfer">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordionFundTransfer" href="#collapseFundTransfer"
                                            aria-expanded="false" aria-controls="collapseFundTransfer">
                                                <h3 class="mb-0">
                                                    <?php _e('Fund Transfer','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                                                </h3>
                                            </a>
                                        </div>
                                        <!-- Card body -->
                                        <div id="collapseFundTransfer" class="collapse show" role="tabpanel" aria-labelledby="headingFundTransfer" data-parent="#accordionFundTransfer">
                                            <div class="card-body">

                                                <div class="form-div">
                                                    <div class="submit_message"></div>
                                                    <form id="fund-transfer-form" class="form-horizontal tab-form" method="post">
                                                        <div id="fund-step-1">
                                                            <div class="form-row">
                                                                <div class="col-sm-12">
                                                                    <label class="control-label  user-dt process-heding btn-warning"><?php _e('Step 1','woocommerce-securewpmlm-unilevel'); ?> <i class="fa fa-long-arrow-right" aria-hidden="true"></i></label><br>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <label class="control-label user-dt" for="ewallet_user_name1"><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?></label>
                                                                    <input type="text" class="form-control fund_transfer_input" name="ewallet_user_name" id="ewallet_user_name1" placeholder="Enter Username" autocomplete="off">
                                                                   
                                                                </div>
                                                                <div class="col-sm-12 balance_amount_div" style="display: none">
                                                                    <label class="control-label  user-dt" for="balance_amount"><?php _e('Balance Amount','woocommerce-securewpmlm-unilevel'); ?></label>
                                                                    <label class="control-label  balance_amount" for="balance_amount" style="float:left;"></label>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="control-label user-dt" for="ewallet_user_name_to"><?php _e('Transfer To (User Name);','woocommerce-securewpmlm-unilevel') ?><?php _e('Balance Amount','woocommerce-securewpmlm-unilevel'); ?></label>
                                                                    <input type="text" class="form-control fund_transfer_input" name="ewallet_user_name_to" id="ewallet_user_name_to" placeholder="Enter transfer to" autocomplete="off">
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="control-label  user-dt" for="fund_amount"><?php _e('Amount','woocommerce-securewpmlm-unilevel'); ?></label>
                                                                    <input type="number" class="form-control fund_transfer_input" name="fund_transfer_amount" id="fund_transfer_amount" placeholder="Enter Amount">
                                                                
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <label class="control-label  user-dt" for="transaction_note"><?php _e('Transaction Note','woocommerce-securewpmlm-unilevel'); ?></label>
                                                                    <input type="text" class="form-control fund_transfer_input" name="transaction_note1" id="transaction_note1" placeholder="Enter Transaction Note">
                                                                </div>
                                                                <div class="col-sm-12"> 
                                                                    <div class="reg-btn">
                                                                        <input type="hidden" name="fund_action" class="fund-action" value=""> 
                                                                        <button id="fund-transfer-continue"  class="fund-transfer-continue btn btn-primary" > <?php _e('Continue','woocommerce-securewpmlm-unilevel'); ?></button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div id="fund-step-2" style="display: none"> 
                                                            <div class="form-group">
                                                                <label class="control-label  process-heding btn-warning">Step 2 <i class="fa fa-long-arrow-right" aria-hidden="true"></i></label><br>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4 user-dt" for="ewallet_balance"><?php _e('Ewallet Balance','woocommerce-securewpmlm-unilevel'); ?> :</label><label class="control-label col-md-2 ewallet_balance" style="text-align:left;"></label>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4 user-dt" for="ewallet_user_name_to"><?php _e('Receiver','woocommerce-securewpmlm-unilevel'); ?>:</label><label class="control-label col-md-2 ewallet_user_name_to" style="text-align:left;"></label>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4 user-dt" for="amount_to_transfer"> <?php _e('Amount to transfer','woocommerce-securewpmlm-unilevel'); ?>:</label><label class="control-label col-md-2 amount_to_transfer" style="text-align:left;"></label>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4 user-dt" for="transaction_note"><?php _e('Transaction Note','woocommerce-securewpmlm-unilevel'); ?>:</label><label class="control-label col-md-8 transaction_note" style="text-align:left;"></label></div>

                                                            <div class="form-group">
                                                                <label class="control-label col-md-4 user-dt" for="transaction_password"><?php _e('Transaction Password','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                                <div class="col-md-6">
                                                                    <input type="password" class="form-control" name="transaction_password" id="transaction_password" placeholder="Enter Transaction Password">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group"> 
                                                                <div class="col-sm-offset-4 col-sm-6">                                                
                                                                    <button id="fund-transfer-send" type="submit" class=" btn btn-primary fund-transfer-send" > Send</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php wp_nonce_field('fund_transfer_add', 'fund_transfer_add_nonce'); ?>   
                                                    </form> 

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tile-single">
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
                                                            <div class="form-row">

                                                                <div class="col-sm-12">
                                                                    <label class="control-label col-md-12 user-dt" for="search1"><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" class="transfer_input form-control typeahead" name="search1" id="search1" placeholder="search" autocomplete="off">
                                                                    </div>
                                                                </div>
                                                            

                                                                <div class="col-sm-6 ewallet-date">
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
                                                                <div class="col-sm-6 ewallet-date" style="margin-top: 0px !important;">
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
                                                                <div class="col-sm-12"> 
                                                                    <div class="col-sm-12 transfer-details-btn">
                                                                        <div class="reg-btn">
                                                                            <?php wp_nonce_field('transfer_details', 'transfer_details_nonce'); ?>
                                                                            <button tabindex="5" class="btn btn-primary " name="weekdate" type="submit" value="Submit"> <?php _e('Submit','woocommerce-securewpmlm-unilevel') ?></button>
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
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h4 style="text-align: center;"><span class="tranfer-detail-caption"></span></h4>

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
                            
                            <div class="tile-single">
                                <div class="wooCommerce-earned">
                                    <div class="accordion md-accordion" id="accordionConfirmPayoutRequests" role="tablist" aria-multiselectable="true">
                                        <!-- Card header -->
                                        <div class="card-header head" role="tab" id="headingConfirmPayoutRequests">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordionConfirmPayoutRequests" href="#collapseConfirmPayoutRequests"
                                            aria-expanded="false" aria-controls="collapseConfirmPayoutRequests">
                                                <h3 class="mb-0">
                                                    <?php _e('Confirm Payout Requests','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                                                </h3>
                                            </a>
                                        </div>
                                        <!-- Card body -->
                                        <div id="collapseConfirmPayoutRequests" class="collapse show" role="tabpanel" aria-labelledby="headingConfirmPayoutRequests" data-parent="#accordionConfirmPayoutRequests">
                                            <div class="card-body">

                                                <div class="">
                                                    <div id="confirm_payout_requests_div" class="">

                                                    <?php 

                                                    $payout_res = wpmlm_get_payout_release_requests('accepted');
                                                    $result2 = wpmlm_get_general_information();

                                                    if (count($payout_res) > 0) {
                                                    ?>

                                                    <div class="col-md-12 please-wait-transaction" style="text-align: center;position: absolute;z-index: 9; display:none"><img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/please-wait.gif'; ?>"></div>


                                                        <table id="confirm_payout_request_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <!-- <th>#</th> -->
                                                                    <th><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?></th>
                                                                    <th><?php _e('Full Name','woocommerce-securewpmlm-unilevel'); ?></th>                                    
                                                                    <th><?php _e('Balance Amount','woocommerce-securewpmlm-unilevel'); ?></th>
                                                                    <th><?php _e('Payout Amount','woocommerce-securewpmlm-unilevel'); ?></th>
                                                                    <th><?php _e('Action','woocommerce-securewpmlm-unilevel'); ?></th>
                                                                </tr>
                                                            </thead>
                                                            
                                                            <tbody>
                                                                <?php
                                                                $pcount=0;
                                                                foreach($payout_res as $payout){
                                                                    $pcount++;


                                                                    // <td>' . $pcount . '</td>
                                                                    echo '<tr>
                                                                    <td>' . $payout->user_login . '</td>
                                                                    <td>' . $payout->user_first_name.' '.$payout->user_second_name. '</td>            
                                                                    <td>' . $result2->company_currency.$payout->requested_amount_balance . '</td>
                                                                    <td>' . $result2->company_currency.$payout->requested_amount . '</td>
                                                                    <td>
                                                                        <div class="table-btn">
                                                                            <button type="button" class="payout_confirm" confirm-id="'. $payout->req_id .'"><i class="fa fa-check"></i></button></td>
                                                                        </div>                             
                                                                    </tr>';
                                                               
                                                               } ?>
                                                            </tbody> 
                                                        </table>

                                                        <?php 
                                                        }else{
                                                            //echo '<div> No Request</div>';
                                                            _e('<div>No Request</div>','woocommerce-securewpmlm-unilevel');
                                                        }
                                                        ?>
                                                        
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </section>
            </div>
            
        </div>
    </div>
    <script>


       

        // jQuery(document).ready(function ($) {
        //     $('#payout_request_table').DataTable({
        //         "pageLength": 10,
        //         "bFilter": false
        //     });
        // });

        // jQuery(document).ready(function ($) {
        //     $('#confirm_payout_request_table').DataTable({
        //         "pageLength": 10,
        //         "bFilter": false
        //     });
        // });



        jQuery("#start_date1").datepicker({
            autoclose: true
        });
        jQuery("#end_date1").datepicker({
            autoclose: true
        });
        jQuery(document).ready(function ($) {

            $("#exTab4 li").click(function () {
                $("#tranfer-detail-main-div").hide();
            });
            

            $("#ewallet_user_name,#ewallet_user_name1,#ewallet_user_name_to").change(function () {

                var id = $(this).attr('id');
                $(".err_msg").remove();

                var ewallet_user_name = $(this).val();
                if (id == 'ewallet_user_name1') {

                    $.ajax({
                        type: "post",
                        url: ajaxurl,
                        data: {'ewallet_user_balance': ewallet_user_name,'action':'wpmlm_ajax_ewallet_management'},
                        success: function (data) {
                            if ($.trim(data) != "no-data") {
                                $(".balance_amount_div").show();
                                $(".balance_amount").html(data);
                            } else {
                                $(".balance_amount_div").hide();
                            }

                        }
                    });
                }
            });

            $('.fund-transfer-continue').click(function () {
                isValid = true;
                $(".submit_message").show();
                $(".submit_message").html('');
                var ewallet_user_name1 = $("#ewallet_user_name1").val();
                var ewallet_user_name_to = $("#ewallet_user_name_to").val();

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
                    $(".submit_message").html('<div class="alert alert-danger">Insufficient Balance</div>');
                    setTimeout(function () {
                        $(".submit_message").hide();
                    }, 2000);
                    isValid = false;
                }
                if (($("#ewallet_user_name1").val() != '') && ($("#ewallet_user_name1").val() == $("#ewallet_user_name_to").val())) {
                    $(".submit_message").html('<div class="alert alert-danger">OOPS! Wrong receiver </div>');
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
                $(".fund-action").val(action);
            });


            // Fund Management Ajax Function

            
            $("#fund-management-form").submit(function () {
                $(".submit_message_fund_management").html('');
                $(".submit_message_fund_management").show();
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_ewallet_management');
                isValid = true;
                $(".fund_input").each(function () {
                    var element = $(this);
                    if (element.val() == '') {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });

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


                            $(".submit_message_fund_management").html('<div class="alert alert-info">' + data + '</div>');
                            setTimeout(function () {
                                $(".submit_message_fund_management").hide();
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


            // Fund Transfer Ajax Function

            
            $("#fund-transfer-form").submit(function () {
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

                            } else {

                                $(".submit_message").show();
                                $(".submit_message").html('<div class="alert alert-info">' + data + '</div>');
                                setTimeout(function () {
                                    $(".submit_message").hide();
                                    $("#fund-transfer-form")[0].reset();
                                    $("#fund-step-1").show();
                                    $("#fund-step-2").hide();
                                    $(".balance_amount_div").hide();


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
            })



            // Fund Transfer Details Ajax Function

            
            $("#transfer-details-form").submit(function () {

                //$(".submit_message").show();
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
