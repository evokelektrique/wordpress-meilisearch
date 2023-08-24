<?php

namespace Evokelektrique\WordpressMeilisearch;

class Options {
    const OPTION_PREFIX = 'meilisearch_';

    public static function get_option($name, $default = '') {
        return get_option(self::OPTION_PREFIX . $name, $default);
    }

    public static function update_option($name, $value) {
        update_option(self::OPTION_PREFIX . $name, $value);
    }
}
