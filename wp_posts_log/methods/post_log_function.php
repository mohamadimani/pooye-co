<?php

//    show post meta log  manage page in admin panel menu
function post_log_panel_page()
{
    global $wpdb, $table_prefix;
    $wp_post_meta = $table_prefix . 'edited_postmeta_log';
    $wp_users = $table_prefix . 'users';
    $wp_posts = $table_prefix . 'posts';
    $date = date('Y-m-d', time() - 86400);
    $two_last_edited_posts = '';
    $where = '';
    $edited_posts = '';
    if (!isset($_GET['log_id']) or empty($_GET['log_id'])) {

        if (isset($_POST["from_date"]) and !empty(trim($_POST["from_date"]))) {
            $startdate = esc_sql($_POST['from_date']);
        } else {
            $startdate = date("Y/m/d", time());
        }
        $where = " where   {$wp_post_meta}.`edit_date` >= '{$startdate} 00:00:00'   order  by {$wp_post_meta}.id desc ";
        $two_last_edited_posts = $wpdb->get_results($wpdb->prepare(" select {$wp_post_meta}.* , {$wp_posts}.`post_title`  from  {$wp_post_meta} left join {$wp_posts} on {$wp_post_meta}.post_id = {$wp_posts}.ID   {$where}   "));
        include POST_LOG_TEM . "post_log_admin_panel.php";
    } else if (isset($_GET['log_id']) and !empty($_GET['log_id'])) {
        $log_id = esc_sql($_GET['log_id']);
        $where = "  where  $wp_post_meta.id={$log_id} ";
        $edited_posts = $wpdb->get_row($wpdb->prepare(" select {$wp_post_meta}.* , {$wp_posts}.`post_title`  from  {$wp_post_meta} left join {$wp_posts} on {$wp_post_meta}.post_id = {$wp_posts}.ID   {$where}   "));
        include POST_LOG_TEM . "post_log_data.php";
    }

}

function insert_post_data($post_id = '', $all_info = '')
{
    global $wpdb, $table_prefix;
//    table name
    $log_table = $table_prefix . "edited_postmeta_log";
//    post meta tags  list in array
    $meta_array = [
        'ebookShop1',
        'ebookShop2',
        'ebookShopname1',
        'ebookShopname2',
        'getdatafrombook',
        'meta_mani'];

    if ($all_info->post_status == 'publish') {
        foreach ($meta_array as $key => $meta_key) {
            $one_last_edited_meta = $wpdb->get_row($wpdb->prepare(" select  *  from  {$log_table}   where meta_key='{$meta_key}' and post_id={$post_id} order by id desc   "));

//    if post_meta_value_next is not the same post_meta_value_prev
            if ($one_last_edited_meta->next_value != $_POST[$meta_key]) {
//    get current user id
                $user_id = get_current_user_id();
//               insert meta data into log table
                $result = $wpdb->insert($log_table,
                    array(
                        "post_id" => $post_id,
                        "meta_key" => $meta_key,
                        "prev_value" => $one_last_edited_meta->next_value,
                        "next_value" => $_POST[$meta_key],
                        "user_id" => $user_id,
                    ), array(
                        "%d",
                        "%s",
                        "%s",
                        "%s",
                        "%d"
                    ));
            }
        }
    }
}

function wp_meta_box_page()
{
    echo "<input type='text' name='meta_mani' >";
}

function get_my_author_name($author_id = '')
{
    global $wpdb, $table_prefix;
    $wp_users = $table_prefix . 'users';
    if (!empty($author_id)) {
        $author_name = $wpdb->get_row($wpdb->prepare(" select `user_login` from {$wp_users} where ID={$author_id}  "));
        return $author_name->user_login;
    } else {
        return;
    }
}

//....................................................................
//"SELECT tx.* , tr.* FROM `wp_term_taxonomy`tx left join wp_terms tr on  tx.term_id =tr.`term_id`  where tx.taxonomy='category' ORDER BY tx.`term_taxonomy_id`  DESC";//get category name for show in page
//....................................................................

// run this function when plugin is activing
function post_log_activate()
{
    post_log_create_tables();
}

//  create post  log table when olugin is activing
function post_log_create_tables()
{
    global $wpdb, $table_prefix;
    $log_table = $table_prefix . "edited_postmeta_log";
    // [=====create table  =====]
    $log_meta_table = "CREATE TABLE {$log_table} (
         `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `post_id` int(10) UNSIGNED DEFAULT NULL,
          `meta_key` varchar(250) DEFAULT NULL,
          `prev_value` varchar(250) DEFAULT NULL,
          `next_value` varchar(250) DEFAULT NULL,
          `user_id` int(10) UNSIGNED DEFAULT NULL,
          `edit_date` datetime NOT NULL DEFAULT current_timestamp()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    include ABSPATH . 'wp_admin/include/tables.php';
    dbDelta($log_meta_table);
}

// run this function when plugin is activing
function post_log_deactivate()
{

}

//  convert gregorian date to persian
function g_date_to_p($date = '', $im_sign = '_', $ex_sign = '_')
{
    if (!empty(trim($date))) {
        if (!function_exists('gregorian_to_jalali')) {
            include VOTE_INC . 'jdf.php';
        }
        $g_date = explode($ex_sign, $date);
        $date_p = gregorian_to_jalali($g_date[0], $g_date[1], $g_date[2], '');
        return $date_p[0] . $im_sign . $date_p[1] . $im_sign . $date_p[2];
    } else {
        return "";
    }
}

//  convert  persian date to  gregorian
function p_date_to_g($date = '', $im_sign = '_', $ex_sign = '_')
{
    if (!empty(trim($date))) {
        if (!function_exists('gregorian_to_jalali')) {
            include VOTE_INC . 'jdf.php';
        }
        $g_date = explode($ex_sign, $date);
        $date_p = jalali_to_gregorian($g_date[0], $g_date[1], $g_date[2], '');
        return $date_p[0] . $im_sign . $date_p[1] . $im_sign . $date_p[2];
    } else {
        return "";
    }
}


//if (isset($_GET['export_users']) and !empty($_GET['export_users'])) {
//    users_vote_list_export();
//    exit();
//}
//if (isset($_GET['export_post']) and !empty($_GET['export_post'])) {
//    posts_vote_list_export();
//    exit();
//}
//if (isset($_GET['export_votes']) and !empty($_GET['export_votes'])) {
//    votes_list_export();
//    exit();
//}

