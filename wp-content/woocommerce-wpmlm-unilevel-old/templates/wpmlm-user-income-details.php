<?php

function wpmlm_user_income_details($user_id = '') {
    $results = wpmlm_get_leg_amount_details_by_user_id($user_id);
    $results1 = wpmlm_get_total_leg_amount_by_user_id($user_id);
    $result_count = count($results);
    $result2 = wpmlm_get_general_information();
    ?>
    <div class="wooCommerce-earned">
        <div class="accordion md-accordion" id="accordionBonusDetails" role="tablist" aria-multiselectable="true">
            <!-- Card header -->
            <div class="card-header head" role="tab" id="headingBonusDetails">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordionBonusDetails" href="#collapseBonusDetails"
                aria-expanded="false" aria-controls="collapseBonusDetails">
                    <h3 class="mb-0">
                        <?php _e('Bonus Details','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                    </h3>
                </a>
            </div>
            <!-- Card body -->
            <div id="collapseBonusDetails" class="collapse show" role="tabpanel" aria-labelledby="headingBonusDetails" data-parent="#accordionBonusDetails">
                <div class="card-body">
                        
                    <div id="profile_print_area" class="report-data" >
                        <?php if ($result_count > 0) { ?>                 
                        <div class="table-responsive">
                            <table id="user-income-table" class="table table-striped table-bordered " cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <!-- <th>#</th> -->
                                        <th><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?></th>
                                        <th><?php _e('Amount Type','woocommerce-securewpmlm-unilevel'); ?></th>                                    
                                        <th><?php _e('Date','woocommerce-securewpmlm-unilevel'); ?></th>
                                        <th><?php _e('Amount','woocommerce-securewpmlm-unilevel'); ?></th>

                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>

                                        <th colspan="2" style="text-align: right;"><?php _e('Total Amount','woocommerce-securewpmlm-unilevel'); ?></th>
                                        <th colspan="2" style="text-align: right;"><?php echo $result2->company_currency . ' ' . $results1->total_amount; ?></th>
                                    </tr>
                                </tfoot>

                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($results as $res) {

                                        // <td>' . $count . '</td>
                                        $count++;
                                        echo '<tr>
                                        <td>' . $res->user_login . '</td>
                                        <td>' . ucwords(str_replace("_", " ", $res->amount_type)) . '</td>
                                        <td>' . date("Y/m/d", strtotime($res->date_of_submission)) . '</td>
                                        <td>' . $result2->company_currency . ' ' . $res->total_amount . '</td></tr>';
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                        <?php
                        } else {
                            //echo '<div class="no-data"> No Data</div>';
                            _e('<div class="no-data"> No Data</div>','woocommerce-securewpmlm-unilevel');
                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
    

    <script>
        jQuery(document).ready(function ($) {
            $('#user-income-table').DataTable({
                "pageLength": 10,
            });
        });
        </script>
    <?php
}