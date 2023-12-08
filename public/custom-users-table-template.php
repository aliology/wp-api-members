<?php
/**
 * Template for displaying custom users table.
 */

// Get global header
get_header();
?>

<div id="primary" class="full-width">
    <main id="main" role="main">
            <?php echo \MembersList\CustomUsersPlugin::displayUsersTable(); ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
