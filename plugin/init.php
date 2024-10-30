<?php

/**
 * @package Logs Display
 * @version 1.3.1
 */

defined('ABSPATH') or exit();

class slwsu_logs_display_plugin_init {

    public function __construct() {
        $this->_init();
    }

    private function _init() {
        if (is_admin()):
            include_once plugin_dir_path(__FILE__) . 'admin/init.php';
            new slwsu_logs_display_admin_init();

            if ('true' === get_option('slwsu_logs_display_show_widget')):
                include_once plugin_dir_path(__FILE__) . 'admin/widget.php';
            endif;
        else:
            include_once plugin_dir_path(__FILE__) . 'front/init.php';
            new slwsu_logs_display_front_init();
        endif;

        include_once plugin_dir_path(__FILE__) . 'logger.php';
    }

}
