<?php
function wpmlm_unilevel_tree($user_id='',$tree_type) {
    
    echo '<div id="dynamic-div" class="mlm-tree-div">';
    $user_details = wpmlm_get_all_user_details_join_tree();
    $tree = wpmlm_buildTree($user_details, $user_id);
    $general = wpmlm_get_general_information(); 
    $tree_count = count($user_details);
    foreach ($tree as $key => $data) {

        if (is_array($data)) {
            for($ii=0;$ii<$tree_count;$ii++) { 
                $tree = wpmlm_flattenArray($tree);
            }
        }
    }

    $arr = array();
    $count = 0;
    $conf_res = wpmlm_get_commission_details();
    $level_eligibility = $conf_res->level_eligibility;
    foreach ($tree as $us) {

        if($us->total_bv >= $level_eligibility){
            $image = 'user-level.png';
        }else{
            $image = 'user.png';
        }
        
        $count++;
        if ($count == 1) {
            $parent_id = null;
            $balance = wpmlm_getBalanceAmount($user_id);
            $bv = wpmlm_getBalanceBVAmount($user_id);
            $us->balance_amount=$balance->balance_amount;
            $us->total_bv = $bv->total_bv;
        } else {
            $parent_id = $us->user_parent_id;
        }

        $date = $us->join_date;
        $dt = new DateTime($date);


        $arr[$us->user_ref_id] = Array(
            'name' => $us->user_login,
            'user_id' => $us->user_ref_id,
            'parent_id' => $parent_id,
            //'email' => $us->user_email,
            'fname' => $us->user_first_name,
            'lname' => $us->user_second_name,
            'balance_amount' => round($us->balance_amount, 2),
            'total_bv' => $us->total_bv,
            'join_date'=>$dt->format('Y-m-d'),
            'image'=>$image,
            'currency'=>$general->company_currency,

        );

       
    }
    $uniLevelTree = wpmlm_makeNested($arr);
    $treeJson = json_encode($uniLevelTree[0]);
    ?>  
    <div class="panel-heading">
        <h4 class="main-head"><i class="fa fa-sitemap" aria-hidden="true"></i><?php _e(' Genealogy Tree ','woocommerce-securewpmlm-unilevel'); ?></h4>
    </div>
    <div class="panel panel-default">
        <div class="gen-tree-icons">
            <div><img src = "<?php echo plugins_url() . "/" . WP_MLM_PLUGIN_NAME . "/images/user-level.png"; ?>"><label><?php _e('Level Active','woocommerce-securewpmlm-unilevel'); ?></label></div>
            <div><img src = "<?php echo plugins_url() . "/" . WP_MLM_PLUGIN_NAME . "/images/user.png"; ?>"><label><?php _e('Level In-Active','woocommerce-securewpmlm-unilevel'); ?></label></div>
        </div>
        <div id="unilevel-tree">
            <div class="">            
                <div id="chart-container" width="100%"></div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        //$(function () {
        jQuery( document ).ready( function( $ ) {
        var datasource =<?php echo $treeJson; ?>

        var nodeTemplate = function(data) {
        return `<span class = "user-image" ><img src = "<?php echo plugins_url() . "/" . WP_MLM_PLUGIN_NAME . "/images/".'${data.image}'; ?>" > </span>
                <div class = "title" > ${data.fname} ${data.lname} </div><div class = "tree-popup" ><p>Username: ${data.name}</p>
                <p>Join Date: ${data.join_date}</p><p>Balance Amount: ${data.currency}${data.balance_amount}</p><p>Total BV: ${data.total_bv}</p></div>`;
        };
        var oc = $('#chart-container').orgchart({
        'data' : datasource,
                'nodeTemplate': nodeTemplate
        });        
       });
        
        
    </script>
<?php 
echo '</div>';
}
