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


    .insert_error {
        width: 100%;
        text-align: center;
        float: right;
        padding: 15px 0;
        border-radius: 3px;
    }

    .insert_error.danger, .insert_error.is, .insert_error.empty {
        background-color: #ffcde4;
        color: #890e17;
    }

    .insert_error.success {
        background-color: #ddffda;
        color: #097c22;
    }

    a.remove_row {
        width: 20px;
        height: 20px;
        cursor: pointer;
        text-align: center;
        background-color: #ffa5a5;
        font-size: 17px;
        border-radius: 3px;
        line-height: 20px;
        display: inline-block;
        text-decoration: none!important;
    }

</style>
<div id="dashboard-widgets-wrap" style="margin: 0">
    <div id="dashboard-widgets" class="metabox-holder">
        <!--box 1-->
        <div id="postbox-container-1" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="dashboard_right_now" class="postbox">
                    <div class="postbox-header"><h2 class="hndle ui-sortable-handle"><b> لیست کلمات فیلتر : </b>
                            <a href="<?= esc_url(add_query_arg(array(
                                "export_votes" => 'all'
                            ))); ?>"> </a>
                        </h2>
                    </div>
                    <?php
                    if (isset($error) and !empty(trim($error))) {
                        ?>
                        <h2 class="insert_error <?= $error ?>"><?= $error_text ?>
                        </h2>
                    <?php } ?>
                    <div class="inside">
                        <div class="main">
                            <form action="" method="post">

                                <table>
                                    <thead>
                                    <tr>
                                        <th>افزودن کلمه جدید :</th>
                                        <th><input type="text" name="filter_letter" class="filter_letter"></th>
                                        <th><input type="submit" value="ثبت" name="filter_submit"></th>
                                    </tr>
                                    <tr>
                                        <th>لیست کلمات :</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (count($spam_letters) > 0) {
                                        foreach ($spam_letters as $key => $spam_letter) {
                                            ?>
                                            <tr>
                                                <td><?= $key + 1 ?></td>
                                                <td> <?= $spam_letter['slug'] ?></td>
                                                <td><a href="<?= esc_url(add_query_arg(array(
                                                        "del_item" =>  $spam_letter['term_id']
                                                    ))); ?>" class="remove_row" >-</a></td>
                                            </tr>
                                        <?php }
                                    } ?>
                                    </tbody>
                                </table>
                                <?php
                                for ($i = 1; $i <= ceil($row_count['row_count'] / 20); $i++) {
                                    $select = '';
                                    if ((isset($_GET['spam_p']) and $_GET['spam_p'] == $i) or (!isset($_GET['spam_p']) and $i == 1)) {
                                        $select = 'select';
                                    } ?>
                                    <a href="<?= esc_url(add_query_arg(array('spam_p' => $i))); ?>"
                                       class="p_number <?= $select ?> "><?= $i ?></a>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>



