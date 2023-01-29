<?php

function wpmlm_general_settings() {
    $result = wpmlm_get_general_information();
    ?>
    <div id="general-settings">
        <div class="form-div">
            <div id="submit_message"></div>
            <form id="general-form" class="form-horizontal tab-form" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label class="control-label user-dt" for="company_name"><?php _e('Company Name','woocommerce-securewpmlm-unilevel'); ?>:</label>   
                    <input type="text" class="form-control company_input" name="company_name" id="company_name" placeholder="Enter Company Name" value="<?php echo $result->company_name; ?>">
                  
                </div>
                <div class="form-group">
                    <label class="control-label user-dt" for="company_address"><?php _e('Company Address','woocommerce-securewpmlm-unilevel'); ?>:</label>
                    <textarea class="form-control company_input" name="company_address" id="company_address" rows="4"><?php echo $result->company_address; ?></textarea>
            
                </div>
                <div class="form-group">
                    <label class="control-label user-dt" ><b><?php _e('Company Logo','woocommerce-securewpmlm-unilevel'); ?></b></label>
                    <div class="" > <img class="thumb-image-general" src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/uploads/' . $result->company_logo; ?>">       
                    </div>
                </div>
                
                <div class="form-group file-area">
                    <div class="company_logo "> 
                        <label for="company_logo" class="custom-file-upload-logo">
                            <?php
                            if ($result->company_logo == 'default_logo.png') {
                                //echo '<i class="fa fa-cloud-upload"></i> Upload Logo';
                                _e('<i class="fa fa-cloud-upload"></i> Upload Logo','woocommerce-securewpmlm-unilevel');
                            } else {
                                //echo '<i class="fa fa-cloud-upload"></i> Change Logo';
                                _e('<i class="fa fa-cloud-upload"></i> Change Logo','woocommerce-securewpmlm-unilevel');
                            }
                            ?>

                        </label>

                        <input type="file" onchange="previewFile()" class="form-control" name="company_logo" id="company_logo" style="">
                        <label for="image-remove" class="image-remove" style="<?php if ($result->company_logo == 'default_logo.png') {
                                echo 'display:none';
                            } ?>" >
                            <i class="fa fa-trash"></i> <?php _e('Remove','woocommerce-securewpmlm-unilevel'); ?>

                        </label>
                        <div class="file-dummy">
                           <div class="success"><?php _e('Great, your files are selected. Keep on.','woocommerce-securewpmlm-unilevel'); ?></div>
                           <div class="default"><?php _e('Please select some files','woocommerce-securewpmlm-unilevel'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="check-new site_logo_label"><?php _e('Use same image in the Login/Register Page','woocommerce-securewpmlm-unilevel'); ?>
                        <input type="checkbox" <?php echo ($result->site_logo=='active'?'checked':'')?> class="form-control" name="site_logo" id="site_logo" value="active">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="form-group">
                    <label class="control-label  user-dt" for="company_email"><?php _e('Company Email','woocommerce-securewpmlm-unilevel'); ?>:</label>
                    <input type="email" class="form-control company_input" name="company_email" id="company_email" placeholder="Enter Email" value="<?php echo $result->company_email; ?>">
               
                </div>

                <div class="form-group">
                    <label class="control-label  user-dt" for="company_phone"><?php _e('Company Phone','woocommerce-securewpmlm-unilevel'); ?>:</label>
                    <input type="number" class="form-control company_input" name="company_phone" id="company_phone" placeholder="Enter Company Phone" value="<?php echo $result->company_phone; ?>">
                
                </div>

                

                <div class="form-group">
                    <label class="control-label user-dt" for="company_currency"><?php _e('Currency','woocommerce-securewpmlm-unilevel'); ?>:</label>
                      <select class="form-control company_input" id="company_currency" name="company_currency" disabled="true">

                        <?php
                       $currency_result = wpmlm_getAllCurrency();
                       foreach($currency_result as $curr){
                        $cc = htmlspecialchars($curr->symbol);
                        if($result->currency_code ==$curr->code){
                          $selected='selected';
                        }else{
                          $selected='';
                        }
                        echo '<option '.$selected.' value="'.$cc.','.$curr->code.'">'.$curr->country.' ('.$curr->symbol.')</option>';
                       }
                       ?>
                    </select>
   
                </div>
                
                <div class="form-group"> 
               
                    <input type="hidden" name="action" value="" id="action">
                    <input type="hidden" name="image" value="<?php echo $result->company_logo; ?>" id="image">
                    <?php wp_nonce_field('general_add', 'general_add_nonce'); ?>
                    <button id="general-save" type="submit" class="btn btn-primary mlm-button"> Save</button>
              
                </div>
            </form>
        </div>
    </div> 


    <script>
        jQuery(document).ready(function ($) {
            var plugin_url = path.pluginsUrl;
            $("#general-form").submit(function () {
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_general_settings');
                
                isValid = true;
                $(".company_input").each(function () {
                    var element = $(this);
                    if (element.val() == '') {
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
                            
                            $("#submit_message").show();
                            $("#submit_message").html('<div class="alert alert-info">' + data + '</div>');
                            $('html, body').animate({ scrollTop: $("#submit_message").offset().top - 100}, 0);
                            setTimeout(function () {
                                    $("#submit_message").hide();

                                }, 2000);
                        }
                    });
                }
                return false;
            })
            $(".company_input").focus(function () {
                $(this).removeClass("invalid");
            });
            
            $(document).on('click', '.image-remove', function () {
            $('.thumb-image-general').attr('src', plugin_url + '/uploads/default_logo.png');
            $("#image").val('');
            $("#company_logo").val('');
            $(".image-remove").hide();
            $(".custom-file-upload-logo").html('<i class="fa fa-cloud-upload"></i> Upload Logo');
        });
        
        $("#company_logo").change(function () {
            readURL1(this);
            $(".image-remove").show();
        }); 
        });
    </script>
    <?php
}