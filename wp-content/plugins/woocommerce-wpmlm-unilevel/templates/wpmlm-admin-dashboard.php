<?php
function wpmlm_admin_dashboard($user_id) {  
    $user_row = wpmlm_getUserDetails();
    $j_count = wpmlm_getJoiningByTodayCount($user_id);
    
    //$ewallet_credit = wpmlm_getEwalletAmount('credit');
    $ewallet_credit = wpmlm_getEwalletTotalAmount('credit');
    //$ewallet_debit  = wpmlm_getEwalletAmount('debit');
    $ewallet_debit = wpmlm_get_payout_amount('confirmed');

    $debit_amt = ($ewallet_debit->sum !=''? $ewallet_debit->sum:0);
    $credit_amt = ($ewallet_credit->sum !=''? $ewallet_credit->sum:0);
    
    $bonus_amount = wpmlm_get_total_leg_amount_all();
    $bonus_amount_today = wpmlm_get_total_leg_amount_all_by_today();
    
    $bonus_total_amt = ($bonus_amount->total_amount !=''? $bonus_amount->total_amount:0);
    $bonus_total_amt_today = ($bonus_amount_today->total_amount !=''? $bonus_amount_today->total_amount:0);
    
    $top_earners = wpmlm_get_total_leg_amount_all_users_under_admin($user_id);
    $general = wpmlm_get_general_information();
    $year = date('Y');

    
    $joining_details = wpmlm_getJoiningDetailsByMonth($year,$user_id);
    
    
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
    
    <div id="general-settings">

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
                        <td><?php echo count($user_row)-1;?></td>
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
                        <td><?php echo $general->company_currency;?><?php echo $credit_amt;?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Payout','woocommerce-securewpmlm-unilevel'); ?></td>
                        <td>::hlo</td>
                        <td><?php echo $general->company_currency;?><?php echo $debit_amt;?></td>
                    </tr>
                </table>
            </div>
        </div>
      </div>

      <div class="panel-border" style="padding-left: 0px;padding-top: 11px;max-width:800px; margin: auto;">
         
          <script>
            window.onload = function () {
                var ctx = document.getElementById("myChart");
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        datasets: [{
                                label: 'Joinings',
                                lineTension: 0,
                                data: [<?php echo $joining_count; ?>],
                                backgroundColor: ['rgba(54, 162, 235, 0.2)'],
                                borderColor: ['rgba(54, 162, 235, 1)'],
                                borderWidth: 1
                            }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                        }
                    }
                });
            }
          </script>
          <canvas id="myChart" width="500" height="200">
          </canvas>
      </div>
      
      <div class="recently-joined-users">
        <div class="table-responsive">
        <table class="table table-bordered table-responsive-lg" cellspacing="0" width="100%">
          <thead>
              <caption class="user-table-profile"><h3><?php _e('Recently joined users','woocommerce-securewpmlm-unilevel'); ?></h3></caption>
            <tr>
              <th scope="col">#</th>
              <th scope="col"><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?></th>
              <th scope="col"><?php _e('Full Name','woocommerce-securewpmlm-unilevel'); ?></th>
              <th scope="col"><?php _e('Email ID','woocommerce-securewpmlm-unilevel'); ?></th>
            </tr>
          </thead>
          <tbody>
            
                
            <?php
              $last_joined = wpmlm_get_recently_joined_users_under_admin($user_id,'4');
              $jcount = 0;
              foreach($last_joined as $lj){
              $jcount++;
              ?>
              <tr>
              <th scope="row"><?php echo $jcount;?></th>
              <td><?php echo $lj->user_login;?></td>
              <td><?php echo $lj->user_first_name.' '.$lj->user_second_name;?> </td>
              <td><?php echo $lj->user_email;?> </td>
              </tr>
            <?php }?>
              
            
            
          </tbody>
        </table>
      </div>
        
        <div class="mlm-users">
          <h3 class="usr"><?php _e('Top Bonus Earned Users','woocommerce-securewpmlm-unilevel'); ?></h3>
          
          <?php 
          $top_earners = wpmlm_get_total_leg_amount_all_users();
          if(count($top_earners)==0){
              //echo '<div class="top_earners_div"><p>No bonus earned users yet</p></div>';
            _e('<div class="top_earners_div"><p>No bonus earned users yet</p></div>');
          }?>
          
          <div class="top-earners">
            <?php 
            foreach($top_earners as $te){?>
              <div class="top-ern-details">
                <img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/avathar.png'; ?>">
                <h5><?php echo $te->user_first_name;?></h5>
                <h6><?php echo $general->company_currency;?><?php echo $te->total_amount;?></h6>
              </div>
            <?php
            }
            ?>
          </div>
        </div>
      </div>
      
    </div>

    <?php
}
