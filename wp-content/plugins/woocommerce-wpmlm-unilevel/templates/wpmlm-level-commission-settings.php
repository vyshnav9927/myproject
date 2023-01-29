<?php
function wpmlm_commission_settings()
{
   $res              = wpmlm_get_commission_level_type();
   $res_width        = wpmlm_get_width_ceiling();
   $level_commission = wpmlm_get_level_commission();
   $result           = wpmlm_get_general_information();
?>    
<div id="level-settings">
  <div class="form-div">
     
    <div class="submit_message_level_settings"></div>
    <form id="depth-form" class="form-horizontal tab-form" method="post">
      <h4><?php _e('Level Settings','woocommerce-securewpmlm-unilevel'); ?></h4>

      <div class="form-row">


        <div class="col-sm-6">
          <label class="control-label user-dt" for="depth"><?php _e('Level Depth','woocommerce-securewpmlm-unilevel'); ?>:</label>
          <input type="number" class="form-control level_input" name="depth" id="depth" placeholder="Enter Level" value="<?php echo count($level_commission);?>" min="0" onkeypress="return isNumberKey(event)">
        </div>
        

        <div class="col-sm-12 mt-2">
          <label class="control-label check-new user-dt" for="depth_agree"><?php _e('I agree','woocommerce-securewpmlm-unilevel'); ?>
            <input type="checkbox" class="form-control" name="depth_agree" id="depth_agree">
            <span class="checkmark"></span>
          </label>
        </div>

        <div class="col-sm-12 mt-2 mb-5">
          <?php wp_nonce_field('level_width_depth', 'level_width_depth_nonce');?>
          <input type="submit" name="depth-submit" class="btn btn-primary mlm-button" value="Save">
        </div>

      </div>

    </form>

    <div class="">
      <div class="submit_message1"></div>
      <form id="commission-form" class="form-horizontal tab-form" method="post">
        <h4><?php _e('Bonus Settings','woocommerce-securewpmlm-unilevel'); ?></h4>
        <div class="commission-inner-div form-row">
          <?php
          if (count($level_commission) > 0) {
          ?>
          <div class="col-sm-12" style="margin-bottom: 10px">
            
            <div class="mt-2">
              <label class="check-new"><?php _e('Percentage','woocommerce-securewpmlm-unilevel'); ?>
                 <input type="radio" value="percentage" name="level_type" <?php echo ($res->level_commission_type == 'percentage' && $result->registration_type != 'with_out_package') ? 'checked' : '';?>  >
                 <span class="checkmark"></span>
              </label>
              <label class="check-new"><?php _e('Flat','woocommerce-securewpmlm-unilevel'); ?>
                 <input type="radio" value="flat" name="level_type" <?php echo ($res->level_commission_type == 'flat' || $result->registration_type == 'with_out_package') ? 'checked' : ''; ?>>
                 <span class="checkmark"></span>
              </label>
            </div>

          </div>
          <?php
          $i = 0;
          foreach ($level_commission as $comm) {
          $i++;
          ?>
          <div class="col-sm-6">
            <label class="control-label user-dt" for="level_commission"><?php _e('Level','woocommerce-securewpmlm-unilevel'); ?> <?php echo $i; ?>:</label>
            <input type="text" class="form-control commission_input" name="level_commission[]" value="<?php echo $comm->level_percentage;?>">
          </div>
          <?php
          }
          ?>
          <div class="col-sm-12 mt-3">
            <?php
            wp_nonce_field('level_commission', 'level_commission_nonce');
            ?>
            <input type="submit" name="commission-submit" class="btn btn-primary mlm-button" value="Update">
          </div>
          <?php
          }
          ?>
        </div>
      </form>
    </div>
</div>
</div>
<script>
   jQuery(document).ready(function ($) {
       $("#depth-form").submit(function () {                
           $(".submit_message_level_settings").html('');
           $(".submit_message_level_settings").show();
           
           var formData = new FormData(this);
           formData.append('action', 'wpmlm_level_bonus');
           isValid = true;
   
           $(".level_input").each(function () {
               var element = $(this);
               if ((element.val() == '')|| (element.val() < 1)) {
                   $(this).addClass("invalid");
                   isValid = false;
               }

               if (element.val() > 10) {
                   $(this).addClass("invalid");
                   isValid = false;
               }


           });
           if ($("#depth_agree").is(':not(:checked)')) {
               $("#depth_agree").addClass("invalid");
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
                       $(".submit_message_level_settings").html('<div class="alert alert-info">' + data + '</div>');
                       setTimeout(function () {
                           $(".commission-notice").hide();
                           $(".submit_message_level_settings").hide();
                           $("#commission-form").load(location.href + " .commission-inner-div");
                           
                           
   
                       }, 1000);
   
                   }
               });
   
           }
           return false;
   
       });
   
       $("#commission-form").submit(function () {
           
           $(".submit_message1").html('');
           $(".submit_message1").show();
           
           var formData = new FormData(this);
           formData.append('action', 'wpmlm_level_bonus');
           isValid = true;
   
           $(".commission_input").each(function () {
               var element = $(this);
               if ((element.val() == '')|| (element.val() < 0)) {
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
                       $(".submit_message1").html('<div class="alert alert-info">' + data + '</div>');
                       setTimeout(function () {
                           $(".submit_message1").hide();
   
                       }, 1000);
   
                   }
               });
   
           }
           return false;
   
       });
       
       
       $(document).on("click", ".commission-tab", function () {
           $("#commission-form").load(location.href + " .commission-inner-div");
   
       });
       
       $(".commission_input").focus(function () {
           $(this).removeClass("invalid");
       });
       
       $(".level_input").focus(function () {
           $(this).removeClass("invalid");
       });
   
   });
   
</script>
<?php
}