<?php
//if give post id to this function return that post meta data but don't give any post id return all images meta data that exist in posts table
function get_meta_data($post_id = ' ')
{
    global $wpdb;

//    $post_id = 167;  // get post id for test
    $where_post = '';
    if (empty(trim($post_id)) or !is_numeric($post_id)) {
        $where_post = ' ';
    } else {
        $where_post = ' and  ID=' . $post_id . ' ';
    }

    //get images from posts table
    $images = $wpdb->get_results($wpdb->prepare("SELECT  ID , post_mime_type , guid , post_excerpt   , post_title , post_content  ,post_date  FROM `wp_posts`  where  post_type='attachment'  " . $where_post), ARRAY_A);
    $images_meta = [];
    foreach ($images as $key => $img) {
        $id = $img['ID'];
    //get images meta data  from post meta table
        $all_images = $wpdb->get_results($wpdb->prepare("SELECT  *  FROM `wp_postmeta`   where   post_id=$id  "), ARRAY_A);
        $img['meta'] = $all_images;
        $images_meta[] = $img;
    }
    ?>
<!--   create table for sho results -->
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <style>
            table {
                width: 100%;
                float: right;
                display: block;
                border: 1px solid silver;
            }

            table thead tr:nth-child(odd) {
                background-color: #beb4b3;
            }


            table tbody tr:nth-child(odd) {
                background-color: #ffe3ff;
            }

            table tbody tr:nth-child(even) {
                background-color: #b8ecff;
            }


            table tr {
                width: 100%;
                float: right;
                display: block;
                border: 1px solid silver;
            }
        </style>
    </head>
    <body>
    <table>
        <thead>
        </thead>
        <tbody>
        <?php foreach ($images_meta as $key => $image) {
            ?>
            <tr>
                <td> img_id (شناسه تصویر):</td>
                <td><?php print_r($image['ID']); ?></td>
            </tr>
            <tr>
                <td>post_mime_type (پسوند تصویر):</td>
                <td><?php print_r($image['post_mime_type']); ?></td>
            </tr>
            <tr>
                <td>guid (آدرس تصویر):</td>
                <td><?php print_r($image['guid']); ?></td>
            </tr>
            <tr>
                <td>post_excerpt (توضیح کوتاه):</td>
                <td><?php print_r($image['post_excerpt']); ?></td>
            </tr>
            <tr>
                <td>post_title (عنوان):</td>
                <td><?php print_r($image['post_title']); ?></td>
            </tr>
            <tr>
                <td>post_content (متن جایگزین):</td>
                <td><?php print_r($image['post_content']); ?></td>
            </tr>
            <tr>
                <td>post_date (تاریخ بارگذاری):</td>
                <td><?php print_r($image['post_date']); ?></td>
            </tr>
            <tr>
                <td>meta :</td>
                <td><?php
                    foreach ($image['meta'] as $meta) {
                        ?>
                        <table>
                            <tr>
                                <td>meta_key (<?php if ($meta['meta_key'] == '_wp_attached_file') {
                                        echo 'نام تصویر';
                                    } elseif ($meta['meta_key'] == '_wp_attachment_image_alt') {
                                        echo 'متن جایگزین';
                                    } elseif ($meta['meta_key'] == '_edit_last') {
                                        echo '  وضعیت ویرایش ';
                                    } elseif ($meta['meta_key'] == '_edit_lock') {
                                        echo '    تاریخ ویرایش ';
                                    } ?>):
                                </td>
                                <td> <?= $meta['meta_key'] ?></td>
                            </tr>
                            <tr>
                                <td>meta_value :</td>
                                <td> <?php
                                    if ($meta['meta_key'] == '_wp_attachment_metadata') {
                                        $meta_val = unserialize($meta['meta_value']);
                                        ?>
                                        <table>
                                            <tr>
                                                <td>width (عرض تصویر) :</td>
                                                <td> <?= $meta_val['width'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>height (ارتفاع تصویر) :</td>
                                                <td> <?= $meta_val['height'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>file (نام تصویر):</td>
                                                <td> <?= $meta_val['file'] ?></td>
                                            </tr>

                                            <tr>
                                                <td>size medium file (نام تصویر متوسط) :</td>
                                                <td> <?= $meta_val['sizes']['medium']['file'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>size medium height (ارتفاع تصویر متوسط) :</td>
                                                <td> <?= $meta_val['sizes']['medium']['height'] ?></td>
                                            </tr>

                                            <tr>
                                                <td>size medium width (عرض تصویر متوسط) :</td>
                                                <td> <?= $meta_val['sizes']['medium']['width'] ?></td>
                                            </tr>

                                            <tr>
                                                <td>size medium mime-type (فرمت تصویر متوسط) :</td>
                                                <td> <?= $meta_val['sizes']['medium']['mime-type'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>size thumbnail file (نام تصویر کوچک):</td>
                                                <td> <?= $meta_val['sizes']['thumbnail']['file'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>size thumbnail height (ارتفاع تصویر کوچک) :</td>
                                                <td> <?= $meta_val['sizes']['thumbnail']['height'] ?></td>
                                            </tr>

                                            <tr>
                                                <td>size thumbnail width (عرض تصویر کوچک):</td>
                                                <td> <?= $meta_val['sizes']['thumbnail']['width'] ?></td>
                                            </tr>

                                            <tr>
                                                <td>size thumbnail mime-type (فرمت تصویر کوچک) :</td>
                                                <td> <?= $meta_val['sizes']['thumbnail']['mime-type'] ?></td>
                                            </tr>
                                        </table>
                                    <?php } else {
                                        print_r($meta['meta_value']);
                                    } ?>
                                </td>
                            </tr>
                        </table>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td> ----------------------------------------------------</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    </body>
    </html>
<?php } ?>

