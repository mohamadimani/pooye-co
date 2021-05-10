<div id="dashboard-widgets-wrap">
    <div id="dashboard-widgets" class="metabox-holder">
        <div id="postbox-container-1" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">

                <div id="dashboard_right_now" class="postbox">
                    <div class="postbox-header"><h2 class="hndle ui-sortable-handle"><b> آمار کلی سایت </b></h2>
                    </div>
                    <div class="inside">
                        <div class="main">


                            <table class="wp-list-table widefat fixed striped table-view-list posts">
                                <tbody id="the-list">

                                <tr id="post-51"
                                    class="iedit author-self level-0 post-51 type-post status-publish format-standard hentry category-1">

                                    <td class="title column-title has-row-actions column-primary page-title"
                                        data-colname="عنوان"><strong> بازدیدکننده کل : </strong>
                                    </td>
                                    <td class="title column-title has-row-actions column-primary page-title"
                                        data-colname="عنوان"><strong><?= $total_uniq_visit->u_v ?> </strong>
                                    </td>
                                </tr>
                                <tr id="post-51"
                                    class="iedit author-self level-0 post-51 type-post status-publish format-standard hentry category-1">

                                    <td class="title column-title has-row-actions column-primary page-title"
                                        data-colname="عنوان"><strong> بازدید کل : </strong>
                                    </td>
                                    <td class="title column-title has-row-actions column-primary page-title"
                                        data-colname="عنوان"><strong><?= $total_uniq_visit->t_v ?> </strong>
                                    </td>
                                </tr>

                                <tr id="post-51"
                                    class="iedit author-self level-0 post-51 type-post status-publish format-standard hentry category-1">

                                    <td class="title column-title has-row-actions column-primary page-title"
                                        data-colname="عنوان"><strong> بازدیدکننده امروز : </strong>
                                    </td>
                                    <td class="title column-title has-row-actions column-primary page-title"
                                        data-colname="عنوان"><strong><?= $total_uniq_visit_today->u_v_tody ?> </strong>
                                    </td>
                                </tr>
                                <tr id="post-51"
                                    class="iedit author-self level-0 post-51 type-post status-publish format-standard hentry category-1">

                                    <td class="title column-title has-row-actions column-primary page-title"
                                        data-colname="عنوان"><strong> بازدید امروز : </strong>
                                    </td>
                                    <td class="title column-title has-row-actions column-primary page-title"
                                        data-colname="عنوان"><strong><?= $total_uniq_visit_today->t_v_tody ?> </strong>
                                    </td>
                                </tr>

                                <tr id="post-51"
                                    class="iedit author-self level-0 post-51 type-post status-publish format-standard hentry category-1">

                                    <td class="title column-title has-row-actions column-primary page-title"
                                        data-colname="عنوان"><strong> بازدیدکننده دبروز : </strong>
                                    </td>
                                    <td class="title column-title has-row-actions column-primary page-title"
                                        data-colname="عنوان"><strong><?= $total_uniq_visit_ydy->u_v_ydy ?> </strong>
                                    </td>
                                </tr>
                                <tr id="post-51"
                                    class="iedit author-self level-0 post-51 type-post status-publish format-standard hentry category-1">

                                    <td class="title column-title has-row-actions column-primary page-title"
                                        data-colname="عنوان"><strong> بازدید دیروز : </strong>
                                    </td>
                                    <td class="title column-title has-row-actions column-primary page-title"
                                        data-colname="عنوان"><strong><?= $total_uniq_visit_ydy->t_v_ydy ?> </strong>
                                    </td>
                                </tr>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>