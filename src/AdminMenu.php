<?php

namespace Evokelektrique\WordpressMeilisearch;

/**
 * Class AdminMenu
 * Handles the admin menu and settings for the MeiliSearch plugin.
 */
class AdminMenu {
    /**
     * Constructor.
     * Adds actions to set up admin menu and settings.
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_custom_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Add custom menu items to the WordPress admin menu.
     */
    public function add_custom_menu(): void {
        // Add "MeiliSearch Settings" menu item
        add_menu_page(
            'MeiliSearch Settings',
            'MeiliSearch',
            'manage_options',
            'meilisearch-settings',
            array($this, 'render_settings_page')
        );

        // Add "MeiliSearch Preview" sub-menu item
        add_submenu_page(
            'meilisearch-settings', // Parent menu slug
            'MeiliSearch Preview',
            'Preview',
            'manage_options',
            'meilisearch-preview',
            array($this, 'render_preview_page')
        );
    }

    /**
     * Render the settings page.
     */
    public function render_settings_page(): void {
        require_once plugin_dir_path(__FILE__) . '/../views/settings-page.php';
    }

    /**
     * Render the preview page.
     */
    public function render_preview_page(): void {
        require_once plugin_dir_path(__FILE__) . '/../views/preview-page.php';
    }

    /**
     * Register settings for the plugin.
     */
    public function register_settings(): void {
        register_setting('wordpress-meilisearch-settings-group', Options::OPTION_PREFIX . 'public_api_key');
        register_setting('wordpress-meilisearch-settings-group', Options::OPTION_PREFIX . 'master_api_key');
        register_setting('wordpress-meilisearch-settings-group', Options::OPTION_PREFIX . 'host');
        register_setting('wordpress-meilisearch-settings-group', Options::OPTION_PREFIX . 'port');
        register_setting('wordpress-meilisearch-settings-group', Options::OPTION_PREFIX . 'index_posts');
    }
}
