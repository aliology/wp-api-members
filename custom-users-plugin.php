<?php
/**
 * Plugin Name: Members List
 * Plugin URI: https://github.com/aliology/members-list
 * Description: A plugin to display users from an external API. To visit the users table <a href="/my-lovely-users-table" target="_blank">Click Here</a>.
 * Version: 1.0
 * Author: Alireza Ebrahimi
 * Author URI: https://github.com/aliology
 */

require_once plugin_dir_path(__FILE__) . 'includes/CustomUsersPlugin.php';

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

function run_custom_users_plugin() {
    $plugin = new \MembersList\CustomUsersPlugin();
    register_activation_hook(__FILE__, function() use ($plugin) {
        $plugin->flushRewriteRules();
    });
}

run_custom_users_plugin();
