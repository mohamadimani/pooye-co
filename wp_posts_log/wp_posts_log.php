<?php
/*
Plugin Name: پست لاگر
Plugin URI: http://www.pooyeco.ir/plugins/vote/
Description: سیستم ثبت تغییرات پست ها
Author: mani mohamadi
Version: 1.0.0
Author URI: http://www.pooyeco.ir/
*/

//control directory access
defined("ABSPATH") || exit("NOT ACCESS");

//start singleton design
final class wp_posts_log
{
    private static $instance = null;

// singleton design pattern method
    public static function getInstance()
    {
        if (null === static::$instance) {
            //..
            static::$instance = new static();
        }
        return self::$instance;
    }

//php magical function  (auto run)
    function __construct()
    {
        //        run define constants function
        $this->define_constants();
        require 'settings.php';

//        run when plugin activate
        register_activation_hook(__FILE__, 'post_log_activate');
//        run when plugin deactivate
        register_deactivation_hook(__FILE__, 'post_log_deactivate');

//         run (add plugin menu to admin panel) function
        add_action('admin_menu', array($this, 'post_log_admin_menu'));


//        run (auto load class)  function
        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }
        spl_autoload_register(array($this, 'autoload'));

//      run insert post hook
//        add_action('wp_insert_post_data', 'insert_post_data', 5, 2);
        add_action('save_post', 'insert_post_data', 5, 2);

        add_action('add_meta_boxes', array($this, 'wp_vote_box_add'));
    }

//for test
    function wp_vote_box_add()
    {
        add_meta_box('wp_meta_box', 'تست متا باکس ها', 'wp_meta_box_page', 'post');
    }

//  add plugin menu to admin panel
    public function post_log_admin_menu()
    {
        //    plugin menu information
        add_menu_page(
            "ثبت تغییرات متا پست",
            "  ثبت تغییرات متا پست",
            "manage_options",
            "post_log_panel/post_log_panel.php",
            'post_log_panel_page',
            "dashicons-editor-ul",
            6
        );
        //    plugin submenu information
        add_submenu_page(
            'post_log_admin',
            'ثبت تغییرات متا پست',
            'ثبت تغییرات متا پست',
            'manage_options',
            'post_log_panel/post_log_panel.php',
            'post_log_panel_page'
        );

//add scripts and styles to admin page
        wp_register_script("jquery-1.10.1.min.js", POST_LOG_JS . "datepicker/js/jquery-1.10.1.min.js");
        wp_register_script("persianDatepicker.min.js", POST_LOG_JS . "datepicker/js/persianDatepicker.min.js", array('jquery'));
        wp_register_style("persianDatepicker-default.css", POST_LOG_JS . "datepicker/css/persianDatepicker-default.css");

        wp_enqueue_script('jquery-1.10.1.min.js');
        wp_enqueue_script("persianDatepicker.min.js");
        wp_enqueue_style("persianDatepicker-default.css");
    }

//    auto load class and make class object
    function autoload($class)
    {
        if (FALSE !== strpos($class, 'vote_')) {
            $class_file_path = VOTE_MTD . strtolower($class) . '.php';
            if (is_file($class_file_path) and file_exists($class_file_path)) {
                include_once $class_file_path;
            }
        }
    }

//  set defines for shorts access to urls and directories
    private function define_constants()
    {
        define('WordpressBasePlugin_DIR', trailingslashit(plugin_dir_path(__FILE__)));
        define('WordpressBasePlugin_URL', trailingslashit(plugin_dir_url(__FILE__)));
        define('POST_LOG_INC', trailingslashit(WordpressBasePlugin_DIR . "inc"));
        define('POST_LOG_MTD', trailingslashit(WordpressBasePlugin_DIR . "methods"));
        define('POST_LOG_TEM', trailingslashit(WordpressBasePlugin_DIR . "templates"));
        define('POST_LOG_CSS', trailingslashit(WordpressBasePlugin_URL . "assets/css"));
        define('POST_LOG_JS', trailingslashit(WordpressBasePlugin_URL . "assets/js"));
        define('POST_LOG_IMG', trailingslashit(WordpressBasePlugin_URL . "assets/images"));

        if (!defined('DB_VERSION')) {
            define('DB_VERSION', 1);
        }
        if (!defined('WordpressBasePlugin_VERSION')) {
            define('WordpressBasePlugin_VERSION', '1.0.0');
        }
        if (!defined('WordpressBasePlugin_REQUIRED_WP_VERSION')) {
            define('WordpressBasePlugin_REQUIRED_WP_VERSION', '5.4');
        }
    }
}

wp_posts_log::getInstance();


