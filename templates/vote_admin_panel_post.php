<div class="wrap vote_table_row">
    <table class="form-table ">
        <tr>
            <td><label><?= $post_vote_question ?></label></td>
            <td><a class="btn btn-info" href="?page=vote_panel%2Fvote_panel.php"> بازگشت</a>
        </tr>
        <tbody class="vote_table">
        <?php
        if (!empty($post_vote_answer[0])) {
            foreach ($post_vote_answer as $key => $answer) {
                ?>
                <tr>
                    <td><span><?= $key + 1 ?> - </span> <label  > <?= $answer ?> : </label>
                        <span style="float: left"><?= round(($answer_val[$key] * 100) / $all_answer_count->answer_count, 1) . '%' ?></span>
                    </td>
                    <td><a href="<?= esc_url(add_query_arg(array(
                            'answer_id' => $key,
                            'answer_title' => $answer
                        ))); ?>">لیست رای دهندگان</a>
                    </td>
                </tr>
            <?php }
        } ?>
        </tbody>

    </table>
</div>

<style>
    .vote_table_row {
        width: 50%;
        display: block;
        /*float: right;*/
        direction: rtl;
    }
</style>
