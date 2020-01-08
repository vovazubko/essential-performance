<?php
/**************************************************************************
 * Essential Performance - Wordpress Plugin
 *
 * @package     Essential Performance - Wordpress Plugin
 * @author      Vova Zubko <vozubko@gmail.com>
 * @copyright   2019 Vova Zubko <vozubko@gmail.com>
 * @license     GPL-2.0-or-later
 * @site        https://vauko.com
 * @file        settings_page.php
 * @date        12/16/2019
 */

?>

<div class="wrap">
    <h2><?php _e('Essential Performance', $this->Config->textDomain); ?></h2>
    <form method="post" action="options.php">
        <?php
        // This prints out all hidden setting fields
        settings_fields($this->Config->textDomain . '_option_group');
        do_settings_sections('essential_settings');
        submit_button();
        ?>
    </form>
</div>
