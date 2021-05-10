<?php

function view_log_function()
{
    global $wpdb;
    $yaster_day = time() - 86400;
    $date_ydy = date('Y-m-d', $yaster_day);
    $date = date('Y-m-d');
    $total_uniq_visit = $wpdb->get_row("select sum(total_visits) as t_v , sum(uniq_visits) as u_v from wp_view_log  ");
    $total_uniq_visit_today = $wpdb->get_row("select sum(total_visits) as t_v_tody , sum(uniq_visits) as u_v_tody from wp_view_log where `date`= '{$date}'  ");
//    $total_uniq_visit_ydy = $wpdb->get_row("select sum(total_visits) as t_v_ydy , sum(uniq_visits) as u_v_ydy from wp_view_log where `date`= '{$yaster_day}'  ");
    $total_uniq_visit_ydy = $wpdb->get_row("select sum(total_visits) as t_v_ydy , sum(uniq_visits) as u_v_ydy from wp_view_log where `date`= DATE_SUB('{$date}',INTERVAL  1 DAY)  ");

    include VIEW_INC . "view_log_page.php";
}

function view_log_admin_menu()
{
    add_menu_page(
        'آمار بازدید سایت',
        'آمار بازدید سایت',
        'manage_options',
        'view_log/view_log.php',
        'view_log_function',
        'dashicons-chart-area',
        3//VIEW_IMG . "icon.png",
    );
}

add_action("admin_menu", "view_log_admin_menu");