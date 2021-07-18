<style>

    div.row {
        padding: 5px 3px;
    }

    .last_edit_row {
        list-style: none;
        float: right;
        display: block;
        width: 100%;
    }

    .last_edit_row_item {
        display: block;
        width: 100% !important;
        height: 100px;
        background-color: #f0f0f1;
        float: right;
    }

    .postbox {
        height: auto !important;
        float: right !important;
        width: 100% !important;
    }

    .inside {

    }

    div.img img {
        border-radius: 50%;
        width: 100%;
        height: 100%;
    }

    div.img {
        width: 20%;
        float: right;
        height: 90px;
        padding: 3px;
    }

    div.info {
        width: 78%;
        float: left;
        height: 100%;
    }

    .edit_post_info li {
        width: 100% !important;
        float: right;
    }

    .edit_post_info {
        line-height: 30px;
    }

    .date_time::before, .user_name::before {
        content: "" !important;
    }

    .user_name {
        float: right;
    }

    .last_edit_row_item_a::before {
        content: none !important;
    }

    .date_date {
        padding: 10px 0;
        float: right;
    }

</style>

<div id="dashboard-widgets-wrap" style="margin: 0">
    <div id="dashboard-widgets" class="metabox-holder">
        <!--box 1-->
        <div id="postbox-container-1" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="dashboard_right_now" class="postbox">
                    <div class="postbox-header"><h2 class="hndle ui-sortable-handle"><b>لیست آخرین تغییرات پست ها </b>
                            <a href="<?= esc_url(add_query_arg(array(
                                "export_votes" => 'all'
                            ))); ?>"><b> </b></a>
                        </h2>
                    </div>
                    <div class="inside">
                        <div class="main">

                            <form action="" method="post">
                                <b>
                                    <label for="from_date">
                                        <span> + آخرین تغییرات از تاریخ :</span>
                                        <span> <?php if (isset($_POST["from_date"])) {
                                                echo $_POST["from_date"];
                                            } else {
                                                echo date('Y/m/d', time());
                                            } ?></span>
                                    </label>
                                    <input type="date" required name="from_date" id="from_date" class="perdate">
                                    <input type="submit" value="فیلتر">
                                </b>
                            </form>

                            <div class="row">
                                <ul class="last_edit_row">
                                    <?php

                                    foreach ($two_last_edited_posts as $post) {
                                        $date = explode(' ', $post->edit_date);
                                        ?>
                                        <li class="last_edit_row_item">
                                            <a href="<?= esc_url(add_query_arg(array(
                                                "log_id" => $post->id,
                                            ))); ?>" class="last_edit_row_item_a">
                                                <div class="row">
                                                    <div class="img">
                                                        <img src="https://vinesh.ir/wp-content/uploads/ultimatemember/1115/profile_photo-190x190.jpg"
                                                             alt="">
                                                    </div>
                                                    <div class="info">
                                                        <ul class="edit_post_info">
                                                            <li>
                                                                <span class="user_name"> <?= get_my_author_name($post->user_id) ?> </span>
                                                                -
                                                                <span class="date_time"> <?= $date[1] ?> </span>
                                                            </li>
                                                            <li><p class="post_title"><b> <?= $post->post_title ?></b>
                                                                </p>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(" #from_date11 ").persianDatepicker();

</script>