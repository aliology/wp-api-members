<?php

declare(strict_types=1);

namespace MembersPlugin\Tests;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class TestCustomUsersPlugin extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {

        parent::setUp();
        Monkey\setUp();
        // Set up any common mocking or setup here
    }

    protected function tearDown(): void
    {

        Monkey\tearDown();
        parent::tearDown();
    }

    public function testAddCustomEndpoint()
    {
        // Test that the custom endpoint is added correctly
        Monkey\Functions\expect('add_rewrite_rule')
            ->once()
            ->with('^my-lovely-users-table/?', 'index.php?custom_users_table=1', 'top');

        $plugin = new CustomUsersPlugin();
        $plugin->addCustomEndpoint();

        // Additional assertions can be made here
    }

    public function testHandleCustomEndpoint()
    {
        // Test that the custom endpoint is handled correctly
        global $wp_query;
        $wp_query = new \stdClass();
        $wp_query->query_vars = ['custom_users_table' => 1];

        Monkey\Functions\expect('plugin_dir_path')
            ->once()
            ->andReturn('/path/to/template.php');

        $plugin = new CustomUsersPlugin();
        $plugin->handleCustomEndpoint();

        // Additional assertions can be made here
    }

    public function testFetchUserDetails()
    {
        // Test the fetch_user_details method
        Monkey\Functions\expect('check_ajax_referer')
            ->once()
            ->with('custom_users_nonce', 'nonce');

        Monkey\Functions\expect('wp_remote_get')
            ->once()
            ->with('https://jsonplaceholder.typicode.com/users/1', ['timeout' => 20])
            ->andReturn(['body' => json_encode(['id' => 1, 'name' => 'John Doe'])]);

        $_POST['user_id'] = 1;
        $_POST['nonce'] = 'valid_nonce';

        $plugin = new CustomUsersPlugin();
        $plugin->fetch_user_details();

        // Additional assertions can be made here
    }

    // Additional tests for other methods...
}
