<?php

/**
 * @package Logs Display
 * @version 1.3.1
 */

defined('ABSPATH') or exit();

/**
 * utilisation:
 * 
 * trigger_error("Ceci est une ERREUR FATALE", E_USER_ERROR);
 * trigger_error("Ceci est un AVERTISSEMENT", E_USER_WARNING);
 * trigger_error("Ceci est une REMARQUE ", E_USER_NOTICE);
 * trigger_error("Ceci est OBSELETE ", E_USER_DEPRECATED);
 */
class slwsu_logs_display_logger {

    public function bfs_logiii_exceptions($exception) {
        $this->bfs_logiii_erreurs(E_USER_ERROR, $exception->getMessage(), $exception->getFile(), $exception->getLine());
    }

    public function bfs_logiii_erreurs($type, $message, $fichier, $ligne) {
        $type_erreur = $this->bfs_logiii_type_erreur($type);
        $date_erreur = date("d.m.Y H:i:s");
        $url = (string) "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $signature = md5($message . $ligne . $fichier . $url);
        $sErreur = "[" . $type_erreur . "] => " . $date_erreur . " * " . $message . " * " . __('in', 'lodi') . " " . $fichier . " (" . $ligne . ") * " . $url . " * " . $signature;

        $logFile = plugin_dir_path(__FILE__) . 'admin/logs/logs.txt';

        $logs = fopen($logFile, 'a');
        fwrite($logs, $sErreur . "\n");
        fclose($logs);
    }

    public function bfs_logiii_type_erreur($type) {
        switch ($type) {
            case E_ERROR:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
                $type_erreur = __('Fatal error', 'lodi');
                break;

            case E_WARNING:
            case E_USER_WARNING:
                $type_erreur = __('Warning', 'lodi');
                break;

            case E_NOTICE:
            case E_USER_NOTICE:
                $type_erreur = __('Notice', 'lodi');
                break;

            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $type_erreur = __('Obsolete syntax', 'lodi');
                break;

            default:
                $type_erreur = __('Unknown error', 'lodi');
        }
        return $type_erreur;
    }

    public function bfs_logiii_erreurs_fatales() {
        if (is_array($e = error_get_last())) {
            $type = isset($e['type']) ? $e['type'] : 0;
            $message = isset($e['message']) ? $e['message'] : '';
            $fichier = isset($e['file']) ? $e['file'] : '';
            $ligne = isset($e['line']) ? $e['line'] : '';
            if ($type > 0):
                $this->bfs_logiii_erreurs($type, $message, $fichier, $ligne);
            endif;
        }
    }

}

$logger = new slwsu_logs_display_logger();

set_exception_handler(array($logger, 'bfs_logiii_exceptions'));
set_error_handler(array($logger, 'bfs_logiii_erreurs'));
register_shutdown_function(array($logger, 'bfs_logiii_erreurs_fatales'));

error_reporting(0);
