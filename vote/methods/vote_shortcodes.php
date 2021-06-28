<?php


//this function make content for  shortcode in site post page
function voting_shortcode_function($param)
{
    global $wpdb, $table_prefix;
    $wp_vote_users_answer = $table_prefix . 'vote_users_answer';


//        get the current post id
    $post_id = get_the_ID();
//        get the current user id
    $user_id = wp_get_current_user();
//        get vote info from postmeta table and use in in page
    $post_vote_activity = get_post_meta($post_id, 'vote_activity', true);
    if ($post_vote_activity == 'on') {
        $post_vote_question = get_post_meta($post_id, 'vote_question', true);
        $post_vote_answer = get_post_meta($post_id, 'vote_answer', true);
        $vote_end_date = get_post_meta($post_id, 'vote_end_date', true);

        $vote_end_time = (strtotime($vote_end_date));
        $vote_now_time = strtotime(date('Y-m-d', time()));

        if ($vote_end_time > $vote_now_time) {
            if (isset($_POST['user_vote_answer']) and !empty(trim($_POST['user_vote_answer']))) {
                $answer_id = explode('_', $_POST['user_vote_answer']);

                $result = $wpdb->insert($wp_vote_users_answer,
                    array(
                        "user_id" => $user_id->ID,
                        "post_id" => $post_id,
                        "vote_key" => 'user_vote_answer',
                        "vote_value" => $answer_id[1],
                    ), array(
                        "%d",
                        "%d",
                        "%s",
                        "%s"
                    ));
            }

//        get vote inswer id to chet user voted or not
            $user_vote_answer = $wpdb->get_row($wpdb->prepare("SELECT id FROM {$wp_vote_users_answer} where user_id=%d and post_id=%d and vote_key=%s ", $user_id->ID, $post_id, 'user_vote_answer'));
            if (empty($user_vote_answer)) {

                require VOTE_TEM . 'template_vote_box.php';

                $vote_temp = vote_temp($post_vote_question, $post_vote_answer);
                return $vote_temp;
            } else {
                $all_answer_count = $wpdb->get_row($wpdb->prepare("SELECT count(id) as answer_count FROM `{$wp_vote_users_answer}`  where post_id=%d and vote_key='user_vote_answer' GROUP by post_id ", $post_id));
                $vote_answer_count = $wpdb->get_results($wpdb->prepare("SELECT count(id) as vote_count , vote_value FROM `{$wp_vote_users_answer}`  where post_id=%d and vote_key='user_vote_answer' GROUP by vote_value ", $post_id));
                $answer_val = [];
                foreach ($vote_answer_count as $key => $vote_a) {
                    $answer_val[$vote_a->vote_value] = $vote_a->vote_count;
                }
                include VOTE_TEM . 'template_voted_box.php';
                $voted_temp = voted_temp($post_vote_question, $post_vote_answer, $all_answer_count, $answer_val);
                return $voted_temp;
            }
        } else {
            $all_answer_count = $wpdb->get_row($wpdb->prepare("SELECT count(id) as answer_count FROM `{$wp_vote_users_answer}`  where post_id=%d and vote_key='user_vote_answer' GROUP by post_id ", $post_id));
            $vote_answer_count = $wpdb->get_results($wpdb->prepare("SELECT count(id) as vote_count , vote_value FROM `{$wp_vote_users_answer}`  where post_id=%d and vote_key='user_vote_answer' GROUP by vote_value ", $post_id));
            $answer_val = [];
            foreach ($vote_answer_count as $key => $vote_a) {
                $answer_val[$vote_a->vote_value] = $vote_a->vote_count;
            }
            include VOTE_TEM . 'template_voted_box.php';
            $voted_temp = voted_temp($post_vote_question, $post_vote_answer, $all_answer_count, $answer_val);
            return $voted_temp;
        }
    }
}

