<div class="metabox_inside">
    <form method="post" action="">
        <table class="form-table ">
            <?php
            if ($post_vote_activity == 'on') { ?>
                <tr>
                    <th scope="row"><label for="vote_question"> </label></th>
                    <td>
                        <label><?= $post_vote_question ?></label>
                    </td>
                </tr>
                <tbody class="vote_table">
                <?php if (!empty($post_vote_answer[0])) {
                    foreach ($post_vote_answer as $key => $answer) {
                        ?>
                        <tr>
                            <th scope="row"><label for="vote_answer<?= $key ?>"> <?= $answer ?>  </label></th>
                            <td>
                                <input type="radio" name="user_vote_answer" id="vote_answer<?= $key ?>"
                                       value="row_<?= $key ?>">
                            </td>
                        </tr>
                    <?php }
                } ?>
                <tr>
                    <th scope="row"></th>
                    <td>
                        <button type="submit">ثبت رای</button>
                    </td>
                </tr>
                </tbody>
            <?php } ?>

        </table>
    </form>
</div>

