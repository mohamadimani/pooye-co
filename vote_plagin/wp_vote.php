<?php
/*
Plugin Name: سیستم رای گیری
Plugin URI: http://www.pooyeco.ir/plugins/vote/
Description: سیستم رای گیری برای پست ها
Author: mani mohamadi
Version: 1.0.0
Author URI: http://www.pooyeco.ir/
*/

//control directory access
defined("ABSPATH") || exit("NOT ACCESS");

//start singleton design
final class wp_vote
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
        register_activation_hook(__FILE__, 'vote_activate');
//        run when plugin deactivate
        register_deactivation_hook(__FILE__, 'vote_deactivate');

//         run (add plugin menu to admin panel) function
        add_action('admin_menu', array($this, 'vote_admin_menu'));
//         run add meta box function
        add_action('add_meta_boxes', array($this, 'wp_vote_box_add'));
//      run save post hook
        add_action('save_post', 'wp_vote_box_page_save');

//        run (auto load class)  function
        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }
        spl_autoload_register(array($this, 'autoload'));
        //      add shortcode
        add_shortcode('voting', 'voting_shortcode_function');
    }

//add metabox to post_page admin
    function wp_vote_box_add()
    {
        add_meta_box('wp_vote_box', 'پلاگین رای گیری', 'wp_vote_box_page', 'post');
    }

//  add plugin menu to admin panel
    public function vote_admin_menu()
    {
        //    plugin menu information
        add_menu_page(
            "سیستم رای گیری ",
            "  سیستم رای گیری",
            "manage_options",
            "vote_panel/vote_panel.php",
            'vote_panel_page',
            "dashicons-editor-ul",
            6
        );
        //    plugin submenu information
        add_submenu_page(
            'vote_admin',
            'سیستم رای گیری',
            'سیستم رای گیری',
            'manage_options',
            'vote_panel/vote_panel.php',
            'vote_panel_page'
        );
        //    plugin submenu information
//        add_submenu_page(
//            'vote_panel/vote_panel.php',
//            'تنظیمات',
//            ' تنظیمات',
//            'manage_options',
//            'vote_panel/vote_setting.php',
//            array($this, 'vote_setting_page'),
//        );

//add scripts and styles to admin page
        wp_register_script("jquery-1.10.1.min.js", VOTE_JS . "jquery-1.10.1.min.js");
        wp_enqueue_script('jquery-1.10.1.min.js');
        wp_register_script("admin2.js", VOTE_JS . "admin2.js", array('jquery'));
        wp_enqueue_script('admin2.js');

        wp_register_style("admin.css", VOTE_CSS . "admin.css");
        wp_enqueue_style('admin.css');


        wp_register_script("persianDatepicker.min.js", VOTE_JS . "datepicker/js/persianDatepicker.min.js", array('jquery'));
        wp_register_style("persianDatepicker-default.css", VOTE_JS . "datepicker/css/persianDatepicker-default.css");

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
        define('VOTE_INC', trailingslashit(WordpressBasePlugin_DIR . "inc"));
        define('VOTE_MTD', trailingslashit(WordpressBasePlugin_DIR . "methods"));
        define('VOTE_TEM', trailingslashit(WordpressBasePlugin_DIR . "templates"));
        define('VOTE_CSS', trailingslashit(WordpressBasePlugin_URL . "assets/css"));
        define('VOTE_JS', trailingslashit(WordpressBasePlugin_URL . "assets/js"));
        define('VOTE_IMG', trailingslashit(WordpressBasePlugin_URL . "assets/images"));
        if (!defined('DB_VERSION')){
            define('DB_VERSION', 1);
        }
        if (!defined('WordpressBasePlugin_VERSION')){
        define('WordpressBasePlugin_VERSION', '1.0.0');
        }
        if (!defined('WordpressBasePlugin_REQUIRED_WP_VERSION')){
        define('WordpressBasePlugin_REQUIRED_WP_VERSION', '5.4');
        }
    }
}

wp_vote::getInstance();


