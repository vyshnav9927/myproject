<?php

function wpmlm_password_settings() {
    ?>
    
    <div class="panel-heading">
        <h4 class="main-head"><i class="fa fa-key" aria-hidden="true"></i> <?php _e('Password Settings','woocommerce-securewpmlm-unilevel'); ?></h4>
    </div>
    <div id="general-settings">

            <div   id="exTab4">
                <div id="password-tab" class="dashboard-tab-container tab-cnt-hed">

                    <section class="tile-area">
                        <div class="container-fluid">
                            <div class="tile-all">

                                <div class="tile-single">
                                    <div class="wooCommerce-earned">
                                        <div class="accordion md-accordion" id="accordionChangeUserPassword" role="tablist" aria-multiselectable="true">
                                            <!-- Card header -->
                                            <div class="card-header head" role="tab" id="headingChangeUserPassword">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordionChangeUserPassword" href="#collapseChangeUserPassword"
                                                aria-expanded="false" aria-controls="collapseChangeUserPassword">
                                                <h3 class="mb-0">
                                                    <?php _e('Change User Password','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                                                </h3>
                                                </a>
                                            </div>
                                            <!-- Card body -->
                                            <div id="collapseChangeUserPassword" class="collapse show" role="tabpanel" aria-labelledby="headingChangeUserPassword" data-parent="#accordionChangeUserPassword">
                                                <div class="card-body">

                                                    <div class="form-div">
                                                        <div id="user-password-form-message"></div>
                                     
                                                        <form id="user-password-form" class="form-horizontal tab-form" method="post">
                                                            <div class="form-row">
                                                                <div class="col-sm-12">
                                                                    <label class="control-label  user-dt" for="user_name"><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                                    <input type="text" class="password-input form-control" name="username_pwd" id="username_pwd" autocomplete="off">
                                                                  
                                                                </div>


                                                                <div class="col-sm-6">
                                                                    <label class="control-label user-dt" for="password_user"><?php _e('New Password','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                                    <input type="password" class="password-input form-control" name="password_user" id="password_user">
                                                                  
                                                                </div>

                                                                <div class="col-sm-6">
                                                                    <label class="control-label  user-dt" for="confirm_password"><?php _e('Confirm Password','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                                    <input type="password" class="password-input form-control confirm_password" name="confirm_password_user" id="confirm_password_user">
                                                                  
                                                                </div>
                                                                
                                                                <div class="col-sm-12 mt-3">
                                                                    <?php wp_nonce_field('user_password_admin', 'user_password_admin_nonce'); ?>
                                                                    <button class="btn btn-primary user_password_save mlm-button" type="submit" name="user_password_save" id="user_password_save"><?php _e('Save','woocommerce-securewpmlm-unilevel'); ?> </button>
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
                                        <div class="accordion md-accordion" id="accordionChangeTransactionPassword" role="tablist" aria-multiselectable="true">
                                            <!-- Card header -->
                                            <div class="card-header head" role="tab" id="headingChangeTransactionPassword">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordionChangeTransactionPassword" href="#collapseChangeTransactionPassword"
                                                aria-expanded="false" aria-controls="collapseChangeTransactionPassword">
                                                    <h3 class="mb-0">
                                                        <?php _e('Change Transaction Password','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                                                    </h3>
                                                </a>
                                            </div>
                                            <!-- Card body -->
                                            <div id="collapseChangeTransactionPassword" class="collapse show" role="tabpanel" aria-labelledby="headingChangeTransactionPassword" data-parent="#accordionChangeTransactionPassword">
                                                <div class="card-body">

                                                    <div class="form-div">
                                                        <div class="submit_message_change_pswrd"></div>
                                               
                                                        <form id="send-tran-pass-form" class="form-horizontal tab-form" method="post">
                                                            <div class="form-row">
                                                                <div class="col-sm-12">
                                                                    <label class="control-label user-dt" for="tran_user_name"><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                                    <input type="text" class="form-control user_tran_input" name="tran_user_name" id="tran_user_name">
                                                                </div>


                                                                <div class="col-sm-6">
                                                                    <label class="control-label  user-dt" for="tran_user_name"><?php _e('Transaction Password','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                                    <input type="password" class="form-control user_tran_input" name="user_tran_password" id="user_tran_password">
                                                                </div>

                                                                <div class="col-sm-6">
                                                                    <label class="control-label user-dt" for="tran_user_name"><?php _e('Confirm Transaction Password','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                                    <input type="password" class="form-control user_tran_input" name="confirm_user_tran_password" id="confirm_user_tran_password">
                                                                </div>


                                                                <div class="col-sm-12 mt-3"> 
                                                                    <div class="">
                                                                        <?php wp_nonce_field('send_tran_pass', 'send_tran_pass_nonce'); ?>
                                                                        <button id="send-tran-pass-button" type="submit" class="btn btn-primary send-tran-pass-button mlm-button" > <?php _e('Update Password','woocommerce-securewpmlm-unilevel'); ?></button>
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
                                        <div class="accordion md-accordion" id="accordionChangeAdminTransactionPassword" role="tablist" aria-multiselectable="true">
                                            <!-- Card header -->
                                            <div class="card-header head" role="tab" id="headingChangeAdminTransactionPassword">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordionChangeAdminTransactionPassword" href="#collapseChangeAdminTransactionPassword"
                                                aria-expanded="false" aria-controls="collapseChangeAdminTransactionPassword">
                                                <h3 class="mb-0">
                                                    <?php _e('Change Admin Transaction Password','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                                                </h3>
                                                </a>
                                            </div>
                                            <!-- Card body -->
                                            <div id="collapseChangeAdminTransactionPassword" class="collapse show" role="tabpanel" aria-labelledby="headingChangeAdminTransactionPassword" data-parent="#accordionChangeAdminTransactionPassword">
                                                <div class="card-body">

                                                    <div class="form-div">
                                                        <div class="submit_message"></div>

                                                        <form id="change-tran-pass-form" class="form-horizontal tab-form" method="post">
                                                            <div class="form-row">
                                                                <div class="col-sm-12">
                                                                    <label class="control-label user-dt" for="current_tran_pass"><?php _e('Current Password','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                                    <input type="password" class="form-control admin_tran_input" name="current_tran_pass" id="current_tran_pass">
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="control-label user-dt" for="new_tran_pass"><?php _e('New Password','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                                    <input type="password" class="form-control admin_tran_input" name="new_tran_pass" id="new_tran_pass" >
                                                                </div>

                                                                <div class="col-sm-6">
                                                                    <label class="control-label  user-dt" for="confirm_tran_pass"><?php _e('Confirm New Password','woocommerce-securewpmlm-unilevel'); ?>:</label>
                                                                    <input type="password" class="form-control admin_tran_input" name="confirm_tran_pass" id="confirm_tran_pass">
                                                                </div>

                                                                <div class="col-sm-12 mt-3"> 
                                                                    <?php wp_nonce_field('change_tran_pass', 'change_tran_pass_nonce'); ?>
                                                                    <button id="change-tran-pass-button" type="submit" class="btn btn-primary change-tran-pass-button mlm-button"> <?php _e('Update','woocommerce-securewpmlm-unilevel'); ?></button>
                                                                </div>
                                                            </div>
                                                        </form>   
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
        jQuery(document).ready(function ($) {

            // Send transaction password

            
            $("#send-tran-pass-form").submit(function () {
                $(".submit_message_change_pswrd").html('');
                $(".submit_message_change_pswrd").show();
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_transaction_password');
                isValid = true;
                $(".user_tran_input").each(function () {
                    var element = $(this);
                    if (element.val() == '') {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });


                var pass = $("#user_tran_password").val();
                var confirm_password = $("#confirm_user_tran_password").val();
                if (pass.length < 6) {
                   $("#user_tran_password").addClass("invalid");
                  isValid = false;
                }                
                
                if (confirm_password != pass && confirm_password != '') {
                    $("#user_tran_password").addClass("invalid");
                    $("#confirm_user_tran_password").addClass("invalid");
                   isValid = false; 
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
                            //alert(data);

                            if ($.trim(data) === "1") {
                                $(".submit_message_change_pswrd").html('<div class="alert alert-info">Transaction Password Updated</div>');
                                setTimeout(function () {
                                    $(".submit_message_change_pswrd").hide();
                                    $("#change-tran-pass-form")[0].reset();

                                }, 3000);

                            } else {

                                $(".submit_message_change_pswrd").html('<div class="alert alert-danger">' + data + '</div>');
                                setTimeout(function () {
                                    $(".submit_message_change_pswrd").hide();

                                }, 3000);
                            }

                        }
                    });
                }
                return false;
            });



            // Admin transaction password change

           
            $("#change-tran-pass-form").submit(function () {
                $(".submit_message").html('');
                $(".submit_message").show();
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_transaction_password');
                isValid = true;
                $(".admin_tran_input").each(function () {
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

                            if ($.trim(data) === "1") {
                                $(".submit_message").html('<div class="alert alert-info">Transaction Password Updated</div>');
                                setTimeout(function () {
                                    $(".submit_message").hide();
                                    $("#change-tran-pass-form")[0].reset();

                                }, 3000);

                            } else {

                                $(".submit_message").html('<div class="alert alert-danger">' + data + '</div>');
                                setTimeout(function () {
                                    $(".submit_message").hide();

                                }, 3000);
                            }

                        }
                    });
                }
                return false;
            });
            $(".admin_tran_input,.user_tran_input,#tran_user_name").focus(function () {
                $(this).removeClass("invalid");
            });

            // User transaction password change

            
            
            $("#change-user-tran-pass-form").submit(function () {
                $(".submit_message").html('');
                $(".submit_message").show();
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_transaction_password');
                isValid = true;
                $(".user_tran_input").each(function () {
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

                            if ($.trim(data) === "1") {
                                $(".submit_message").html('<div class="alert alert-info">Transaction Password Updated</div>');
                                setTimeout(function () {
                                    $(".submit_message").hide();
                                    $("#change-user-tran-pass-form")[0].reset();

                                }, 3000);

                            } else {

                                $(".submit_message").html('<div class="alert alert-danger">' + data + '</div>');
                                setTimeout(function () {
                                    $(".submit_message").hide();

                                }, 3000);
                            }
                        }
                    });
                }
                return false;
            });



            $(document).on('submit', '#user-password-form', function () {
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_transaction_password');
                isValid = true;
                $(".password-input").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });
                
                var pass = $("#password_user").val();
                var confirm_password = $("#confirm_password_user").val();
                if (pass.length < 6) {
                  isValid = false;
                }
                
                
                if (confirm_password != pass && confirm_password != '') {
                   isValid = false; 
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

                            $("#user-password-form")[0].reset();
                            $("#user-password-form-message").show();
                            $("#user-password-form-message").html('<div class="alert alert-info">' + data + '</div>');
                            setTimeout(function () {
                                $("#user-password-form-message").hide();
                                //window.location.href = site.siteUrl + '/user-login/';
                            }, 1000);

                        }

                    });
                }
                return false;
            });

            $(".user-input").focus(function () {
                $(this).removeClass("invalid");
            });
            $(".password-input").focus(function () {
                $(this).removeClass("invalid");
            });


        });
    </script>
    
    <?php
}
