<?php

namespace Evokelektrique\WordpressMeilisearch;

use Meilisearch\Client;
use Meilisearch\Endpoints\Indexes;

/**
 * Class IndexManagement
 * Manages indexing and clearing of data in MeiliSearch index.
 */
class IndexManagement {

    /**
     * Nonce action for synchronizing posts.
     */
    const SYNC_NONCE_ACTION = 'meilisearch_sync_posts_nonce';

    /**
     * Nonce name for synchronizing posts.
     */
    const SYNC_NONCE_NAME = 'meilisearch_sync_posts_nonce_field';

    /**
     * Nonce action for clearing indexes.
     */
    const CLEAR_NONCE_ACTION = 'meilisearch_clear_indexes_nonce';

    /**
     * Nonce name for clearing indexes.
     */
    const CLEAR_NONCE_NAME = 'meilisearch_clear_indexes_nonce_field';

    /**
     * MeiliSearch client instance.
     *
     * @var Client
     */
    private Client $meili_client;

    /**
     * MeiliSearch index instance.
     *
     * @var Indexes
     */
    private Indexes $meili_index;

    /**
     * IndexManagement constructor.
     * Initializes the MeiliSearch client and index.
     */
    public function __construct() {
        $this->meili_client = new Client(Options::get_option('host') . ":" . Options::get_option('port'), Options::get_option('master_api_key'));
        $this->meili_index = $this->meili_client->index(Options::get_option('index_posts'));
    }

    /**
     * Perform synchronization of WordPress posts with MeiliSearch index.
     */
    public function perform_sync() {
        // Perform synchronization logic here
        error_log('Post Synchronization Background Job Executed: ' . current_time('Y-m-d H:i:s'));

        $args = array(
            'post_type' => 'post',   // Change to your custom post type if needed
            'posts_per_page' => 10,  // Number of posts per page
            'paged' => 1,            // Initial page number
        );
        $query = new \WP_Query($args);
        $total_pages = $query->max_num_pages;  // Total number of paginated pages

        for ($current_page = 1; $current_page <= $total_pages; $current_page++) {
            $args['paged'] = $current_page;   // Update the paged parameter
            $query = new \WP_Query($args);
            $posts = [];

            while ($query->have_posts()) {
                $query->the_post();
                $posts[] = [
                    "id" => get_the_ID(),
                    "title" => get_the_title(),
                    "image" => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                    "content" => apply_filters('the_content', get_the_content()),
                    "author" => get_the_author(),
                    "publish_date" => get_the_date(),
                ];
            }

            // Index posts batch to meilisearch
            $this->meili_index->addDocuments($posts);

            wp_reset_postdata();  // Reset post data for the next iteration
        }
    }

    /**
     * Perform clearing of MeiliSearch index.
     */
    public function perform_clear() {
        // Perform clear index logic here
        error_log('Clear Indexes Background Job Executed: ' . current_time('Y-m-d H:i:s'));
    }

    public function get_indexes() {

    }
}
