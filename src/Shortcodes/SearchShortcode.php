<?php

namespace Evokelektrique\WordpressMeilisearch\Shortcodes;

class SearchShortcode {
    public function __construct() {
        add_shortcode('meilisearch_search', array($this, 'render_search_shortcode'));
    }

    public function render_search_shortcode() {
        // Your shortcode rendering logic goes here
        return '<form action="#" method="get">
            <input type="text" name="search_query" placeholder="Search...">
            <input type="submit" value="Search">
        </form>';
    }
}

// Create an instance of the SearchShortcode class
$search_shortcode = new SearchShortcode();
