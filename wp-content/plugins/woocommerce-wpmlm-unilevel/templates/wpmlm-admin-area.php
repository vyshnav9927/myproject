<?php
function wpmlm_admin_area() {
	$user_id = get_current_user_id();
    $current_user = wp_get_current_user();
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
    
    $recent_joinee = wpmlm_get_recently_joined_users('1');
	$last_jo = $recent_joinee[0];
    $date = strtotime($last_jo->user_registered);
    $last_joining_date = date('Y-m-d', $date); 
    
    $user_details = wpmlm_get_user_details($user_id);
    $user = get_user_by('id', $user_id);
	$parent_id = $user_details->user_parent_id;
	$packages = wpmlm_select_all_packages();
	$depth = wpmlm_get_level_depth();

	$year = date('Y');
   	$joining_details = wpmlm_getJoiningDetailsByMonth($year,$user_id);
    
   	$month_array_joining=array();
	if($joining_details){
		foreach($joining_details as $jdt){
			$m_array_joining['month'][] = $jdt->month;
			$m_array_joining['count'][]=$jdt->count;
		}
		
		$j=0;
		for($i=1;$i<13;$i++){					

			if(in_array($i,$m_array_joining['month'])){
				array_push($month_array_joining,$m_array_joining['count'][$j]);
			}else{
				array_push($month_array_joining,0);
				$j=$j-1;						
			}
		 	$j++;
		}
	
		$joining_count=implode(',',$month_array_joining);
	}else{
		$joining_count='0,0,0,0,0,0,0,0,0,0,0,0';
	}
	?>
	<section class="tile-area">
		<div class="container-fluid">
			<div class="panel-heading mb-4">
		        <h3 class="main-head"><?php _e('WP MLM ADMIN','woocommerce-securewpmlm-unilevel'); ?></h3>
		    </div>
			<div class="card-columns">

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
    									                <h6><a href="<?php echo admin_url(); ?>admin.php?page=wpmlm-users"><?php _e('Downlines','woocommerce-securewpmlm-unilevel'); ?></a></h6>
    									                <table>
    									                    <tbody><tr>
    									                        <td>Total</td>
    									                        <td>:</td>
    									                        <td><?php echo count($user_row)-1;?></td>
    									                    </tr>
    									                    <tr>
    									                        <td><?php _e('Today','woocommerce-securewpmlm-unilevel'); ?></td>
    									                        <td>:</td>
    									                        <td><?php echo $j_count->count;?></td>
    									                    </tr>
    									                </tbody></table>
    									            </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-6 ">
                                            <div class="card card-inverse card-success p-0">
                                                <div class="card-block">
                                                    <div class="downlines">
    									                <div class="downline-pic"><img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/money-bag.png'; ?>"></div>
    									                <h6><a href="<?php echo admin_url(); ?>admin.php?page=wpmlm-reports"><?php _e('Bonus','woocommerce-securewpmlm-unilevel'); ?></a></h6>
    									                <table>
    									                    <tbody><tr>
    									                        <td><?php _e('Total','woocommerce-securewpmlm-unilevel'); ?></td>
    									                        <td>:</td>
    									                        <td><?php echo $general->company_currency;?><?php echo nice_number($bonus_total_amt);?></td>
    									                    </tr>
    									                    <tr>
    									                        <td><?php _e('Today','woocommerce-securewpmlm-unilevel'); ?></td>
    									                        <td>:</td>
    									                        <td><?php echo $general->company_currency;?><?php echo nice_number($bonus_total_amt_today);?></td>
    									                    </tr>
    									                </tbody></table>
    									            </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-6 ">
                                            <div class="card card-inverse card-success p-0">
                                                <div class="card-block">
                                                    <div class="downlines">
    									                <div class="downline-pic"><img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/wallet.png'; ?>"></div>
    									                <h6><a href="<?php echo admin_url(); ?>admin.php?page=wpmlm-e-wallet-management"><?php _e('E-Wallet','woocommerce-securewpmlm-unilevel'); ?></a></h6>
    									                <table>
    									                    <tbody><tr>
    									                        <td><?php _e('Balance','woocommerce-securewpmlm-unilevel'); ?></td>
    									                        <td>:</td>
    									                        <td><?php echo $general->company_currency;?><?php echo nice_number($credit_amt);?></td>
    									                    </tr>
    									                    <tr>
    									                        <td><?php _e('Payout','woocommerce-securewpmlm-unilevel'); ?></td>
    									                        <td>:</td>
    									                        <td><?php echo $general->company_currency;?><?php echo nice_number($debit_amt);?></td>
    									                    </tr>
    									                </tbody></table>
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

				<div class="tile-single">
					<div class="wooCommerce-earned">
						<div class="accordion md-accordion" id="accordionMLMusers" role="tablist" aria-multiselectable="true">
							<!-- Card header -->
							<div class="card-header head" role="tab" id="headingMLMusers">
								<a class="collapsed" data-toggle="collapse" data-parent="#accordionMLMusers" href="#collapseMLMusers"
								aria-expanded="false" aria-controls="collapseMLMusers">
									<h3 class="mb-0">
										<?php _e('MLM users','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
									</h3>
								</a>
							</div>
							<!-- Card body -->
							<div id="collapseMLMusers" class="collapse show" role="tabpanel" aria-labelledby="headingMLMusers" data-parent="#accordionMLMusers">
								<div class="card-body">
									<div class="panel panel-primary">
										<table id="userTable" class="table table-bordered table-responsive-lg table-intel">
											<thead>
						                        <tr class="filters">
						                            <!-- <th>Sl.NO</th> -->
						                            <th><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?></th>
						                            <th><?php _e('Full Name','woocommerce-securewpmlm-unilevel'); ?></th>
						                            <th><?php _e('Email','woocommerce-securewpmlm-unilevel'); ?></th>
						                        </tr>
						                    </thead>
						                    <tbody>
						                        <?php
						                        $results = wpmlm_get_all_user_details_join();
						                        // print_r($results);die;
						                        $p_count = 0;
						                            // <td>' . $p_count . '</td>
						                        foreach (array_slice($results, 0, 5) as $res) {
						                            $p_count++;
						                            echo '<tr>
						                            <td>' . $res->user_login . '</td>
						                            <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
						                            <td>' . $res->user_email . '</td>
						                            </tr>';
						                        }
						                        ?>
						                    </tbody>
										</table>
										<div class="view-all-btn">
											<a href="<?php echo admin_url(); ?>admin.php?page=wpmlm-users" class="btn btn-primary mlm-button"><?php _e('View All Users','woocommerce-securewpmlm-unilevel'); ?></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php

				$income_result = wpmlm_get_company_total_income();
				$month_array_income=array();
				$m_array = array();
				if($income_result){
					foreach($income_result as $inc){
						$m_array_income['month'][] = $inc->month;
						$m_array_income['amount'][]=$inc->amount;
					}
					
					$j=0;
					for($i=1;$i<13;$i++){					

						if(in_array($i,$m_array_income['month'])){
							array_push($month_array_income,$m_array_income['amount'][$j]);
						}else{
							array_push($month_array_income,0);
							$j=$j-1;						
						}
					 	$j++;
					}
				
					$month_data_income=implode(',',$month_array_income);
				}else{
					$month_data_income='0,0,0,0,0,0,0,0,0,0,0,0';
				}


				$commission_result = wpmlm_get_company_total_commission();
				$month_array_commission=array();
				$m_array_commission = array();
				if($commission_result){
					foreach($commission_result as $comm){
						$m_array_commission['month'][] = $comm->month;
						$m_array_commission['amount'][]=$comm->amount;
					}
					
					$k=0;
					for($l=1;$l<13;$l++){					

						if(in_array($l,$m_array_commission['month'])){
							array_push($month_array_commission,$m_array_commission['amount'][$k]);
						}else{
							array_push($month_array_commission,0);
							$k=$k-1;						
						}
					 	$k++;
					}
			
					$month_data_commission=implode(',',$month_array_commission);
					// echo $month_data_commission;
				}else{
					$month_data_commission='0,0,0,0,0,0,0,0,0,0,0,0';
				}
				?>
				
				<div class="tile-single">
					<div class="wooCommerce-line-graph">
						<div class="accordion md-accordion" id="accordionGraph" role="tablist" aria-multiselectable="true">
							<!-- Card header -->
							<div class="card-header head" role="tab" id="headingGraph">
								<a class="collapsed" data-toggle="collapse" data-parent="#accordionGraph" href="#collapseGraph"
								aria-expanded="false" aria-controls="collapseGraph">
									<h3 class="mb-0">
										<?php _e('Income v/s Commission comparison','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
									</h3>
								</a>
							</div>
							<!-- Card body -->
							<div id="collapseGraph" class="collapse show" role="tabpanel" aria-labelledby="headingGraph"
								data-parent="#accordionGraph">
								<div class="card-body">
									<script>
						            $( document ).ready(function() {
						            	$('#line-graph').highcharts({
										    chart: {
										        type: 'column'
										    },
										  
										    title: {
										        text: 'Income v/s Commission'
										    },
										    subtitle: {
										        text: 'Monthly Comparison'
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
										            text: 'Amount'
										        }
										    },
										  
										    series: [{
										        name: 'Income',
										        data: [<?php echo $month_data_income;?>]

										    }, {
										        name: 'Commission',
										        data: [<?php echo $month_data_commission;?>]

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
										                },
										                credits: {
										                    enabled: false
										                }
										            }
										        }]
										    }
										});
						            });
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
					<div class="wooCommerce-table">
						<div class="accordion md-accordion" id="accordionRecentlyjoinedusers" role="tablist" aria-multiselectable="true">
							<!-- Card header -->
							<div class="card-header head" role="tab" id="headingRecentlyjoinedusers">
								<a class="collapsed" data-toggle="collapse" data-parent="#accordionRecentlyjoinedusers" href="#collapseRecentlyjoinedusers"
								aria-expanded="false" aria-controls="collapseRecentlyjoinedusers">
									<h3 class="mb-0">
										<?php _e('Recently joined users','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
									</h3>
								</a>
							</div>
							<!-- Card body -->
							<div id="collapseRecentlyjoinedusers" class="collapse show" role="tabpanel" aria-labelledby="headingRecentlyjoinedusers"
								data-parent="#accordionRecentlyjoinedusers">
								<div class="card-body">
									<div class="table-responsive">
										<div class="panel panel-primary">
											<table id="table" class="table table-bordered table-responsive-lg table-intel">
												<thead>
												<tr class="filters">
													<!-- <th>SL.No</th> -->
									              	<th><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?></th>
									              	<th><?php _e('Full name','woocommerce-securewpmlm-unilevel'); ?></th>
									              	<th><?php _e('Joining Date','woocommerce-securewpmlm-unilevel'); ?></th>
												</tr>
												</thead>
												<tbody>
													<?php
									              	$last_joined = wpmlm_get_recently_joined_users_under_admin($user_id,'5');
									              	// print_r($last_joined);
									              	// die;
									              	$jcount = 0;
									              	if(count($last_joined)==0){
										              	echo '<tr><td colspan="4" >No Recently joined users yet</td></tr>';
								          		   	}
								              		foreach($last_joined as $lj){
									              	$jcount++;
									              	?>
										              	<tr>
											              	<!-- <td><?php //echo $jcount;?></td> -->
											              	<td><?php echo $lj->user_login;?></td>
											              	<td><?php echo $lj->user_first_name.' '.$lj->user_second_name;?> </td>
											              	<td><?php echo date("Y/m/d", strtotime($lj->join_date));?> </td>
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

				
				<div class="tile-single user-info">
					<div class="wooCommerce-earned">
						<div class="accordion md-accordion" id="accordionUserInfo" role="tablist" aria-multiselectable="true">
							<!-- Card header -->
							<div class="card-header head" role="tab" id="headingUserInfo">
								<a class="collapsed" data-toggle="collapse" data-parent="#accordionUserInfo" href="#collapseUserInfo"
									aria-expanded="false" aria-controls="collapseUserInfo">
									<h3 class="mb-0">
										<?php _e('User Info','woocommerce-securewpmlm-unilevel'); ?> <i class="fa fa-caret-down rotate-icon"></i>
									</h3>
								</a>
							</div>
							<!-- Card body -->
							<div id="collapseUserInfo" class="collapse show" role="tabpanel" aria-labelledby="headingUserInfo"
							data-parent="#accordionUserInfo">
								<div class="card-body">
									<div class="form-div">
										<div class="user-container">
									        <div class="user-details-wp">
									            <div class="user-img">
									                <img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/avathar.png'; ?>" style="width: 100px; height: auto;"/>
									            </div>
									            <div class="user-cnt">
									                <div class="user-cnt1"><?php echo $current_user->user_firstname;?> <?php echo $current_user->user_lastname; ?></div>
									                <div class="user-cnt2"><?php echo $current_user->user_email; ?></div>
									                <div class="user-cnt3">Last Joined : <?php echo $last_joining_date; ?></div>
									            </div>
									            <div class="user-affiliate">
									            	<div class="form-group text-center">
										                <label class="user-affiliate-label"><?php _e('Affiliate Link','woocommerce-securewpmlm-unilevel'); ?></label>
										                <div class="user-affiliate-link">
											                <label class="form-control" id="affiliate_link" style="height:auto;"><?php echo site_url(); ?>/<?php echo $current_user->user_login; ?></label>
											                <div class="tooltip-button">
											                	<button class="btn btn-primary mlm-button affiliate_link" onclick="copyToClipboard('#affiliate_link')" onmouseout="outFunc()"><span class="tooltiptext" id="myTooltip"><?php _e('Copy to clipboard','woocommerce-securewpmlm-unilevel'); ?></span><?php _e('Copy Link','woocommerce-securewpmlm-unilevel'); ?></button>
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
				

				<div class="tile-single">
					<div class="wooCommerce-earned">
						<div class="accordion md-accordion" id="accordionEwallet" role="tablist" aria-multiselectable="true">
							<!-- Card header -->
							<div class="card-header head" role="tab" id="headingEwallet">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordionEwallet" href="#collapseEwallet"
								aria-expanded="false" aria-controls="collapseEwallet">
								<h3 class="mb-0">
									<?php _e('E-Wallet','woocommerce-securewpmlm-unilevel'); ?> <i class="fa fa-caret-down rotate-icon"></i>
								</h3>
							</a>
							</div>
							<!-- Card body -->
							<div id="collapseEwallet" class="collapse show" role="tabpanel" aria-labelledby="headingEwallet"
							data-parent="#accordionEwallet">
								<div class="card-body">
									<div class="panel panel-primary">
										<table id="eWalletTable" class="table table-bordered table-responsive-lg table-intel">
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
						                        $balamountresults = wpmlm_getBalanceAmountAll();
						                        $p_count = 0;
					                            // <td>' . $p_count . '</td>
						                        foreach (array_slice($balamountresults, 0, 5) as $balres) {
						                            $p_count++;
						                            echo '<tr>
						                            <td>' . $balres->user_login . '</td>
						                            <td>' . $balres->display_name . '</td>
						                            <td>' . $general->company_currency.$balres->balance_amount . '</td>
						                            </tr>';
						                        }
						                        ?>
						                    </tbody>
										</table>
										<div class="view-all-btn">
											<a href="<?php echo admin_url(); ?>admin.php?page=wpmlm-e-wallet-management" class="btn btn-primary mlm-button"><?php _e('View All','woocommerce-securewpmlm-unilevel'); ?></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tile-single">
					<div class="wooCommerce-tree">
						<div class="accordion md-accordion" id="accordionTree" role="tablist" aria-multiselectable="true">
							<!-- Card header -->
							<div class="card-header head" role="tab" id="headingTree">
								<a class="collapsed" data-toggle="collapse" data-parent="#accordionTree" href="#collapseTree"
								aria-expanded="false" aria-controls="collapseTree">
									<h3 class="mb-0">
										<?php _e('Tree','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
									</h3>
								</a>
							</div>
							<!-- Card body -->
							<div id="collapseTree" class="collapse show" role="tabpanel" aria-labelledby="headingTree"
								data-parent="#accordionTree">
								<div class="card-body" style="padding-top: 1.25rem ;">
									<div id="both-genealogy-tab" class="dashboard-tab-container tab-cnt-hed">
										<div class="form-div">
								        	<?php wpmlm_unilevel_tree($user_id,'sponsor'); ?>
								        </div>
								    </div>
								</div>
							</div>
						</div>
					</div>
				</div>

				
				<div class="tile-single">
				    <div class="wooCommerce-earned">
				        <div class="accordion md-accordion" id="accordionCompareGraph" role="tablist" aria-multiselectable="true">
				            <!-- Card header -->
				            <div class="card-header head" role="tab" id="headingCompareGraph">
				                <a class="collapsed" data-toggle="collapse" data-parent="#accordionCompareGraph" href="#collapseCompareGraph"
				                    aria-expanded="false" aria-controls="collapseCompareGraph">
				                    <h3 class="mb-0">
				                        <?php _e('Joining Report','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
				                    </h3>
				                </a>
				            </div>
				            <!-- Card body -->
				            <div id="collapseCompareGraph" class="collapse show" role="tabpanel" aria-labelledby="headingCompareGraph"
				                data-parent="#accordionCompareGraph">
				                <div class="card-body">
				                    <script>
				                        $( document ).ready(function() {
				                        
				                           $('#joining-graph').highcharts({
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
				                           
			                        	});
				                    </script>
				                    <figure class="highcharts-figure">
				                        <div id="joining-graph"></div>
				                    </figure>
				                </div>
				            </div>
				        </div>
				    </div>
				</div>
				

				<div class="tile-single">
					<div class="wooCommerce-earned">
						<div class="accordion md-accordion" id="accordionTopBonusEarnedUsers" role="tablist" aria-multiselectable="true">
							<!-- Card header -->
							<div class="card-header head" role="tab" id="headingTopBonusEarnedUsers">
								<a class="collapsed" data-toggle="collapse" data-parent="#accordionTopBonusEarnedUsers" href="#collapseTwo4"
								aria-expanded="false" aria-controls="collapseTwo4">
									<h3 class="mb-0">
										<?php _e('Top Bonus Earned Users','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
									</h3>
								</a>
							</div>
							<!-- Card body -->
							<div id="collapseTwo4" class="collapse show" role="tabpanel" aria-labelledby="headingTopBonusEarnedUsers" data-parent="#accordionTopBonusEarnedUsers">
								<div class="card-body">
									<div class="panel panel-primary">
										<table id="table" class="table table-bordered table-responsive-lg table-intel">
											<thead>
											<tr class="filters">
												<!-- <th>SL.No</th> -->
												<th><?php _e('Name','woocommerce-securewpmlm-unilevel'); ?></th>
												<th></th>
											</tr>
											</thead>
											<tbody>
												<?php 
									          	$top_earners = wpmlm_get_total_leg_amount_all_users();
									          	$bcount = 0;
									          	if(count($top_earners)==0){
									              	//echo '<tr><td colspan="4" >No bonus earned users yet</td></tr>';
									              	//echo '<tr><td colspan="4" >No bonus earned users yet</td></tr>';
									              	 _e('<tr><td colspan="4" >No bonus earned users yet','woocommerce-securewpmlm-unilevel</td></tr>');
									          	}?>
									          	<?php 
									            foreach($top_earners as $te){
								            	$bcount++;
								            	?>
									            	<tr>
										              <!-- <td><?php //echo $bcount;?></td> -->
										              <td><?php echo $te->user_first_name.' '.$te->user_second_name;?> </td>
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
	</section>
<?php
}