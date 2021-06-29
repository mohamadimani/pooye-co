<?php

function spam_comment_function($comment_id = '', $comment_object = '')
{
    global $wpdb, $table_prefix;

    $wp_terms = $table_prefix . 'terms';
    $user_id = $comment_object->user_id;
    $comment = $comment_object->comment_content;
    $user_meta = get_userdata($user_id);
    $user_roles = $user_meta->roles[0];



//    if (empty(trim($user_roles)) or ($user_roles != 'author' and $user_roles != 'editor' and $user_roles != 'administrator' and $comment_object->comment_ID > 0)) {
        if ($user_roles != 'author' and $user_roles != 'editor' and $user_roles != 'administrator' and $comment_object->comment_ID > 0) {


    $spam_letters = $wpdb->get_results($wpdb->prepare("SELECT * FROM  {$wp_terms} where `name`='spam_filter'   "));
        // The Regular Expression filter
//        $reg_exUrl = '~[a-z]+://\S+~';
        if (preg_match_all('(http|https|www|://|www.)', $comment)) {
            $result = $wpdb->update($table_prefix . 'comments',
                array('comment_approved' => 'spam', 'comment_author_url' => ' '),
                array('comment_ID' => $comment_id),
                array('%s', '%s'),
                array('%d'));
        } else {
            $is_spam = 0;
            foreach ($spam_letters as $key => $letter) {

//                    for check is letter in text
                if (strpos($comment, $letter->slug)) {
                    $is_spam = 1;
                    break;
                }
            }
            $wp_comment = $table_prefix . 'comments';
            if ($is_spam == 1) {
                $result2 = $wpdb->update($wp_comment,
                    array('comment_approved' => '0', 'comment_author_url' => ' '),
                    array('comment_ID' => $comment_id),
                    array('%s', '%s', '%s'),
                    array('%d'));
            } else if ($is_spam == 0) {
                $result3 = $wpdb->update($wp_comment,
                    array('comment_approved' => '1', 'comment_author_url' => ' '),
                    array('comment_ID' => $comment_id),
                    array('%s', '%s', '%s'),
                    array('%d'));
            }
        }
    }
}


//  spam panel page for admin
function spam_panel_page($data = '')
{
    global $wpdb, $table_prefix;
    $terms_tabel = $table_prefix . 'terms';
//  delete items by id
    if (isset($_GET['del_item']) AND !empty($_GET['del_item']) and is_numeric($_GET['del_item'])) {
        $result_d = $wpdb->query("delete from {$terms_tabel}  where term_id={$_GET['del_item']}");
        if ($result_d == 1) {
            $error = 'success';
            $error_text = 'با موفقیت حذف شد !';
        }
    }

    if (isset($_POST['filter_submit'])) {
        $error = save_spam_letters($_POST['filter_letter']);
        $error_text = '';
        if ($error == 'is') {
            $error_text = 'این کلمه قبلا ثبت شده است !';
        } else if ($error == 'danger') {
            $error_text = 'مشکل در ثبت !';
        } else if ($error == 'success') {
            $error_text = 'با موفقیت ثبت شد !';
        } else if ($error == 'empty') {
            $error_text = 'چیزی بنویسید !';
        }
    }

    $spam_p = 0;
    if (isset($_GET['spam_p']) and !empty($_GET['spam_p'])) {
        $spam_p = ($_GET['spam_p'] - 1) * 20;
    }

    $row_count = $wpdb->get_row($wpdb->prepare("SELECT   count(term_id) as row_count FROM   {$terms_tabel}  where `name`='spam_filter' "), ARRAY_A);
    $spam_letters = $wpdb->get_results($wpdb->prepare("SELECT *   FROM  {$terms_tabel} where `name`='spam_filter' order by term_id desc limit {$spam_p} , 20 "), ARRAY_A);
    include SPAM_TEM . "spam_admin_panel.php";
}

//save spam letters
function save_spam_letters($param = '')
{
    if (!empty($param)) {

        global $wpdb, $table_prefix;
        $terms_tabel = $table_prefix . 'terms';
        $is_letter = $wpdb->get_row($wpdb->prepare("SELECT * FROM  {$terms_tabel} where `name`='spam_filter' and slug=%s   ", $param));
        if ($is_letter) {
            return 'is';
        } else {
            $result = $wpdb->insert($terms_tabel, array('name' => 'spam_filter', 'slug' => $param), array('%s', '%s'));
            if ($result) {
                return 'success';
            } else {
                return 'danger';
            }
        }
    } else {
        return "empty";
    }
}

//....................................

//  convert gregorian date to persian
function spam_g_date_to_p($date = '', $ex_sign = '_', $im_sign = '_')
{
    if (!empty(trim($date))) {
        if (!function_exists('gregorian_to_jalali')) {
            include SPAM_INC . 'jdf.php';
        }
        $g_date = explode($ex_sign, $date);
        $date_p = gregorian_to_jalali($g_date[0], $g_date[1], $g_date[2], '');
        return $date_p[0] . $im_sign . $date_p[1] . $im_sign . $date_p[2];
    } else {
        return "";
    }
}

//  convert  persian date to  gregorian
function spam_p_date_to_g($date = '', $ex_sign = '_', $im_sign = '_')
{
    if (!empty(trim($date))) {
        if (!function_exists('gregorian_to_jalali')) {
            include SPAM_INC . 'jdf.php';
        }
        $g_date = explode($ex_sign, $date);
        $date_p = jalali_to_gregorian($g_date[0], $g_date[1], $g_date[2], '');
        return $date_p[0] . $im_sign . $date_p[1] . $im_sign . $date_p[2];
    } else {
        return "";
    }
}

// run this function when plugin is activing
function spam_activate()
{
}

// run this function when plugin is activing
function spam_deactivate()
{

}

