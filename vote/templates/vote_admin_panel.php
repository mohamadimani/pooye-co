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

<div id="dashboard-widgets-wrap" style="margin: 0">
    <div id="dashboard-widgets" class="metabox-holder">
        <!--box 1-->
        <div id="postbox-container-1" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="dashboard_right_now" class="postbox">
                    <div class="postbox-header"><h2 class="hndle ui-sortable-handle"><b> آمار کلی </b>
                            <a href="<?= esc_url(add_query_arg(array(
                                "export_votes" => 'all'
                            ))); ?>"><b>خروجی : csv</b></a>
                        </h2>
                    </div>
                    <div class="inside">
                        <div class="main">

                            <table class="wp-list-table widefat fixed striped table-view-list posts">
                                <thead>
                                <tr>
                                    <td><label for="vote_answer "> عنوان پست </label></td>
                                    <td><label for="vote_answer "> تعداد رای کل </label></td>
                                    <td><label for="vote_answer "> مشاهده جزئیات </label></td>
                                    <td><label for="vote_answer "> مشاهده پست</label></td>
                                    <td><label for="vote_answer "> ویرایش پست</label></td>
                                </tr>
                                </thead>
                                <tbody id="the-list">
                                <?php
                                foreach ($all_answer_count as $key => $answers) {
                                    $date = explode('-', $answers->post_date);
                                    $date_y = $date[0];
                                    $date_m = $date[1];
                                    $date_d = explode(' ', $date[2]);
                                    $date_d = $date_d[0];

                                    $title = explode(" ", $answers->post_title);
                                    $title = implode("-", $title);

                                    ?>
                                    <tr>
                                        <td>
                                            <span> <?= $answers->post_title ?>  </span>
                                        </td>
                                        <td>
                                            <label for="vote_answer<?= $key ?>"> <?= $answers->answer_count ?>   </label>
                                        </td>
                                        <td>
                                            <a href="<?= esc_url(add_query_arg(array('post_id' => $answers->post_id))); ?>">جزئیات</a>
                                        </td>
                                        <td>
                                            <a href="<?= $date_y . '/' . $date_m . '/' . $date_d . '/' . $title ?>"><span>View</span></a>
                                        </td>
                                        <td>
                                            <a href="post.php?post=<?= $answers->post_id ?>&action=edit">
                                                <span style="content:'\f177';">Edit</span> </a>
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>
                            </table>
                            <div class="row">
                                <?php
                                for ($i = 1; $i <= ceil(count($all_posts_count) / 20); $i++) {
                                    $select = '';
                                    if (isset($_GET['page_all']) and $_GET['page_all'] == $i) {
                                        $select = 'select';
                                    } ?>
                                    <a href="<?= esc_url(add_query_arg(array('page_all' => $i))); ?>"
                                       class="p_number <?= $select ?> "><?= $i ?></a>
                                <?php } ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--box 2-->
        <div id="postbox-container-1" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="dashboard_right_now" class="postbox">
                    <div class="postbox-header"><h2 class="hndle ui-sortable-handle"><b> آمار امروز </b>
                            <a href="<?= esc_url(add_query_arg(array(
                                "export_votes" => 'day'
                            ))); ?>"><b>خروجی : csv</b></a>
                        </h2>
                    </div>
                    <div class="inside">
                        <div class="main">

                            <table class="wp-list-table widefat fixed striped table-view-list posts">
                                <thead>
                                <tr>
                                    <td><label for="vote_answer "> عنوان پست </label></td>
                                    <td><label for="vote_answer "> تعداد رای کل </label></td>
                                    <td><label for="vote_answer "> مشاهده جزئیات </label></td>
                                    <td><label for="vote_answer "> مشاهده پست</label></td>
                                    <td><label for="vote_answer "> ویرایش پست</label></td>
                                </tr>
                                </thead>
                                <tbody id="the-list">

                                <?php

                                foreach ($all_answer_count_tdy as $key => $answers) {
                                    $date = explode('-', $answers->post_date);
                                    $date_y = $date[0];
                                    $date_m = $date[1];
                                    $date_d = explode(' ', $date[2]);
                                    $date_d = $date_d[0];

                                    $title = explode(" ", $answers->post_title);
                                    $title = implode("-", $title);

                                    ?>
                                    <tr>
                                        <td>
                                            <span> <?= $answers->post_title ?>   </span>
                                        </td>
                                        <td>
                                            <label for="vote_answer<?= $key ?>"> <?= $answers->answer_count ?>   </label>
                                        </td>
                                        <td>
                                            <a href="<?= esc_url(add_query_arg(array('post_id' => $answers->post_id))); ?>">جزئیات</a>
                                        </td>
                                        <td>
                                            <a href="<?= $date_y . '/' . $date_m . '/' . $date_d . '/' . $title ?>"><span>View</span></a>
                                        </td>
                                        <td>
                                            <a href="post.php?post=<?= $answers->post_id ?>&action=edit">
                                                <span>Edit</span> </a>
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>
                            </table>
                            <div class="row">
                                <?php
                                for ($i = 1; $i <= ceil(count($t_posts_count) / 20); $i++) {
                                    $select = '';
                                    if (isset($_GET['page_day']) and $_GET['page_day'] == $i) {
                                        $select = 'select';
                                    }
                                    ?>
                                    <a href="<?= esc_url(add_query_arg(array('page_day' => $i))); ?>"
                                       class="p_number <?= $select ?>"><?= $i ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--box 3-->
        <div id="postbox-container-1" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="dashboard_right_now" class="postbox">
                    <div class="postbox-header"><h2 class="hndle ui-sortable-handle"><b> آمار یک هفته اخیر </b>
                            <a href="<?= esc_url(add_query_arg(array(
                                "export_votes" => 'week'
                            ))); ?>"><b>خروجی : csv</b></a>
                        </h2>
                    </div>
                    <div class="inside">
                        <div class="main">

                            <table class="wp-list-table widefat fixed striped table-view-list posts">
                                <thead>
                                <tr>
                                    <td><label for="vote_answer "> عنوان پست </label></td>
                                    <td><label for="vote_answer "> تعداد رای کل </label></td>
                                    <td><label for="vote_answer "> مشاهده جزئیات </label></td>
                                    <td><label for="vote_answer "> مشاهده پست</label></td>
                                    <td><label for="vote_answer "> ویرایش پست</label></td>
                                </tr>
                                </thead>
                                <tbody id="the-list">

                                <?php

                                foreach ($all_answer_count_wdy as $key => $answers) {
                                    $date = explode('-', $answers->post_date);
                                    $date_y = $date[0];
                                    $date_m = $date[1];
                                    $date_d = explode(' ', $date[2]);
                                    $date_d = $date_d[0];

                                    $title = explode(" ", $answers->post_title);
                                    $title = implode("-", $title);

                                    ?>
                                    <tr>
                                        <td>
                                            <span> <?= $answers->post_title ?>   </span>
                                        </td>
                                        <td>
                                            <label for="vote_answer<?= $key ?>"> <?= $answers->answer_count ?>   </label>
                                        </td>
                                        <td>
                                            <a href="<?= esc_url(add_query_arg(array('post_id' => $answers->post_id))); ?>">جزئیات</a>
                                        </td>
                                        <td>
                                            <a href="<?= $date_y . '/' . $date_m . '/' . $date_d . '/' . $title ?>"><span>View</span></a>
                                        </td>
                                        <td>
                                            <a href="post.php?post=<?= $answers->post_id ?>&action=edit">
                                                <span>Edit</span> </a>
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>
                            </table>
                            <div class="row">
                                <?php
                                for ($i = 1; $i <= ceil(count($w_posts_count) / 20); $i++) {
                                    $select = '';
                                    if (isset($_GET['page_week']) and $_GET['page_week'] == $i) {
                                        $select = 'select';
                                    } ?>
                                    <a href="<?= esc_url(add_query_arg(array('page_week' => $i))); ?>"
                                       class="p_number <?= $select ?>"><?= $i ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


