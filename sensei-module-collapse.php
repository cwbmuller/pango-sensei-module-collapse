<?php
/*
 * Plugin Name: Pango Sensei Module Collapse
 * Version: 1.2.6
 * Plugin URI: http://pango.world
 * Description: Add collapsible modules to your Sensei courses
 * Author: Pango
 * Author URI: http://pango.world
 * Requires at least: 3.5
 * Tested up to: 4.4
 * @package WordPress
 * @author Pango
 * @since 1.0.0
 */
if ( ! defined('ABSPATH')) {
    exit;
}

/**
 * Functions used by plugins
 */
if ( ! class_exists('WooThemes_Sensei_Dependencies')) {
    require_once 'woo-includes/class-woothemes-sensei-dependencies.php';
}
/**
 * Sensei Detection
 */
if ( ! function_exists('is_sensei_active')) {
    function is_sensei_active() {
        return WooThemes_Sensei_Dependencies::sensei_active_check();
    }
}
/**
 * Include plugin class
 */
if (is_sensei_active()) {
    require_once('classes/class-sensei-module-collapse.php');

    global $sensei_module_collapse;
    $sensei_module_collapse = new Sensei_Module_Collapse(__FILE__);

    require_once('classes/class-sensei-module-collapse-settings.php');

    global $sensei_module_collapse_settings;
    $sensei_module_collapse_settings = new Sensei_Module_Collapse_Settings(__FILE__);
}
function sensei_collapse_load_scripts() {
    wp_enqueue_style('font-awesome-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' , array(), '1.0.0');
}

add_action('wp_enqueue_scripts', 'sensei_collapse_load_scripts');
