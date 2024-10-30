<?php
/**
 * @package Logs Display
 * @version 1.3.1
 */

defined('ABSPATH') or exit();

include_once plugin_dir_path(__FILE__) . 'form.php';

class slwsu_logs_display_admin_panel {

    /**
     *
     */
    public function __construct() {
        $this->_init();
    }

    /**
     *
     */
    private function _init() {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'admin_settings'));
        add_action('admin_head', array($this, 'admin_css'));
    }

    /**
     * ...
     */
    public function admin_menu() {
        global $GROUPER_LOGS_DISPLAY;
        if (is_object($GROUPER_LOGS_DISPLAY)):
            // Grouper
            $GROUPER_LOGS_DISPLAY->add_admin_menu();
            add_submenu_page($GROUPER_LOGS_DISPLAY->grp_id, 'Logs Display', 'Logs Display', 'manage_options', 'logs-display', array($this, 'admin_page'));
        else:
            add_menu_page('Logs Display', 'Logs Display', 'activate_plugins', 'logs-display', array($this, 'admin_page'));
        endif;
    }

    /**
     * ...
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <?php
            slwsu_logs_display_admin_form::action();
            echo '<h1>Logs Display</h1>';
            slwsu_logs_display_admin_form::validation();
            slwsu_logs_display_admin_form::message($_POST);

            $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'config';
            ?>
            <h2 class = "nav-tab-wrapper">
                <a href="?page=logs-display&tab=config" class="nav-tab<?php echo ('config' === $active_tab) ? ' nav-tab-active' : ''; ?>">Config</a>
                <a href="?page=logs-display&tab=grouper" class="nav-tab<?php echo ('grouper' === $active_tab) ? ' nav-tab-active' : ''; ?>">Grouper</a>
            </h2>

            <form method="post" action="options.php">
                <?php
                if ($active_tab == 'config'):
                    do_settings_sections('slwsu_logs_display_options');
                    settings_fields('slwsu_logs_display_settings');
                elseif ($active_tab == 'grouper') :
                    do_settings_sections('slwsu_logs_display_grouper_options');
                    settings_fields('slwsu_logs_display_grouper_settings');
                else:
                    echo '</br /> Erreur !';
                endif;

                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     *
     */
    public function admin_settings() {
        // Section plugin
        add_settings_section(
                'slwsu_logs_display_section_plugin', __('Configuration', 'lodi'), array($this, 'section_plugin'), 'slwsu_logs_display_options'
        );

        // ...
        add_settings_field(
                'slwsu_logs_display_show_widget', __('Show widget', 'lodi'), array($this, 'show_widget'), 'slwsu_logs_display_options', 'slwsu_logs_display_section_plugin'
        );
        register_setting(
                'slwsu_logs_display_settings', 'slwsu_logs_display_show_widget'
        );
        
        
        

        // Section options
        add_settings_section(
                'slwsu_logs_display_section_options', __('Deactivation', 'lodi'), array($this, 'section_options'), 'slwsu_logs_display_options'
        );

        // ...
        add_settings_field(
                'slwsu_logs_display_delete_options', __('Delete options', 'lodi'), array($this, 'delete_options'), 'slwsu_logs_display_options', 'slwsu_logs_display_section_options'
        );
        register_setting(
                'slwsu_logs_display_settings', 'slwsu_logs_display_delete_options'
        );

        /**
         * Support GRP
         */
        if ('true' === get_option('slwsu_is_active_grouper', 'false')):
            // Section grouper
            add_settings_section(
                    'slwsu_logs_display_section_grouper', __('Group', 'lodi'), array($this, 'section_grouper'), 'slwsu_logs_display_grouper_options'
            );
            // ...
            add_settings_field(
                    'slwsu_logs_display_grouper', __('Plugin Group', 'lodi'), array($this, 'grouper_nom'), 'slwsu_logs_display_grouper_options', 'slwsu_logs_display_section_grouper'
            );
            register_setting(
                    'slwsu_logs_display_grouper_settings', 'slwsu_logs_display_grouper'
            );
        else:
            // Section NO grouper
            add_settings_section(
                    'slwsu_logs_display_section_grouper', __('Grouper', 'ptro'), array($this, 'section_grouper_no'), 'slwsu_logs_display_grouper_options'
            );
        endif;
    }

    /**
     * Plugin
     */
    public function section_plugin() {
        echo __('This section concerns the configuration of the plugin', 'lodi') . '&nbsp;<strong><i>Logs Display</i></strong>';
    }

    public function show_widget() {
        ?>
        <?= __('Activate', 'lodi'); ?> <input name="slwsu_logs_display_show_widget" type="radio" value="true" <?php checked('true', get_option('slwsu_logs_display_show_widget')); ?> />
        &nbsp;&nbsp;&nbsp;
        <?= __('Deactivate', 'lodi'); ?> <input name="slwsu_logs_display_show_widget" type="radio" value="false" <?php checked('false', get_option('slwsu_logs_display_show_widget')); ?> />
        <p class="description"><?= __('Enable admin widget logs dashboard.', 'lodi'); ?></p>
        <?php
    }

    /**
     * Options
     */
    public function section_options() {
        echo __('This section is about saving plugin options of', 'lodi') . '&nbsp;<strong><i>Logs Display</i></strong>';
    }

    public function delete_options() {
        $input = get_option('slwsu_logs_display_delete_options');
        ?>
        <input name="slwsu_logs_display_delete_options" type="radio" value="true" <?php if ('true' == $input) echo 'checked="checked"'; ?> />
        <span class="description">On</span>
        &nbsp;
        <input name="slwsu_logs_display_delete_options" type="radio" value="false" <?php if ('false' == $input) echo 'checked="checked"'; ?> />
        <span class="description">Off</span>
        &nbsp;-&nbsp;
        <span class="description"><?php echo __('Delete plugin options when disabling.', 'lodi'); ?> </span>
        <?php
    }

    /**
     * Support GRP
     */
    public function section_grouper() {
        echo __('This section concerns the Grouper plugin group of', 'lodi') . '&nbsp;<strong><i>Logs Display</i></strong>';
    }

    public function grouper_nom() {
        $input = get_option('slwsu_logs_display_grouper', 'Grouper');
        echo '<input id="slwsu_logs_display_grouper" name="slwsu_logs_display_grouper" value="' . $input . '" type="text" class="regular-text" />';
        echo '<p class="description">' . __('Specify here the Grouper group to attach', 'lodi') . '&nbsp;<strong><i>Logs Display</i></strong>.</p>';
        echo '<p>' . __('WARNING :: changing the value of this field amounts to modifying the name of the parent link in the WordPress admin menu !', 'lodi') . '</p>';
        echo '<p>' . __('You can use this option to isolate this plugin or to add this plugin to an existing Grouper group.', 'lodi') . '</p>';
    }

    public function section_grouper_no() {
        echo '<strong><i>Logs Display</i></strong> ' . __('is compatible with Grouper', 'lodi');
        if (file_exists(WP_PLUGIN_DIR . '/grouper')):
            echo '.<br />Grouper ' . __('is installed but does not appear to be enabled', 'lodi') . ' : ';
            echo '<a href="plugins.php">' . __('you can activate', 'lodi') . ' Grouper</a>';
        else:
            echo ' : <a href="https://web-startup.fr/grouper/" target="_blank">' . __('more information here', 'lodi') . '</a>.';
        endif;
    }

    /**
     *
     */
    public function admin_css() {
        echo '<style>
            .logs-display-modal-link {
                position: relative;
                float: right;
            }

            .logs-display-modal {
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                opacity: 0;
                z-index: 99999;
                position: fixed;
                pointer-events: none;
                background: rgba(0,0,0,0.8);
                font-family: Arial, Helvetica, sans-serif;
                -webkit-transition: opacity 250ms ease-in;
                -moz-transition: opacity 250ms ease-in;
                transition: opacity 250ms ease-in;
            }

            .logs-display-modal:target {
                opacity: 1;
                pointer-events: auto;
            }

            .logs-display-modal > div {
                width: 400px;
                background: #fff;
                margin: 7% auto;
                position: relative;
                border-radius: 10px;
                padding: 5px 20px 13px 20px;
                background: -o-linear-gradient(bottom, rgb(245,245,245) 25%, rgb(232,232,232) 63%);
                background: -moz-linear-gradient(bottom, rgb(245,245,245) 25%, rgb(232,232,232) 63%);
                background: -webkit-linear-gradient(bottom, rgb(245,245,245) 25%, rgb(232,232,232) 63%);
            }

            .logs-display-modal-close {
                top: 10px;
                right: 10px;
                font-weight: bold;
                position: absolute;
                text-align: center;
                text-decoration: none;
            }

            .logs-display-modal-close:hover { color: #333; }

            #logs-display-contact input[type="text"],
            #logs-display-contact input[type="email"],
            #logs-display-contact input[type="url"],
            #logs-display-contact textarea,
            #logs-display-contact button[type="submit"] {
                font:400 12px/16px "Open Sans", Helvetica, Arial, sans-serif;
            }

            fieldset {
                border: medium none !important;
                margin: 0 0 6px;
                min-width: 100%;
                padding: 0;
                width: 100%;
            }

            #logs-display-contact input[type="text"],
            #logs-display-contact input[type="email"],
            #logs-display-contact input[type="tel"],
            #logs-display-contact input[type="url"],
            #logs-display-contact textarea {
                width:100%;
                border:1px solid #CCC;
                background:#FFF;
                margin:0 0 5px;
                padding:10px;
            }

            #logs-display-contact input[type="text"]:hover,
            #logs-display-contact input[type="email"]:hover,
            #logs-display-contact input[type="tel"]:hover,
            #logs-display-contact input[type="url"]:hover,
            #logs-display-contact textarea:hover {
                -webkit-transition:border-color 0.3s ease-in-out;
                -moz-transition:border-color 0.3s ease-in-out;
                transition:border-color 0.3s ease-in-out;
                border:1px solid #AAA;
            }

            #logs-display-contact textarea {
                height:100px;
                max-width:100%;
                resize:none;
                margin-bottom: 0px;
            }

            #logs-display-contact input:focus,
            #logs-display-contact textarea:focus {
                outline:0;
                border:1px solid #999;
            }

            ::-webkit-input-placeholder { color:#888; }
            :-moz-placeholder { color:#888; }
            ::-moz-placeholder { color:#888; }
            :-ms-input-placeholder { color:#888; }


            .logs-display-contact-valide, .logs-display-contact-error{
                padding: 8px;
                background-color: white;
            }
            .logs-display-contact-valide{
                border-left: 4px solid #46b450;
            }
            .logs-display-contact-error{
                border-left: 4px solid #dc3232;
            }
        </style>';
    }

}
