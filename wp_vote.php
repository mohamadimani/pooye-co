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
//      add shortcode
        add_shortcode('voting', array($this, 'voting_shortcode_function'));
//         run (add plugin menu to admin panel) function
        add_action('admin_menu', array($this, 'vote_admin_menu'));
//         run add meta box function
        add_action('add_meta_boxes', array($this, 'wp_vote_box_add'));
//      run save post hook
        add_action('save_post', array($this, 'wp_vote_box_page_save'));
//        run define constants function
        $this->define_constants();
//        run (auto load class)  function
        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }
        spl_autoload_register(array($this, 'autoload'));
    }

//add metabox to post_page admin
    function wp_vote_box_add()
    {
        add_meta_box('wp_vote_box', 'پلاگین رای گیری', array($this, 'wp_vote_box_page'), 'post');
    }

//this function make content for  shortcode in site post page
    function voting_shortcode_function($param)
    {
        global $wpdb;
//        get the current post id
        $post_id = get_the_ID();
//        get the current user id
        $user_id = wp_get_current_user();
//        get vote info from postmeta table and use in in page
        $post_vote_activity = get_post_meta($post_id, 'vote_activity', true);
        $post_vote_question = get_post_meta($post_id, 'vote_question', true);
        $post_vote_answer = get_post_meta($post_id, 'vote_answer', true);

//        get vote inswer id to chet user voted or not
        $user_vote_answer = $wpdb->get_row($wpdb->prepare("SELECT id FROM wp_vote_users_answer where user_id=%d and post_id=%d and vote_key=%s ", $user_id->ID, $post_id, 'user_vote_answer'));

        if (empty(trim($user_vote_answer->id))) {
            if (isset($_POST['user_vote_answer']) and !empty(trim($_POST['user_vote_answer']))) {
                $answer_id = explode('_', $_POST['user_vote_answer']);
//            $value = $user_id->ID . '_' . $answer_id[1];
//            update_post_meta($post_id, 'user_vote_answer', $value);
                $result = $wpdb->insert('wp_vote_users_answer',
                    array(
                        "user_id" => $user_id->ID,
                        "post_id" => $post_id,
                        "vote_key" => 'user_vote_answer',
                        "vote_value" => $answer_id[1],
                    ), array(
                        "%d",
                        "%d",
                        "%s",
                        "%s"
                    ));
            }
            include VOTE_TEM . 'template_vote_box.php';
        } else {
            $all_answer_count = $wpdb->get_row($wpdb->prepare("SELECT count(id) as answer_count FROM `wp_vote_users_answer`  where post_id=%d and vote_key='user_vote_answer' GROUP by post_id ", $post_id));
            $vote_answer_count = $wpdb->get_results($wpdb->prepare("SELECT count(id) as vote_count , vote_value FROM `wp_vote_users_answer`  where post_id=%d and vote_key='user_vote_answer' GROUP by vote_value ", $post_id));
            $answer_val = [];
            foreach ($vote_answer_count as $key => $vote_a) {
                $answer_val[$vote_a->vote_value] = $vote_a->vote_count;
            }
            include VOTE_TEM . 'template_voted_box.php';
        }
    }

// run this function when plugin is activing
    function vote_activate()
    {
        $current_db_version = get_option('vote_db_version');
        if (intval(DB_VERSION) > intval($current_db_version)) {
            $this->vote_create_tables();
        }

    }

//  create vote plugin table when olugin is activing
    function vote_create_tables()
    {
        global $wpdb;
        // [=====create table vote_count=====]

        $vote_view_count = "CREATE TABLE `wp_vote_users_answer` (
          `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `user_id` int(10) UNSIGNED DEFAULT NULL,
          `post_id` int(10) UNSIGNED DEFAULT NULL,
          `vote_key` varchar(200) DEFAULT NULL,
          `vote_value` varchar(200) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        include ABSPATH . 'wp_admin/include/tables.php';

        dbDelta($vote_view_count);
        $current_db_version = update_option('vote_db_version', DB_VERSION);
    }

//  save vote boxinfo in admin post page
    function wp_vote_box_page_save($post_id)
    {
        // Bail if we're doing an auto save
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        // if our current user can't edit this post, bail
        if (!current_user_can('edit_post')) return;

        if (isset($_POST['vote_question'])) {
            update_post_meta($post_id, 'vote_activity', $_POST['vote_activity']);
            update_post_meta($post_id, 'vote_question', $_POST['vote_question']);
            update_post_meta($post_id, 'vote_answer', $_POST['vote_answer']);
        }
    }

//  show vote box in admin post page
    function wp_vote_box_page($post)
    {
//        [=====add plagin box to site post  page but doesn't work=====]♥
//        if (!empty(trim($post_vote_activity))) {
//            add_meta_box('wp_vote_box_page2', 'پلاگین رای گیری', array($this, 'wp_vote_box_page2'), 'page');
//        }

//        get vote info from postmeta table and use in in page
        $post_vote_activity = get_post_meta($post->ID, 'vote_activity', true);
        $post_vote_question = get_post_meta($post->ID, 'vote_question', true);
        $post_vote_answer = get_post_meta($post->ID, 'vote_answer', true);
        include VOTE_TEM . 'vote_box.php';
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

//    show vote manage page in admin panel menu
    public function vote_panel_page()
    {
        global $wpdb;
        if (isset($_GET['post_id']) and !empty($_GET['post_id']) and !isset($_GET['answer_id'])) {
            $post_id = $_GET['post_id'];
            $post_vote_activity = get_post_meta($post_id, 'vote_activity', true);
            $post_vote_answer = get_post_meta($post_id, 'vote_answer', true);
            $vote_answer_count = $wpdb->get_results($wpdb->prepare("SELECT count(id) as vote_count , vote_value FROM `wp_vote_users_answer`  where post_id=%d and vote_key='user_vote_answer' GROUP by vote_value ", $post_id));
            $answer_val = [];
            foreach ($vote_answer_count as $key => $vote_a) {
                $answer_val[$vote_a->vote_value] = $vote_a->vote_count;
            }
            $all_answer_count = $wpdb->get_row($wpdb->prepare("SELECT count(id) as answer_count FROM `wp_vote_users_answer`  where post_id=%d and vote_key='user_vote_answer' GROUP by post_id ", $post_id));

            include VOTE_TEM . 'vote_admin_panel_post.php';
        } else if (isset($_GET['answer_id'])) {
            $answer_id = $_GET['answer_id'];
            $post_id = $_GET['post_id'];
            $answer_title = $_GET['answer_title'];
            $users_vote_answer = $wpdb->get_results($wpdb->prepare("SELECT     v.user_id ,u.display_name 
                                                            FROM `wp_vote_users_answer` v left join wp_users u on v.user_id=u.id 
                                                            where v.post_id=%d  and    v.vote_key='user_vote_answer' and v.vote_value=%d  ", $post_id, $answer_id));
            include VOTE_TEM . 'vote_admin_panel_users.php';
        } else {
            $all_answer_count = $wpdb->get_results($wpdb->prepare("SELECT count(v.id) as answer_count , v.post_id ,p.post_title 
                                                            FROM `wp_vote_users_answer` v left join wp_posts p on v.post_id=p.id 
                                                            where   v.vote_key='user_vote_answer' GROUP by v.post_id "));
            include VOTE_TEM . 'vote_admin_panel.php';
        }
    }////

//    show vote setting page in admin panel menu
    public function vote_setting_page()
    {
//        include VOTE_TEM . 'setting_page.php';
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

//  set defines for shorts access to urls and directorys
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


