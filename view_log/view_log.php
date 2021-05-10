<?php
/*
Plugin Name: view log
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description:  page view log.
Author: mani mohamadi
Version: 1.0.0
Author URI: http://ma.tt/
*/
defined("ABSPATH") || exit("NOT ACCESS");

define('VIEW_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('VIEW_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('VIEW_INC', trailingslashit(VIEW_DIR . "inc"));
define('VIEW_CSS', trailingslashit(VIEW_URL . "assets/css"));
define('VIEW_JS', trailingslashit(VIEW_URL . "assets/js"));
define('VIEW_IMG', trailingslashit(VIEW_URL . "assets/images"));


function view_log_activate()
{

}

function view_log_deactivate()
{

}

register_activation_hook(__FILE__, 'view_log_activate');
register_deactivation_hook(__FILE__, 'view_log_deactivate');

if (is_admin()) {
    include VIEW_INC . "backend.php";
} else {
    include VIEW_INC . "front.php";
}
