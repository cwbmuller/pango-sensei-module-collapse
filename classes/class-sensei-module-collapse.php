<?php

if ( ! defined('ABSPATH')) {
    exit;
}

class Sensei_Module_Collapse {
    private $dir;
    private $file;
    private $assets_dir;
    private $assets_url;
    private $order_page_slug;
    public $taxonomy;


    public function __construct($file)
    {
        $this->file = $file;
        $this->dir = dirname($this->file);
        $this->assets_dir = trailingslashit($this->dir).'assets';
        $this->assets_url = esc_url(trailingslashit(plugins_url('/assets/', $this->file)));
        $this->taxonomy = 'collapse';
        $this->order_page_slug = 'module-collapse';

        // Enque CSS and JS scripts
        add_action('sensei_single_course_modules_content', array($this, 'enqueue_module_collapse_scripts'), 10);

        // Remove native Sensei module title and content display
        add_action('sensei_single_course_modules_before', array($this, 'mod_title_remove_action')); // prioroty of 1, but can be anything higher (lower number) then the priority of the action
        add_action('sensei_single_course_modules_content', array($this, 'mod_content_remove_action')); // prioroty of 1, but can be anything higher (lower number) then the priority of the action
        add_action('sensei_single_course_content_inside_after', array($this, 'mod_content_remove_action_new'), 5); // prioroty of 1, but can be anything higher (lower number) then the priority of the action

        // Add collapsible module content display Sensei
        add_action('sensei_single_course_content_inside_after', array($this, 'load_course_module_collapse_content_template'), 8);

        // Add collapsible module title
        add_action('sensei_single_course_modules_before', array($this, 'course_modules_collapse_title'), 21);

    }

    /**
     * Remove native Sensei modules title on single course page
     */
    public function mod_title_remove_action() {
        remove_action('sensei_single_course_modules_before', array(Sensei()->modules, 'course_modules_title'), 20);
    }

    /**
     * Remove native Sensei modules content on single course page
     */
    public function mod_content_remove_action() {
        remove_action('sensei_single_course_modules_content', array(Sensei()->modules, 'course_module_content'), 20);
    }
    /**
     * Remove native Sensei modules content on single course page for Sensei v1.9
     */
    public function mod_content_remove_action_new() {
        remove_action( 'sensei_single_course_content_inside_after', array(Sensei()->modules, 'load_course_module_content_template') , 8 );
    }

    /**
     * Load admin JS
     * @return void
     */
    public function enqueue_module_collapse_scripts() {
        global $wp_version;

            wp_enqueue_style('module-collapse', $this->assets_url.'css/sensei-module-collapse.css', '1.0.0');
            wp_register_script('module-collapsed', $this->assets_url.'js/sensei-module-collapse.js', array(),
                '1.0',
                true);
            wp_enqueue_script('module-collapsed');

    }

    /**
     * Add collapsible Sensei modules content on single course page for Sensei v1.9
     */
    public function load_course_module_collapse_content_template(){


        // load backwards compatible template name if it exists in the users theme
        $located_template= locate_template( Sensei()->template_url . 'single-course/course-modules.php' );
        if( $located_template ){

            Sensei_Templates::get_template( 'single-course/course-modules.php' );
            return;

        }
        // load collapsible Sensei template name if it exists in the users theme
        require ( ABSPATH . 'wp-content/plugins/pango-sensei-module-collapse/templates/collapse-modules.php');

    } // end course_module_content


    /**
     * Show the title modules on the single course template with Collapse All/Expand All links.
     *
     * Function is hooked into sensei_single_course_modules_before.
     *
     * Sensei < V1.9
     *
     * @since 1.8.0
     * @return void
     */
    public function course_modules_collapse_title( ) {

         if( sensei_have_modules() ) {

            if( sensei_module_has_lessons() ) {
                echo '<header><h2>' . esc_html__('Modules', 'woothemes-sensei') . '</h2></header>';
                echo '<div class="listControl"><a class="expandList">' . esc_html__('Expand All', 'pango-sensei-mod-collapse') . '</a> | <a class="collapseList">' . esc_html__('Collapse All', 'pango-sensei-mod-collapse') . '</a></div></br>';
            }
        }
    }
    public function get_setting( $setting_token ){

        // get all settings from sensei
        $settings = Sensei()->settings->get_settings();

        if( empty( $settings )  || ! isset(  $settings[ $setting_token ]  ) ){
            return '';
        }

        return $settings[ $setting_token ];
    }


}