<?php

namespace Evokelektrique\WordpressMeilisearch;

class BackgroundJobManagement {
    const SYNC_EVENT = 'meilisearch_synchronize_event';
    const CLEAR_EVENT = 'meilisearch_clear_indexes_event'; // New event for clearing indexes
    private IndexManagement $indexManagement;

    public function __construct() {
        $this->indexManagement = new IndexManagement();

        add_action('admin_post_synchronize_posts', array($this, 'schedule_synchronize_posts'));
        add_action('admin_post_clear_indexes', array($this, 'schedule_clear_indexes')); // New action for clearing indexes
        add_action(self::SYNC_EVENT, array($this, 'run_synchronization'));
        add_action(self::CLEAR_EVENT, array($this, 'run_clear_indexes')); // New action for running clear indexes
    }

    public function schedule_synchronize_posts() {
        if (isset($_POST[IndexManagement::SYNC_NONCE_NAME]) && wp_verify_nonce($_POST[IndexManagement::SYNC_NONCE_NAME], IndexManagement::SYNC_NONCE_ACTION)) {
            // Schedule synchronization to run in the background
            wp_schedule_single_event(time(), self::SYNC_EVENT);

            // Redirect back to the settings page after scheduling
            wp_redirect(add_query_arg('synchronization_scheduled', 'true', $_SERVER['HTTP_REFERER']));
            exit;
        }
    }

    public function run_synchronization() {
        $this->indexManagement->perform_sync();
    }

    public function schedule_clear_indexes() {
        if (isset($_POST[IndexManagement::CLEAR_NONCE_NAME]) && wp_verify_nonce($_POST[IndexManagement::CLEAR_NONCE_NAME], IndexManagement::CLEAR_NONCE_ACTION)) {
            // Schedule clearing indexes to run in the background
            wp_schedule_single_event(time(), self::CLEAR_EVENT);

            // Redirect back to the settings page after scheduling
            wp_redirect(add_query_arg('indexes_clear_scheduled', 'true', $_SERVER['HTTP_REFERER']));
            exit;
        }
    }

    public function run_clear_indexes() {
        $this->indexManagement->perform_clear();
    }
}
