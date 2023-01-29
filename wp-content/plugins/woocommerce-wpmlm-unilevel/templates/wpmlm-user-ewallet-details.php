<?php

function wpmlm_user_ewallet_details($user_id = '') {
    $results = wpmlm_getEwalletHistory($user_id);
    $bal_amount_arr = wpmlm_getBalanceAmount($user_id);
    $bal_amount = $bal_amount_arr->balance_amount;    
    $bal_amount=number_format((float)$bal_amount, 2, '.', '');    
    $result2 = wpmlm_get_general_information();
    ?>
    

    <div class="wooCommerce-earned">
        <div class="accordion md-accordion" id="accordionEwalletDetails" role="tablist" aria-multiselectable="true">
            <!-- Card header -->
            <div class="card-header head" role="tab" id="headingEwalletDetails">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordionEwalletDetails" href="#collapseEwalletDetails"
                aria-expanded="false" aria-controls="collapseEwalletDetails">
                    <h3 class="mb-0">
                        <?php _e('E-wallet Details','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                    </h3>
                </a>
            </div>
            <!-- Card body -->
            <div id="collapseEwalletDetails" class="collapse show" role="tabpanel" aria-labelledby="headingEwalletDetails" data-parent="#accordionEwalletDetails">
                <div class="card-body">
                    
                    <div  id="profile_print_area" class="report-data" >
                        <?php if (count($results) > 0) { ?>
                        <div class="table-responsive">
                            <table id="ewallet_details_table" class="table table-striped table-bordered table-responsive-lg" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <!-- <th>#</th> -->
                                        <th><?php _e('Date','woocommerce-securewpmlm-unilevel'); ?></th>
                                        <th><?php _e('Description','woocommerce-securewpmlm-unilevel'); ?></th>                                    
                                        <th><?php _e('Account','woocommerce-securewpmlm-unilevel'); ?></th>
                                        <th><?php _e('Balance','woocommerce-securewpmlm-unilevel'); ?></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>

                                        <th colspan="2" style="text-align: right;"><?php _e('Available Balance','woocommerce-securewpmlm-unilevel'); ?></th>
                                        <th colspan="2" style="text-align: right;"><?php echo $result2->company_currency . ' ' . $bal_amount; ?></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    foreach ($results as $res) {
                                        //$count++;
                                        $debit = ($res->type == 'debit') ? $result2->company_currency . $res->amount : '';
                                        $credit = ($res->type == 'credit') ? $result2->company_currency . $res->amount : '';
                                        $balance_amt = ($res->type == 'credit') ? $result2->company_currency . $balance = $balance + $res->amount : $result2->company_currency . $balance = $balance - $res->amount;

                                        $from_id = $res->from_id;

                                        if($res->ewallet_type=='commission'){
                                            $bonus_type='';
                                        }else{
                                            $bonus_type='repurchase ';
                                        }

                                        $the_user = get_user_by('ID', $from_id);
                                        $amount_type = $res->amount_type;
                                        if ($amount_type == "level_bonus") {
                                            $amount_type_des = 'You received '.$bonus_type.'level bonus from ' . $the_user->user_login;
                                        }
                                        if ($amount_type == "self_bonus") {
                                            $amount_type_des = 'You received self purchase bonus';
                                        }
                                        if ($amount_type == "referral_bonus") {
                                            $amount_type_des = 'You received referral bonus from ' . $the_user->user_login;
                                        }

                                        if ($amount_type == "admin_credit") {
                                            if($res->ewallet_type!='payout_request'){
                                               $amount_type_des = 'Credited By Admin - '.$res->transaction_note.'&#013; [Transaction Id : ' . $res->transaction_id . ']'; 
                                           }else{


                                            $amount_type_des = 'Credited By Admin - '.$res->transaction_note; 

                                           }
                                            
                                        }
                                        if ($amount_type == "admin_debit") {
                                            if($res->ewallet_type!='payout_request'){
                                            $amount_type_des = 'Debited By Admin - '.$res->transaction_note.'&#013; [Transaction Id : ' . $res->transaction_id . ']';
                                            }else{
                                                $amount_type_des = 'Debited By Admin - '.$res->transaction_note;

                                            }
                                        }

                                        if ($amount_type == "user_credit") {
                                            $amount_type_des = 'Fund transfered from ' . $the_user->user_login . '&#013; [Transaction Id : ' . $res->transaction_id . ']';
                                        }
                                        if ($amount_type == "user_debit") {
                                            $amount_type_des = 'Fund transfered to ' . $the_user->user_login . '&#013; [Transaction Id : ' . $res->transaction_id . ']';
                                        }


                                        if ($amount_type == "NA") {
                                            $amount_type_des = $res->transaction_note;
                                        }

                                        if($debit){
                                            $account = '<span style="color:red;">- '.$debit.'</span>';
                                        }else{
                                            $account = '<span style="color:green;">+ '.$credit.'</span>';
                                        }

                                        $amnt_type = ucwords(str_replace("_", " ", $amount_type));

                                        $description = "<a id='tooltip-desc' data-toggle='tooltip' title='".$amount_type_des."'>".$amnt_type."</a>";


                                        // <td>' . $count . '</td>
                                        echo '<tr>
                                        <td>' . date("d/m/y", strtotime($res->date_added)) . '</td>
                                        <td>' . $description . '</td>
                                        <td>' . $account . '</td>
                                        <td>' . $balance_amt . '</td>                             
                                        </tr>';
                                    }
                                    ?>
                                </tbody> 
                            </table>
                        </div>

                        <?php
                        } else {
                            //echo '<div class="no-data"> No Data</div>';
                            _e('<div class="no-data">No Data</div>','woocommerce-securewpmlm-unilevel');                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <script>

        jQuery(document).ready(function ($) {
            $('#ewallet_details_table').DataTable({
                "pageLength": 10
            });
        });

    </script>
    <?php
}