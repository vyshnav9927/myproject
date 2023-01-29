<?php
function wpmlm_user_referrals($user_id = '') {
    $results = wpmlm_get_user_details_by_parent_id_join($user_id);
    $current_user_id = get_current_user_id();
    $user_info=get_userdata($current_user_id);
    $role = implode(', ', $user_info->roles);     
    ?>
    
     <div class="wooCommerce-earned">
        <div class="accordion md-accordion" id="accordionReferralDetails" role="tablist" aria-multiselectable="true">
            <!-- Card header -->
            <div class="card-header head" role="tab" id="headingReferralDetails">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordionReferralDetails" href="#collapseReferralDetails"
                aria-expanded="false" aria-controls="collapseReferralDetails">
                    <h3 class="mb-0">
                        <?php _e('Referral Details','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                    </h3>
                </a>
            </div>
            <!-- Card body -->
            <div id="collapseReferralDetails" class="collapse show" role="tabpanel" aria-labelledby="headingReferralDetails" data-parent="#accordionReferralDetails">
                <div class="card-body">
                        
                    <div  id="profile_print_area" class="report-data" >
                        <?php
                        if (count($results) > 0) {
                        ?>
                        <div class="table-responsive">
                            <table id="user-referrals-table" class="table table-striped table-bordered table-responsive-lg" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <!-- <th>#</th> -->
                                        <th><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?></th>
                                        <th><?php _e('Full Name','woocommerce-securewpmlm-unilevel'); ?></th>                                    
                                        <th><?php _e('Joining Date','woocommerce-securewpmlm-unilevel'); ?></th>
                                        <th><?php _e('Email','woocommerce-securewpmlm-unilevel'); ?></th>
                                        <?php if($role=='administrator'){?>
                                        <th><?php _e('Action','woocommerce-securewpmlm-unilevel'); ?></th>
                                        <?php }?>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($results as $res) {
                                        
                                        if($role=='administrator'){
                                            $action = '<td><div class="view-all-btn"><button type="button" class="btn btn-default btn-sm user_view mlm-button" edit-id="'.$res->ID.'">'. _e('View details','woocommerce-securewpmlm-unilevel') .'</button></div></td>';
                                        }else{
                                            $action ='';
                                        }

                                        // <td>' . $count . '</td>
                                        $count++;
                                        echo '<tr>
                                        <td>' . $res->user_login . '</td>
                                            <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
                                            <td>' . date("Y/m/d", strtotime($res->join_date)) . '</td>
                                            <td>' . $res->user_email . '</td>'.$action;                                    
                                        '</tr>';
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
            $('#user-referrals-table').DataTable({
                "pageLength": 10
            });
        });

    </script>
    <?php
}