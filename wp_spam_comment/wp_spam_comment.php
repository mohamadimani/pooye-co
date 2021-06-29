<?php
/*
Plugin Name: کامنت اسپم
Plugin URI: http://www.pooyeco.ir/plugins/SPAM/
Description: سیستم فیلتر کامنت های اسپم
Author: mani mohamadi
Version: 1.0.0
Author URI: http://www.pooyeco.ir/
*/

//control directory access
defined("ABSPATH") || exit("NOT ACCESS");

//start singleton design
final class wp_spam_comment
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
        register_activation_hook(__FILE__, 'spam_activate');
//        run when plugin deactivate
        register_deactivation_hook(__FILE__, 'spam_deactivate');

//         run (add plugin menu to admin panel) function
        add_action('admin_menu', array($this, 'spam_admin_menu'));

//        run (auto load class)  function
        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }
        spl_autoload_register(array($this, 'autoload'));

        add_action('wp_insert_comment', 'spam_comment_function' , 10 , 2);
    }

//  add plugin menu to admin panel
    public function spam_admin_menu()
    {
        //    plugin menu information
        add_menu_page(
            "  فیلتر کامنت ها ",
            "    فیلتر کامنت ها ",
            "manage_options",
            "spam_panel/spam_panel.php",
            'spam_panel_page',
            "dashicons-shield",
            7
        );
        //    plugin submenu information
        add_submenu_page(
            'spam_admin',
            '  فیلتر کامنت ها ',
            '   فیلتر کامنت ها',
            'manage_options',
            'spam_panel/spam_panel.php',
            'spam_panel_page'
        );

//      add scripts and styles to admin page
        wp_register_script("jquery-1.10.1.min.js2", SPAM_JS . "jquery-1.10.1.min.js");
        wp_enqueue_script('jquery-1.10.1.min.js2');
    }

//    auto load class and make class object
    function autoload($class)
    {
        if (FALSE !== strpos($class, 'spam_')) {
            $class_file_path = SPAM_MTD . strtolower($class) . '.php';
            if (is_file($class_file_path) and file_exists($class_file_path)) {
                include_once $class_file_path;
            }
        }
    }

//  set defines for shorts access to urls and directories
    private function define_constants()
    {
        define('spam_DIR', trailingslashit(plugin_dir_path(__FILE__)));
        define('spam_URL', trailingslashit(plugin_dir_url(__FILE__)));
        define('SPAM_INC', trailingslashit(spam_DIR . "inc"));
        define('SPAM_MTD', trailingslashit(spam_DIR . "methods"));
        define('SPAM_TEM', trailingslashit(spam_DIR . "templates"));
        define('SPAM_CSS', trailingslashit(spam_URL . "assets/css"));
        define('SPAM_JS', trailingslashit(spam_URL . "assets/js"));
        define('SPAM_IMG', trailingslashit(spam_URL . "assets/images"));
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

wp_spam_comment::getInstance();


