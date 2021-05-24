<?php
/*
Plugin Name: سیستم رای گیری
Plugin URI: http://www.pooyeco.ir/plugins/vote /
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
//        run when plugin activate
        register_activation_hook(__FILE__, array($this, 'vote_activate'));
//        run when plugin deactivate
        register_deactivation_hook(__FILE__, array($this, 'vote_deactivate'));
//         run (add plugin menu to admin panel) function
        add_action('admin_menu', array($this, 'vote_admin_menu'));
//         run add meta box function
        add_action('add_meta_boxes', array($this, 'wp_vote_box_add'));

//        run define constants function
        $this->define_constants();
//        run (auto load class)  function
        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }
        spl_autoload_register(array($this, 'autoload'));
    }

    function wp_vote_box_add()
    {
        add_meta_box('wp_vote_box', 'پلاگین رای گیری', array($this, 'wp_vote_box_page'), 'post');
        add_meta_box('wp_vote_box_page', 'پلاگین رای گیری', array($this, 'wp_vote_box_page'), 'page');
    }

    function wp_vote_box_page_save($post_id)
    {
        update_post_meta($post_id, 'vote_question', 'mani mohamadi2');
        update_post_meta($post_id, 'vote_answer', '1-2-3-4-5-6-7-8-9-0-3');
        update_post_meta(66, 'vote_question', 'mani mohamadi1');
        update_post_meta(66, 'vote_answer', '1-2-3-4-5-6-7-8-9-0-6');

//        // Bail if we're doing an auto save
//        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
//
//        // if our nonce isn't there, or we can't verify it, bail
//        if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'my_meta_box_nonce')) return;
//
//        // if our current user can't edit this post, bail
//        if (!current_user_can('edit_post')) return;
//
//        $allowed = array(
//            'a' => array( // on allow a tags
//                'href' => array() // and those anchors can only have href attribute
//            )
//        );
//
//        // Make sure your data is set before trying to save it
//        if (isset($_POST['my_meta_box_text']))
//            update_post_meta($post_id, 'my_meta_box_text', wp_kses($_POST['my_meta_box_text'], $allowed));
//
//        if (isset($_POST['my_meta_box_select']))
//            update_post_meta($post_id, 'my_meta_box_select', esc_attr($_POST['my_meta_box_select']));
//
//        // This is purely my personal preference for saving check-boxes
//        $chk = isset($_POST['my_meta_box_check']) && $_POST['my_meta_box_select'] ? 'on' : 'off';
//        update_post_meta($post_id, 'my_meta_box_check', $chk);
    }

    function wp_vote_box_page($post)
    {
//           save post hook
        add_action('save_post', array($this, 'wp_vote_box_page_save'));
//        update_post_meta($post->ID, 'vote_question', 'mani mohamadi');
//        update_post_meta($post->ID, 'vote_answer', '1-2-3-4-5-6-7-8-9-0-4');
        $post_vote_question = get_post_meta($post->ID, 'vote_question', true);
        $post_vote_answer = get_post_meta($post->ID, 'vote_answer', true);
        $post_vote_answer = explode('-', $post_vote_answer);
        include VOTE_TEM . 'vote_box.php';
    }

//add plugin menu to admin panel
    public function vote_admin_menu()
    {
        //    plugin menu information
        add_menu_page(
            "سیستم رای گیری ",
            "  سیستم رای گیری",
            "manage_options",
            "vote_panel/vote_panel.php",
            array($this, 'vote_panel_page'),
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
            array($this, 'vote_panel_page'),
        );
        //    plugin submenu information
        add_submenu_page(
            'vote_panel/vote_panel.php',
            'تنظیمات',
            ' تنظیمات',
            'manage_options',
            'vote_panel/vote_setting.php',
            array($this, 'vote_setting_page'),
        );
//add scripts and styles to admin page
        wp_register_script("jquery-1.10.1.min.js", VOTE_JS . "jquery-1.10.1.min.js");
        wp_enqueue_script('jquery-1.10.1.min.js');
        wp_register_script("admin2.js", VOTE_JS . "admin2.js", array('jquery'));
        wp_enqueue_script('admin2.js');

        wp_register_style("admin.css", VOTE_CSS . "admin.css");
        wp_enqueue_style('admin.css');

    }

    public function vote_panel_page()
    {
//        vote_admin_panel::save_vote();
//        include VOTE_TEM . 'vote_box.php';
    }

    public function vote_setting_page()
    {
        include VOTE_TEM . 'setting_page.php';
    }

//    auto load class and make class object
    function autoload($class)
    {
        if (FALSE !== strpos($class, 'vote_')) {
            $class_file_path = VOTE_CLASS . strtolower($class) . '.php';
            if (is_file($class_file_path) and file_exists($class_file_path)) {
                include_once $class_file_path;
            }
        }
    }

//set defines for shorts access to urls and directorys
    private function define_constants()
    {
        define('VOTE_DIR', trailingslashit(plugin_dir_path(__FILE__)));
        define('VOTE_URL', trailingslashit(plugin_dir_url(__FILE__)));
        define('VOTE_INC', trailingslashit(VOTE_DIR . "inc"));
        define('VOTE_CLASS', trailingslashit(VOTE_DIR . "classes"));
        define('VOTE_TEM', trailingslashit(VOTE_DIR . "templates"));
        define('VOTE_CSS', trailingslashit(VOTE_URL . "assets/css"));
        define('VOTE_JS', trailingslashit(VOTE_URL . "assets/js"));
        define('VOTE_IMG', trailingslashit(VOTE_URL . "assets/images"));
        define('DB_VERSION', 1);
    }
}

wp_vote::getInstance();

