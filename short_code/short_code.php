<?php
/*
Plugin Name: short codes
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Matt Mullenweg
Version: 1.0.0
Author URI: http://ma.tt/
*/

defined("ABSPATH") || exit("NO ACCESS");

function s_shortcode_function($param = [])
{
    $param = shortcode_atts(['name' => 'no name', 'age' => 0], $param);
    return "سلام  " . $param ['name'] . " . اولین شرت کد  در   " . $param['age'] . 'سالگی';
}

add_shortcode("simple_short_code", "s_shortcode_function");


function get_shortcode_tag($params, $contents)
{
    if (is_user_logged_in()) {
        return "$contents";
    }else{
        return "این پست مخصوص اعضای سایت میباشد!";
    }
}

add_shortcode('shortcode_tag', 'get_shortcode_tag');