<?php

/**
 * @package Logs Display
 * @version 1.3.1
 */

defined('ABSPATH') or exit();

class slwsu_logs_display_admin_init {

    /**
     * 
     */
    public function __construct() {
        $this->admin_page();
    }

    /**
     * 
     */
    public function admin_page() {
        // Page simple
        // include_once plugin_dir_path(__FILE__) . 'page.php';
        // new slwsu_logs_display_admin_page();

        // Page onglets
        include_once plugin_dir_path(__FILE__) . 'panel.php';
        new slwsu_logs_display_admin_panel();
    }

}
