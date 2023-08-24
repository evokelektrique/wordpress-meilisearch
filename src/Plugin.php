<?php

namespace Evokelektrique\WordpressMeilisearch;

class Plugin {
    private static $instance;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        new AdminMenu();
    }
}
