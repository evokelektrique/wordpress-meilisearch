<?php

namespace Evokelektrique\WordpressMeilisearch;

use Meilisearch\Client;
use Meilisearch\Endpoints\Indexes;
use GuzzleHttp\Client as GuzzleHttpClient;

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
        error_log('index management initiated');

        $this->meili_client = new Client(
            Options::get_option('host') . ":" . Options::get_option('port'), // Host
            Options::get_option('master_api_key'), // Master API key
            new GuzzleHttpClient(['timeout' => 60]) // Guzzle http client
        );
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
                $post_data = $this->extract_post_data(get_the_ID());

                if (empty($post_data)) {
                    // Skip the current iteration and move to the next post
                    continue;
                }

                // Add valid post data to the $posts array
                $posts[] = $post_data;
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

    /**
     * Adds a post document to the MeiliSearch index.
     *
     * @param int $post_id The ID of the post to add.
     * @return bool True if the document was added successfully, false otherwise.
     */
    public function add_document(int $post_id): bool {
        $post_data = $this->extract_post_data($post_id);

        if (empty($post_data)) {
            return false; // No data to add, return false
        }

        $this->meili_index->addDocuments([$post_data]);
        error_log('Post ID ' . $post_id . ' indexed');

        return true; // Document added successfully
    }

    /**
     * Extracts post data for a given post ID.
     *
     * @param int $post_id The ID of the post to extract data for.
     * @return array An array containing the extracted post data.
     */
    private function extract_post_data(int $post_id): array {
        $post = get_post($post_id);
        $post_data = [];

        // If the post does not exist, return an empty array
        if (!$post) {
            return [];
        }

        // Extract post data
        $post_data['id']           = $post_id;
        $post_data['title']        = $post->post_title;
        $post_data['content']      = $post->post_content;
        $post_data['author']       = $post->post_author;
        $post_data['publish_date'] = $post->post_date;

        // Get the full-size thumbnail image URL
        $thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');

        // Assign the thumbnail URL or an empty string if not available
        $post_data['image'] = $thumbnail_url ?: '';

        return $post_data;
    }
}
