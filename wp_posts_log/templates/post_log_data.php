<style>


    .postbox {
        height: auto !important;
        float: right !important;
        width: 100% !important;
        padding-bottom: 10px;
    }


    div.before_edit {
        width: 45%;
        float: right;
        height: 100%;
        padding: 5px;
        background-color: #f0f0f1;
    }

    div.after_edit {
        width: 45%;
        float: left;
        height: 100%;
        padding: 5px;
        background-color: #f0f0f1;
    }

    .after_edit_list li:nth-child(odd), .before_edit_list li:nth-child(odd) {
        background-color: white;
    }

    .after_edit_list li, .before_edit_list li {
        width: 100% !important;
        float: right;
        margin: 0 !important;
        padding: 5px !important;
    }

    h3 {
        text-align: center;
        font-weight: bold;
        font-size: 18px;
    }

    .before_edit_list span::after, .after_edit_list span::after, .before_edit_list span::before, .after_edit_list span::before {
        content: none !important;
    }

    .post_content_style img {
        width: 80px !important;
        height: 80px;
    }
</style>

<div id="dashboard-widgets-wrap" style="margin: 0">
    <div id="dashboard-widgets" class="metabox-holder">
        <!--box 1-->
        <a href="?page=post_log_panel%2Fpost_log_panel.php" class="btn"> بازگشت </a>
        <div id="postbox-container-1" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="dashboard_right_now" class="postbox">
                    <div class="postbox-header"><h2 class="hndle ui-sortable-handle"><b> تغییرات پست
                                (<span><?= $edited_posts->post_title ?></span>)
                            </b>
                            <b><span>زمان :</span> <span> <?= $edited_posts->edit_date ?>  </span> </b>
                        </h2>
                    </div>
                    <div class="inside">
                        <div class="main">

                            <h3><b><span>عنوان متا : </span><span><?= $edited_posts->meta_key ?></span></b></h3>

                            <div class="before_edit">
                                <h3><b>قبل</b></h3>
                                <ul class="before_edit_list">
                                    <li>
                                        <span><?= $edited_posts->prev_value ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="after_edit">
                                <h3><b> بعد</b></h3>
                                <ul class="after_edit_list">
                                    <li>
                                        <span><?= $edited_posts->next_value ?></span>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
print_r("<pre>");
print_r($two_last_edited_posts);
print_r("<br>");
print_r("<br>");
print_r("</pre>");
?>