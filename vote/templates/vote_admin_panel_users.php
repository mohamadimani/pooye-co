<style>
    .vote_table_row {
        width: 50%;
        display: block;
        /*float: right;*/
        direction: rtl;
    }

    .p_number {
        padding: 2px 1px;
        background-color: #3cadec;
        border-radius: 3px;
        cursor: pointer;
        width: 20px;
        display: inline-block;
        text-align: center;
        margin: 2px 1px;
    }

    div.row {
        padding: 5px 3px;
    }

    .select {
        background-color: #fc8181 !important;
    }

</style>
<?php
$ex_filter = '';
if (isset($_GET['answer_filter'])) {
    $ex_filter = $_GET['answer_filter'];
}
?>
<div id="dashboard-widgets-wrap" style="margin: 0">
    <div id="dashboard-widgets" class="metabox-holder">
        <h2> گزینه : ( <?= $answer_title ?>)
            <a href="<?= admin_url('admin.php?page=vote_panel%2Fvote_panel.php&post_id=' . $post_id) ?>"
               class="page-title-action"> بازگشت </a>
        </h2>

        <!--box 1-->
        <div id="postbox-container-1" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="dashboard_right_now" class="postbox">
                    <div class="postbox-header">
                        <h2 class="hndle ui-sortable-handle">
                            <b> لیست رای دهندگان : </b>
                            <a href="<?= esc_url(add_query_arg(array(
                                "export_users" => $_GET['answer_filter']
                            ))); ?>"><b>خروجی : csv</b></a>
                        </h2>
                    </div>
                    <?php

                    ?>
                    <div class="inside">
                        <div class="main">
                            <table class="wp-list-table widefat fixed striped table-view-list posts">
                                <thead>
                                <tr>
                                    <td><label for="vote_answer "> نام </label></td>
                                </tr>
                                </thead>
                                <tbody id="the-list">
                                <?php
                                if (!empty($users_vote_answer[0])) {
                                    foreach ($users_vote_answer as $key => $users) {
                                        ?>
                                        <tr>
                                            <th><span><?= $key + 1 ?> - </span>
                                                <span> <?= $users['display_name'] ?> </span></th>
                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                            </table>
                            <div class="row">
                                <?php
                                for ($i = 1; $i <= ceil(count($all_users_count) / 20); $i++) {
                                    $select = '';
                                    if (isset($_GET['user_all']) and $_GET['user_all'] == $i) {
                                        $select = 'select';
                                    } ?>
                                    <a href="<?= esc_url(add_query_arg(array('user_all' => $i))); ?>"
                                       class="p_number <?= $select ?> "><?= $i ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

