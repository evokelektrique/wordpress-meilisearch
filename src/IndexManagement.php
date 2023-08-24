<?php

namespace Evokelektrique\WordpressMeilisearch;

class IndexManagement {

    const SYNC_NONCE_ACTION = 'meilisearch_sync_posts_nonce';
    const SYNC_NONCE_NAME = 'meilisearch_sync_posts_nonce_field';
    const CLEAR_NONCE_ACTION = 'meilisearch_clear_indexes_nonce';
    const CLEAR_NONCE_NAME = 'meilisearch_clear_indexes_nonce_field';

    public function __construct() {
    }

    public function perform_sync() {
        // Perform synchronization logic here
        error_log('Post Synchronization Background Job Executed: ' . current_time('Y-m-d H:i:s'));
    }

    public function perform_clear() {
        // Perform clear index logic here
        error_log('Clear Indexes Background Job Executed: ' . current_time('Y-m-d H:i:s'));
    }
}
