<?php

if (isset($_GET['export_users']) and !empty($_GET['export_users'])) {
    users_vote_list_export();
    exit();
}
if (isset($_GET['export_post']) and !empty($_GET['export_post'])) {
    posts_vote_list_export();
    exit();
}
if (isset($_GET['export_votes']) and !empty($_GET['export_votes'])) {
    votes_list_export();
    exit();
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

// run this function when plugin is activing
function vote_activate()
{
    vote_create_tables();
}

//  create vote plugin table when olugin is activing
function vote_create_tables()
{
    global $wpdb;
    // [=====create table vote_count=====]

    $vote_view_count = "CREATE TABLE `wp_vote_users_answer` (
          `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `user_id` int(10) UNSIGNED DEFAULT NULL,
          `post_id` int(10) UNSIGNED DEFAULT NULL,
          `vote_key` varchar(200) DEFAULT NULL,
          `vote_value` varchar(200) DEFAULT NULL, 
          `vote_date` date NOT NULL DEFAULT current_timestamp()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    include ABSPATH . 'wp_admin/include/tables.php';

    dbDelta($vote_view_count);
    $current_db_version = update_option('vote_db_version', DB_VERSION);
}

// run this function when plugin is activing
function vote_deactivate()
{
    vote_create_tables();
}

//  export users vote result per (all / week / day) to csv file
function posts_vote_list_export()
{

    global $wpdb;

    $date = date('Y-m-d', time());

    $post_id = $_GET['post_id'];
    $post_vote_question = get_post_meta($post_id, 'vote_question', true);
    $post_vote_answer = get_post_meta($post_id, 'vote_answer', true);

    $vote_answer_count = $wpdb->get_results($wpdb->prepare("SELECT count(id) as vote_count , vote_value FROM `wp_vote_users_answer`  where post_id=%d and vote_key='user_vote_answer' GROUP by vote_value ", $post_id));
    $answer_val = [];
    foreach ($vote_answer_count as $key => $vote_a) {
        $answer_val[$vote_a->vote_value] = $vote_a->vote_count;
    }
    $all_answer_count = $wpdb->get_row($wpdb->prepare("SELECT count(id) as answer_count FROM `wp_vote_users_answer`  where post_id=%d and vote_key='user_vote_answer' GROUP by post_id ", $post_id));
//          query for last day and last week votes
//            week
    $vote_answer_count_w = $wpdb->get_results($wpdb->prepare("SELECT count(id) as vote_count , vote_value FROM `wp_vote_users_answer`  where post_id=%d and vote_key='user_vote_answer' and  `vote_date`>  DATE_SUB(%s,INTERVAL  7 DAY) GROUP by vote_value ", $post_id, $date));
    $answer_val_w = [];
    foreach ($vote_answer_count_w as $key => $vote_a) {
        $answer_val_w[$vote_a->vote_value] = $vote_a->vote_count;
    }
    $all_answer_count_wdy = $wpdb->get_row($wpdb->prepare("SELECT count(id) as answer_count FROM `wp_vote_users_answer` where post_id=%d and vote_key='user_vote_answer' and  `vote_date`>  DATE_SUB(%s,INTERVAL  7 DAY)  GROUP by post_id ", $post_id, $date));

//          day => vote answer count
    $vote_answer_count_t = $wpdb->get_results($wpdb->prepare("SELECT count(id) as vote_count , vote_value FROM `wp_vote_users_answer`  where post_id=%d and vote_key='user_vote_answer' and   `vote_date`=%s  GROUP by vote_value ", $post_id, $date));
    $answer_val_t = [];
    foreach ($vote_answer_count_t as $key => $vote_a) {
        $answer_val_t[$vote_a->vote_value] = $vote_a->vote_count;
    }
//          all answer count per every post
    $all_answer_count_tdy = $wpdb->get_row($wpdb->prepare("SELECT count(id) as answer_count FROM `wp_vote_users_answer` where post_id=%d and vote_key='user_vote_answer' and  `vote_date`=%s   GROUP by post_id ", $post_id, $date));
    $answers_data = ["all" => $answer_val, "week" => $answer_val_w, "day" => $answer_val_t];
    $answers_count = ["all" => $all_answer_count, "week" => $all_answer_count_wdy, "day" => $all_answer_count_tdy];

    $date = date('Y-m-d', time());
    $name_uniq = 'p' . $post_id;
    $date_p = g_date_to_p($date, $im_sign = '_', $ex_sign = '-');
    $post_title = $wpdb->get_row($wpdb->prepare("SELECT post_title FROM  wp_posts where ID=%d     ", $post_id), ARRAY_A);

    $all_answers_count = $answers_data['all'];
    $week_answers_count = $answers_data['week'];
    $day_answers_count = $answers_data['day'];

    $all_answers_count_sum = $answers_count['all'];
    $week_answers_count_sum = $answers_count['week'];
    $day_answers_count_sum = $answers_count['day'];


    if (!empty(trim($all_answers_count_sum->answer_count))) {
        $all_answers_count_sum = $all_answers_count_sum->answer_count;
    } else {
        $all_answers_count_sum = 1;
    }

    if (!empty(trim($week_answers_count_sum->answer_count))) {
        $week_answers_count_sum = $week_answers_count_sum->answer_count;
    } else {
        $week_answers_count_sum = 1;
    }

    if (!empty(trim($day_answers_count_sum->answer_count))) {
        $day_answers_count_sum = $day_answers_count_sum->answer_count;
    } else {
        $day_answers_count_sum = 1;
    }


    $file_name = $date_p . '_' . $name_uniq . '_' . $_GET['export_post'] . "_users_list.csv";
    $export_file = fopen(VOTE_TEM . $file_name, "w");

    if ($_GET['export_post'] == 'all') {

        $export_file = fopen('php://output', 'w');
        header('Content-Disposition: attachment; filename="' . $file_name . '";');
        header('Content-Encoding: UTF-8');
        header("content-type:text/csv;charset=UTF-8");
        echo "\xEF\xBB\xBF";
        header('Cache-Control:no-cache, no-store,must-revalidate');
        header('Expires: 0');
        header('Pragma: no-cache');

        $text = [];
        foreach ($post_vote_answer as $key => $answers) {
            if ($key == 0) {
                fputcsv($export_file, [$date_p, ' تاریخ گزارش : ', ' گزارش کلی لیست درصد آرا '], ",");
                fputcsv($export_file, [' - ', ' سوال ', '  پست '], ",");
                fputcsv($export_file, [' - ', $post_vote_question, $post_title['post_title']], ",");
                fputcsv($export_file, ['  گزینه ها ', '  تعداد رای ', ' درصد'], ",");
            }
            $text[$key]['item'] = $answers;
            $text[$key]['count'] = intval($all_answers_count[$key]);
            $text[$key]['per'] = round(($all_answers_count[$key] * 100) / $all_answers_count_sum, 1) . '%';
        }

        if (is_array($text)) {
            foreach ($text as $key => $text2) {
                fputcsv($export_file, $text2, ",");
            }
        }
    } elseif ($_GET['export_post'] == 'week') {

        $export_file = fopen('php://output', 'w');
        header('Content-Disposition: attachment; filename="' . $file_name . '";');
        header('Content-Encoding: UTF-8');
        header("content-type:text/csv;charset=UTF-8");
        echo "\xEF\xBB\xBF";
        header('Cache-Control:no-cache, no-store,must-revalidate');
        header('Expires: 0');
        header('Pragma: no-cache');


        $text = [];
        foreach ($post_vote_answer as $key => $answers) {
            if ($key == 0) {
                fputcsv($export_file, [$date_p, ' تاریخ گزارش : ', ' گزارش هفتگی لیست درصد آرا '], ",");
                fputcsv($export_file, [' - ', ' سوال ', '  پست '], ",");
                fputcsv($export_file, [' - ', $post_vote_question, $post_title['post_title']], ",");
                fputcsv($export_file, ['  گزینه ها ', '  تعداد رای ', ' درصد'], ",");
            }
            $text[$key]['item'] = $answers;
            $text[$key]['count'] = intval($week_answers_count[$key]);
            $text[$key]['per'] = round(($week_answers_count[$key] * 100) / $week_answers_count_sum, 1) . '%';
        }

        if (is_array($text)) {
            foreach ($text as $key => $text2) {
                fputcsv($export_file, $text2, ",");
            }
        }
    } elseif ($_GET['export_post'] == 'day') {

        $export_file = fopen('php://output', 'w');
        header('Content-Disposition: attachment; filename="' . $file_name . '";');
        header('Content-Encoding: UTF-8');
        header("content-type:text/csv;charset=UTF-8");
        echo "\xEF\xBB\xBF";
        header('Cache-Control:no-cache, no-store,must-revalidate');
        header('Expires: 0');
        header('Pragma: no-cache');

        $text = [];
        foreach ($post_vote_answer as $key => $answers) {
            if ($key == 0) {
                fputcsv($export_file, [$date_p, ' تاریخ گزارش : ', ' گزارش روزانه لیست درصد آرا '], ",");
                fputcsv($export_file, [' - ', ' سوال ', '  پست '], ",");
                fputcsv($export_file, [' - ', $post_vote_question, $post_title['post_title']], ",");
                fputcsv($export_file, ['  گزینه ها ', '  تعداد رای ', ' درصد'], ",");
            }
            $text[$key]['item'] = $answers;
            $text[$key]['count'] = intval($day_answers_count[$key]);
            $text[$key]['per'] = round(($day_answers_count[$key] * 100) / $day_answers_count_sum, 1) . '%';
        }

        if (is_array($text)) {
            foreach ($text as $key => $text2) {
                fputcsv($export_file, $text2, ",");
            }
        }
    }
    fclose($export_file);
}

//  export users vote result per (all / week / day) to csv file
function users_vote_list_export($answer_title = '', $post_vote_question = '')
{
    global $wpdb;


    $answer_id = $_GET['answer_id'];
    $post_id = $_GET['post_id'];
    $date = date('Y-m-d', time());
    $name_uniq = 'p' . $post_id . '_' . 'a' . $answer_id;
    $date_p = g_date_to_p($date, $im_sign = '_', $ex_sign = '-');

    $answer_title = $_GET['answer_title'];
    $post_vote_question = get_post_meta($post_id, 'vote_question', true);

    $post_title = $wpdb->get_row($wpdb->prepare("SELECT post_title FROM  wp_posts where ID=%d     ", $post_id), ARRAY_A);

    if ($_GET['export_users'] == 'all') {

        $users_vote_answer = $wpdb->get_results($wpdb->prepare("SELECT    u.display_name , v.vote_date  
                                                            FROM `wp_vote_users_answer` v 
                                                            left join wp_users u on v.user_id=u.id     
                                                            where v.post_id=%d  and    v.vote_key='user_vote_answer' and v.vote_value=%s    ", $post_id, $answer_id), ARRAY_A);
        $file_name = $date_p . '_' . $name_uniq . '_' . $_GET['export_users'] . "_users_list.csv";

        $export_file = fopen('php://output', 'w');
        header('Content-Disposition: attachment; filename="' . $file_name . '";');
        header('Content-Encoding: UTF-8');
        header("content-type:text/csv;charset=UTF-8");
        echo "\xEF\xBB\xBF";
        header('Cache-Control:no-cache, no-store,must-revalidate');
        header('Expires: 0');
        header('Pragma: no-cache');


        foreach ($users_vote_answer as $key => $users) {
            if ($key == 0) {
                fputcsv($export_file, [$date_p, ' تاریخ گزارش : ', ' گزارش کلی لیست رای دهندگان '], ',', '"', "\\");
                fputcsv($export_file, ['  گزینه ', ' سوال ', '  پست '], ',', '"', "\\");
                fputcsv($export_file, [$answer_title, $post_vote_question, $post_title['post_title']], ',', '"', "\\");
                fputcsv($export_file, ['  نام ', '  تاریخ ', '    ردیف'], ',', '"', "\\");
            }
            $users['vote_date'] = g_date_to_p($users['vote_date'], $im_sign = '/', $ex_sign = '-');
            $users['user_id'] = $key + 1;
            $text[] = $users;
        }
        if (is_array($text)) {
            foreach ($text as $key => $text2) {
                fputcsv($export_file, $text2, ",", '"', "\\");
            }
        }
        fclose($export_file);

    } elseif ($_GET['export_users'] == 'week') {

        $users_vote_answer = $wpdb->get_results($wpdb->prepare("SELECT    u.display_name , v.vote_date  
                                                            FROM `wp_vote_users_answer` v 
                                                            left join wp_users u on v.user_id=u.id     
                                                            where v.post_id=%d  and     v.vote_key='user_vote_answer' and v.vote_value=%s and  v.`vote_date`>  DATE_SUB(%s ,INTERVAL  7 DAY)    ", $post_id, $answer_id, $date), ARRAY_A);
        $file_name = $date_p . '_' . $name_uniq . '_' . $_GET['export_users'] . "_users_list.csv";


        $export_file = fopen('php://output', 'w');
        header('Content-Disposition: attachment; filename="' . $file_name . '";');
        header('Content-Encoding: UTF-8');
        header("content-type:text/csv;charset=UTF-8");
        echo "\xEF\xBB\xBF";
        header('Cache-Control:no-cache, no-store,must-revalidate');
        header('Expires: 0');
        header('Pragma: no-cache');

        foreach ($users_vote_answer as $key => $users) {
            if ($key == 0) {
                fputcsv($export_file, [$date_p, ' تاریخ گزارش : ', ' گزارش هفتگی لیست رای دهندگان '], ",", '"', "\\");
                fputcsv($export_file, ['  گزینه ', ' سوال ', '  پست '], ",", '"', "\\");
                fputcsv($export_file, [$answer_title, $post_vote_question, $post_title['post_title']], ",", '"', "\\");
                fputcsv($export_file, ['  نام ', '  تاریخ ', '    ردیف'], ",", '"', "\\");
            }
            $users['vote_date'] = g_date_to_p($users['vote_date']);
            $users['user_id'] = $key + 1;
            $text[] = $users;
        }
        if (is_array($text)) {
            foreach ($text as $key => $text2) {
                fputcsv($export_file, $text2, ",", '"', "\\");
            }
        }
        fclose($export_file);

    } elseif ($_GET['export_users'] == 'day') {

        $users_vote_answer = $wpdb->get_results($wpdb->prepare("SELECT    u.display_name , v.vote_date  
                                                            FROM `wp_vote_users_answer` v 
                                                            left join wp_users u on v.user_id=u.id     
                                                            where v.post_id=%d  and     v.vote_key='user_vote_answer' and v.vote_value=%s and  v.`vote_date`=%s     ", $post_id, $answer_id, $date), ARRAY_A);
        $file_name = $date_p . '_' . $name_uniq . '_' . $_GET['export_users'] . "_users_list.csv";

        $export_file = fopen('php://output', 'w');
        header('Content-Disposition: attachment; filename="' . $file_name . '";');
        header('Content-Encoding: UTF-8');
        header("content-type:text/csv;charset=UTF-8");
        echo "\xEF\xBB\xBF";
        header('Cache-Control:no-cache, no-store,must-revalidate');
        header('Expires: 0');
        header('Pragma: no-cache');

        foreach ($users_vote_answer as $key => $users) {
            if ($key == 0) {
                fputcsv($export_file, [$date_p, ' تاریخ گزارش : ', ' گزارش روزانه لیست رای دهندگان '], ",");
                fputcsv($export_file, ['  گزینه ', ' سوال ', '  پست '], ",");
                fputcsv($export_file, [$answer_title, $post_vote_question, $post_title['post_title']], ",");
                fputcsv($export_file, ['  نام ', '  تاریخ ', '    ردیف'], ",");
            }
            $users['vote_date'] = g_date_to_p($users['vote_date']);
            $users['user_id'] = $key + 1;
            $text[] = $users;
        }
        if (is_array($text)) {
            foreach ($text as $key => $text2) {
                fputcsv($export_file, $text2, ",");
            }
        }
        fclose($export_file);

    }
}

//   export  votes result per (all / week / day) to csv file
function votes_list_export()
{
    global $wpdb;
    $date = date('Y-m-d', time());
    $date_p = g_date_to_p($date, $im_sign = '_', $ex_sign = '-');
    if ($_GET['export_votes'] == 'all') {

        $all_answer_count = $wpdb->get_results($wpdb->prepare("SELECT count(v.id) as answer_count   ,p.post_title    ,pm.meta_value
                                                            FROM `wp_vote_users_answer` v 
                                                            left join wp_posts p on v.post_id=p.id 
                                                            left join wp_postmeta pm on v.post_id=pm.post_id and pm.meta_key='vote_question' 
                                                            where   v.vote_key='user_vote_answer' GROUP by v.post_id   "), ARRAY_A);
        $file_name = $date_p . '_' . $_GET['export_votes'] . "_posts_list.csv";
        $export_file = fopen(VOTE_TEM . $file_name, "w");

        $export_file = fopen('php://output', 'w');
        header('Content-Disposition: attachment; filename="' . $file_name . '";');
        header('Content-Encoding: UTF-8');
        header("content-type:text/csv;charset=UTF-8");
        echo "\xEF\xBB\xBF";
        header('Cache-Control:no-cache, no-store,must-revalidate');
        header('Expires: 0');
        header('Pragma: no-cache');

        foreach ($all_answer_count as $key => $posts) {
            if ($key == 0) {
                fputcsv($export_file, [$date_p, ' تاریخ گزارش : ', ' گزارش   آرای کل  پست ها '], ",");
                fputcsv($export_file, ['جمع آرا', 'عنوان پست', 'عنوان سوال'], ",");
            }
            fputcsv($export_file, $posts, ",");
        }
        fclose($export_file);

    } elseif ($_GET['export_votes'] == 'week') {

        $all_answer_count_wdy = $wpdb->get_results($wpdb->prepare("SELECT count(v.id) as answer_count   ,p.post_title    ,pm.meta_value
                                                            FROM `wp_vote_users_answer` v 
                                                            left join wp_posts p on v.post_id=p.id 
                                                            left join wp_postmeta pm on v.post_id=pm.post_id and pm.meta_key='vote_question' 
                                                            where   v.vote_key='user_vote_answer' and v.`vote_date`>DATE_SUB(%s,INTERVAL  7 DAY) GROUP by v.post_id   ", $date), ARRAY_A);
        $file_name = $date_p . '_' . $_GET['export_votes'] . "_posts_list.csv";
        $export_file = fopen(VOTE_TEM . $file_name, "w");

        $export_file = fopen('php://output', 'w');
        header('Content-Disposition: attachment; filename="' . $file_name . '";');
        header('Content-Encoding: UTF-8');
        header("content-type:text/csv;charset=UTF-8");
        echo "\xEF\xBB\xBF";
        header('Cache-Control:no-cache, no-store,must-revalidate');
        header('Expires: 0');
        header('Pragma: no-cache');

        foreach ($all_answer_count_wdy as $key => $posts) {
            if ($key == 0) {
                fputcsv($export_file, [$date_p, ' تاریخ گزارش : ', ' گزارش   آرای هفتگی پست ها '], ",");
                fputcsv($export_file, ['جمع آرا', 'عنوان پست', 'عنوان سوال'], ",");
            }
            fputcsv($export_file, $posts, ",");
        }
        fclose($export_file);

    } elseif ($_GET['export_votes'] == 'day') {

        $all_answer_count_tdy = $wpdb->get_results($wpdb->prepare("SELECT count(v.id) as answer_count   ,p.post_title    ,pm.meta_value
                                                            FROM `wp_vote_users_answer` v 
                                                            left join wp_posts p on v.post_id=p.id 
                                                            left join wp_postmeta pm on v.post_id=pm.post_id and pm.meta_key='vote_question' 
                                                            where   v.vote_key='user_vote_answer' and v.`vote_date`=%s  GROUP by v.post_id   ", $date), ARRAY_A);
        $file_name = $date_p . '_' . $_GET['export_votes'] . "_posts_list.csv";
        $export_file = fopen(VOTE_TEM . $file_name, "w");

        $export_file = fopen('php://output', 'w');
        header('Content-Disposition: attachment; filename="' . $file_name . '";');
        header('Content-Encoding: UTF-8');
        header("content-type:text/csv;charset=UTF-8");
        echo "\xEF\xBB\xBF";
        header('Cache-Control:no-cache, no-store,must-revalidate');
        header('Expires: 0');
        header('Pragma: no-cache');

        foreach ($all_answer_count_tdy as $key => $posts) {
            if ($key == 0) {
                fputcsv($export_file, [$date_p, ' تاریخ گزارش : ', ' گزارش  آرای روزانه پست ها '], ",");
                fputcsv($export_file, ['جمع آرا', 'عنوان پست', 'عنوان سوال'], ",");
            }
            fputcsv($export_file, $posts, ",");
        }
        fclose($export_file);
    }
}

//  show vote box in admin post page
function wp_vote_box_page($post)
{
//        get vote info from postmeta table and use in in page
    $post_vote_activity = get_post_meta($post->ID, 'vote_activity', true);
    $post_vote_question = get_post_meta($post->ID, 'vote_question', true);
    $post_vote_answer = get_post_meta($post->ID, 'vote_answer', true);
    $vote_end_date = get_post_meta($post->ID, 'vote_end_date', true);
    $vote_end_date = g_date_to_p($vote_end_date, '/', '/');
    include VOTE_TEM . 'vote_box.php';
}

//  save vote boxinfo in admin post page
function wp_vote_box_page_save($post_id)
{
    // Bail if we're doing an auto save
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // if our current user can't edit this post, bail
    if (!current_user_can('edit_post')) return;
    if (isset($_POST['end_date_vote'])) {
        $e_date = p_date_to_g($_POST['end_date_vote'], '/', '/');
        update_post_meta($post_id, 'vote_end_date', $e_date);
    }
    if (isset($_POST['vote_question'])) {
        update_post_meta($post_id, 'vote_activity', $_POST['vote_activity']);
        update_post_meta($post_id, 'vote_question', $_POST['vote_question']);
        update_post_meta($post_id, 'vote_answer', $_POST['vote_answer']);
    }
}

//    show vote manage page in admin panel menu
function vote_panel_page()
{
    global $wpdb;

    $date = date('Y-m-d', time());
    if (isset($_GET['post_id']) and !empty($_GET['post_id']) and !isset($_GET['answer_id'])) {
        $post_id = $_GET['post_id'];
        $post_vote_question = get_post_meta($post_id, 'vote_question', true);
        $post_vote_activity = get_post_meta($post_id, 'vote_activity', true);
        $post_vote_answer = get_post_meta($post_id, 'vote_answer', true);

        $vote_answer_count = $wpdb->get_results($wpdb->prepare("SELECT count(id) as vote_count , vote_value FROM `wp_vote_users_answer`  where post_id=%d and vote_key='user_vote_answer' GROUP by vote_value ", $post_id));
        $answer_val = [];
        foreach ($vote_answer_count as $key => $vote_a) {
            $answer_val[$vote_a->vote_value] = $vote_a->vote_count;
        }
        $all_answer_count = $wpdb->get_row($wpdb->prepare("SELECT count(id) as answer_count FROM `wp_vote_users_answer`  where post_id=%d and vote_key='user_vote_answer' GROUP by post_id ", $post_id));
//          query for last day and last week votes
//            week
        $vote_answer_count_w = $wpdb->get_results($wpdb->prepare("SELECT count(id) as vote_count , vote_value FROM `wp_vote_users_answer`  where post_id=%d and vote_key='user_vote_answer' and  `vote_date`>  DATE_SUB(%s,INTERVAL  7 DAY) GROUP by vote_value ", $post_id, $date));
        $answer_val_w = [];
        foreach ($vote_answer_count_w as $key => $vote_a) {
            $answer_val_w[$vote_a->vote_value] = $vote_a->vote_count;
        }
        $all_answer_count_wdy = $wpdb->get_row($wpdb->prepare("SELECT count(id) as answer_count FROM `wp_vote_users_answer` where post_id=%d and vote_key='user_vote_answer' and  `vote_date`>  DATE_SUB(%s,INTERVAL  7 DAY)  GROUP by post_id ", $post_id, $date));

//          day => vote answer count
        $vote_answer_count_t = $wpdb->get_results($wpdb->prepare("SELECT count(id) as vote_count , vote_value FROM `wp_vote_users_answer`  where post_id=%d and vote_key='user_vote_answer' and   `vote_date`=%s  GROUP by vote_value ", $post_id, $date));
        $answer_val_t = [];
        foreach ($vote_answer_count_t as $key => $vote_a) {
            $answer_val_t[$vote_a->vote_value] = $vote_a->vote_count;
        }
//          all answer count per every post
        $all_answer_count_tdy = $wpdb->get_row($wpdb->prepare("SELECT count(id) as answer_count FROM `wp_vote_users_answer` where post_id=%d and vote_key='user_vote_answer' and  `vote_date`=%s   GROUP by post_id ", $post_id, $date));
        $answers_data = ["all" => $answer_val, "week" => $answer_val_w, "day" => $answer_val_t];
        $answers_count = ["all" => $all_answer_count, "week" => $all_answer_count_wdy, "day" => $all_answer_count_tdy];

        //posts_vote_list_export(   ); ////////////////////////////////////////

        include VOTE_TEM . 'vote_admin_panel_post.php';

    } else if (isset($_GET['answer_id'])) {
        $answer_id = $_GET['answer_id'];
        $post_id = $_GET['post_id'];
        $answer_title = $_GET['answer_title'];
        $post_vote_question = get_post_meta($post_id, 'vote_question', true);
        $all_u = 0;
        if (isset($_GET['user_all']) and !empty($_GET['user_all'])) {
            $all_u = ($_GET['user_all'] - 1) * 20;
        }
        if (isset($_GET['answer_filter']) and !empty($_GET['answer_filter'])) {
            $answer_filter = $_GET['answer_filter'];
            if ($answer_filter == 'all') {
                $users_vote_answer = $wpdb->get_results($wpdb->prepare("SELECT     v.user_id ,u.display_name 
                                                            FROM `wp_vote_users_answer` v left join wp_users u on v.user_id=u.id 
                                                            where v.post_id=%d  and    v.vote_key='user_vote_answer' and v.vote_value=%s   limit $all_u , 20  ", $post_id, $answer_id), ARRAY_A);

            } elseif ($answer_filter == 'day') {
                $users_vote_answer = $wpdb->get_results($wpdb->prepare("SELECT     v.user_id ,u.display_name 
                                                            FROM `wp_vote_users_answer` v left join wp_users u on v.user_id=u.id 
                                                            where v.post_id=%d  and    v.vote_key='user_vote_answer' and v.vote_value=%s and  v.`vote_date`=%s   limit $all_u , 20  ", $post_id, $answer_id, $date), ARRAY_A);

            } elseif ($answer_filter == 'week') {
                $users_vote_answer = $wpdb->get_results($wpdb->prepare("SELECT     v.user_id ,u.display_name 
                                                            FROM `wp_vote_users_answer` v left join wp_users u on v.user_id=u.id 
                                                            where v.post_id=%d  and    v.vote_key='user_vote_answer' and v.vote_value=%s   and  v.`vote_date`>  DATE_SUB(%s ,INTERVAL  7 DAY) limit $all_u , 20  ", $post_id, $answer_id, $date), ARRAY_A);
            }
        }
        $all_users_count = $wpdb->get_results($wpdb->prepare("SELECT   COUNT(user_id)  as user_count   FROM `wp_vote_users_answer` where  post_id=%d and  vote_value=%s  GROUP BY user_id   ", $post_id, $answer_id));


//        if (isset($_GET['export_users']) and !empty($_GET['export_users'])) {
//            add_action('users_vote_list_exports', 'users_vote_list_export');
//            do_action("users_vote_list_exports");
//            exit();
//        }

        include VOTE_TEM . 'vote_admin_panel_users.php';
    } else {
        $all_p = 0;
        $wd_p = 0;
        $td_p = 0;
        if (isset($_GET['page_all']) and !empty($_GET['page_all'])) {
            $all_p = ($_GET['page_all'] - 1) * 20;
        }
        if (isset($_GET['page_week']) and !empty($_GET['page_week'])) {
            $wd_p = ($_GET['page_week'] - 1) * 20;
        }
        if (isset($_GET['page_day']) and !empty($_GET['page_day'])) {
            $td_p = ($_GET['page_day'] - 1) * 20;
        }
        $all_answer_count = $wpdb->get_results($wpdb->prepare("SELECT count(v.id) as answer_count , v.post_id ,p.post_title ,p.post_date  
                                                            FROM `wp_vote_users_answer` v left join wp_posts p on v.post_id=p.id 
                                                            where   v.vote_key='user_vote_answer' GROUP by v.post_id limit $all_p , 20 "));
        $all_answer_count_wdy = $wpdb->get_results($wpdb->prepare("SELECT count(v.id) as answer_count , v.post_id ,p.post_title ,p.post_date  
                                                            FROM `wp_vote_users_answer` v left join wp_posts p on v.post_id=p.id 
                                                            where   v.vote_key='user_vote_answer' and v.`vote_date`>DATE_SUB(%s,INTERVAL  7 DAY) GROUP by v.post_id limit $wd_p , 20 ", $date));
        $all_answer_count_tdy = $wpdb->get_results($wpdb->prepare("SELECT count(v.id) as answer_count , v.post_id ,p.post_title ,p.post_date  
                                                            FROM `wp_vote_users_answer` v left join wp_posts p on v.post_id=p.id 
                                                            where   v.vote_key='user_vote_answer' and v.`vote_date`=%s GROUP by v.post_id  limit $td_p , 20", $date));

        $all_posts_count = $wpdb->get_results($wpdb->prepare("SELECT   COUNT(post_id)  as post_count   FROM `wp_vote_users_answer`  GROUP BY post_id ORDER BY `wp_vote_users_answer`.`post_id` ASC "));
        $w_posts_count = $wpdb->get_results($wpdb->prepare("SELECT   COUNT(post_id)  as post_count   FROM `wp_vote_users_answer` where `vote_date`>DATE_SUB(%s,INTERVAL  7 DAY)  GROUP BY post_id ORDER BY `wp_vote_users_answer`.`post_id` ASC ", $date));
        $t_posts_count = $wpdb->get_results($wpdb->prepare("SELECT   COUNT(post_id)  as post_count   FROM `wp_vote_users_answer` where `vote_date`=%s GROUP BY post_id ORDER BY `wp_vote_users_answer`.`post_id` ASC ", $date));


//            votes_list_export();

        include VOTE_TEM . 'vote_admin_panel.php';
    }
}

//    show vote setting page in admin panel menu

function vote_setting_page()
{
//        include VOTE_TEM . 'setting_page.php';
}

//export data to csv format for test
//add_action('admin_post_mani.csv', 'export_csv');
//do_action("admin_post_mani.csv");
function export_csv($post_id = '')
{
    global $wpdb;
    $filename = 'mani.csv';
    $headers = array('id', 'userid', 'post_id', 'vote_key', 'سلام سلام', 'vote_date', 'Name', 'voteQuestion', 'voteAnswer', 'postIdtest');
    $handle = fopen('php://output', 'w');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    header('Content-Encoding: UTF-8');
    header("content-type:text/csv;charset=UTF-8");
    echo "\xEF\xBB\xBF";
    header('Pragma: no-cache');
    fputcsv($handle, $headers, ',', '"', "\\");
    $postIdTest = $post_id;
    $vote_users_answer = $wpdb->prefix . 'vote_users_answer';
    $users = $wpdb->prefix . 'users';
    $postmeta = $wpdb->prefix . 'postmeta';
    $results = $wpdb->get_results("SELECT $vote_users_answer.*,$users.display_name
    ,Max(CASE WHEN meta_key = 'vote_question' THEN meta_value END) as voteQuestion
    ,MAX(CASE WHEN meta_key = 'vote_answer' THEN meta_value END) as voteAnswer
    FROM $vote_users_answer left JOIN $users on $vote_users_answer.user_id = $users.ID left join $postmeta on $vote_users_answer.post_id = $postmeta.post_id
    GROUP BY $vote_users_answer.id");

    foreach ($results as $results1) {

        $row = array(
            $results1->id,
            $results1->userid,
            $results1->post_id,
            $results1->vote_key,
            $results1->vote_value,
            $results1->vote_date,
            $results1->Name,
            $results1->voteQuestion,
            $results1->voteAnswer,
            $postIdTest,
        );

        fputcsv($handle, $row, ',', '"', "\\");
    }
}