<div class="wrap vote_table_row">
    <table class="form-table ">
        <?php
        if ($post_vote_activity == 'on') { ?>
            <tr>
                <td><label><?= $post_vote_question ?></label></td>

            </tr>
            <tbody class="vote_table">
            <?php
            if (!empty($post_vote_answer[0])) {
                foreach ($post_vote_answer as $key => $answer) {
                    ?>
                    <tr>
                        <td><label for="vote_answer<?= $key ?>"> <?= $answer ?> : </label>
                            <span style="float: left"><?= round(($answer_val[$key] * 100) / $all_answer_count->answer_count, 1) . '%' ?></span>
                        </td>
                    </tr>
                <?php }
            } ?>
            </tbody>
        <?php } ?>

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
