<div class="wrap vote_table_row">
    <table class="form-table ">
        <tr>
            <td> گزینه : <label><?= $answer_title ?></label></td>
            <td><a class="btn btn-info" href="?page=vote_panel%2Fvote_panel.php&post_id=<?= $post_id ?>"> بازگشت</a>
            </td>
        </tr>
        <tbody class="vote_table">
        <?php
        if (!empty($users_vote_answer[0])) {
            foreach ($users_vote_answer as $key => $users) {
                ?>
                <tr>
                    <th><span><?= $key + 1 ?> - </span> <span> <?= $users->display_name ?> </span></th>
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
