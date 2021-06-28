<?php

require VOTE_MTD . 'vote_function.php';
require VOTE_MTD . 'vote_shortcodes.php';


//    $images = $wpdb->get_results($wpdb->prepare("SELECT img.id , img.post_mime_type , img.guid , img.post_excerpt as  img_introduction   , img.post_title , img.post_content ,p_meta.*  FROM `wp_posts` img   right join wp_postmeta p_meta on  img.id=p_meta.post_id    where img.post_type='attachment'  " . $where_post), ARRAY_A);

