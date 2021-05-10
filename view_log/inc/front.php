<?php

function user_view_function()
{
    global $wpdb, $table_prefix;
//    $user_ip = "12345689712";
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $date = date('Y-m-d');
    $is_user_visit_today = $wpdb->get_var("select id from wp_view_count where ip='{$user_ip}' and '{$date}'=`date`  limit 1");
    if (intval($is_user_visit_today) == 0) {
        $result = $wpdb->insert('wp_view_count',
            array(
                "ip" => $user_ip,
                "date" => $date,
            ), array(
                "%s",
                "%s"
            ));
    } else {
        $wpdb->query("update wp_view_count set visit_count =visit_count+1 where id={$is_user_visit_today}");
    }

    $today_visit = $wpdb->get_row("select id , total_visits   from wp_view_log where `date`='{$date}'");
    if (intval($today_visit) > 0) {
        $wpdb->query("update wp_view_log set total_visits =total_visits+1 where id={$today_visit->id}");
        if ($is_user_visit_today == 0) {
            $wpdb->query("update wp_view_log set uniq_visits =uniq_visits+1 where id={$today_visit->id}");
        }
    } else {
        $result = $wpdb->insert('wp_view_log',
            array(
                "total_visits" => 1,
                "uniq_visits" => 1,
                "date" => $date
            ), array(
                "%d",
                "%d",
                "%s"
            ));
    }
}

add_action("init", "user_view_function");

if (!function_exists('dd')) {
    function dd($data = '')
    {
        print_r($data);
        exit();
    }
}