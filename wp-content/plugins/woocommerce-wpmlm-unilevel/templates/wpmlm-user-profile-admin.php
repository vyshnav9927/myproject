<?php
function wpmlm_user_profile_admin($user_id = '') {
    $results = wpmlm_get_user_details_by_id_join($user_id);
    $results1 = wpmlm_get_user_details_by_id_join($results[0]->user_parent_id);
    $date = strtotime($results1[0]->join_date);
    $joining_date = date('Y-m-d', $date);    
    $package_details = wpmlm_select_package_by_id($results[0]->package_id);
    ?>
    <div class="wooCommerce-earned">
        <div class="accordion md-accordion" id="accordionUserProfile" role="tablist" aria-multiselectable="true">
            <!-- Card header -->
            <div class="card-header head" role="tab" id="headingUserProfile">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordionUserProfile" href="#collapseUserProfile"
                aria-expanded="false" aria-controls="collapseUserProfile">
                <h3 class="mb-0">
                    <?php _e('User Profile','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                </h3>
                </a>
            </div>
            <!-- Card body -->
            <div id="collapseUserProfile" class="collapse show" role="tabpanel" aria-labelledby="headingUserProfile" data-parent="#accordionUserProfile">
                <div class="card-body">
                    <div class="form-div">
                        <div id="user-profile">
                            <div class="panel-border" style="padding-left: 0px;padding-top: 11px;max-width:800px; margin: auto;">

                                <h4 class="mar-t10 mar-b10"><?php _e('Sponsor & Package Information','woocommerce-securewpmlm-unilevel'); ?></h4>
                                <form id="user-form1" class="" method="post">

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="user_name"><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control-plaintext" name="user_name" id="user_name" value="<?php echo $results[0]->user_login; ?>" readonly style="border: none;">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="sponsor_name"><?php _e('Sponsor Name','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control-plaintext" name="sponsor_name" id="sponsor_name" value="<?php echo $results1[0]->user_login; ?>" readonly style="border: none;">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="joining_date"><?php _e('Joining Date','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control-plaintext" name="joining_date" id="joining_date" value="<?php echo $joining_date; ?>" readonly style="border: none;">
                                        </div>
                                    </div>
                                    
                                    <?php if($package_details){?>
                                    <div class="form-group row">
                                        <label class="control-label col-sm-4 col-form-label user-dt" for="registration_package"><?php _e('Registration Package','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control-plaintext" name="registration_package" id="registration_package" value="<?php echo $package_details->package_name; ?>" readonly style="border: none;">
                                        </div>
                                    </div>
                                    <?php }?>   
                                    <hr>
                                </form>
                           
                                <div id="user-form2-message"></div>
                                
                                <h4 class="mar-t15 mar-b10"><?php _e('Personal Information','woocommerce-securewpmlm-unilevel'); ?></h4>
                                <form id="user-form2" class="" method="post" style="margin-top: 20px;">

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="fname"><?php _e('First Name','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="user-input form-control-plaintext" name="fname" id="fname" value="<?php echo $results[0]->user_first_name; ?>" readonly style="border: none;">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="lname"><?php _e('Last Name','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="user-input form-control-plaintext" name="lname" id="lname" value="<?php echo $results[0]->user_second_name; ?>" readonly style="border: none;">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                            <div class="col-sm-4">
                                                <a class="btn btn-primary user-form2-edit"><i class="fa fa-edit"></i>&nbsp;<?php _e('Edit','woocommerce-securewpmlm-unilevel'); ?></a>
                                            </div>
                                            <input id="user_id" type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                            <?php wp_nonce_field('user_form2', 'user_form2_nonce'); ?>
                                            <div class="col-sm-8" id="user-form2-update" style="display: none">
                                                <div>
                                                    <button class="btn btn-success custom-button" type="submit" name="user_form2_save" id="user_form2_save"><?php _e('Update','woocommerce-securewpmlm-unilevel'); ?></button>
                                                    <a data-cancel="user-form2" class="btn btn-danger custom-button edit-cancel"><?php _e('Update','woocommerce-securewpmlm-unilevel'); ?></a>
                                                </div>
                                            </div> 
                                        </div>


                                     <hr>
                                </form>
                                <div id="user-form3-message"></div>

                                <h4 class="mar-t15 mar-b10"><?php _e('Contact Information','woocommerce-securewpmlm-unilevel'); ?></h4>


                                <div id="user-form3-div">
                                    <form id="user-form3" class="" method="post">
                                        <div style="clear: both;"></div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="address1"><?php _e('Address 1','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control-plaintext" name="address1" id="address1" value="<?php echo $results[0]->user_address; ?>" readonly style="border: none;" >
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="user_email"><?php _e('Email','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="email" class="user-input form-control-plaintext" name="user_email" id="user_email" value="<?php echo $results[0]->user_email; ?>" readonly style="border: none;" >
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="dob"><?php _e('Date of birth','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" type="text" class="form-control-plaintext date-picker" name="dob" id="dob" value="<?php echo $results[0]->user_dob; ?>" readonly style="border: none;">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="contact_no"><?php _e('Telephone','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control-plaintext" name="contact_no" id="contact_no" value="<?php echo $results[0]->user_mobile; ?>" readonly style="border: none;" onkeypress="return isNumberKey(event)">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="city"><?php _e('City','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control-plaintext" name="city" id="city" value="<?php echo $results[0]->user_city; ?>" readonly style="border: none;">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="state"><?php _e('State','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control-plaintext" name="state" id="state" value="<?php echo $results[0]->user_state; ?>" readonly style="border: none;">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="country"><?php _e('Country','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <select  class="form-control-plaintext" name="country" id="country" disabled>
                                                    <?php
                                                    $country_results = wpmlm_getAllCountry();
                                                
                                                    foreach ($country_results as $res) {
                                                        if ($results[0]->user_country == $res->id) {
                                                            $selected = 'selected';
                                                            $country_name = $res->name;
                                                        } else {
                                                            $selected = '';
                                                        }
                                                        echo '<option ' . $selected . ' value="' . $res->id . '">' . $res->name . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="zip">Zip Code :</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control-plaintext" name="zip" id="zip" value="<?php echo $results[0]->user_zip; ?>" readonly style="border: none;" onkeypress="return isNumberKey(event)">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <a class="btn btn-primary user-form3-edit"><i class="fa fa-edit"></i>&nbsp;<?php _e('Edit','woocommerce-securewpmlm-unilevel'); ?></a>
                                            </div>
                                            <input id="user_id" type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                            <?php wp_nonce_field('user_form3', 'user_form3_nonce'); ?>
                                            <div class="col-sm-8" id="user-form3-update" style="display: none">
                                                <div>
                                                    <button class="btn btn-success custom-button" type="submit" name="user_form3_save" id="user_form3_save"><?php _e('Update','woocommerce-securewpmlm-unilevel'); ?></button>
                                                    <a data-cancel="user-form3" class="btn btn-danger custom-button edit-cancel"><?php _e('Cancel','woocommerce-securewpmlm-unilevel'); ?></a>
                                                </div>
                                            </div> 
                                        </div>
                                        <hr>
                                    </form>
                                </div>


                                <div class="" id="account-details">
                                    <div id="user-form5-message"></div>
                                    <h4 class="mar-t15 mar-b10"><?php _e('Bank Account Information','woocommerce-securewpmlm-unilevel'); ?></h4>
                                    
                                    <form id="user-form5" class="" method="post">

                                        <div style="clear: both;"></div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="bank_name"><?php _e('Bank Name','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="text" readonly class="form-control-plaintext user-account-input form-control" name="bank_name" id="bank_name" value="<?php echo $results[0]->user_nbank; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="branch_name"><?php _e('Branch Name','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="text" readonly class="form-control-plaintext user-account-input form-control" name="branch_name" id="branch_name" value="<?php echo $results[0]->user_nbranch; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="account_holder"><?php _e('Account Holder','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="text" readonly class="form-control-plaintext user-account-input form-control" name="account_holder" id="account_holder" value="<?php echo $results[0]->user_nacct_holder; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="account_number"><?php _e('Account Number','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="number" readonly class="form-control-plaintext user-account-input form-control" name="account_number" id="account_number" value="<?php echo $results[0]->user_acnumber; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="ifsc_code"><?php _e('IFSC Code','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="text" readonly class="form-control-plaintext user-account-input form-control" name="ifsc_code" id="ifsc_code" value="<?php echo $results[0]->user_ifsc; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <a class="btn btn-primary user-form5-edit"><i class="fa fa-edit"></i>&nbsp;<?php _e('Edit','woocommerce-securewpmlm-unilevel'); ?></a>
                                            </div>
                                            <input id="user_id" type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                            <?php wp_nonce_field('user_form5', 'user_form5_nonce'); ?>
                                            <div class="col-sm-8" id="user-form5-update" style="display: none">
                                                <div>
                                                    <button class="btn btn-success col-sm-offset-2 user_form5_save custom-button" type="submit" name="user_form5_save" id="user_form5_save"><?php _e('Update','woocommerce-securewpmlm-unilevel'); ?></button>
                                                    <a data-cancel="user-form5" class="btn btn-danger edit-cancel custom-button"><?php _e('Cancel','woocommerce-securewpmlm-unilevel'); ?></a>
                                                </div>
                                            </div> 
                                        </div>

                                    </form> 
                                </div>


                                <?php if (!current_user_can('administrator')) { ?>
                                <div class="" id="change-password">
                                    <div id="user-form4-message"></div>
                                    <h4 class="mar-t15 mar-b10"><?php _e('Change Password','woocommerce-securewpmlm-unilevel'); ?></h4>
                                    <form id="user-form4" class="" method="post">
                                        <div style="clear: both;"></div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="password_admin"><?php _e('New Password','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="password" class="form-control-plaintext user-password-input form-control" name="password_admin" id="password_admin">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="confirm_password_admin"><?php _e('New Password','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="password" class="form-control-plaintext user-password-input form-control confirm_password" name="confirm_password_admin" id="confirm_password_admin">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                                <?php wp_nonce_field('user_form4_admin', 'user_form4_admin_nonce'); ?>
                                                <button class="btn btn-primary custom-button user_form4_save" type="submit" name="user_form4_save" id="user_form4_save"><?php _e('Save','woocommerce-securewpmlm-unilevel'); ?></button>
                                            </div>
                                        </div>
                                    </form> 
                                </div>
                                <div id="change-user-trans-password">
                                    
                                    <h4 class="mar-t15 mar-b10"><?php _e('Change Transaction Password','woocommerce-securewpmlm-unilevel'); ?></h4>
                                    
                                    <div id="change-user-trans-pass-form-message"></div>
                                    <form id="change-user-trans-pass-form" class="form-horizontal tab-form" method="post">
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="current_trans_password_user"><?php _e('Current Password','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="password" class="form-control-plaintext user_tran_input form-control" name="current_trans_password_user" id="current_trans_password_user">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="new_trans_password_user"><?php _e('New Password','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="password" class="form-control-plaintext user_tran_input form-control" name="new_trans_password_user" id="new_trans_password_user">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="confirm_trans_password_user"><?php _e('Confirm Password','woocommerce-securewpmlm-unilevel'); ?> :</label>
                                            <div class="col-sm-8">
                                                <input type="password" class="form-control-plaintext user_tran_input form-control confirm_password" name="confirm_trans_password_user" id="confirm_trans_password_user">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <input type="hidden" name="trans_user_id" value="<?php echo $user_id; ?>">
                                                <?php wp_nonce_field('change_user_tran_pass_by_user', 'change_user_tran_pass_by_user_nonce'); ?>
                                                <button class="btn btn-primary custom-button change_user_trans_pswd_save" type="submit" name="change_user_trans_pswd_save" id="change_user_trans_pswd_save"><?php _e('Save','woocommerce-securewpmlm-unilevel'); ?></button>
                                            </div>
                                        </div>
                                        
                                    </form>
                                </div>
                                <?php } ?>
                                
                            </div>  
                                   
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
        
                
        jQuery(document).ready(function ($) {

            
            $(".user-form5-edit").click(function () {
                

                $("#user-form5 [type=text],[type=email],[type=number]").addClass("form-control");
                $("#user-form5 [type=text],[type=email],[type=number]").attr("readonly", false);
                $("#user-form5 [type=text],[type=email],[type=number]").css("border", "1px solid #bbb");
                $("#user-form5 .form-group").css("margin-bottom", "10px");
                $("#user-form5-update").show();
            });


            $(".user-form2-edit").click(function () {                

                $("#user-form2 [type=text]").addClass("form-control");
                $("#user-form2 [type=text]").attr("readonly", false);
                $("#user-form2 [type=text]").css("border", "1px solid #bbb");
                $("#user-form2 .form-group").css("margin-bottom", "10px");
                $("#user-form2-update").show();
            });



            

            $(".user-form3-edit").click(function () {
                $("#dob").datepicker({
                    autoclose: true
                });
                
                $('#country').prop('disabled', false);
                //$("#country").show();
                $("#country_temp").hide();
                

                $("#user-form3 [type=text],[type=email]").addClass("form-control");
                $("#user-form3 [type=text],[type=email]").attr("readonly", false);
                $("#user-form3 [type=text],[type=email]").css("border", "1px solid #bbb");
                $("#user-form3 .form-group").css("margin-bottom", "10px");
                $("#user-form3-update").show();
            });


            $(document).on('click', '.edit-cancel', function () {
                var cancel_id = $(this).attr('data-cancel');
                $("#" + cancel_id + " [type=text], #" + cancel_id + " [type=number], #" + cancel_id + " [type=email]").removeClass("form-control");
                $("#" + cancel_id + " [type=text], #" + cancel_id + " [type=number], #" + cancel_id + " [type=email]").attr("readonly", true);
                $("#" + cancel_id + " [type=text], #" + cancel_id + " [type=number], #" + cancel_id + " [type=email]").css("border", "none");
                //$("#" + cancel_id + " .form-group").css("margin-bottom", "10px");
                $("#" + cancel_id + "-update").hide();
                
                $('#country').prop('disabled', true);
                //$("#country").hide();
                $("#country_temp").show();

                

            });


             $(document).on('submit', '#user-form2', function () {

                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_user_profile');
                isValid = true;
                $(".user-input").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
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
                            //alert(data);
                            $("#user-form2-message").show();
                            $("#user-form2-message").html('<div class="alert alert-info">' + data + '</div>');
                            setTimeout(function () {
                                $("#user-form2-message").hide('slow');
                                $(".edit-cancel").trigger('click');
                            }, 1000);

                        }

                    });
                }
                return false;
            });



            // form 2 update start //
            

            $(document).on('submit', '#user-form3', function () {

                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_user_profile');
                isValid = true;
                $(".user-input").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });
                
                if($( "#user_email" ).hasClass( "invalid" )){
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
                            $("#user-form3-message").show();
                            $("#user-form3-message").html('<div class="alert alert-info">' + data + '</div>');
                            $('html, body').animate({ scrollTop: $("#user-form3-message").offset().top - 100}, 0);
                            setTimeout(function () {
                                $("#user-form3-message").hide('slow');
                                $(".edit-cancel").trigger('click');
                            }, 1000);
                            

                        }

                    });
                }
                return false;
            });


            $(document).on('submit', '#user-form5', function () {

                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_user_profile');
                isValid = true;
                $(".user-account-input").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
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
                            //alert(data);
                            $("#user-form5-message").show();
                            $("#user-form5-message").html('<div class="alert alert-info">' + data + '</div>');
                            setTimeout(function () {
                                $("#user-form5-message").hide('slow');
                                $(".edit-cancel").trigger('click');
                            }, 1000);

                        }

                    });
                }
                return false;
            });



            $(document).on('submit', '#user-form4', function () {
                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_user_profile');
                isValid = true;
                $(".user-password-input").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });
                
                var pass = $("#password_admin").val();
                var confirm_password = $("#confirm_password_admin").val();
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
                            
                            
                            $("#user-form4")[0].reset();
                            $("#user-form4-message").show();
                            $("#user-form4-message").html('<div class="alert alert-info">' + data + '</div>');
                            setTimeout(function () {
                                $("#user-form4-message").hide();
                            }, 1000);

                        }

                    });
                }
                return false;
            })

            $(".user-input").focus(function () {
                $(this).removeClass("invalid");
            });
            $(".user-password-input").focus(function () {
                $(this).removeClass("invalid");
            });

            $(document).on('submit', '#change-user-trans-pass-form', function () {
                $("#change-user-trans-pass-form-message").html('');
                $("#change-user-trans-pass-form-message").show();
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
                                $("#change-user-trans-pass-form-message").html('<div class="alert alert-info">Transaction Password Updated</div>');
                                setTimeout(function () {
                                    $("#change-user-trans-pass-form-message").hide();
                                    $("#change-user-trans-pass-form")[0].reset();
        
                                }, 3000);
        
                            } else {
        
                                $("#change-user-trans-pass-form-message").html('<div class="alert alert-danger">' + data + '</div>');
                                setTimeout(function () {
                                    $("#change-user-trans-pass-form-message").hide();
        
                                }, 3000);
                            }
        
                        }
                    });
                }
                return false;
            });
            $(".user_tran_input").focus(function () {
                $(this).removeClass("invalid");
            });


        });

    </script>
<?php
}