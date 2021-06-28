<div class="metabox_inside">
    <!--    <form method="post" action="">-->
    <table class="form-table ">
        <?php
        if (!empty($post_vote_question)) { ?>
            <tr>
                <th scope="row"><label for="vote_activity"> فعال کردن </label></th>
                <td>
                    <input type="checkbox" name="vote_activity" id="vote_activity"
                        <?php checked(1, $post_vote_activity == 'on' ? 1 : 0) ?> >
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="end_date">تا تاریخ : </label></th>
                <td>
                    <input type="text" name="end_date_vote" id="end_date_vote" value="<?= @$vote_end_date ?>">
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="vote_question"> سوال </label></th>
                <td>
                    <input type="text" name="vote_question" id="vote_question" value="<?= $post_vote_question ?>">
                </td>
            </tr>
            <tbody class="vote_table">
            <?php if (!empty($post_vote_answer[0])) {
                foreach ($post_vote_answer as $key => $answer) {
                    ?>
                    <tr>
                        <th scope="row"><label for="vote_answer<?= $key ?>"> پاسخ </label></th>
                        <td>
                            <input type="text" name="vote_answer[]" id="vote_answer<?= $key ?>"
                                   value="<?= $answer ?>">
                            <span class="remove_row" onclick="remove_row(this)">-</span>
                        </td>
                    </tr>
                <?php }
            } ?>
            </tbody>
        <?php } else { ?>
            <tr>
                <th scope="row"><label for="vote_activity"> فعال کردن </label></th>
                <td>
                    <input type="checkbox" name="vote_activity" id="vote_activity">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="end_date">تا تاریخ : </label></th>
                <td>
                    <input type="text" name="end_date_vote" id="end_date_vote">
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="vote_question"> سوال </label></th>
                <td>
                    <input type="text" name="vote_question" id="vote_question">
                </td>
            </tr>
            <tbody class="vote_table">
            <tr>
                <th scope="row"><label for="vote_answer1"> پاسخ </label></th>
                <td>
                    <input type="text" name="vote_answer[]" id="vote_answer1">
                    <span class="remove_row" onclick="remove_row(this)">-</span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="vote_answer2"> پاسخ </label></th>
                <td>
                    <input type="text" name="vote_answer[]" id="vote_answer2">
                    <span class="remove_row" onclick="remove_row(this)">-</span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="vote_answer3"> پاسخ </label></th>
                <td>
                    <input type="text" name="vote_answer[]" id="vote_answer3">
                    <span class="remove_row" onclick="remove_row(this)">-</span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="vote_answer4"> پاسخ </label></th>
                <td>
                    <input type="text" name="vote_answer[]" id="vote_answer4">
                    <span class="remove_row" onclick="remove_row(this)">-</span>
                </td>
            </tr>
            </tbody>

        <?php } ?>
        <tfoot>
        <tr>
            <td><b>افزودن پاسخ :</b></td>
            <td><a class="add_row">+</a></td>
        </tr>

        </tfoot>
    </table>
    <!--    </form>-->
</div>
<script> $("#end_date_vote").persianDatepicker();</script>
