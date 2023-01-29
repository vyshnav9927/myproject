<?php

function wpmlm_purchace_commission_settings() {
    $result = wpmlm_get_commission_details();
    $self_purchase_commission = wpmlm_get_self_purchase_commission();
    $self_comm_amnt = $self_purchase_commission->self_commission;
    ?>
    <div id="purchase-settings">      
        <div class="form-div">               
            <div class="submit_message"></div>
            <form id="purchase-form" class="form-horizontal tab-form" method="post" enctype="multipart/form-data">
                <div class="form-row  commission-setting">

                    <div class="col-sm-6">
                        <label class="control-label  user-dt" for="minimum_withdraw"><?php _e('Minimum Payout Request Amount','woocommerce-securewpmlm-unilevel'); ?></label>
                        <input type="text" class="form-control purchase_input" name="minimum_withdraw" id="minimum_withdraw" placeholder="10" value="<?php echo $result->minimum_withdraw;?>">
                    </div>
                    
                    <div class="col-sm-6">
                        <label class="control-label user-dt" for="referral_commission"><?php _e('Referral commission','woocommerce-securewpmlm-unilevel'); ?>:(%)</label>
                        <input type="text" class="form-control purchase_input" name="referral_commission" id="referral_commission" placeholder="10" value="<?php echo $result->referral_commission;?>">
                    </div>
                    
                    
                    <div class="col-sm-6">
                        <label class="control-label  user-dt" for="self_commission"><?php _e('Self purchase commission','woocommerce-securewpmlm-unilevel'); ?>:(%)</label>
                        <input type="text" class="form-control purchase_input" name="self_commission" id="self_commission" placeholder="10" value="<?php echo $result->self_commission;?>">
                        <span class="commission_desc"><p><em><?php _e('This is the % (Product amount or Product BV) get by purchasing products himself/herself.','woocommerce-securewpmlm-unilevel'); ?></em></p></span>
                    </div>
                    <?php if($self_comm_amnt > 0): ?>
                    <div class="col-sm-6">
                        <label class="control-label  user-dt" for="level_eligibility"><?php _e('Level commission eligibility','woocommerce-securewpmlm-unilevel'); ?>:</label>
                        <input type="text" class="form-control purchase_input" name="level_eligibility" id="level_eligibility" placeholder="1000" value="<?php echo $result->level_eligibility;?>">
                        <span class="commission_desc"><p><em><?php _e('User will get level commission from downlines only if he/she should reach this amount from Self Purchase Commission.','woocommerce-securewpmlm-unilevel'); ?></em></p></span>
                    </div>
                    <?php endif; ?>
                    <div class="col-sm-12"> 
                        <div class="mt-3">
                            <?php wp_nonce_field('purchase_add', 'purchase_add_nonce'); ?>
                            <button id="purchase-save" type="submit" class="btn btn-primary mlm-button"> <?php _e('Save','woocommerce-securewpmlm-unilevel'); ?></button>
                        </div>
                    </div>

                </div>

            </form>
        </div>
    </div> 


    <script>
        jQuery(document).ready(function ($) {
            var plugin_url = path.pluginsUrl;
            $("#purchase-form").submit(function () {
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_purchase_settings');
                
                isValid = true;
                $(".purchase_input").each(function () {
                    var element = $(this);
                    if (element.val() == '') {
                        $(this).addClass("invalid");
                        isValid = false;
                    }

                    if (element.val() < 0) {
                        $(this).addClass("invalid");
                        isValid = false;
                    }

                });
                if (isValid) {
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                           
                            
                            $(".submit_message").show();
                            $(".submit_message").html('<div class="alert alert-info">' + data + '</div>');
                            setTimeout(function () {
                                    $(".submit_message").hide();
                                    $("#purchase-form").load(location.href + " .commission-setting");
                                }, 2000);
                        }
                    });
                }
                return false;
            })
            $(".purchase_input").focus(function () {
                $(this).removeClass("invalid");
            });
            
           
        
        });
    </script>
    <?php
}