# Members List Plugin
The Members List Plugin is a custom WordPress plugin designed to display a list of users fetched from an external API. It showcases users in an HTML table on a custom endpoint, with asynchronous loading of user details.

## Requirements
- WordPress 5.0 or higher
- PHP 8.0 or higher
- Composer for dependency management

## Installation
1. Clone the repository to your WordPress plugins directory:
    `git clone https://github.com/aliology/members-plugin.git`
2. Navigate to the plugin directory:
    `cd members-list`
3. Install dependencies with Composer:
    `composer install`
4. Activate the plugin through the WordPress admin interface.

## Usage
Once activated, the plugin adds a custom endpoint to your WordPress site with /my-lovely-users-table at the end of your domain (https://yourdomain.com/my-lovely-users-table/) where you can view the users' table. Clicking on any user's ID, name, or username will asynchronously load and display detailed information about that user without reloading the page.

## Implementation Details
- Custom Endpoint: We use WordPress rewrite rules to create a non-standard URL for displaying the users' table.
- External API Integration: User data is fetched from https://jsonplaceholder.typicode.com/users. Server-side PHP handles all HTTP requests.
- AJAX for Asynchronous Data Loading: Clicking on a user's detail in the table triggers an AJAX request, fetching detailed user information without a page refresh.
- Caching: To optimize performance, user data is cached using WordPress transients.
- Error Handling: Both PHP and JavaScript include error handling to maintain site stability in case of API failures.
- Frontend Technologies: jQuery is used for AJAX requests, and custom CSS is provided for basic styling.

## Development Decisions
- Server-side API Requests: Although client-side requests might seem more straightforward, server-side requests were used to demonstrate backend development skills.
- Caching Strategy: Caching is implemented to reduce API calls, enhancing performance and user experience.
- Use of jQuery: Chosen for its compatibility and simplicity in handling AJAX within the WordPress ecosystem.
- Error Retry in JavaScript: AJAX calls include a retry mechanism to handle temporary network issues.

## Testing
Unit tests are provided to ensure the reliability of core functionalities. Run the tests with:
    `vendor/bin/phpunit`

## Dependencies
- guzzlehttp/guzzle: Used for making HTTP requests to the external API.
- Other dependencies are used for unit testing and code quality checks.

## Contributions
Contributions are welcome. Please ensure your code adheres to the project's coding standards and include unit tests where applicable. For major changes, open an issue first to discuss what you would like to change.

## Contact
For support or further inquiries, you can contact me at aliologys@gmail.com.

## License
This plugin is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
