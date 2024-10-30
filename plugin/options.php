<?php

/**
 * @package Logs Display
 * @version 1.3.1
 */

defined('ABSPATH') or exit();

class slwsu_logs_display_options {
    
    /**
     * ...
     */
    public static function options() {
        $return = [
            // Options plugin
            'show_widget' => 'true',
            // Options config
            'delete_options' => 'true',
            'grouper' => 'Grouper'
        ];
        return $return;
    }
    
    /**
     * ...
     */
    public static function get_options() {
        $return = [];
        foreach (self::options() as $k => $v):
            $return['slwsu_logs_display_' . $k] = get_option('slwsu_logs_display_' . $k, $v);
        endforeach;
        unset($k, $v);

        return $return;
    }
    
    /**
     * ...
     */
    public static function get_transient() {
        $return = get_transient('slwsu_logs_display_options');
        return $return;
    }
    
    /**
     * ...
     */
    public static function set_transient($aOptions) {
        set_transient('slwsu_logs_display_options', $aOptions, '');
    }

}
