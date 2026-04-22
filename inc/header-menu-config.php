<?php
/**
 * Header menu configuration.
 *
 * Return an empty array by default so the theme falls back to
 * boilerplate_get_primary_navigation_items(), which auto-builds
 * navigation from published pages.
 */

function boilerplate_get_header_menu_config()
{
    return apply_filters('boilerplate_header_menu_config', []);
}

function boilerplate_filter_public_navigation_items($items)
{
    if (!is_array($items)) {
        return [];
    }

    $excluded_labels = ['sample page', 'portfolio'];
    $excluded_paths = ['/sample-page/', '/portfolio/'];
    $filtered_items = [];

    foreach ($items as $item) {
        $label = strtolower(trim((string)($item['label'] ?? '')));
        $path = strtolower(trailingslashit((string)(wp_parse_url($item['url'] ?? '', PHP_URL_PATH) ?: '')));

        if (in_array($label, $excluded_labels, true) || in_array($path, $excluded_paths, true)) {
            continue;
        }

        if ($label === 'services' || $path === '/services/') {
            $item['label'] = 'Practice Areas';
            $item['kicker'] = 'Practice Areas';
            $item['description'] = 'Explore the legal services and practice areas available to clients.';
        }

        $filtered_items[] = $item;
    }

    return $filtered_items;
}
add_filter('boilerplate_primary_navigation_items', 'boilerplate_filter_public_navigation_items', 20);
