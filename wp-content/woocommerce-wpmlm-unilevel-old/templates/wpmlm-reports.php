<?php

function wpmlm_all_reports() {

    ?>

<style> 

/* Style for loader icon starts */
 .lds-spinner {
  color: official;
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-spinner div {
  transform-origin: 40px 40px;
  animation: lds-spinner 1.2s linear infinite;
}
.lds-spinner div:after {
  content: " ";
  display: block;
  position: absolute;
  top: 3px;
  left: 37px;
  width: 6px;
  height: 18px;
  border-radius: 20%;
  background: #21074b;
}
.lds-spinner div:nth-child(1) {
  transform: rotate(0deg);
  animation-delay: -1.1s;
}
.lds-spinner div:nth-child(2) {
  transform: rotate(30deg);
  animation-delay: -1s;
}
.lds-spinner div:nth-child(3) {
  transform: rotate(60deg);
  animation-delay: -0.9s;
}
.lds-spinner div:nth-child(4) {
  transform: rotate(90deg);
  animation-delay: -0.8s;
}
.lds-spinner div:nth-child(5) {
  transform: rotate(120deg);
  animation-delay: -0.7s;
}
.lds-spinner div:nth-child(6) {
  transform: rotate(150deg);
  animation-delay: -0.6s;
}
.lds-spinner div:nth-child(7) {
  transform: rotate(180deg);
  animation-delay: -0.5s;
}
.lds-spinner div:nth-child(8) {
  transform: rotate(210deg);
  animation-delay: -0.4s;
}
.lds-spinner div:nth-child(9) {
  transform: rotate(240deg);
  animation-delay: -0.3s;
}
.lds-spinner div:nth-child(10) {
  transform: rotate(270deg);
  animation-delay: -0.2s;
}
.lds-spinner div:nth-child(11) {
  transform: rotate(300deg);
  animation-delay: -0.1s;
}
.lds-spinner div:nth-child(12) {
  transform: rotate(330deg);
  animation-delay: 0s;
}
@keyframes lds-spinner {
  0% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}
/* Style for loader icon ends */

</style>



    <div class="panel-heading">
        <h4 class="main-head"><i class="fa fa-file-text-o" aria-hidden="true"></i> <?php _e('Reports','woocommerce-securewpmlm-unilevel'); ?></h4>
    </div>
    <div id="all-reports">

        <div  class="reports" id="exTab4">
        
            <div id="report-tab" class="dashboard-tab-container tab-cnt-hed">
                <ul class="resp-tabs-list hor_11 ">
                    <li id="commission_report" ><?php _e('Bonus Report','woocommerce-securewpmlm-unilevel'); ?></li>
                    <li id="profile_report" ><?php _e('Profile Report','woocommerce-securewpmlm-unilevel'); ?></li>
                    <li id="joining_report" ><?php _e('Joining Report','woocommerce-securewpmlm-unilevel'); ?></li>
                    <li id="payout_report" ><?php _e('Payout Report','woocommerce-securewpmlm-unilevel'); ?></li>
                    <li id="ewallet_report" ><?php _e('Ewallet Report','woocommerce-securewpmlm-unilevel'); ?></li>
                </ul>  

                <div class="resp-tabs-container hor_11 tab-cnt">

                    <div id="commission">
                        <form name="commission-report-search" id="commission-report-search">
                            <div id="commission-date-error"></div>
                            <div class="bonus-report-head">
                                <div class="form-row">
                                    <div class="col-sm-6">
                                        <label class="control-label" for="commission_start_date">
                                            <?php _e('From Date','woocommerce-securewpmlm-unilevel'); ?>: <span class="symbol required"></span>
                                        </label>
                                        <div class="">
                                            <div class="input-group">
                                                <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker commission_date_input" name="commission_start_date" id="commission_start_date" type="text" tabindex="3" size="20" maxlength="10" value="">
                                                <label for="commission_start_date" class="date_label input-group-addon"> <i class="fa fa-calendar"></i> </label>
                                            </div>                        
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label" for="commission_end_date">
                                            <?php _e('To Date','woocommerce-securewpmlm-unilevel'); ?>:<span class="symbol required"></span>
                                        </label>
                                        <div class="">
                                            <div class="input-group">
                                                <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker commission_date_input" name="commission_end_date" id="commission_end_date" type="text" tabindex="4" size="20" maxlength="10" value="">
                                                <label for="commission_end_date" class="date_label input-group-addon"> <i class="fa fa-calendar"></i> </label>
                                            </div>                        
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <div class="">
                                            <?php wp_nonce_field('commission_report', 'commission_report_nonce'); ?>
                                            <button class="btn btn btn-primary mlm-button" tabindex="5" name="commission" type="submit" value="Submit"> <?php _e('Submit','woocommerce-securewpmlm-unilevel'); ?></button>
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="profile">
                        <div class="submit_message"></div>
                        <form id="profile-report-search" name="profile-report-search" class="search-form">
                            <div class="pro-search">
                                <div class="">
                                    <label class="check-new"><?php _e('Username','woocommerce-securewpmlm-unilevel'); ?>
                                        <input type="radio" checked="checked" name="radio">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="check-new"><?php _e('All','woocommerce-securewpmlm-unilevel'); ?>
                                        <input type="radio" name="radio">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-row width-input">
                                    <input type="text" class="search_input col-sm-6 form-control typeahead" name="search" id="search" placeholder="<?php _e('Search..','woocommerce-securewpmlm-unilevel'); ?>" autocomplete="off">
                                    <div class="pro-search-btn col-sm-6"> 
                                        <button type="submit" class="btn btn-primary mlm-button"><?php _e('Search','woocommerce-securewpmlm-unilevel'); ?></button>
                                    </div>
                                </div>
                              
                                
                            </div>
                        </form>                             
                    </div>


                    <div id="joining">
                        <form name="joining-report-search" id="joining-report-search">
                            <div id="date-error"></div>
                            <div class="join-report-head">
                                <div class="form-row">
                                    <div class="col-sm-6">
                                        <label class="control-label" for="start_date">
                                            <?php _e('From Date','woocommerce-securewpmlm-unilevel'); ?>: <span class="symbol required"></span>
                                        </label>
                                        <div class="">
                                            <div class="input-group">
                                                <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker date_input" name="start_date" id="start_date" type="text" tabindex="3" size="20" maxlength="10" value="">
                                                <label for="start_date" class="date_label input-group-addon"> <i class="fa fa-calendar"></i> </label>
                                            </div>                        
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label" for="end_date">
                                            <?php _e('To Date','woocommerce-securewpmlm-unilevel'); ?>:<span class="symbol required"></span>
                                        </label>
                                        <div class="">
                                            <div class="input-group">
                                                <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker date_input" name="end_date" id="end_date" type="text" tabindex="4" size="20" maxlength="10" value="">
                                                <label for="end_date" class="date_label input-group-addon"> <i class="fa fa-calendar"></i> </label>
                                            </div>                        
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <div class="">
                                            <?php wp_nonce_field('joining_report', 'joining_report_nonce'); ?>
                                            <button class="btn btn btn-primary mlm-button" tabindex="5" name="weekdate" type="submit" value="Submit"> <?php _e('Submit','woocommerce-securewpmlm-unilevel'); ?></button>
                                        </div>
                                    </div>
                               
                                </div>

                            </div>
                        </form>
                    </div>

                    <div id="payout">
                        <form name="payout-report-search" id="payout-report-search">
                            <div id="date-error"></div>
                            <div class="payout-report-head">  
                                <div class="form-row">          
                                    <div class="col-sm-4">
                                        <label class="control-label" for="payout_status">
                                            <?php _e('Status','woocommerce-securewpmlm-unilevel'); ?>: <span class="symbol required"></span>
                                        </label>
                                        <div class="">
                                            <div class="input-group">
                                                <select name="payout_status" class="form-control">
                                                    <option value="select_all"><?php _e('Select All','woocommerce-securewpmlm-unilevel'); ?></option>
                                                    <option value="confirmed"><?php _e('Confirmed','woocommerce-securewpmlm-unilevel'); ?></option>
                                                    <option value="pending"><?php _e('Pending','woocommerce-securewpmlm-unilevel'); ?></option>
                                                    <option value="rejected"><?php _e('Rejected','woocommerce-securewpmlm-unilevel'); ?></option>
                                                </select> 
                                            </div>
                                        </div>
                                        <div class=""></div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" for="payout_start_date"><?php _e('From Date','woocommerce-securewpmlm-unilevel'); ?>: <span class="symbol required"></span></label>
                                        <div class="">
                                            <div class="input-group">
                                                <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker payout_date_input" name="payout_start_date" id="payout_start_date" type="text" tabindex="3" size="20" maxlength="10" value="">
                                                <label for="payout_start_date" class="date_label input-group-addon"> <i class="fa fa-calendar"></i> </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" for="payout_end_date"><?php _e('To Date','woocommerce-securewpmlm-unilevel'); ?>:<span class="symbol required"></span></label>
                                        <div class="">
                                            <div class="input-group">
                                                <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker payout_date_input" name="payout_end_date" id="payout_end_date" type="text" tabindex="4" size="20" maxlength="10" value="">
                                                <label for="payout_end_date" class="date_label input-group-addon"> <i class="fa fa-calendar"></i> </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <div class="">
                                            <?php wp_nonce_field('payout_report', 'payout_report_nonce'); ?>
                                            <button class="btn btn btn-primary mlm-button" tabindex="5" name="weekdate" type="submit" value="Submit"> <?php _e('Submit','woocommerce-securewpmlm-unilevel'); ?></button>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </form>
                    </div>



                    <div id="ewallet">
                        <form name="ewallet-report-search" id="ewallet-report-search">
                            <div id="ewallet-date-error"></div>
                            <div class="ewallet-report-head">  
                                <div class="form-row">          
                                   
                                    
                                    <div class="col-sm-4">
                                        <label class="control-label" for="ewallet_start_date">From Date: <span class="symbol required"></span></label>
                                        <div class="">
                                            <div class="input-group">
                                                <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker ewallet_date_input" name="ewallet_start_date" id="ewallet_start_date" type="text" tabindex="3" size="20" maxlength="10" value="">
                                                <label for="ewallet_start_date" class="date_label input-group-addon"> <i class="fa fa-calendar"></i> </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <label class="control-label" for="ewallet_end_date">To Date:<span class="symbol required"></span></label>
                                        <div class="">
                                            <div class="input-group">
                                                <input data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker ewallet_date_input" name="ewallet_end_date" id="ewallet_end_date" type="text" tabindex="4" size="20" maxlength="10" value="">
                                                <label for="ewallet_end_date" class="date_label input-group-addon"> <i class="fa fa-calendar"></i> </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12 mt-3">
                                        <div class="">
                                            <?php wp_nonce_field('ewallet_report', 'ewallet_report_nonce'); ?>
                                            <button class="btn btn btn-primary mlm-button" tabindex="5" name="weekdate" type="submit" value="Submit"> Submit</button>
                                        </div>
                                    </div>
                                    <div class="loading" style="margin:auto; display:none;">
                                        
                                        <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                                        <p>Please Wait...</p>
                                        
                                    </div> 
                                </div>
                            </div>
                        </form>
                    </div>
                    



                </div>
                <div class="" style="display: none" id="report-main-div">
                    <!-- <i class="fa fa-external-link-square"></i> -->
                    <div><div class="no-data"></div></div>
                    <div  id="print_area"class="report-data panel-default" ></div>
                    <div class="container-fluid text-right">
                    <div id = "frame" >
                        <div class="reg-btn">
                            <a class="btn btn-primary print-button mlm-button" href="" onClick="print_report(); return false;"><?php _e('Print','woocommerce-securewpmlm-unilevel'); ?>
                                <span class="report-caption"></span>
                                <img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/document-print.png'; ?>" alt="Print" height="20" width="20" border="none" align="center" >
                            </a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

        </div>
    
    </div>

    <script>

        
        default_commission_data();
        //default_payout_data();


        function default_payout_data() {

           jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {action: 'wpmlm_ajax_payout_report', default_payout: "payout_report_all"},
                cache: false,
            
                success: function (data) {

                    jQuery("#date-error").html('');
                    jQuery("#report-main-div").show();
                    jQuery(".report-caption").html(' Profile Report');
                    if (jQuery.trim(data) === "0") {
                        jQuery(".no-data").show();
                        jQuery(".report-data").html('');
                        jQuery(".print-div").hide();
                        jQuery(".no-data").html('No Data');


                    } else {
                        jQuery("#report-main-div").show();
                        jQuery(".report-data").html(data);
                        jQuery(".print-div").show();
                        jQuery(".no-data").hide();
                        jQuery('#profile_search_table').DataTable({ 
                          "destroy": true, //use for reinitialize datatable
                        });
                    }
                }


            });

        }



        //default_profile_data();


        function default_profile_data() {

            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {action: 'wpmlm_ajax_profile_report', default_profile: "profile_report_all"},
                cache: false,
          
                success: function (data) {

                    jQuery("#date-error").html('');
                    jQuery("#report-main-div").show();
                    jQuery(".report-caption").html(' Profile Report');
                    if (jQuery.trim(data) === "0") {
                        jQuery(".no-data").show();
                        jQuery(".report-data").html('');
                        jQuery(".print-div").hide();
                        jQuery(".no-data").html('No Data');


                    } else {
                        jQuery("#report-main-div").show();
                        jQuery(".report-data").html(data);
                        jQuery(".print-div").show();
                        jQuery(".no-data").hide();
                        jQuery('#profile_search_table').DataTable({ 
                          "destroy": true, //use for reinitialize datatable
                        });
                    }
                }


            });

        }


        function default_joining_data() {
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {action: 'wpmlm_ajax_joining_report', default_joining: "joining_report_all"},
                cache: false,
          
                success: function (data) {


                    jQuery("#date-error").html('');
                    jQuery("#report-main-div").show();
                    jQuery(".report-caption").html(' Joining Report');
                    if (jQuery.trim(data) === "0") {
                        jQuery(".no-data").show();
                        jQuery(".report-data").html('');
                        jQuery(".print-div").hide();
                        jQuery(".no-data").html('No Data');


                    } else {
                        jQuery("#report-main-div").show();
                        jQuery(".report-data").html(data);
                        jQuery(".print-div").show();
                        jQuery(".no-data").hide();
                        jQuery('#profile_search_table').DataTable({ 
                          "destroy": true, //use for reinitialize datatable
                        });
                    }
                }


            });

        }



        function default_commission_data() {

            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {action: "wpmlm_ajax_bonus_report", default_commission: 'commission_report_all'},
                cache: false,
          
                success: function (data) {
                    jQuery("#date-error").html('');
                    jQuery("#report-main-div").show();
                    jQuery(".report-caption").html(' Bonus Report');
                    if (jQuery.trim(data) === "0") {
                        jQuery(".no-data").show();
                        jQuery(".report-data").html('');
                        jQuery(".print-div").hide();
                        jQuery(".no-data").html('No Data');


                    } else {
                        jQuery("#report-main-div").show();
                        jQuery(".report-data").html(data);
                        jQuery(".print-div").show();
                        jQuery(".no-data").hide();
                        jQuery('#profile_search_table').DataTable({ 
                          "destroy": true, //use for reinitialize datatable
                        });
                    }
                }


            });

        }

        function default_ewallet_data(){
            
            jQuery.ajax({
           type: "POST",
           url: ajaxurl,
           data: {action: "wpmlm_ajax_ewallet_report", default_ewallet: 'ewallet_report_all'},
           cache: false,
           beforeSend:function(){
               $('.loading').show();
           },
           complete:function(){
               $('.loading').hide();
           },
           success: function (data) {
               console.log(data);
               jQuery("#ewallet-date-error").html('');
               jQuery("#report-main-div").show();
               jQuery(".report-caption").html(' Ewallet Report');
               if (jQuery.trim(data) === "0") {
                   jQuery(".no-data").show();
                   jQuery(".report-data").html('');
                   jQuery(".print-div").hide();
                   jQuery(".no-data").html('No Data');


               } else {
                   jQuery("#report-main-div").show();
                   jQuery(".report-data").html(data);
                   jQuery(".print-div").show();
                   jQuery(".no-data").hide();
                  
                  
                   
                   
                   jQuery('#ewallet_search_table').DataTable({ 
                     "destroy": true, //use for reinitialize datatable
                   });
               }
           }


       });
}


        jQuery(document).ready(function ($) {


            //$('#profile_search_table').DataTable();
            $(document).on("click", "#profile_report", function () {
                default_profile_data();
            });

            $(document).on("click", "#joining_report", function () {
                default_joining_data();
            });

            $(document).on("click", "#commission_report", function () {
                default_commission_data();
            });

            $(document).on("click", "#payout_report", function () {
                default_payout_data();
            });
            $(document).on("click","#ewallet_report",function(){
                
                default_ewallet_data();
                
            });



            $(".reports li").on("click", function () {
                $("#report-main-div").hide();
            })



            $("#profile-report-search").submit(function () {
                $(".submit_message").html('');
                $(".submit_message").show();
                var search_type = $('input[name=profile_report_sel]:checked').val();
                var search = $('#search').val();
                isValid = true;
                if (search_type == 'user_name') {
                    if (search == "") {
                        $('#search').addClass("invalid");
                        isValid = false;
                    }

                }

                if (isValid) {
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {search: search, search_type: search_type, action: 'wpmlm_ajax_profile_report'},
                        cache: false,
                        success: function (data) {
                            $("#search").val('');
                            $("#date-error").html('');
                            $("#report-main-div").show();
                            $(".report-caption").html(' Profile Report');
                            if ($.trim(data) === "0") {
                                $(".no-data").show();
                                $(".report-data").html('');
                                $(".print-div").hide();
                                $(".no-data").html('No Data');


                            } else if ($.trim(data) === "no-user") {
                                $("#report-main-div").hide();
                                $(".submit_message").html('<div class="alert alert-danger">User name not exists</div>');
                                setTimeout(function () {
                                    $(".submit_message").hide();


                                }, 3000);
                            } else {
                                $("#report-main-div").show();
                                $(".report-data").html(data);
                                $(".print-div").show();
                                $(".no-data").hide();
                                $('#profile_search_table').DataTable({ 
                                  "destroy": true, //use for reinitialize datatable
                                });
                            }
                        }


                    });
                }
                return false;
            })


            // Joining Report Ajax


            $("#joining-report-search").submit(function () {


                var startDate = new Date($('#start_date').val());
                var endDate = new Date($('#end_date').val());

                if (startDate > endDate) {
                    $("#date-error").html('<p style="color:red">You must select an end date greater than start date</p>');
                    $("#report-main-div").hide();
                    return false;
                }

                isValid = true;
                $(".date_input").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });


                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_joining_report');

                if (isValid) {
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $("#date-error").html('');
                            $("#report-main-div").show();
                            $(".report-caption").html(' Joining Report');
                            if ($.trim(data) === "0") {
                                $(".no-data").show();
                                $(".report-data").html('');
                                $(".print-div").hide();
                                $(".no-data").html('No Data');


                            } else {

                                $(".report-data").html(data);
                                $(".print-div").show();
                                $(".no-data").hide();
                                $('#profile_search_table').DataTable({ 
                                  "destroy": true, //use for reinitialize datatable
                                });
                            }
                        }
                    });
                }
                return false;
            })


            //Commission Report Ajax


            $("#commission-report-search").submit(function () {


                var startDate = new Date($('#commission_start_date').val());
                var endDate = new Date($('#commission_end_date').val());

                if (startDate > endDate) {
                    $("#commission-date-error").html('<p style="color:red">You must select an end date greater than start date</p>');
                    $("#report-main-div").hide();
                    return false;
                }

                isValid = true;
                $(".commission_date_input").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });

                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_bonus_report');

                if (isValid) {
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $("#commission-date-error").html('');
                            $("#report-main-div").show();
                            $(".report-caption").html(' Bonus Report');
                            if ($.trim(data) === "0") {
                                $(".no-data").show();
                                $(".report-data").html('');
                                $(".print-div").hide();
                                $(".no-data").html('No Data');


                            } else {

                                $(".report-data").html(data);
                                $(".print-div").show();
                                $(".no-data").hide();
                                $('#profile_search_table').DataTable({ 
                                  "destroy": true, //use for reinitialize datatable
                                });
                            }
                        }
                    });
                }
                return false;
            })


            // payout Report Ajax


            $("#payout-report-search").submit(function () {


                var startDate = new Date($('#payout_start_date').val());
                var endDate = new Date($('#payout_end_date').val());

                if (startDate > endDate) {
                    $("#date-error").html('<p style="color:red">You must select an end date greater than start date</p>');
                    $("#report-main-div").hide();
                    return false;
                }

                isValid = true;
                $(".payout_date_input").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        $(this).addClass("invalid");
                        isValid = false;
                    }
                });


                var formData = new FormData(this);
                formData.append('action', 'wpmlm_ajax_payout_report');

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

                            $("#date-error").html('');
                            $("#report-main-div").show();
                            $(".report-caption").html(' Payout Report');
                            if ($.trim(data) === "0") {
                                $(".no-data").show();
                                $(".report-data").html('');
                                $(".print-div").hide();
                                $(".no-data").html('No Data');


                            } else {

                                $(".report-data").html(data);
                                $(".print-div").show();
                                $(".no-data").hide();
                                $('#profile_search_table').DataTable({ 
                                  "destroy": true, //use for reinitialize datatable
                                });
                            }
                        }
                    });
                }
                return false;
            })


 $("#ewallet-report-search").submit(function () {


var startDate = new Date($('#ewallet_start_date').val());
var endDate = new Date($('#ewallet_end_date').val());

if (startDate > endDate) {
    $("#ewallet-date-error").html('<p style="color:red">You must select an end date greater than start date</p>');
    $("#report-main-div").hide();
    return false;
}

isValid = true;
$(".ewallet_date_input").each(function () {
    var element = $(this);
    if (element.val() == "") {
        $(this).addClass("invalid");
        isValid = false;
    }
});


var formData = new FormData(this);
formData.append('action', 'wpmlm_ajax_ewallet_report');

if (isValid) {

    $.ajax({
        type: "POST",
        url: ajaxurl,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend:function(){
        $('.loading').show();
        $('.table-report').hide();
        },
        complete:function(){
        $('.loading').hide();
        $('.table-report').show();
        },
        success: function (data) {
            //alert(data);

            $("#ewallet-date-error").html('');
            $("#report-main-div").show();
            $(".report-caption").html(' Ewallet Report');
            if ($.trim(data) === "0") {
                $(".no-data").show();
                $(".report-data").html('');
                $(".print-div").hide();
                $(".no-data").html('No Data');


            } else {

                $(".report-data").html(data);
                $(".print-div").show();
                $(".no-data").hide();
                $('#ewallet_search_table').DataTable({ 
                  "destroy": true, //use for reinitialize datatable
                });
            }
        }
    });
}
return false;
})






$(".search_input,.payout_date_input,.date_input,.commission_date_input,.ewallet_date_input").focus(function () {
$(this).removeClass("invalid");
})


          
        });
    </script>




    <script>

        function print_report() {

            var divToPrint = document.getElementById('print_area');
            var htmlToPrint = '' +
                    '<style type="text/css">' +
                    '.table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th{' +
                    'border: 1px solid #727272;' +
                    '}' +
                    '.table-bordered>tbody>tr>td{' +
                    'padding: 0px 2px 0px 2px;' +
                    '}' +
                    '.report-header-right{' +
                    'float: none; margin: auto;' +
                    '}' +
                    '.ui-toolbar{display:none; }' +
                    '.wp-mlm{display: flex;flex-direction: column; }' +
                    '</style>';
            htmlToPrint += divToPrint.outerHTML;
            var newWin = window.open("");
            newWin.document.write(htmlToPrint);
            newWin.print();
            newWin.close();
            return false;
        }


        jQuery("#start_date").datepicker({
            autoclose: true
        });
        jQuery("#end_date").datepicker({
            autoclose: true
        });

        jQuery("#payout_start_date").datepicker({
            autoclose: true
        });
        jQuery("#payout_end_date").datepicker({
            autoclose: true
        });


        jQuery("#commission_start_date").datepicker({
            autoclose: true
        });
        jQuery("#commission_end_date").datepicker({
            autoclose: true
        });
        jQuery("#ewallet_start_date").datepicker({
            autoclose: true
        });
        jQuery("#ewallet_end_date").datepicker({
            autoclose: true
        });

    </script>

    <?php
}
