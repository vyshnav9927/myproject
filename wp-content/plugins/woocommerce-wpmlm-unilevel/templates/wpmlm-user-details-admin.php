<?php

function wpmlm_user_details_admin() {

    ?>
    <div class="panel-heading">
        <h4 class="main-head"><i class="fa fa-users" aria-hidden="true"></i><?php _e(' MLM Users ','woocommerce-securewpmlm-unilevel'); ?></h4>
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
                        <div class="user-details" id="user-div">
                            <div class="panel panel-primary filterable">
                              <table id="user-table" class="table table-striped table-bordered table-responsive-lg" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <!-- <th>SL.No</th> -->
                                    <th><?php _e('User Name','woocommerce-securewpmlm-unilevel'); ?></th>
                                    <th><?php _e('Full Name','woocommerce-securewpmlm-unilevel'); ?></th>
                                    <th><?php _e('Joining Date','woocommerce-securewpmlm-unilevel'); ?></th>
                                    <th><?php _e('Action','woocommerce-securewpmlm-unilevel'); ?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                //   $results = wpmlm_get_all_user_details_join();
                                //   $p_count = 0;
                                //   foreach ($results as $res) {
              
                                //       // <td>' . $p_count . '</td>
                                //       $p_count++;
                                //       echo '<tr>
                                //       <td>' . $res->user_login . '</td>
                                //       <td>' . $res->user_first_name . ' ' . $res->user_second_name . '</td>
                                //       <td>' . date("Y/m/d", strtotime($res->join_date)) . '</td>
                                //       <td> <button type="button" class="btn btn-default btn-sm mlm-button user_view" edit-id="' . $res->ID . '">View details</button> </td>
                                //       </tr>';
                                //   }
                                  ?>
                                </tbody>
                                
                              </table>
                            </div>
                        </div>
                        <div class="col-md-12 please-wait" style="text-align: center; display: none">
                            <img src="<?php echo plugins_url() . '/' . WP_MLM_PLUGIN_NAME . '/images/please-wait.gif'; ?>">
                        </div>
                        <div class="user-details-all">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <script>

    jQuery(document).ready(function ($) {
        
        // function cbDropdown(column) {
        //     return $('<ul>', {
        //         'class': 'cb-dropdown'
        //     }).appendTo($('<div>', {
        //         'class': 'cb-dropdown-wrap'
        //     }).appendTo(column));
        // }

        var wpmlm_all_users_table = $('#user-table').DataTable({
            'processing': true,
            ajax: {
                url: ajaxurl + '?action=wpmlm_all_users_table'
            },
            // initComplete: function () {
            //     this.api().columns().every(function () {
            //         var column = this;
            //         var ddmenu = cbDropdown($(column.header()))
            //             .on('change', ':checkbox', function () {
            //                 var active;
            //                 var vals = $(':checked', ddmenu).map(function (index, element) {
            //                     active = true;
            //                     return $.fn.dataTable.util.escapeRegex($(element).val());
            //                 }).toArray().join('|');

            //                 column
            //                     .search(vals.length > 0 ? '^(' + vals + ')$' : '', true, false)
            //                     .draw();

            //                 // Highlight the current item if selected.
            //                 if (this.checked) {
            //                     $(this).closest('li').addClass('active');
            //                 } else {
            //                     $(this).closest('li').removeClass('active');
            //                 }

            //                 // Highlight the current filter if selected.
            //                 var active2 = ddmenu.parent().is('.active');
            //                 if (active && !active2) {
            //                     ddmenu.parent().addClass('active');
            //                 } else if (!active && active2) {
            //                     ddmenu.parent().removeClass('active');
            //                 }
            //             });

            //         column.data().unique().sort().each(function (d, j) {
            //             var // wrapped
            //                 $label = $('<label>'),
            //                 $text = $('<span>', {
            //                     text: d
            //                 }),
            //                 $cb = $('<input>', {
            //                     type: 'checkbox',
            //                     value: d
            //                 });

            //             $text.appendTo($label);
            //             $cb.appendTo($label);

            //             ddmenu.append($('<li>').append($label));
            //         });
            //     });
            // }
        });
        
        $(document).on("click", ".user_view", function () {
            $(".please-wait").show();
            $(".user-details-all").show();

            var user_id = $(this).attr('edit-id');
            $.get(ajaxurl + '?user_id=' + user_id+'&action=wpmlm_ajax_user_details', function (data) {
                $('.user-details-all').html(data);
                $(".please-wait").hide("slow");

            });
            //$("#user-div").hide();
            return false;

        });

    });

    </script>
    <?php
}

