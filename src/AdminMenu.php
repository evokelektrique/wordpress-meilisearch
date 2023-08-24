<?php

namespace Evokelektrique\WordpressMeilisearch;

class AdminMenu {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_custom_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_custom_menu() {
        add_menu_page(
            'MeiliSearch Settings',
            'MeiliSearch',
            'manage_options',
            'meilisearch-settings',
            array($this, 'render_settings_page')
        );
    }

    public function render_settings_page() {
        require_once plugin_dir_path(__FILE__) . '/../views/settings-page.php';
    }

    public function register_settings() {
        register_setting('wordpress-meilisearch-settings-group', Options::OPTION_PREFIX . 'public_api_key');
        register_setting('wordpress-meilisearch-settings-group', Options::OPTION_PREFIX . 'host');
        register_setting('wordpress-meilisearch-settings-group', Options::OPTION_PREFIX . 'port');
    }
}
