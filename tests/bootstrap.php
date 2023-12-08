<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

// Initialize Brain Monkey
Brain\Monkey\setUp();

// Add your WordPress function mocks here
// Example:
Brain\Monkey\Functions\stubs([
    'add_action',
    'add_filter',
    // ...other WordPress functions you use
]);

// Optionally, you can mock individual functions with specific behavior
Brain\Monkey\Functions\when('get_option')->justReturn('some_value');

exit;
