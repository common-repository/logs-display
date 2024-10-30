<?php

/**
 * @package Logs Display
 * @version 1.3.1
 */

defined('ABSPATH') or exit();

class slwsu_logs_display_widget {

    public function __construct() {
        add_action('wp_dashboard_setup', array($this, 'widget_init'));
    }

    public function widget_init() {
        wp_add_dashboard_widget('logs', '<a href="./" style="text-decoration:none;"><span class="dashicons dashicons-update"></span></a> Logs Display <span style="font-size:12px; float:right; font-weight:normal; margin-top:3px;">WP (' . get_bloginfo('version') . ') PHP (' . PHP_VERSION . ')</span>', array($this, 'affiche_admin_logs'));
    }

    function affiche_admin_logs() {
        $logsPath = plugin_dir_path(__FILE__) . 'logs/logs.txt';
        $deleted = false;
        $bUserCanDelete = current_user_can('manage_options');

        if ($bUserCanDelete && isset($_GET['logs-display']) && $_GET['logs-display'] === 'clean') {
            $deleted = $this->delete_logs($logsPath);
        }

        $this->get_admin_logs($logsPath, $bUserCanDelete, $deleted);
    }

    function get_admin_logs($logsPath, $bUserCanDelete, $deleted) {
        if (file_exists($logsPath)) {
            $aErreurs = file($logsPath);
            // $aErreurs = array_reverse($aFile);
            $sNoErreur = '<p>' . __('No recorded error : "for the time being" !!!', 'lodi') . '</p>';

            if ($deleted):
                $sNoErreur = '';

                echo '<p style="font-weight:bold;">' . __('Cleaned file !!!', 'lodi') . '</p>';
            endif;

            if ($aErreurs) {
                $this->get_intro_logs($aErreurs, $bUserCanDelete);
                $this->get_contenu_logs($aErreurs);
            } else {
                echo $sNoErreur;
            }
        } else {
            echo '<p><em>' . __('logs.txt the file does not exist, your site seems perfect : "for the time being" !!!', 'lodi') . '</em></p>';
        }
    }

    function delete_logs($logsPath) {
        $delete = fopen($logsPath, "w");
        fclose($delete);
        $deleted = true;
        return $deleted;
    }

    function get_intro_logs($aErreurs, $bUserCanDelete) {
        echo '<p>' . count($aErreurs) . ' ' . __('error', 'lodi');
        if ($aErreurs != 1):
            echo 's';
        endif;
        echo ' : ';

        if ($bUserCanDelete):
            echo '[ <b><a href="' . get_bloginfo("url") . '/wp-admin/?logs-display=clean" onclick="return confirm(' . __('\'Are you sure you want to empty the file ?\'', 'lodi') . ');">' . __('CLEAN THE LOG', 'lodi') . '</a></b> ]';
        endif;
        echo '</p>';
    }

    function get_contenu_logs($aErreurs) {
        echo '<div style="height:250px; overflow:scroll; padding:2px; background-color:white; border:1px solid #ccc;">';
        $this->parse_logs($aErreurs);
        echo '</div>';
    }

    function parse_logs($aErreurs) {

        foreach ($aErreurs as $erreur) {
            /*
              if (strlen($sErreur) > ERR_LENGTH_MAX) {
              echo substr($sErreur, 0, ERR_LENGTH_MAX) . ' [...]';
              } else {
              echo $sErreur;
              }
             */

            $sErreurs = preg_replace('/\[([^\]]+)\]/', '<b>[$1]</b>', $erreur, 1);
            $aErreur = explode("*", $sErreurs);

            echo '<div style="background-color:#f1f1f1; margin:5px; padding:5px;">';

            $e = explode("=>", $aErreur[0]);

            if (preg_match('[' . __('Fatal error', 'lodi') . ']', $e[0])):
                $color = 'red';
            elseif (preg_match('[' . __('Warning', 'lodi') . ']', $e[0])):
                $color = 'DarkOrange';
            elseif (preg_match('[' . __('Notice', 'lodi') . ']', $e[0])):
                $color = 'gold';
            elseif (preg_match('[' . __('Obsolete syntax', 'lodi') . ']', $e[0])):
                $color = 'MediumVioletRed';
            elseif (preg_match('[' . __('Unknown error', 'lodi') . ']', $e[0])):
                $color = 'black';
            endif;

            $filePath = trim($aErreur[2]);
            // https://regex101.com/r/yY4vT3/2
            preg_match('#[a-z]+ (\/[a-zA-Z\/]+|[ a-zA-Z\:\\\_\-]+)wp#', $filePath, $matches);
            $path = str_replace($matches[1], '', $filePath);

            echo '<span style="color:' . $color . '; font-weight:bold; text-transform:uppercase;"' . $e[0] . '</span> => ' . $e[1] . '<br />';
            echo '<span style="color:#008ec2;">' . $aErreur[1] . '</span><br />';
            echo '<code style="font-size:12px;">' . $path . '</code><br />';
            // echo '<span style="color:orange;">' . $aErreur[3] . '</span><br />';
            // echo '<span style="color:orange;">' . $aErreur[4] . '</span><br />';
            echo '</div>';

            $i = 0;
            if ($i > 100) {
                echo '<li>' . __('Again', 'lodi') . ' ' . $iErreurNbMax . ' ' . __('errors in your log', 'lodi') . '...</li>';
                break;
            }
            $i++;
        }
    }

}

/**
 * 
 */
new slwsu_logs_display_widget();
