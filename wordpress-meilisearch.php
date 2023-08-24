<?php
/*
Plugin Name: WordPress MeiliSearch
Description: Enhance WordPress search using MeiliSearch for lightning-fast and accurate results.
Version: 0.1
Author: Majid
*/

require_once __DIR__ . '/vendor/autoload.php';

use Evokelektrique\WordpressMeilisearch\Plugin;
use Evokelektrique\WordpressMeilisearch\BackgroundJobs;
use Evokelektrique\WordpressMeilisearch\IndexManagement;

$wordpress_meilisearch_plugin = Plugin::get_instance();

// Create instances of BackgroundJobs and IndexManagement classes
new BackgroundJobs();
new IndexManagement();

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wordpress_meilisearch_settings_link');

function wordpress_meilisearch_settings_link($links) {
    $settings_link = '<a href="admin.php?page=meilisearch-settings">' . __('Settings') . '</a>';
    $links[] = $settings_link;
    return $links;
}

function do_this_in_an_hour() {

    // do something
}
add_action( 'my_new_event','do_this_in_an_hour' );

// put this line inside a function,
// presumably in response to something the user does
// otherwise it will schedule a new event on every page visit

wp_schedule_single_event( time() + 3600, 'my_new_event' );
