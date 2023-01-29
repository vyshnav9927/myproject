<?php
/* Get level depth */
function wpmlm_get_level_depth() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_level_commission";
    $sql1 = "SELECT COUNT(*) FROM {$table_name}";
    return $count = $wpdb->get_var($sql1);
}

/* Set level commission */
function wpmlm_setLevel($depth) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_level_commission";
    $level_depth = wpmlm_get_level_depth();
    if ($level_depth < $depth) {

        for ($j = $level_depth + 1; $j <= $depth; $j++) {
            $sql1 = "INSERT INTO {$table_name}(level_no,level_percentage) VALUES('" . $j . "',0)";
            $wpdb->query($sql1);
        }
    } else {
        $limit = $level_depth - $depth;
        $sql2 = "DELETE FROM {$table_name} order by id DESC LIMIT $limit";
        $wpdb->query($sql2);
    }
}


/* Update level commission */
function wpmlm_update_level_commission($level_commission) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_level_commission";
    $sql1 = "TRUNCATE TABLE {$table_name}";
    $wpdb->query($sql1);
    $j = 0;
    foreach ($level_commission as $com) {
        $j++;
        $sql2 = "INSERT INTO {$table_name}(level_no,level_percentage) VALUES('" . $j . "','" . $com . "')";
        $wpdb->query($sql2);
    }
}

function wpmlm_get_level_commission() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_level_commission";
    $sql1 = "SELECT * FROM {$table_name}";
    return $results = $wpdb->get_results($sql1);
}
function wpmlm_get_self_purchase_commission() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_configuration";
    $sql = "SELECT self_commission FROM {$table_name}";
    return $results = $wpdb->get_row($sql);
}
function wpmlm_update_level_commission_type($level_type) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_configuration";
    $sql = "UPDATE {$table_name} SET  `level_commission_type`= '" . $level_type . "' where `id`= 1  ";
    $wpdb->query($sql);
}

function wpmlm_get_commission_level_type() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_configuration";
    $sql = "SELECT level_commission_type FROM {$table_name} where `id`= 1  ";
    return $result = $wpdb->get_row($sql);
}

function wpmlm_get_width_ceiling() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_configuration";
    $sql = "SELECT width_ceiling FROM {$table_name} where `id`= 1  ";
    return $result = $wpdb->get_row($sql);
}


function wpmlm_get_commission_details() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_configuration";
    $sql = "SELECT * FROM {$table_name} where `id`= 1  ";
    return $result = $wpdb->get_row($sql);
}

/* get all user details of a particular level */

function wpmlm_get_user_details_by_level($level) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $sql = "SELECT * FROM {$table_name} WHERE user_level = '" . $level . "'";
    $results = $wpdb->get_results($sql);
    return $results;
}

/* insert user registration details */

function wpmlm_insert_user_registration_details($user_details) {

    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $result = $wpdb->insert($table_name, $user_details);


    if ($result) {
        return true;
    } else {
        return false;
    }
}

/* get userlevel by parent id */

function wpmlm_get_user_level_by_id($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $sql = "SELECT user_level FROM {$table_name} WHERE user_ref_id = '" . $user_id . "'";
    $user_level = $wpdb->get_var($sql);
    return $user_level;
}


/* get userlevel by parent id */

function wpmlm_get_user_level_by_parent_id($user_parent_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $sql = "SELECT user_level FROM {$table_name} WHERE user_ref_id = '" . $user_parent_id . "'";
    $user_level = $wpdb->get_var($sql);
    return $user_level + 1;
}

/* get user details by user id */

function wpmlm_get_user_details($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $sql = "SELECT * FROM {$table_name} WHERE user_ref_id = '" . $user_id . "'";
    $results = $wpdb->get_row($sql);
    return $results;
}

/* get bonus percentage of a paricular level */

function wpmlm_get_level_percentage($level) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_level_commission";
    $sql = "SELECT level_percentage FROM {$table_name} WHERE level_no = '" . $level . "'";
    $result = $wpdb->get_var($sql);
    return $result;
}

/* get all registration packages */

function wpmlm_select_all_packages() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_registration_packages";
    $sql = "SELECT * FROM {$table_name}";
    $results = $wpdb->get_results($sql);
    return $results;
}

/* registration package name duplication checking */

function wpmlm_package_name_check($package_name) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_registration_packages";
    $sql = "SELECT count(*) FROM {$table_name} where package_name='" . $package_name . "'";
    $result = $wpdb->get_var($sql);
    return $result;
}

/* get registration package by package id */

function wpmlm_select_package_by_id($package_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_registration_packages";
    $sql = "SELECT * FROM {$table_name} WHERE id='" . $package_id . "'";
    $results = $wpdb->get_row($sql);
    return $results;
}

/* delete package by package id */

function wpmlm_delete_package_by_id($package_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_registration_packages";
    $results1 = wpmlm_select_package_by_id($package_id);
    $filepath = WP_MLM_PLUGIN_DIR . '/uploads/' . $results1->package_image;

    if ($results1->package_image != 'no-image.png') {
        unlink($filepath);
    }
    $sql = "DELETE FROM {$table_name} WHERE id='" . $package_id . "'";
    $results2 = $wpdb->query($sql);
    return $results2;
}

/* insert registration type paid or free or both */

function wpmlm_insert_reg_type($type) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_reg_type";
    $sql1 = "TRUNCATE TABLE {$table_name}";
    $wpdb->query($sql1);

    foreach ($type as $ty) {
        $sql2 = "INSERT INTO {$table_name}(reg_type) VALUES('" . $ty . "')";
        $wpdb->query($sql2);
    }
}

/* get registration type */

function wpmlm_select_reg_type() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_reg_type";
    $sql = "SELECT * FROM {$table_name}";
    $results = $wpdb->get_results($sql);
    return $results;
}

/* get registration type name */

function wpmlm_select_reg_type_name() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_reg_type";
    $sql = "SELECT GROUP_CONCAT(reg_type) as reg_type FROM {$table_name}";
    $results = $wpdb->get_row($sql);
    return $results;
}

/* get paypal details */

function wpmlm_get_paypal_details() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_paypal";
    $sql = "SELECT * FROM {$table_name} where id=1";
    $results = $wpdb->get_row($sql);
    return $results;
}

/* get all country list */

function wpmlm_getAllCountry() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_country";
    $sql = "SELECT * FROM {$table_name}";
    $results = $wpdb->get_results($sql);
    return $results;
}


/* get all currency list */

function wpmlm_getAllCurrency() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_currency";
    $sql = "SELECT * FROM {$table_name}";
    $results = $wpdb->get_results($sql);
    return $results;
}


/* get all children of a particular parent by parent id */

function wpmlm_display_children($parent_id, $level) {
    $count = 0;
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $sql = "SELECT user_ref_id FROM {$table_name} WHERE user_parent_id='" . $parent_id . "'";
    $results = $wpdb->get_results($sql);

    foreach ($results as $res) {
        $var = str_repeat(' ', $level) . $res->user_ref_id . "\n";
        $count += 1 + wpmlm_display_children($res->user_ref_id, $level + 1);
    }

    return $var;
}

/* get all user details */

function wpmlm_getUserDetails() {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "wpmlm_users";
    $table_name2 = $wpdb->prefix . "users";
    $sql = "SELECT a.user_id,a.user_ref_id,a.user_parent_id,a.user_first_name,a.user_email FROM {$table_name1} as a  INNER JOIN {$table_name2} b ON a.user_ref_id=b.ID ";
    
    $results = $wpdb->get_results($sql);
    return $results;
}

//changed
/* get all userdetails by parent id */
function wpmlm_getUserDetailsByParent($parent_id) {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "wpmlm_users";
    $table_name2 = $wpdb->prefix . "users";
    $sql = "SELECT a.user_id,a.user_ref_id,a.user_parent_id,a.user_first_name,a.user_email FROM {$table_name1} as a  INNER JOIN {$table_name2} b ON a.user_ref_id=b.ID AND a.user_parent_id='" . $parent_id . "'";
    $results = $wpdb->get_results($sql);
    return $results;
}

/* get user details by user id */

function wpmlm_get_user_details_by_id($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $sql = "SELECT * FROM {$table_name} WHERE user_ref_id = '" . $user_id . "'";
    $results = $wpdb->get_row($sql);
    return $results;
}

/* get user details by joining with wordpress users by user id */

function wpmlm_get_user_details_by_id_join($user_id) {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "users";
    $table_name2 = $wpdb->prefix . "wpmlm_users";
    $table_name3 = $wpdb->prefix . "wpmlm_user_balance_amount";

    $sql = "SELECT a.*,b.*,c.* FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.ID=b.user_ref_id AND b.user_ref_id = '" . $user_id . "' INNER JOIN {$table_name3} c ON c.user_id=a.ID";
    $results = $wpdb->get_results($sql);
    return $results;
}

function wpmlm_get_user_details_by_father_id_join($user_id) {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "users";
    $table_name2 = $wpdb->prefix . "wpmlm_users";
    $table_name3 = $wpdb->prefix . "wpmlm_user_balance_amount";

    $sql = "SELECT a.*,b.*,c.* FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.ID=b.father_id AND b.father_id = '" . $user_id . "' INNER JOIN {$table_name3} c ON c.user_id=a.ID";
    $results = $wpdb->get_results($sql);
    return $results;
}


/* get all user details between two dates */

function wpmlm_get_all_user_details_by_date_join($start_date, $end_date) {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "users";
    $table_name2 = $wpdb->prefix . "wpmlm_users";

    $sql = "SELECT a.*,b.* FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.ID=b.user_ref_id WHERE b.join_date BETWEEN '" . $start_date . "'  AND '" . $end_date . "'  ORDER BY b.join_date ";
    $results = $wpdb->get_results($sql);
    return $results;
}

/* get all user details by joing */

function wpmlm_get_all_user_details_join() {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "users";
    $table_name2 = $wpdb->prefix . "wpmlm_users";

    $sql = "SELECT a.*,b.* FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.ID=b.user_ref_id ";
    $results = $wpdb->get_results($sql);
    return $results;
}

/* get recently joined users details under admin */

function wpmlm_get_recently_joined_users_under_admin($user_id, $num) {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "users";
    $table_name2 = $wpdb->prefix . "wpmlm_users";

    $sql = "SELECT a.*,b.* FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.ID=b.user_ref_id WHERE a.`ID`!= $user_id  ORDER BY a.ID DESC LIMIT 0,$num";
    $results = $wpdb->get_results($sql);
    return $results;
}

/* get recently joined users details */

function wpmlm_get_recently_joined_users($num) {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "users";
    $table_name2 = $wpdb->prefix . "wpmlm_users";

    $sql = "SELECT a.*,b.* FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.ID=b.user_ref_id   ORDER BY a.ID DESC LIMIT 0,$num";
    $results = $wpdb->get_results($sql);
    return $results;
}

/* get recently joined users details of a particular parent */

function wpmlm_get_recently_joined_users_by_parent($user_id, $num) {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "users";
    $table_name2 = $wpdb->prefix . "wpmlm_users";

    $sql = "SELECT a.*,b.* FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.ID=b.user_ref_id  where b.`user_parent_id`='$user_id' ORDER BY a.ID DESC LIMIT 0,$num";
    $results = $wpdb->get_results($sql);
    return $results;
}

/* username serch */

function wpmlm_get_all_user_like($keyword) {
    global $wpdb;
    $table_name = $wpdb->prefix . "users";
    $sql = "SELECT user_login FROM {$table_name} WHERE user_login LIKE '{$keyword}%' ";
    $results = $wpdb->get_results($sql);
    return $results;
}

/* username serch except the current user */

function wpmlm_get_all_user_like_except_current($keyword, $username) {
    global $wpdb;
    $table_name = $wpdb->prefix . "users";
    $sql = "SELECT user_login FROM {$table_name} WHERE user_login LIKE '{$keyword}%' AND user_login !='$username' ";
    $results = $wpdb->get_results($sql);
    return $results;
}

/* get all username */

function wpmlm_get_all_user_login() {
    global $wpdb;
    $table_name = $wpdb->prefix . "users";
    $sql = "SELECT user_login FROM {$table_name} order by user_login ASC ";
    $results = $wpdb->get_results($sql);
    return $results;
}

/* tree function */

function wpmlm_buildTree(array $elements, $parentId) {
    static $counter = 0;
    ++$counter;
    $tree = array();
    foreach ($elements as $element) {
        if ($element->user_ref_id == $parentId) {
            if ($counter == 1) {
                $tree[] = wpmlm_get_user_details_by_id_join($parentId);
            }
        }
        if ($element->user_parent_id == $parentId) {
            $children = wpmlm_buildTree($elements, $element->user_ref_id);
            if ($children) {
                $tree[] = $children;
            }

            $tree[] = $element;
        }
    }


    return $tree;
}


function wpmlm_buildTree_matrix(array $elements, $parentId) {
    static $counter = 0;
    ++$counter;
    $tree = array();
    foreach ($elements as $element) {
        if ($element->user_ref_id == $parentId) {
            if ($counter == 1) {
                $tree[] = wpmlm_get_user_details_by_id_join($parentId);
            }
        }
        if ($element->father_id == $parentId) {
            $children = wpmlm_buildTree_matrix($elements, $element->user_ref_id);
            if ($children) {
                $tree[] = $children;
            }

            $tree[] = $element;
        }
    }


    return $tree;
}


function wpmlm_treeData($id) {
    $res = array();
    $res[] = wpmlm_get_user_details_by_id($id);
    $res[]['children'] = wpmlm_getUserDetailsByParent($id);
    return $res;
}

function wpmlm_makeNested($source) {
    $nested = array();

    foreach ($source as &$s) {
        if (is_null($s['parent_id'])) {
            // no parent_id so we put it in the root of the array
            $nested[] = &$s;
        } else {
            $pid = $s['parent_id'];
            if (isset($source[$pid])) {

                if (!isset($source[$pid]['children'])) {
                    $source[$pid]['children'] = array();
                }

                $source[$pid]['children'][] = &$s;
            }
        }
    }
    return $nested;
}

function wpmlm_makeNested_matrix($source_matrix) {
    $nested_matrix = array();

    foreach ($source_matrix as &$s) {
        if (is_null($s['parent_id'])) {
            // no parent_id so we put it in the root of the array
            $nested_matrix[] = &$s;
        } else {
            $pid = $s['parent_id'];
            if (isset($source_matrix[$pid])) {

                if (!isset($source_matrix[$pid]['children'])) {
                    $source_matrix[$pid]['children'] = array();
                }

                $source_matrix[$pid]['children'][] = &$s;
            }
        }
    }
    return $nested_matrix;
}

function wpmlm_get_leg_amount_details_all() {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "wpmlm_leg_amount";
    $table_name2 = $wpdb->prefix . "wpmlm_users";
    $table_name3 = $wpdb->prefix . "users";

    $sql = "SELECT a.user_id, ROUND(SUM(a.total_amount),2) as total_amount, b.user_first_name,b.user_second_name,a.amount_type,a.date_of_submission,c.user_login FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.user_id=b.user_ref_id INNER JOIN {$table_name3} c ON a.user_id = c.ID GROUP BY a.user_id ";
    $results = $wpdb->get_results($sql);
    return $results;
}

function wpmlm_get_leg_amount_details($start_date, $end_date) {
    global $wpdb;    
    $table_name1 = $wpdb->prefix . "wpmlm_leg_amount";
    $table_name2 = $wpdb->prefix . "wpmlm_users";
    $table_name3 = $wpdb->prefix . "users";

    $sql = "SELECT a.user_id, ROUND(SUM(a.total_amount),2) as total_amount, b.user_first_name,b.user_second_name,a.amount_type,a.date_of_submission,c.user_login FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.user_id=b.user_ref_id INNER JOIN {$table_name3} c ON a.user_id = c.ID WHERE a.date_of_submission BETWEEN '" . $start_date . "'  AND '" . $end_date . "'  GROUP BY a.user_id ";
    $results = $wpdb->get_results($sql);
    return $results;
}

function wpmlm_get_leg_amount_details_by_user_id($user_id) {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "users";
    $table_name2 = $wpdb->prefix . "wpmlm_leg_amount";

    $sql = "SELECT a.user_login, b.total_amount, b.amount_type,b.date_of_submission FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.ID=b.from_id WHERE b.user_id='" . $user_id . "' order by b.date_of_submission";
    $results = $wpdb->get_results($sql);
    return $results;
}

function wpmlm_get_user_details_by_parent_id_join($parent_id) {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "users";
    $table_name2 = $wpdb->prefix . "wpmlm_users";

    $sql = "SELECT a.*,b.* FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.ID=b.user_ref_id WHERE b.user_parent_id = '" . $parent_id . "' order by b.join_date";
    $results = $wpdb->get_results($sql);
    return $results;
}

function wpmlm_get_total_leg_amount_by_user_id($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_leg_amount";
    $sql = "SELECT ROUND(SUM(total_amount),2) as total_amount FROM {$table_name} WHERE user_id='" . $user_id . "' ";
    $results = $wpdb->get_row($sql);
    return $results;
}

function wpmlm_get_total_leg_amount_by_user_id_today($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_leg_amount";
    $date = date('Y-m-d');
    $sql = "SELECT ROUND(SUM(total_amount),2) as total_amount FROM {$table_name} WHERE user_id='" . $user_id . "' AND DATE(`date_of_submission`) = '$date' ";
    $results = $wpdb->get_row($sql);
    return $results;
}

function wpmlm_get_total_leg_amount_all() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_leg_amount";
    $sql = "SELECT ROUND(SUM(total_amount),2) as total_amount FROM {$table_name}";
    $results = $wpdb->get_row($sql);
    return $results;
}

function wpmlm_get_total_leg_amount_all_by_user() {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "wpmlm_leg_amount";
    $table_name2 = $wpdb->prefix . "wpmlm_users";

    $sql = "SELECT ROUND(SUM(amt.`total_amount`),2) as total_amount ,u.`user_first_name` FROM {$table_name1} amt left join {$table_name2}  u ON amt.`user_id`=u.`user_id` group by amt.`user_id` LIMIT 0,5";
    $results = $wpdb->get_results($sql);
    return $results;
}

function wpmlm_get_total_leg_amount_all_users_under_admin($user_id) {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "wpmlm_leg_amount";
    $table_name2 = $wpdb->prefix . "wpmlm_users";

    $sql = "SELECT ROUND(SUM(amt.`total_amount`),2) as total_amount ,u.`user_first_name` FROM {$table_name1} amt left join {$table_name2}  u ON amt.`user_id`=u.`user_id` WHERE u.`user_parent_id`!='0' group by amt.`user_id` LIMIT 0,5";
    $results = $wpdb->get_results($sql);
    return $results;
}

function wpmlm_get_total_leg_amount_all_users_under_parent($user_id) {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "wpmlm_leg_amount";
    $table_name2 = $wpdb->prefix . "wpmlm_users";

    $sql = "SELECT ROUND(SUM(amt.`total_amount`),2) as total_amount ,u.`user_first_name` ,u.`user_second_name` ,u.`user_email` FROM {$table_name1} amt left join {$table_name2}  u ON amt.`user_id`=u.`user_ref_id` where u.`user_parent_id`='$user_id' group by amt.`user_id` order by  total_amount LIMIT 0,3";
    $results = $wpdb->get_results($sql);
    return $results;
}


function wpmlm_get_total_leg_amount_all_users() {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "wpmlm_leg_amount";
    $table_name2 = $wpdb->prefix . "wpmlm_users";

    $sql = "SELECT ROUND(SUM(amt.`total_amount`),2) as total_amount ,u.`user_first_name` ,u.`user_second_name` FROM {$table_name1} amt left join {$table_name2}  u ON amt.`user_id`=u.`user_ref_id`group by amt.`user_id` order by  total_amount DESC LIMIT 0,3  ";
    $results = $wpdb->get_results($sql);
    return $results;
}



function wpmlm_get_total_leg_amount_all_by_today() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_leg_amount";
    $date = date('Y-m-d');
    $sql = "SELECT ROUND(SUM(total_amount),2) as total_amount FROM {$table_name} where DATE(`date_of_submission`)= '$date' ";
    $results = $wpdb->get_row($sql);
    return $results;
}

function wpmlm_get_total_leg_amount($start_date, $end_date) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_leg_amount";
    $sql = "SELECT ROUND(SUM(total_amount),2) as total_amount FROM {$table_name} WHERE date_of_submission BETWEEN '" . $start_date . "'  AND '" . $end_date . "'  ";
    $results = $wpdb->get_row($sql);
    return $results;
}

function wpmlm_get_general_information() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_general_information";
    $sql = "SELECT * FROM {$table_name} WHERE id = 1 ";
    $results = $wpdb->get_row($sql);
    return $results;
}

//Ewallet Functions

function wpmlm_getRandTransPasscode($length) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_tran_password";

    $key = '';
    $charset = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    for ($i = 0; $i < $length; $i++)
        $key .= $charset[(mt_rand(0, (strlen($charset) - 1)))];
    $randum_id = wp_hash_password($key);
    //$randum_id = $key;

    $sql = "SELECT * FROM {$table_name} WHERE tran_password = '" . $randum_id . "'  ";
    $wpdb->get_row($sql);
    $count = $wpdb->num_rows;
    if (!$count)
        return $key;
    else
        wpmlm_getRandTransPasscode($length);
}

function wpmlm_insert_tran_password($tran_pass_details) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_tran_password";
    $wpdb->insert($table_name, $tran_pass_details);
}

function wpmlm_update_tran_password($tran_pass, $user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_tran_password";
    $sql = "UPDATE {$table_name} SET  `tran_password`= '" . $tran_pass . "' where `user_id`= '" . $user_id . "'  ";
    $result = $wpdb->query($sql);
    return $result;
}

function wpmlm_getUserPasscode($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_tran_password";
    $sql = "SELECT tran_password FROM {$table_name} WHERE user_id = '" . $user_id . "'  ";
    $result = $wpdb->get_row($sql);
    return $result;
}

function wpmlm_getUniqueTransactionId() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_transaction_id";

    $date = date('Y-m-d H:i:s');
    $code = wpmlm_getRandStr(9, 9);
    $data = array(
        'transaction_id' => $code,
        'added_date' => $date
    );

    $wpdb->insert($table_name, $data);
    return $code;
}

function wpmlm_getRandStr() {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_transaction_id";

    $key = "";
    $charset = "0123456789";
    $length = 10;
    for ($i = 0; $i < $length; $i++)
        $key .= $charset[(mt_rand(0, (strlen($charset) - 1)))];

    $randum_number = $key;

    $sql = "SELECT * FROM {$table_name} WHERE transaction_id = '" . $randum_number . "'  ";
    $wpdb->get_row($sql);
    $count = $wpdb->num_rows;
    if (!$count)
        return $key;
    else
        wpmlm_getRandStr();
}

function wpmlm_getRandStrPassword() {

    $key = "";
    $charset = "0123456789";
    $length = 8;
    for ($i = 0; $i < $length; $i++)
        $key .= $charset[(mt_rand(0, (strlen($charset) - 1)))];
    return $key;
}

function wpmlm_insert_fund_transfer_details($fund_details) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_fund_transfer_details";
    $result = $wpdb->insert($table_name, $fund_details);
    return $result;
}

function wpmlm_insertBalanceAmount($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_user_balance_amount";
    $data = array(
        'balance_amount' => 0,
        'user_id' => $user_id
    );
    $wpdb->insert($table_name, $data);
}

function wpmlm_getEwalletHistory($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_ewallet_history";
    $sql = "SELECT * FROM {$table_name} WHERE user_id = '" . $user_id . "'  ";
    $result = $wpdb->get_results($sql);
    return $result;
}

function wpmlm_addEwalletHistory($ewallet_details) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_ewallet_history";
    $result = $wpdb->insert($table_name, $ewallet_details);
    return $result;
}

function wpmlm_updateBalanceAmountDetailsFrom($from_user_id, $fund_amount) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_user_balance_amount";
    $sql = "UPDATE {$table_name} SET  `balance_amount`= balance_amount - {$fund_amount} where `user_id`= '" . $from_user_id . "'  ";
    $wpdb->query($sql);
}

function wpmlm_updateBalanceAmountDetailsTo($to_user_id, $fund_amount) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_user_balance_amount";
    $sql = "UPDATE {$table_name} SET  `balance_amount`= balance_amount + {$fund_amount} where `user_id`= '" . $to_user_id . "'  ";
    $wpdb->query($sql);
}

function wpmlm_updateUserBv($to_user_id, $bv_amount) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_user_balance_amount";
    $sql = "UPDATE {$table_name} SET  `total_bv`= total_bv + {$bv_amount} where `user_id`= '" . $to_user_id . "'  ";
    $wpdb->query($sql);
}


function wpmlm_getBalanceAmount($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_user_balance_amount";
    $sql = "SELECT ROUND(`balance_amount`,2) as balance_amount  FROM {$table_name} WHERE user_id = '" . $user_id . "'  ";
    $result = $wpdb->get_row($sql);
    return $result;
}

function wpmlm_getBalanceAmountAll() {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "wpmlm_user_balance_amount";
    $table_name2 = $wpdb->prefix . "users";

    $sql = "SELECT ROUND(a.`balance_amount`,2) as balance_amount, b.display_name, b.user_login FROM {$table_name1} as a INNER JOIN {$table_name2} as b WHERE a.user_id = b.ID ";

    $result = $wpdb->get_results($sql);
    return $result;
}


function wpmlm_getBalanceBVAmount($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_user_balance_amount";
    $sql = "SELECT ROUND(`total_bv`,2) as total_bv  FROM {$table_name} WHERE user_id = '" . $user_id . "'  ";
    $result = $wpdb->get_row($sql);
    return $result;
}



function wpmlm_getTransferDetails($user_id, $start_date, $end_date) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_fund_transfer_details";
    $sql = "SELECT * FROM {$table_name} WHERE to_user_id = '" . $user_id . "' AND (date BETWEEN '" . $start_date . "'  AND '" . $end_date . "')  ORDER BY date ";
    $result = $wpdb->get_results($sql);
    return $result;
}

function wpmlm_sendMailTransactionPass($to_mail, $tran_pass) {
    $res = wpmlm_get_general_information();
    $subject = 'Your new transaction password';
    $message = 'Your new password is: ' . $tran_pass;
    $headers[] = 'MIME-Version: 1.0' . "\r\n";
    $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers[] = "X-Mailer: PHP \r\n";
    $headers[] = 'From: ' . $res->company_name . ' < ' . $res->company_email . '>' . "\r\n";
    $mail = wp_mail($to_mail, $subject, $message, $headers);
    return $mail;
}

function wpmlm_sendMailRegistrationKey($to_mail, $key) {
    $current_user = wp_get_current_user();
    $subject = 'WP MLM Activation Key';
    $message = 'WP MLM activation Key is: ' . $key;
    $headers[] = 'MIME-Version: 1.0' . "\r\n";
    $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers[] = "X-Mailer: PHP \r\n";
    $headers[] = 'From: ' . $current_user->user_login . ' < ' . $current_user->user_email . '>' . "\r\n";
    $mail = wp_mail($to_mail, $subject, $message, $headers);
    return $mail;
}

function wpmlm_sendMailRegistration($to_mail, $username, $password, $user_first_name, $user_second_name) {
    $res = wpmlm_get_general_information();
    $subject = "Registration Completed Succesfully!";
    $message = "
    <p></p><br>
    Hi " . $user_first_name . ' ' . $user_second_name . "<br>
   Thank you for registering with us.<br><br>
   Your Username is " . $username . "<br> and,
   your password : " . $password . "<br><br>";

    $headers[] = 'MIME-Version: 1.0' . "\r\n";
    $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers[] = "X-Mailer: PHP \r\n";
    $headers[] = 'From: ' . $res->company_name . ' < ' . $res->company_email . '>' . "\r\n";
    $mail = wp_mail($to_mail, $subject, $message, $headers);
    return $mail;
}



function wpmlm_getAllParents($user_id = NULL, $level_from) {
    if ($user_id == NULL) {
        return false;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $depth = wpmlm_get_level_depth();
    $sql = "SELECT * FROM {$table_name} WHERE user_ref_id = '" . $user_id . "' AND `user_level` >=$level_from ";
    $result = $wpdb->get_row($sql);

    $res[] = $result;
    if ($result->user_parent_id != 0) {

        $result = wpmlm_getAllParents($result->user_parent_id, $level_from);
        $res = array_merge($res, $result);
    }
    return $res;
}





function wpmlm_deleteUser($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "users";
    $sql = "DELETE FROM {$table_name} WHERE ID='" . $id . "'";
    $wpdb->query($sql);
}

function wpmlm_getJoiningDetailsByMonth($year, $user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $sql = "SELECT  MONTH(`join_date`) as month,count(`user_id`) as count FROM {$table_name} where YEAR(`join_date`)='$year' AND `user_id`!='$user_id' GROUP BY  MONTH(`join_date`)";

    $result = $wpdb->get_results($sql);
    return $result;
}

function wpmlm_getJoiningDetailsUsersByMonth($user_id, $year) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $sql = "SELECT  MONTH(`join_date`) as month,count(`user_id`) as count FROM {$table_name} where `user_parent_id`='$user_id' AND YEAR(`join_date`)='$year' GROUP BY  MONTH(`join_date`)";

    $result = $wpdb->get_results($sql);
    return $result;
}

function wpmlm_getJoiningByTodayCount($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $date = date('Y-m-d');
    $sql = "SELECT count(`user_id`) as count FROM {$table_name} where DATE(`join_date`) = '$date' AND `user_ref_id`!='$user_id' ";

    $result = $wpdb->get_row($sql);
    return $result;
}

function wpmlm_getJoiningByTodayCountByUser($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $date = date('Y-m-d');
    $sql = "SELECT count(`user_id`) as count FROM {$table_name} where DATE(`join_date`) = '$date' AND `user_parent_id`='$user_id' ";

    $result = $wpdb->get_row($sql);
    return $result;
}

function wpmlm_getEwalletAmount($type) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_ewallet_history";
    $sql = "SELECT ROUND(SUM(`amount`),2) as sum FROM {$table_name} where `type` = '$type' ";
    $result = $wpdb->get_row($sql);
    return $result;
}

function wpmlm_getEwalletTotalAmount($type) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_ewallet_history";
    $sql = "SELECT ROUND(SUM(`amount`),2) as sum FROM {$table_name} where `type` = '$type' AND `ewallet_type`!='payout_request'";
    $result = $wpdb->get_row($sql);
    return $result;
}




function wpmlm_getEwalletAmountByUser($type, $user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_ewallet_history";
    $sql = "SELECT ROUND(SUM(`amount`),2) as sum FROM {$table_name} where `type` = '$type' AND `user_id`='$user_id' AND `ewallet_type`!='payout_request'";
    $result = $wpdb->get_row($sql);
    return $result;
}


function wpmlm_getCurrentEwalletAmountByUser($user_id){
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_ewallet_history";
    $sql1 = "SELECT ROUND(SUM(`amount`),2) as credit_amount FROM {$table_name} WHERE `user_id` = '$user_id' AND `type`='credit' ";
    $results1 = $wpdb->get_row($sql1);



    $sql2 = "SELECT ROUND(SUM(`amount`),2) as debit_amount FROM {$table_name} WHERE `user_id` = '$user_id' AND `type`='debit' ";
    $results2 = $wpdb->get_row($sql2);
    $result = $results1->credit_amount - $results2->debit_amount;
    return $result;
}

function wpmlm_check_wpmlm_license() {

    $wpmlm_license_key = get_option('wpmlm_license_key');
    $status = wpmlm_check_license_status($wpmlm_license_key);
    if ($status['status'] == 'active') {
        return true;
    } else {
        return false;
    }
}

/**
 * function to check is registered
 * @param  [type] $username [description]
 * @return [type]           [description]
 */
function wpmlm_checkSponsorIsRegistered( $username ){

    $user_id   =   wpmlm_getUserIdFromUsername( $username );
    if ($user_id) {
        return wpmlm_checkUserMlmRegistered( $user_id, $username ); 
    }else{
        return false;
    }
}

/**
 * Get user)id from user_name
 * @param  $username 
 * @return $user_id
 */
function wpmlm_getUserIdFromUsername( $username )
{
    global              $wpdb;
    $table_name     =   $wpdb->prefix . "users";
    $result         =   false;
    $user_id        =   '';

    if ( $username ) {
        // Assign the query to a string so you can output it for testing
        $query = $wpdb->prepare( "SELECT ID FROM {$table_name} WHERE user_login = %s", $username );
        $results = $wpdb->get_results( $query );
        foreach ($results as $row) {
            $user_id =  $row->ID;
        }
        return $user_id;
    }
}

/**
 * Check user is registered and set session for it
 * @param  $user_id, $username
 * @return session
 */
function wpmlm_checkUserMlmRegistered( $user_id='', $username='' ){
    global              $wpdb;
    $table_name     =   $wpdb->prefix . "wpmlm_users";
    $result         =   false;
    
    if ($user_id && $username) {
        if (isset($_SESSION["sponsor"])) {
            unset($_SESSION["sponsor"]);
        }
        
        $sql        =   "SELECT count(`user_ref_id`) as count FROM {$table_name} where `user_ref_id`='$user_id' ";
        $result     =   $wpdb->get_row($sql);
    
        if (isset($result) && $result->count == 1  && $username) {
            $_SESSION['sponsor']  =  $username;
            return true;
        }else{
            //die("failed");
            return false;
        } 
    }else if ($user_id) {
        $sql        =   "SELECT count(`user_ref_id`) as count FROM {$table_name} where `user_ref_id`='$user_id' ";
        $result     =   $wpdb->get_row($sql);
        if (isset($result) && $result->count == 1) {
            return true;
        }else{
            return false;
        } 
    }else{
        return false;
    }
}

function wpmlm_updateUserDetails($user_id, $data, $column){
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $sql = "UPDATE {$table_name} SET  `$column`= '" . $data . "' where `user_ref_id`= '" . $user_id . "'  ";
    $result = $wpdb->query($sql);
    return $result;
}

function wpmlm_insert_payout_request($data){
   global $wpdb;    
    $table_name = $wpdb->prefix . "wpmlm_payout_release_requests";  
    $result = $wpdb->insert($table_name, $data);
    if ($result) {
        return true;
    } else {
        return false;
    }
}

/* get admin userdetails by parent id */
function wpmlm_getAdminDetailsByParent($parent_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users"; 
    $sql = "SELECT `user_ref_id` FROM {$table_name} where `user_parent_id`='" . $parent_id . "'";
    $result = $wpdb->get_row($sql);
    return $result;
}
function wpmlm_get_payout_release_requests_by_id($id){

    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_payout_release_requests";
    $sql = "SELECT * FROM {$table_name} WHERE `req_id` = '$id' ";
    $results = $wpdb->get_row($sql);
    return $results;

}


function wpmlm_get_payout_amount_by_id($user_id,$status){

    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_payout_release_requests";
    $sql = "SELECT ROUND(SUM(`requested_amount`),2) as total_amount FROM {$table_name} WHERE `requested_user_id` = '$user_id' AND `status`='$status' ";
    $results = $wpdb->get_row($sql);
    return $results;

}

function wpmlm_get_payout_amount($status){

    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_payout_release_requests";
    $sql = "SELECT ROUND(SUM(`requested_amount`),2) as sum FROM {$table_name} WHERE  `status`='$status' ";
    $results = $wpdb->get_row($sql);
    return $results;

}



function wpmlm_get_payout_release_requests($status){

    global $wpdb;
    $table_name1 = $wpdb->prefix . "wpmlm_users";
    $table_name2 = $wpdb->prefix . "wpmlm_payout_release_requests";
    $table_name3 = $wpdb->prefix . "users";

    $sql = "SELECT a.*,b.*,c.* FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.user_ref_id=b.requested_user_id INNER JOIN {$table_name3} c ON a.user_ref_id = c.ID WHERE b.`status` = '$status' ";
    $results = $wpdb->get_results($sql);
    return $results;
}
function wpmlm_get_payout_release_requests_all(){

    global $wpdb;
    $table_name1 = $wpdb->prefix . "wpmlm_users";
    $table_name2 = $wpdb->prefix . "wpmlm_payout_release_requests";
    $table_name3 = $wpdb->prefix . "users";

    $sql = "SELECT a.*,b.*,c.* FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.user_ref_id=b.requested_user_id INNER JOIN {$table_name3} c ON a.user_ref_id = c.ID ";
    $results = $wpdb->get_results($sql);
    return $results;
}

function wpmlm_update_payout_request_status($req_id,$status){
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_payout_release_requests";
    $date = date('Y-m-d H:i:s');
    $sql = "UPDATE {$table_name} SET  `status`= '$status', `updated_date`= '$date' where `req_id`= '" . $req_id . "'  ";
    $result = $wpdb->query($sql);
    return $result;
}



function wpmlm_get_payout_release_requests_by_date($start_date, $end_date,$status){

    global $wpdb;
    $table_name1 = $wpdb->prefix . "wpmlm_users";
    $table_name2 = $wpdb->prefix . "wpmlm_payout_release_requests";
    $table_name3 = $wpdb->prefix . "users";

    $sql = "SELECT a.*,b.*,c.* FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.user_ref_id=b.requested_user_id INNER JOIN {$table_name3} c ON a.user_ref_id = c.ID WHERE b.`status` = '$status' AND b.updated_date BETWEEN '" . $start_date . "'  AND '" . $end_date . "'  ORDER BY b.updated_date ";
    $results = $wpdb->get_results($sql);
    return $results;
}

function wpmlm_get_payout_release_requests_all_by_date($start_date, $end_date){

    global $wpdb;
    $table_name1 = $wpdb->prefix . "wpmlm_users";
    $table_name2 = $wpdb->prefix . "wpmlm_payout_release_requests";
    $table_name3 = $wpdb->prefix . "users";

    $sql = "SELECT a.*,b.*,c.* FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.user_ref_id=b.requested_user_id INNER JOIN {$table_name3} c ON a.user_ref_id = c.ID WHERE b.updated_date BETWEEN '" . $start_date . "'  AND '" . $end_date . "'  ORDER BY b.updated_date ";
    $results = $wpdb->get_results($sql);
    return $results;
}


function wpmlm_get_all_user_details_join_tree() {
    global $wpdb;
    $table_name1 = $wpdb->prefix . "users";
    $table_name2 = $wpdb->prefix . "wpmlm_users";
    $table_name3 = $wpdb->prefix . "wpmlm_user_balance_amount";

    $sql = "SELECT a.*,b.*,c.* FROM {$table_name1} a INNER JOIN {$table_name2} b ON a.ID=b.user_ref_id INNER JOIN {$table_name3} c ON c.user_id=a.ID";
    $results = $wpdb->get_results($sql);
    return $results;
}


function wpmlm_insert_paid_amount($paid_details){

    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_amount_paid";
    $result = $wpdb->insert($table_name, $paid_details);
    return $result;
    
}

function wpmlm_get_available_payout_income($user_id){
    

    // $last_month = date('Y-m-d', strtotime("last day of -1 month"));
    // $this_month = date('Y-m-d', strtotime("last day of 0 month"));
    // $today = date('Y-m-d');
    // $last_month = $last_month . " 23:59:59";

    // if($today == $this_month){
    //     $last_month = $today . " 23:59:59";
    // }



    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_ewallet_history";
    // $sql1 = "SELECT SUM(`amount`) as credit_amount FROM {$table_name} WHERE `user_id` = '$user_id' AND `type`='credit' AND DATE(`date_added`) <= '$last_month' ";
    // $results1 = $wpdb->get_row($sql1);

    $sql1 = "SELECT SUM(`amount`) as credit_amount FROM {$table_name} WHERE `user_id` = '$user_id' AND `type`='credit' ";
    $results1 = $wpdb->get_row($sql1);


    //$sql2 = "SELECT SUM(`amount`) as credit_payout FROM {$table_name} WHERE `user_id` = '$user_id' AND `type`='credit' AND `ewallet_type`='payout_request' ";


    //$results2 = $wpdb->get_row($sql2);    


    $sql3 = "SELECT SUM(`amount`) as debit_amount FROM {$table_name} WHERE `user_id` = '$user_id' AND `type`='debit' ";
    $results3 = $wpdb->get_row($sql3);

    //$result = $results1->credit_amount + $results2->credit_payout - $results3->debit_amount;

    $result = $results1->credit_amount - $results3->debit_amount;
    return $result;



}

function wpmlm_get_all_user_id_by_parent_id($user_parent_id){
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $sql = "SELECT GROUP_CONCAT(user_ref_id) as level_ids FROM {$table_name} where `user_parent_id`= '$user_parent_id' ";
    $result = $wpdb->get_row($sql);
    return $result;
}

function wpmlm_get_total_bv_childs($user_ids){
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_user_balance_amount";
    $sql = "SELECT SUM(total_bv) as total_child_bv from {$table_name} where `user_id` IN($user_ids) ";
    $result = $wpdb->get_row($sql);
    return $result;
}

// function myfunction(){
//     wp_die("order is completed");
// }

// add_action('woocommerce_order_status_completed','myfunction');

function wpmlm_insert_leg_amount($user_id, $package_id, $order_id, $from='') {
    // wp_die($user_id);
    global $wpdb;
    $table_name     =   $wpdb->prefix . "wpmlm_leg_amount";
    $user_details   =   wpmlm_get_user_details($user_id);
    $parent_level   =   $user_details->user_level - 1;
    $depth          =   wpmlm_get_level_depth();
    $level_from     =   $parent_level - $depth + 1;
    $general        =   wpmlm_get_general_information();
    $reg_amt        =   $general->registration_amt;  
    $user_level     =   $user_details->user_level;  


    if ($from == "woocommerce") {
        if ($package_id !== NULL) {

            $order = new WC_Order( $order_id );
            $items = $order->get_items(); 
            $package_amount=0;
            foreach ( $items as $item ) { 
                $product = wc_get_product( $item['product_id'] );
                $quantity = $item['qty'];
                $product_id = $product->get_id();

                $pv_value   =   get_post_meta( $product_id, '_product_pv', true );
                
                if ($pv_value != '' && $pv_value != 0) {
                    $package_amount_temp =   $quantity*$pv_value; 

                }else{

                    if ($product->get_type() == 'variable') {
                                
                        $variation_id = $item->get_variation_id();
                        $product = wc_get_product( $variation_id );
                    }

                    if($product->get_sale_price()){
                        $package_amount_temp =   $quantity*$product->get_sale_price();
                    }else{
                        $package_amount_temp =   $quantity*$product->get_regular_price();
                    }                                     
                    
                }

                $package_amount+=$package_amount_temp;
 
            }
            
        }else{
            $package_amount     =   $reg_amt;
        }
    }else if ($from == "default") {
        if ($package_id !== NULL) {
            $package_details    =   wpmlm_select_package_by_id($package_id);
            $package_amount     =   $package_details->package_price;
            $package_id         =   $package_details->id;
        } else {
            $package_amount     =   $reg_amt;
        }
    }else{
        $package_amount     =   $reg_amt;
    }
    
    $result1    =   wpmlm_get_commission_level_type();
    $result     =   wpmlm_getAllParents($user_details->user_parent_id, $level_from);
    $i  =   0;

    $conf_res = wpmlm_get_commission_details();
    $level_eligibility = $conf_res->level_eligibility;
    $referral_commission = $conf_res->referral_commission;

    // wp_die(print_r($result));
    foreach ($result as $res) {
        $i++;
        $level_percentage   =   wpmlm_get_level_percentage($i);
        $flat               =   $level_percentage;
        $percentage         =   $package_amount * ($level_percentage / 100);
        $depth              =   $depth - 1;
        $commission_amount  =   ($result1->level_commission_type == 'percentage') ? $percentage : $flat;


        $total_bv_arr = wpmlm_getBalanceBVAmount($res->user_ref_id);
        // wp_die($total_bv_arr);
        $total_bv = $total_bv_arr->total_bv;

        


        if($res->user_ref_id==1){
            $total_bv=$level_eligibility;
        }
        //  wp_die($total_bv.">".$level_eligibility);
        if($total_bv >= $level_eligibility){
           

            $data   =   array(
                'user_id'               =>  $res->user_ref_id,
                'from_id'               =>  $user_id,
                'amount_type'           =>  'level_bonus',
                'total_amount'          =>  $commission_amount,
                'product_id'            =>  $package_id,
                'product_value'         =>  $package_amount,
                'user_level'            =>  $i,
                'date_of_submission'    =>  date("Y-m-d H:i:s")
            );

            $wpdb->insert($table_name, $data);
            $ewallet_id     =   $wpdb->insert_id;


            $user_status = wpmlm_get_user_status($user_id);
            if($user_status==2){
                $commission_type='repurchase commission';
            }else{
                $commission_type='commission';
            }

            $ewallet_details    =   array(
                'from_id'           =>  $user_id,
                'user_id'           =>  $res->user_ref_id,
                'ewallet_id'        =>  $ewallet_id,
                'ewallet_type'      =>  $commission_type,
                'amount'            =>  $commission_amount,
                'amount_type'       =>  'level_bonus',
                'type'              =>  'credit',
                'date_added'        =>  date("Y-m-d H:i:s")
            );

            wpmlm_addEwalletHistory($ewallet_details);
            wpmlm_updateBalanceAmountDetailsTo($res->user_ref_id, $commission_amount); 

            

            if(($i==1) && ($user_status==1) && ($referral_commission!=0)){

                $commission_amount_1 =   $package_amount * ($referral_commission / 100);
                $data   =   array(
                'user_id'               =>  $res->user_ref_id,
                'from_id'               =>  $user_id,
                'amount_type'           =>  'referral_bonus',
                'total_amount'          =>  $commission_amount_1,
                'product_id'            =>  $package_id,
                'product_value'         =>  $package_amount,
                'user_level'            =>  $i,
                'date_of_submission'    =>  date("Y-m-d H:i:s")
            );

            $wpdb->insert($table_name, $data);
            $ewallet_id     =   $wpdb->insert_id;

            $ewallet_details    =   array(
                'from_id'           =>  $user_id,
                'user_id'           =>  $res->user_ref_id,
                'ewallet_id'        =>  $ewallet_id,
                'ewallet_type'      =>  'commission',
                'amount'            =>  $commission_amount_1,
                'amount_type'       =>  'referral_bonus',
                'type'              =>  'credit',
                'date_added'        =>  date("Y-m-d H:i:s")
            );

            wpmlm_addEwalletHistory($ewallet_details);
            wpmlm_updateBalanceAmountDetailsTo($res->user_ref_id, $commission_amount_1); 


            }


        }
    


    }

    // Start Self Commission
    // ---------------------
    wpmlm_updateUserBv($user_id, $package_amount);
    $self_commission = $conf_res->self_commission;
    if($self_commission!=0){
        $commission_amount =   $package_amount * ($self_commission / 100);

        $data   =   array(
                'user_id'               =>  $user_id,
                'from_id'               =>  $user_id,
                'amount_type'           =>  'self_bonus',
                'total_amount'          =>  $commission_amount,
                'product_id'            =>  $package_id,
                'product_value'         =>  $package_amount,
                'user_level'            =>  $parent_level+2,
                'date_of_submission'    =>  date("Y-m-d H:i:s")
            );

        $wpdb->insert($table_name, $data);
        $ewallet_id     =   $wpdb->insert_id;

        $ewallet_details    =   array(
            'from_id'           =>  $user_id,
            'user_id'           =>  $user_id,
            'ewallet_id'        =>  $ewallet_id,
            'ewallet_type'      =>  'commission',
            'amount'            =>  $commission_amount,
            'amount_type'       =>  'self_bonus',
            'type'              =>  'credit',
            'date_added'        =>  date("Y-m-d H:i:s")
        );

        wpmlm_addEwalletHistory($ewallet_details);
        wpmlm_updateBalanceAmountDetailsTo($user_id, $commission_amount);
        
    }
        // End Self Commission
        // -------------------
        

        wpmlm_update_user_status($user_id,'2');

}


function wpmlm_getAllChildren($user_id) {
    if ($user_id == NULL) {
        return false;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";

    $sql = "SELECT user_ref_id,user_first_name,user_level FROM {$table_name} WHERE user_parent_id = '" . $user_id . "' ";
    $result = $wpdb->get_results($sql);

    $res[] = $result;
    foreach($result as $res1){

        $result = wpmlm_getAllChildren($res1->user_ref_id);
        $res = array_merge($res, $result);
    }


    foreach ($res as $key => $data) {
        if (is_array($data)) {            
            $array = flattenArray($res);            
        }
    }    
    return $array;
}


function wpmlm_flattenArray($arr) {
        for ($i = 0; $i < count($arr); $i++) {
            if (is_array($arr[$i])) {
                array_splice($arr, $i, 1, $arr[$i]);
            }
        }
        return $arr;
}

/* get userlevel by parent id */

function wpmlm_get_user_ref_by_id($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $sql = "SELECT user_ref_id FROM {$table_name} WHERE user_id = '" . $user_id . "'";
    $user_ref_id = $wpdb->get_var($sql);
    return $user_ref_id;
}


function wpmlm_getLevelFirstChildrenId($user_id) {
    if ($user_id == NULL) {
        return false;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";

    $sql = "SELECT user_ref_id FROM {$table_name} WHERE user_parent_id = '" . $user_id . "' ";
    $result = $wpdb->get_results($sql); 
    $out = array();
    foreach($result as $ar){
        
            array_push($out, $ar->user_ref_id);
    }
    $user_ids = implode(', ', $out);
    return $user_ids;
}


function wpmlm_getPlacementMatrix($sponsor_id){

    $user["0"] = $sponsor_id;
    $sponser_arr = wpmlm_checkPosition($user);
    return $sponser_arr;
}

function wpmlm_checkPosition($downlineuser) {

    $p = 0;
    $child_arr = array();

    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";

    for ($i = 0; $i < count($downlineuser); $i++) {
        $sponsor_id = $downlineuser["$i"];

        $sql = "SELECT user_ref_id,user_id,position FROM {$table_name} WHERE father_id = '" . $sponsor_id . "' ";
        $result = $wpdb->get_results($sql);
        $row_count = $wpdb->num_rows;

        if ($row_count > 0) {
         
            foreach ($result as $row) {           

                $res_width        = wpmlm_get_width_ceiling();
                $width_ceiling = $res_width->width_ceiling;
                if ($row_count < $width_ceiling) {
                    $sponsor['id'] = $sponsor_id;
                    $sponsor['position'] = $row_count + 1;
                    return $sponsor;
                } else {                       

                    $child_arr[$p]= $row->user_ref_id;
                    $p++;
                }
            }

            
        } else {
        
            $sponsor['id'] = $sponsor_id;
            $sponsor['position'] = 1;
            return $sponsor;
        }
    }

    if (count($child_arr) > 0) {
        $position = wpmlm_checkPosition($child_arr);            
        return $position;
    }
}


function wpmlm_update_user_status($user_id,$status){
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_users";
    $sql = "UPDATE {$table_name} SET  `user_status`= '" . $status . "' where `user_ref_id`= '" . $user_id . "'  ";
    $result = $wpdb->query($sql);
    return $result;
}

function wpmlm_get_user_status($user_id){

global $wpdb;
$table_name = $wpdb->prefix . "wpmlm_users";
$sql = "SELECT user_status FROM {$table_name} WHERE user_ref_id = '" . $user_id . "'";
$user_status = $wpdb->get_var($sql);
return $user_status;

}

function wpmlm_update_currency_symbol($symbol) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_general_information";
    $sql = "UPDATE {$table_name} SET  `company_currency`= '" . $symbol . "' where `id`= 1  ";
    $wpdb->query($sql);
}

function wpmlm_update_woo_currency($curr_symbol,$currency_code) {

    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_general_information";
    $sql = "UPDATE {$table_name} SET  `company_currency`= '" . $curr_symbol . "' , `currency_code`= '" . $currency_code . "' where `id`= 1  ";
    $wpdb->query($sql);

}

function wpmlm_insert_products_purchased_amount($user_id, $package_id, $order_id, $from='') {

    global $wpdb;
    $table_name     =   $wpdb->prefix . "wpmlm_products_purchased_amount";
    $user_details   =   wpmlm_get_user_details($user_id);
    $general        =   wpmlm_get_general_information();
    $reg_amt        =   $general->registration_amt;  

    if ($package_id !== NULL) {

        $order = new WC_Order( $order_id );
        $items = $order->get_items(); 
        $package_amount=0;
        foreach ( $items as $item ) { 
            $product = wc_get_product( $item['product_id'] );
            $quantity = $item['qty'];
            $product_id = $product->get_id();


            if ($product->get_type() == 'variable') {
                                
                $variation_id = $item->get_variation_id();
                $product = wc_get_product( $variation_id );
            }

            if($product->get_sale_price()){
                $package_amount_temp =   $quantity*$product->get_sale_price();
            }else{
                $package_amount_temp =   $quantity*$product->get_regular_price();
            } 

            $package_amount+=$package_amount_temp;

        }
        
    }else{
        $package_amount     =   $reg_amt;
    }

    if ($package_id !== NULL) {

        $data   =   array(
            'user_id'         =>  $user_id,
            'order_id'        =>  $order_id,
            'product_id'      =>  $package_id,
            'product_value'   =>  $package_amount,
            'purchase_date'   =>  date("Y-m-d H:i:s")
        );

        $wpdb->insert($table_name, $data);
    }       

}

function wpmlm_get_company_total_income() {

    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_products_purchased_amount";

    $sql = "SELECT  SUM(`product_value`) as amount, MONTH(`purchase_date`) as month FROM {$table_name} GROUP BY MONTH(`purchase_date`), YEAR(`purchase_date`)";
    $result = $wpdb->get_results($sql);
    return $result;

}

function wpmlm_get_company_total_commission() {

    global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_ewallet_history";

    $sql = "SELECT  SUM(`amount`) as amount, MONTH(`date_added`) as month FROM {$table_name} WHERE `ewallet_type` = 'commission' GROUP BY MONTH(`date_added`), YEAR(`date_added`)";
    $result = $wpdb->get_results($sql);
    return $result;

}

function get_all_ewallet_transactions(){
    global $wpdb;
    $sql="SELECT a.user_login,b.ewallet_type,b.type,b.amount_type,b.amount,b.date_added FROM `{$wpdb->prefix}users` a INNER JOIN `{$wpdb->prefix}wpmlm_ewallet_history` b ON a.ID=b.user_id ORDER BY b.date_added DESC";
    $result=$wpdb->get_results($sql);
    return $result;
    
}
function get_all_ewallet_transactions_by_date($start_date,$end_date){
    global $wpdb;
  $sql="SELECT a.user_login,b.ewallet_type,b.type,b.amount_type,b.amount,b.date_added FROM `{$wpdb->prefix}users` a INNER JOIN `{$wpdb->prefix}wpmlm_ewallet_history` b ON a.ID=b.user_id WHERE b.date_added BETWEEN '".$start_date."' AND '".$end_date."' ORDER BY b.date_added ASC";
  $result=$wpdb->get_results($sql);
  return $result;
}

function wpmlm_insert_unpaid_users($user_details){
     global $wpdb;
    $table_name = $wpdb->prefix . "wpmlm_unpaid_users";
    $result = $wpdb->insert($table_name, $user_details);


    if ($result) {
        return true;
    } else {
        return false;
    }
}

function wpmlm_get_unpaid_user($user_id){
    global $wpdb;
    $sql="SELECT * FROM {$wpdb->prefix}wpmlm_unpaid_users WHERE `user_ref_id`=$user_id";
    $result=$wpdb->get_row($sql);
    return $result;
       
    
}
function wpmlm_delete_unpaid_user($user_id){
    global $wpdb;
    $sql="DELETE FROM {$wpdb->prefix}wpmlm_unpaid_users WHERE `user_ref_id`=$user_id";
    $wpdb->query($sql);
}
