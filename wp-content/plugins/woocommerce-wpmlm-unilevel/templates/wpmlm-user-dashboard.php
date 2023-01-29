<?php
function wpmlm_user_dashboard($user_id) {
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
     $joining_count = implode(',',$joining_count);
   } else {
      $joining_count = '0,0,0,0,0,0,0,0,0,0,0,0';
   }

   ?>
    
   <div id="general-settings">

      <div class="tile-all-items">

         <div class="tile-single">
            <div class="wooCommerce-earned">
               <div class="accordion md-accordion" id="accordionDownlines" role="tablist" aria-multiselectable="true">
                  <!-- Card header -->
                  <div class="card-header head" role="tab" id="headingDownlines">
                  <a class="collapsed" data-toggle="collapse" data-parent="#accordionDownlines" href="#collapseDownlines"
                     aria-expanded="false" aria-controls="collapseDownlines">
                     <h3 class="mb-0">
                        <?php _e('MLM Info','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                     </h3>
                  </a>
                  </div>
                  <!-- Card body -->
                  <div id="collapseDownlines" class="collapse show" role="tabpanel" aria-labelledby="headingDownlines"
                  data-parent="#accordionDownlines">
                     <div class="card-body">
                         
                        <div class="row">
                            <div class="col-xl-4 col-lg-6 ">
                                <div class="card card-inverse card-success p-0">
                                    <div class="card-block">
                                        <div class="downlines">
                                            <div class="downline-pic"><img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/bar-chart.png'; ?>"></div>
                                            <h6><?php _e('Downlines','woocommerce-securewpmlm-unilevel'); ?></h6>
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
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 ">
                                <div class="card card-inverse card-success p-0">
                                    <div class="card-block">
                                        <div class="downlines">
                                            <div class="downline-pic"><img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/money-bag.png'; ?>"></div>
                                            <h6><?php _e('Bonus','woocommerce-securewpmlm-unilevel'); ?></h6>
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
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 ">
                                <div class="card card-inverse card-success p-0">
                                    <div class="card-block">
                                        <div class="downlines">
                                            <div class="downline-pic"><img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/wallet.png'; ?>"></div>
                                            <h6><?php _e('E-Wallet','woocommerce-securewpmlm-unilevel'); ?></h6>
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
                            </div>
                        </div>
                          
                     </div>
                  </div>
               </div>
            </div>
         </div>

        <?php //echo wpmlm_user_ewallet_details($user_id); ?>
        
         <div class="tile-single">
            <div class="wooCommerce-earned">
               <div class="accordion md-accordion" id="accordionCompareGraph" role="tablist" aria-multiselectable="true">
                  <!-- Card header -->
                  <div class="card-header head" role="tab" id="headingCompareGraph">
                     <a class="collapsed" data-toggle="collapse" data-parent="#accordionCompareGraph" href="#collapseCompareGraph"
                        aria-expanded="false" aria-controls="collapseCompareGraph">
                        <h3 class="mb-0">
                           <?php _e('Joining Report','woocommerce-securewpmlm-unilevel'); ?> <i class="fa fa-caret-down rotate-icon"></i>
                        </h3>
                     </a>
                  </div>
                  <!-- Card body -->
                  <div id="collapseCompareGraph" class="collapse show" role="tabpanel" aria-labelledby="headingCompareGraph"
                  data-parent="#accordionCompareGraph">
                     <div class="card-body">
                        <script>
                        window.onload = function () {

                           var chart = Highcharts.chart('line-graph', {
                              chart: {
                                 type: 'area'
                              },
                              title: {
                                 text: 'Joinings'
                              },
                              subtitle: {
                                 text: 'Monthly Joinings'
                              },
                              legend: {
                                 align: 'right',
                                 verticalAlign: 'middle',
                                 layout: 'vertical'
                              },
                              xAxis: {
                                 categories: [
                                    'Jan',
                                    'Feb',
                                    'Mar',
                                    'Apr',
                                    'May',
                                    'Jun',
                                    'Jul',
                                    'Aug',
                                    'Sep',
                                    'Oct',
                                    'Nov',
                                    'Dec'
                                 ],
                                 labels: {
                                    x: -10
                                 }
                              },
                              yAxis: {
                                 allowDecimals: false,
                                 title: {
                                    text: 'Count'
                                 }
                              },
                              plotOptions: {
                                 area: {
                                    fillOpacity: 0.5
                                 }
                              },
                              series: [{
                                 name: 'Joinings',
                                 data: [<?php echo $joining_count; ?>]

                              }],
                              responsive: {
                                 rules: [{
                                    condition: {
                                       maxWidth: 500
                                    },
                                    chartOptions: {
                                       legend: {
                                          align: 'center',
                                          verticalAlign: 'bottom',
                                          layout: 'horizontal'
                                        },
                                        yAxis: {
                                          labels: {
                                             align: 'left',
                                             x: 0,
                                             y: -5
                                          },
                                          title: {
                                             text: null
                                          }
                                       },
                                       subtitle: {
                                          text: null
                                       }
                                    }
                                 }]
                              }
                           });
                           
                        }
                        </script>
                        <figure class="highcharts-figure">
                           <div id="line-graph"></div>
                        </figure>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="tile-single">
            <div class="wooCommerce-earned">
               <div class="accordion md-accordion" id="accordionUserRecentlyjoinedusers" role="tablist" aria-multiselectable="true">
                  <!-- Card header -->
                  <div class="card-header head" role="tab" id="headingUserRecentlyjoinedusers">
                     <a class="collapsed" data-toggle="collapse" data-parent="#accordionUserRecentlyjoinedusers" href="#collapseUserRecentlyjoinedusers"
                     aria-expanded="false" aria-controls="collapseUserRecentlyjoinedusers">
                        <h3 class="mb-0">
                           <?php _e('Recently joined users','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                        </h3>
                     </a>
                  </div>
                  <!-- Card body -->
                  <div id="collapseUserRecentlyjoinedusers" class="collapse show" role="tabpanel" aria-labelledby="headingUserRecentlyjoinedusers"
                     data-parent="#accordionUserRecentlyjoinedusers">
                     <div class="card-body">
                        <div class="table-responsive">
                           <div class="panel panel-primary filterable">
                              <table id="table" class="table table-bordered table-responsive-lg table-intel">
                                 <thead>
                                 <tr class="filters">
                                    <!--<th>SL.No</th>-->
                                    <th><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?></th>
                                    <th><?php _e('Fullname','woocommerce-securewpmlm-unilevel'); ?></th>
                                    <th><?php _e('Email ID','woocommerce-securewpmlm-unilevel'); ?></th>
                                 </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                    $last_joined = wpmlm_get_recently_joined_users_by_parent($user_id,'4');
                                    $jcount = 0;
                                    if(count($last_joined)==0){
                                       echo '<tr><td colspan="4" >No Recently joined users yet</td></tr>';
                                       }
                                    foreach($last_joined as $lj){
                                    $jcount++;
                                    ?>
                                       <tr>
                                          <!--<td><?php //echo $jcount;?></td>-->
                                          <td><?php echo $lj->user_login;?></td>
                                          <td><?php echo $lj->user_first_name.' '.$lj->user_second_name;?> </td>
                                          <td><?php echo $lj->user_email;?> </td>
                                       </tr>
                                    <?php }?>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="tile-single">
            <div class="wooCommerce-earned">
               <div class="accordion md-accordion" id="accordionUserTopBonusEarnedUsers" role="tablist" aria-multiselectable="true">
                  <!-- Card header -->
                  <div class="card-header head" role="tab" id="headingUserTopBonusEarnedUsers">
                     <a class="collapsed" data-toggle="collapse" data-parent="#accordionUserTopBonusEarnedUsers" href="#collapseUserTopBonusEarnedUsers"
                     aria-expanded="false" aria-controls="collapseUserTopBonusEarnedUsers">
                        <h3 class="mb-0">
                           <?php _e('Top Bonus Earned Users','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                        </h3>
                     </a>
                  </div>
                  <!-- Card body -->
                  <div id="collapseUserTopBonusEarnedUsers" class="collapse show" role="tabpanel" aria-labelledby="headingUserTopBonusEarnedUsers" data-parent="#accordionUserTopBonusEarnedUsers">
                     <div class="card-body">
                        <div class="panel panel-primary filterable">
                           <table id="table" class="table table-bordered table-responsive-lg table-intel">
                              <thead>
                                 <tr class="filters">
                                    <!--<th>SL.No</th>-->
                                    <th><?php _e('Name','woocommerce-securewpmlm-unilevel'); ?></th>
                                    <th><?php _e('Email','woocommerce-securewpmlm-unilevel'); ?></th>
                                    <th><?php _e('Top Bonus','woocommerce-securewpmlm-unilevel'); ?></th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php 
                                 $top_earners = wpmlm_get_total_leg_amount_all_users_under_parent($user_id);
                                 $bcount = 0;
                                 if(count($top_earners)==0){
                                    echo '<tr><td colspan="3" >No bonus earned users yet</td></tr>';
                                 }?>
                                 <?php 
                                 foreach($top_earners as $te){
                                 $bcount++;
                                 ?>
                                    <tr>
                                      <!--<th><?php //echo $bcount;?></th>-->
                                      <td><?php echo $te->user_first_name.' '.$te->user_second_name;?> </td>
                                      <td><?php echo $te->user_email;?></td>
                                      <td><?php echo $general->company_currency;?><?php echo $te->total_amount;?></td>
                                    </tr>
                                 <?php
                                 }
                                 ?>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

      </div>
                       

    </div>

    <?php
}