<?php
/*
Plugin Name: جملات بزرگان
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Matt Mullenweg
Version: 1.0.0
Author URI: http://ma.tt/
*/

defined("ABSPATH") || exit("NO ACCESS");

function get_gomalate_bozorgan($param, $content = null)
{
    $jomalat = null;
    $file = plugin_dir_path(__FILE__) . "jomalat.txt";
    if (file_exists($file)) {
        $jomalat = file($file);
        if (count($jomalat) > 0) {
            $jomalat_key = array_rand($jomalat);
            $jomle = "<h4 style='color: #9202b6'>$jomalat[$jomalat_key]</h4>";
            return $jomle;
        }
    }
}

add_shortcode("get_GB", "get_gomalate_bozorgan");


