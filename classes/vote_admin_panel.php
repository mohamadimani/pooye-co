<?php

class vote_admin_panel
{
    public static function save_vote()
    {
        if (isset($_POST['vote_answer']) and isset($_POST['vote_question'])) {
            $question = $_POST['vote_question'];
            $answer = implode('-', $_POST['vote_answer']);
            print_r($question);
            print_r($answer);
            add_post_meta('vote_question', $question,);
            add_post_meta('vote_answer', $answer);
        }
    }

}
