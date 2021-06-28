<style>
    .vote_table_row {
        width: 50%;
        display: block;
        /*float: right;*/
        direction: rtl;
    }
</style>
<div id="dashboard-widgets-wrap" style="margin: 0">
    <div id="dashboard-widgets" class="metabox-holder">
        <h2> جزئیات سوال ( <?= $post_vote_question ?>)
            <a href="<?= admin_url('admin.php?page=vote_panel%2Fvote_panel.php') ?>" class="page-title-action">
                بازگشت </a>
        </h2>

        <!--box 1-->
        <div id="postbox-container-1" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="dashboard_right_now" class="postbox">
                    <div class="postbox-header">
                        <h2 class="hndle ui-sortable-handle">
                            <b> آمار کلی </b>
                            <a href="<?= esc_url(add_query_arg(array(
                                "export_post" => 'all'
                            ))); ?>"><b>خروجی : csv</b></a>
                        </h2>
                    </div>
                    <div class="inside">
                        <div class="main">

                            <table class="wp-list-table widefat fixed striped table-view-list posts">
                                <thead>
                                <tr>
                                    <td><label for="vote_answer "> پاسخ ها </label></td>
                                    <td><label for="vote_answer "> درصد انتخاب </label></td>
                                    <td><label for="vote_answer "> مشاهده رای دهندگان </label></td>

                                </tr>
                                </thead>
                                <tbody id="the-list">

                                <?php
//                                $out = ob_get_contents();
//                                ob_end_flush();

                                if (!empty($post_vote_answer[0])) {
                                    foreach ($post_vote_answer as $key => $answer) {
                                        if (!empty(trim($all_answer_count->answer_count))) {
                                            $anser_c = $all_answer_count->answer_count;
                                        } else {
                                            $anser_c = 1;
                                        }

                                        ?>
                                        <tr>
                                            <td><span><?= $key + 1 ?> - </span> <label> <?= $answer ?> : </label>
                                            </td>
                                            <td style="text-align: center">
                                                <span><?= round(($answer_val[$key] * 100) / $anser_c, 1) . '%' . ' (' . intval($answer_val[$key]) . ') ' ?></span>
                                            </td>
                                            <td><a href="<?= esc_url(add_query_arg(array(
                                                    'answer_id' => $key,
                                                    'answer_title' => $answer,
                                                    'answer_filter' => 'all'
                                                ))); ?>">لیست رای دهندگان</a></td>
                                        </tr>
                                    <?php }

                                } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--box 2-->
        <div id="postbox-container-1" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="dashboard_right_now" class="postbox">
                    <div class="postbox-header"><h2 class="hndle ui-sortable-handle">
                            <b> آمار روز</b>
                            <a href="<?= esc_url(add_query_arg(array(
                                "export_post" => 'day'
                            ))); ?>"><b>خروجی : csv</b></a>
                        </h2>
                    </div>
                    <div class="inside">
                        <div class="main">

                            <table class="wp-list-table widefat fixed striped table-view-list posts">
                                <thead>
                                <tr>
                                    <td><label for="vote_answer "> پاسخ ها </label></td>
                                    <td><label for="vote_answer "> درصد انتخاب </label></td>
                                    <td><label for="vote_answer "> مشاهده رای دهندگان </label></td>

                                </tr>
                                </thead>
                                <tbody id="the-list">

                                <?php
                                if (!empty($post_vote_answer[0])) {
                                    foreach ($post_vote_answer as $key => $answer) {
                                        if (!empty(trim($all_answer_count_tdy->answer_count))) {
                                            $anser_w = $all_answer_count_tdy->answer_count;
                                        } else {
                                            $anser_w = 1;
                                        }
                                        ?>
                                        <tr>
                                            <td><span><?= $key + 1 ?> - </span> <label> <?= $answer ?> : </label>
                                            </td>
                                            <td style="text-align: center">
                                                <span><?= round(($answer_val_t[$key] * 100) / $anser_w, 1) . '%' . ' (' . intval($answer_val_t[$key]) . ') ' ?></span>
                                            </td>
                                            <td><a href="<?= esc_url(add_query_arg(array(
                                                    'answer_id' => $key,
                                                    'answer_title' => $answer,
                                                    'answer_filter' => 'day'
                                                ))); ?>">لیست رای دهندگان</a></td>
                                        </tr>
                                    <?php }

                                } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--box 3-->
        <div id="postbox-container-1" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="dashboard_right_now" class="postbox">
                    <div class="postbox-header">
                        <h2 class="hndle ui-sortable-handle">
                            <b> آمار یک هفته </b>
                            <a href="<?= esc_url(add_query_arg(array(
                                "export_post" => 'week'
                            ))); ?>"><b>خروجی : csv</b></a>
                        </h2>
                    </div>
                    <div class="inside">
                        <div class="main">

                            <table class="wp-list-table widefat fixed striped table-view-list posts">
                                <thead>
                                <tr>
                                    <td><label for="vote_answer "> پاسخ ها </label></td>
                                    <td><label for="vote_answer "> درصد انتخاب </label></td>
                                    <td><label for="vote_answer "> مشاهده رای دهندگان </label></td>

                                </tr>
                                </thead>
                                <tbody id="the-list">

                                <?php
                                if (!empty($post_vote_answer[0])) {
                                    foreach ($post_vote_answer as $key => $answer) {
                                        if (!empty(trim($all_answer_count_wdy->answer_count))) {
                                            $anser_d = $all_answer_count_wdy->answer_count;
                                        } else {
                                            $anser_d = 1;
                                        }
                                        ?>
                                        <tr>
                                            <td><span><?= $key + 1 ?> - </span> <label> <?= $answer ?> : </label>
                                            </td>
                                            <td style="text-align: center">
                                                <span><?= round(($answer_val_w[$key] * 100) / $anser_d, 1) . '%' . ' (' . intval($answer_val_w[$key]) . ') ' ?></span>
                                            </td>
                                            <td><a href="<?= esc_url(add_query_arg(array(
                                                    'answer_id' => $key,
                                                    'answer_title' => $answer,
                                                    'answer_filter' => 'week'
                                                ))); ?>">لیست رای دهندگان</a></td>
                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


