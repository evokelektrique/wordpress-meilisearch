<?php

use Evokelektrique\WordpressMeilisearch\Options;
use Evokelektrique\WordpressMeilisearch\IndexManagement;
?>

<div class="wrap">
    <h1>MeiliSearch Settings</h1>

    <!-- API Key Form -->
    <form method="post" action="options.php">
        <?php settings_fields('wordpress-meilisearch-settings-group'); ?>
        <?php do_settings_sections('wordpress-meilisearch-settings-group'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Public API Key</th>
                <td><input type="text" name="meilisearch_public_api_key" value="<?php echo esc_attr(Options::get_option('public_api_key')); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">MeiliSearch Host</th>
                <td><input type="text" name="meilisearch_host" value="<?php echo esc_attr(Options::get_option('host')); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">MeiliSearch Port</th>
                <td><input type="text" name="meilisearch_port" value="<?php echo esc_attr(Options::get_option('port')); ?>" /></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field(IndexManagement::SYNC_NONCE_ACTION, IndexManagement::SYNC_NONCE_NAME); ?>
        <input type="hidden" name="action" value="synchronize_posts">
        <button type="submit" class="button">Synchronize Posts</button>
    </form>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field(IndexManagement::CLEAR_NONCE_ACTION, IndexManagement::CLEAR_NONCE_NAME); ?>
        <input type="hidden" name="action" value="clear_indexes">
        <button type="submit" class="button">Clear Indexes</button>
    </form>

</div>
