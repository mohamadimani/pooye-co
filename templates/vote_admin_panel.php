<div class="wrap vote_table_row">
    <table class="form-table ">
        <tr>
            <td><label for="vote_answer "> عنوان پست </label></td>
            <td><label for="vote_answer "> تعداد رای </label></td>
            <td><label for="vote_answer "> مشاهده </label></td>
        </tr>
        <tbody class="vote_table">
        <?php

        foreach ($all_answer_count as $key => $answers) {            ?>
            <tr>
                <td><label for="vote_answer<?= $key ?>"> <?= $answers->post_title ?>   </label></td>
                <td><label for="vote_answer<?= $key ?>"> <?= $answers->answer_count ?>   </label></td>
                <td><a href="<?= esc_url(add_query_arg(array('post_id' => $answers->post_id))); ?>">جزئیات</a></td>
            </tr>
        <?php }
        ?>
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
