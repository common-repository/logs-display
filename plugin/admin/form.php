<?php
/**
 * @package Logs Display
 * @version 1.3.1
 */

defined('ABSPATH') or exit();

class slwsu_logs_display_admin_form {

    public static function validation() {
        if (isset($_GET['settings-updated'])) {
            delete_transient('slwsu_logs_display_options');
            ?>
            <div id="message" class="updated">
                <p><strong><?php echo __('Settings saved', 'lodi') ?></strong></p>
            </div>
            <?php
        }
    }

    public static function action() {
        ?>
        <a class="logs-display-modal-link" style="text-decoration:none; font-weight:bold;" href="#openModal"><?php echo __('About', 'lodi'); ?> <span class="dashicons dashicons-info"></span></a>
        <?php
    }

    public static function message($post) {
        ?>
        <div id="openModal" class="logs-display-modal">
            <div>
                <a href="#logs-display-modal-close" title="Close" class="logs-display-modal-close"><span class="dashicons dashicons-dismiss"></span></a>
                <h2><?php echo __('About', 'lodi'); ?></h2>
                <p><span class="dashicons dashicons-admin-users"></span> <?php echo __('By', 'lodi'); ?> <?php echo 'Steeve Lefebvre - slWsu'; ?></p>
                <p><span class="dashicons dashicons-admin-site"></span> <?php echo __('More information', 'lodi'); ?> : <a href="<?php echo 'https://web-startup.fr/logs-display/'; ?>" target="_blank"><?php _e('plugin page', 'lodi'); ?></a></p>
                <p><span class="dashicons dashicons-admin-tools"></span> <?php echo __('Development for the web', 'lodi'); ?> : HTML, PHP, JS, WordPress</p>
                <h2><?php echo __('Support', 'lodi'); ?></h2>
                <p><span class="dashicons dashicons-email-alt"></span> <?php echo __('Ask your question', 'lodi'); ?></p>
                <?php
                if (isset($post['submit'])) {
                    global $current_user; $to = 'steeve.lfbvr@gmail.com'; $subject = "Support Grouper !!!";
                    $roles = implode(", ", $current_user->roles);
                    $message = "From: " . get_bloginfo('name') . " - " . get_bloginfo('home') . " - " . get_bloginfo('admin_email') . "\n";
                    $message .= "By : " . strip_tags($post['nom']) . " - " . $post['email'] . " - " . $roles . "\n";
                    $message .= strip_tags($post['message']) . "\n";
                    if (wp_mail($to, $subject, $message)):
                        echo '<p class="logs-display-contact-valide"><strong>' . __('Your message has been sent !', 'lodi') . '</strong></p>';
                    else:
                        echo '<p class="logs-display-contact-error">' . __('Something went wrong, go back and try again !', 'lodi') . '</p>';
                    endif;
                }
                ?>
                <form id="logs-display-contact" action="" method="post">
                    <fieldset>
                        <input id="nom" name="nom" type="text" placeholder="<?php echo __('Your name', 'lodi'); ?>" required="required">
                    </fieldset>
                    <fieldset>
                        <input id="email" name="email" type="email" placeholder="<?php echo __('Your Email Address', 'lodi'); ?>" required="required">
                    </fieldset>
                    <fieldset>
                        <textarea id="message" name="message" placeholder="<?php echo __('Formulate your support request or feature proposal here...', 'lodi'); ?>" required="required"></textarea>
                    </fieldset>
                    <fieldset>
                        <input id="submit" name="submit" type="submit" value="<?php echo __('Send', 'lodi'); ?>" class="button button-primary" type="submit" id="logs-display-contact-submit" data-submit="...Sending" />
                    </fieldset>
                </form>
            </div>
        </div>
        <?php
    }

}
