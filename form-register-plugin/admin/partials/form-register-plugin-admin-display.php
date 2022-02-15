<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       Gerson
 * @since      1.0.0
 *
 * @package    Form_Register_Plugin
 * @subpackage Form_Register_Plugin/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<form method="POST" action='options.php'>
   <?php
         settings_fields($this->plugin_name);
         do_settings_sections('form-register-settings-page');

         submit_button();  
   ?>
</form>