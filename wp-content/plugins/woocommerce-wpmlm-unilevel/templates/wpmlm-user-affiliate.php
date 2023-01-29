<?php
function wpmlm_user_affiliate($user_name) {
    

    $user_id = get_current_user_id();
    $user = get_user_by('id', $user_id);
    $bal_amount_arr = wpmlm_getBalanceAmount($user_id);
    $bal_amount = $bal_amount_arr->balance_amount;
    $result2 = wpmlm_get_general_information();
    
    $user_row = wpmlm_getUserDetailsByParent($user_id);    
    $j_count =  wpmlm_getJoiningByTodayCountByUser($user_id);
    
    $payout = wpmlm_get_payout_amount_by_id($user_id,'confirmed');
    $payout_amt = ($payout->total_amount !=''? $payout->total_amount:0);
    //$ewallet_credit = wpmlm_getEwalletAmountByUser('credit',$user_id);    
    //$ewallet_debit = wpmlm_getEwalletAmountByUser('debit',$user_id);  
    $ewallet_amount= wpmlm_getCurrentEwalletAmountByUser($user_id);
      
    //$debit_amt = ($ewallet_debit->sum !=''? $ewallet_debit->sum:0);
    //$credit_amt = ($ewallet_credit->sum !=''? $ewallet_credit->sum:0);    
    $bonus_amount = wpmlm_get_total_leg_amount_by_user_id($user_id);
    $bonus_amount_today = wpmlm_get_total_leg_amount_by_user_id_today($user_id);
    
    $bonus_total_amt = ($bonus_amount->total_amount !=''? $bonus_amount->total_amount:0);
    $bonus_total_amt_today = ($bonus_amount_today->total_amount !=''? $bonus_amount_today->total_amount:0);   
    $general = wpmlm_get_general_information();
    $year = date('Y');

    
    $joining_details = wpmlm_getJoiningDetailsUsersByMonth($user_id,$year);
    
    
    if ($joining_details) {
        $i = 0;
        foreach ($joining_details as $jdt) {
            $i++;
            if ($i == $jdt->month) {
                $joining_count[] = $jdt->count;
            } else {

                for ($j = $i; $j < $jdt->month; $j++) {
                    $joining_count[] = 0;
                }
                $joining_count[] = $jdt->count;
                $i++;
            }
        }
        $joining_count = implode($joining_count, ',');
    } else {
        $joining_count = '0,0,0,0,0,0,0,0,0,0,0,0';
    }
            
    ?>
    <div class="dashboard-grid">
        <div></div>
        <div class="downlines">
            <div>
                <img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/bar-chart.png'; ?>"/>
                <h4><?php _e('Downlines','woocommerce-securewpmlm-unilevel'); ?></h4>
                <table>
                    <tr>
                        <td><?php _e('Total','woocommerce-securewpmlm-unilevel'); ?></td>
                        <td>:</td>
                        <td><?php echo count($user_row);?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Today','woocommerce-securewpmlm-unilevel'); ?></td>
                        <td>:</td>
                        <td><?php echo $j_count->count;?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="bonus">
            <div>
                <img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/money-bag.png'; ?>"/>
                <h4><?php _e('Bonus','woocommerce-securewpmlm-unilevel'); ?></h4>
                <table>
                    <tr>
                        <td><?php _e('Total','woocommerce-securewpmlm-unilevel'); ?></td>
                        <td>:</td>
                        <td><?php echo $general->company_currency;?><?php echo $bonus_total_amt;?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Today','woocommerce-securewpmlm-unilevel'); ?></td>
                        <td>:</td>
                        <td><?php echo $general->company_currency;?><?php echo $bonus_total_amt_today;?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="wallet">
            <div>
                <img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/wallet.png'; ?>"/>
                <h4><?php _e('E-Wallet','woocommerce-securewpmlm-unilevel'); ?></h4>
                <table>
                    <tr>
                        <td><?php _e('Balance','woocommerce-securewpmlm-unilevel'); ?></td>
                        <td>:</td>
                        <td><?php echo $general->company_currency;?><?php echo $ewallet_amount;?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Payout','woocommerce-securewpmlm-unilevel'); ?></td>
                        <td>:</td>
                        <td><?php echo $general->company_currency;?><?php echo $payout_amt;?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><i class="fa fa-bars" aria-hidden="true"></i> <span><?php _e('Affiliate','woocommerce-securewpmlm-unilevel'); ?></span></h4>
                    </div>
                    
<div class="panel-border">
                    <div class="col-md-6">
        <label class="form-control" id="affiliate_link" style="height:auto;"><?php echo site_url(); ?>/<?php echo $user_name; ?></label>
    </div>
    <button class="btn btn-danger affiliate_link" onclick="copyToClipboard('#affiliate_link')"><?php _e('Copy Link','woocommerce-securewpmlm-unilevel'); ?></button>
                </div>
                

            </div>
        </div>
    </div> 
    
    <?php
}