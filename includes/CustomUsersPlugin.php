<?php

declare(strict_types=1);

namespace MembersList;

/**
 * Class CustomUsersPlugin
 * Handles custom functionalities for displaying user data.
 */
class CustomUsersPlugin
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('init', [$this, 'addCustomEndpoint']);
        add_action('template_redirect', [$this, 'handleCustomEndpoint']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('wp_ajax_nopriv_fetch_user_details', [$this, 'fetchUserDetails']);
        add_action('wp_ajax_fetch_user_details', [$this, 'fetchUserDetails']);
    }

    /**
     * Adds a custom endpoint for the users table.
     */
    public function addCustomEndpoint()
    {
        add_rewrite_rule('^my-lovely-users-table/?', 'index.php?custom_users_table=1', 'top');
        add_rewrite_tag('%custom_users_table%', '([^&]+)');
    }

    /**
     * Handles the custom endpoint request.
     */
    public function handleCustomEndpoint()
    {
        global $wp_query;

        if (isset($wp_query->query_vars['custom_users_table'])) {
            add_filter('template_include', static function () {
                return plugin_dir_path(dirname(__FILE__)) . 'public/custom-users-table-template.php';
            });
        }
    }

    /**
     * Displays the users table.
     */
    public static function displayUsersTable(): string
    {
        $transientKey = 'custom_users_data';
        $users = get_transient($transientKey);
        $output = '';

        if (false === $users) {
            $apiUrl = 'https://jsonplaceholder.typicode.com/users';
            $response = wp_remote_get($apiUrl, ['timeout' => 20]);

            if (is_wp_error($response)) {
                return '<div class="error-message">' .
                    'We are currently experiencing issues retrieving user data. ' .
                    'Please try again later.</div>';
            }

            $users = json_decode(wp_remote_retrieve_body($response), true);

            if (empty($users)) {
                return 'No users found.';
            }

            // Cache the API response for 1 hour
            set_transient($transientKey, $users, HOUR_IN_SECONDS);
        }

    // Build the HTML output
        $output .= '<div class="custom-users-table-wrapper">';
        $output .= '<table class="custom-users-table">';
        $output .= '<thead><tr><th>ID</th><th>Name</th><th>Username</th><th>City</th><th>Company</th></tr></thead>';
        $output .= '<tbody>';

        foreach ($users as $user) {
            $output .= '<tr>';
            $output .= '<td><a href="#" class="user-detail-link" ' .
                            'data-userid="' . esc_attr($user['id']) . '">' .
                            esc_html($user['id']) . '</a></td>';
            $output .= '<td><a href="#" class="user-detail-link" ' .
                            'data-userid="' . esc_attr($user['id']) . '">' .
                            esc_html($user['name']) . '</a></td>';
            $output .= '<td><a href="#" class="user-detail-link" ' .
                            'data-userid="' . esc_attr($user['id']) . '">' .
                            esc_html($user['username']) . '</a></td>';
            $output .= '<td>' . esc_html($user['address']['city']) . '</td>';
            $output .= '<td>' . esc_html($user['company']['name']) . '</td>';
            $output .= '</tr>';
        }

        $output .= '</tbody></table>';
        $output .= '</div>';
        $output .= '<div id="user-details-display"></div>';

        return $output;
    }

    /**
     * Enqueues scripts and styles.
     */
    public function enqueueScripts()
    {
        $pluginUrl = plugin_dir_url(dirname(__FILE__));

        wp_enqueue_script(
            'custom-users-ajax',
            $pluginUrl . 'public/js/custom-users-ajax.js',
            ['jquery'],
            filemtime(plugin_dir_path(dirname(__FILE__)) . 'public/js/custom-users-ajax.js'),
            true
        );

        wp_localize_script(
            'custom-users-ajax',
            'customUsersAjax',
            [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('custom_users_nonce'),
            ]
        );

        wp_enqueue_style(
            'custom-users-style',
            $pluginUrl . 'public/css/custom-users-style.css',
            [],
            filemtime(plugin_dir_path(dirname(__FILE__)) . 'public/css/custom-users-style.css')
        );
    }

    /**
     * Fetches user details via AJAX.
     */
    public function fetchUserDetails()
    {
        check_ajax_referer('custom_users_nonce', 'nonce');
        $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

        // Define a unique transient key for each user
        $transientKey = 'custom_user_details_' . $userId;
        $userDetails = get_transient($transientKey);

        if (false === $userDetails) {
            // Making an API call to fetch user details
            $apiUrl = "https://jsonplaceholder.typicode.com/users/{$userId}";
            $response = wp_remote_get($apiUrl, ['timeout' => 20]);

            // Error handling
            if (is_wp_error($response)) {
                $errorMessage = ['error' => 'Request failed: ' . $response->get_error_message()];
                echo json_encode($errorMessage);
                wp_die();
            }

            $userDetails = json_decode(wp_remote_retrieve_body($response), true);
            if (empty($userDetails)) {
                echo json_encode(['error' => 'User not found']);
                wp_die();
            }

            // Cache the API response for a certain duration, e.g., 1 hour
            set_transient($transientKey, $userDetails, HOUR_IN_SECONDS);
        }

        echo json_encode($userDetails);
        wp_die();
    }

    public function flushRewriteRules()
    {
        $this->addCustomEndpoint();
        flush_rewrite_rules();
    }
}
