<?php

namespace Evokelektrique\WordpressMeilisearch;

/**
 * Class PostManagement
 * Handles WordPress post-related actions.
 */
class PostManagement {

    /**
     * PostManagement constructor.
     * Registers the post save action.
     */
    public function __construct() {
        add_action('save_post', array($this, 'handle_post_save'), 10, 3);
    }

    /**
     * Handles the post save action.
     *
     * @param int      $post_id   The ID of the post being saved.
     * @param \WP_Post $post      The post object.
     * @param bool     $is_update Whether this is an update or a new post.
     */
    public function handle_post_save(int $post_id, \WP_Post $post, bool $is_update) {
        // Check if this is a revision, autosave, or user lacks permission
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (wp_is_post_revision($post_id)) {
            return;
        }

        error_log('Post ID ' . $post_id . ' was saved.');

        $indexManagement = new IndexManagement();
        $indexManagement->add_document($post_id);
    }
}
