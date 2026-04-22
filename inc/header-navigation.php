<?php
/**
 * Header-specific navigation data and rendering helpers.
 */

function boilerplate_get_header_menu_source_post($item)
{
    $page_id = (int)($item['page_id'] ?? 0);
    if ($page_id > 0) {
        $post = get_post($page_id);
        return $post instanceof WP_Post && $post->post_status === 'publish' ? $post : null;
    }

    $page_path = trim((string)($item['page_path'] ?? ''), '/');
    if ($page_path !== '') {
        $post = get_page_by_path($page_path);
        return $post instanceof WP_Post && $post->post_status === 'publish' ? $post : null;
    }

    $url = trim((string)($item['url'] ?? ''));
    if ($url === '') {
        return null;
    }

    $absolute_url = preg_match('#^https?://#i', $url) ? $url : home_url('/' . ltrim($url, '/'));
    $post_id = url_to_postid($absolute_url);

    if ($post_id > 0) {
        $post = get_post($post_id);
        return $post instanceof WP_Post && $post->post_status === 'publish' ? $post : null;
    }

    $path = trim((string)(wp_parse_url($absolute_url, PHP_URL_PATH) ?? ''), '/');
    if ($path === '') {
        $front_page_id = (int)get_option('page_on_front');
        if ($front_page_id > 0) {
            $front_page = get_post($front_page_id);
            return $front_page instanceof WP_Post && $front_page->post_status === 'publish' ? $front_page : null;
        }

        return null;
    }

    $post = get_page_by_path($path);
    return $post instanceof WP_Post && $post->post_status === 'publish' ? $post : null;
}

function boilerplate_get_header_menu_item_url($item, $source_post = null)
{
    $url = trim((string)($item['url'] ?? ''));

    if ($url !== '') {
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        return home_url('/' . ltrim($url, '/'));
    }

    if ($source_post instanceof WP_Post) {
        return get_permalink($source_post);
    }

    $page_path = trim((string)($item['page_path'] ?? ''), '/');
    if ($page_path !== '') {
        return home_url('/' . $page_path . '/');
    }

    return home_url('/');
}

function boilerplate_get_header_menu_fallback_label($item)
{
    $page_path = trim((string)($item['page_path'] ?? ''), '/');
    if ($page_path !== '') {
        $segments = array_values(array_filter(explode('/', $page_path)));
        $segment = end($segments);

        if (is_string($segment) && $segment !== '') {
            return ucwords(str_replace(['-', '_'], ' ', $segment));
        }
    }

    $url = trim((string)($item['url'] ?? ''));
    if ($url !== '') {
        $path = trim((string)(wp_parse_url($url, PHP_URL_PATH) ?? ''), '/');
        if ($path !== '') {
            $segments = array_values(array_filter(explode('/', $path)));
            $segment = end($segments);

            if (is_string($segment) && $segment !== '') {
                return ucwords(str_replace(['-', '_'], ' ', $segment));
            }
        }
    }

    return '';
}

function boilerplate_get_header_menu_auto_children($source_post)
{
    if (!$source_post instanceof WP_Post || $source_post->post_type !== 'page') {
        return [];
    }

    $children = get_pages([
        'parent' => $source_post->ID,
        'sort_column' => 'menu_order,post_title',
        'sort_order' => 'ASC',
        'post_status' => 'publish',
    ]);

    $items = [];

    foreach ($children as $child) {
        $items[] = [
            'label' => get_the_title($child),
            'url' => get_permalink($child),
            'description' => boilerplate_get_page_summary($child),
        ];
    }

    return $items;
}

function boilerplate_prepare_header_menu_item($item, $depth = 0)
{
    if (!is_array($item)) {
        return [];
    }

    $source_post = boilerplate_get_header_menu_source_post($item);
    $label = trim((string)($item['label'] ?? ''));

    if ($label === '' && $source_post instanceof WP_Post) {
        $label = get_the_title($source_post);
    }

    if ($label === '') {
        $label = boilerplate_get_header_menu_fallback_label($item);
    }

    if ($label === '') {
        return [];
    }

    $description = isset($item['description'])
        ? trim((string)$item['description'])
        : boilerplate_get_page_summary($source_post);
    $prepared = [
        'label' => $label,
        'url' => boilerplate_get_header_menu_item_url($item, $source_post),
        'kicker' => trim((string)($item['kicker'] ?? $label)),
        'description' => $description,
    ];

    $children = [];

    foreach (($item['children'] ?? []) as $child) {
        $prepared_child = boilerplate_prepare_header_menu_item($child, $depth + 1);
        if (!empty($prepared_child)) {
            $children[] = $prepared_child;
        }
    }

    if ($depth === 1 && empty($children) && ($item['auto_children'] ?? true)) {
        $children = boilerplate_get_header_menu_auto_children($source_post);
    }

    if (!empty($children)) {
        $prepared['children'] = $children;
    }

    return $prepared;
}

function boilerplate_get_header_menu_items()
{
    $config = boilerplate_get_header_menu_config();

    if (empty($config) || !is_array($config)) {
        return boilerplate_get_primary_navigation_items();
    }

    $items = [];

    foreach ($config as $item) {
        $prepared_item = boilerplate_prepare_header_menu_item($item);
        if (!empty($prepared_item)) {
            $items[] = $prepared_item;
        }
    }

    return !empty($items) ? $items : boilerplate_get_primary_navigation_items();
}

function boilerplate_is_nav_item_active($item, $request_uri)
{
    $current_path = trailingslashit((string)$request_uri);
    $item_path = trailingslashit(wp_parse_url($item['url'] ?? '', PHP_URL_PATH) ?: ($item['url'] ?? ''));

    if ($current_path === $item_path) {
        return true;
    }

    foreach (($item['children'] ?? []) as $child) {
        if (boilerplate_is_nav_item_active($child, $request_uri)) {
            return true;
        }
    }

    return false;
}

function boilerplate_get_header_navigation_data()
{
    $request_uri = wp_parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $menu_items = boilerplate_get_header_menu_items();
    $site_identity = boilerplate_get_site_identity();
    $contact_details = boilerplate_get_site_contact_details();
    $site_name = $site_identity['name'] !== '' ? $site_identity['name'] : 'Site';
    $site_tagline = $site_identity['tagline'];
    $contact_url = $contact_details['contact_url'];
    $drawer_summary = $site_tagline !== ''
        ? $site_tagline
        : 'Browse the main pages and jump directly into the section you need.';

    return [
        'request_uri' => $request_uri,
        'menu_items' => $menu_items,
        'site_name' => $site_name,
        'site_tagline' => $site_tagline,
        'contact_url' => $contact_url,
        'contact_details' => $contact_details,
        'drawer_summary' => $drawer_summary,
    ];
}

function boilerplate_render_header_nav($items, $request_uri)
{
    echo '<ul class="site-header__nav-list">';

    foreach ($items as $item) {
        $children = $item['children'] ?? [];
        $has_children = !empty($children);
        $is_active = boilerplate_is_nav_item_active($item, $request_uri);
        $item_classes = 'site-header__nav-item';

        if ($has_children) {
            $item_classes .= ' has-dropdown';
        }

        if ($is_active) {
            $item_classes .= ' is-active';
        }

        echo '<li class="' . esc_attr($item_classes) . '">';
        echo '<a href="' . esc_url($item['url']) . '" class="site-header__nav-link">';
        echo '<span>' . esc_html($item['label']) . '</span>';

        if ($has_children) {
            echo '<span class="site-header__nav-chevron" aria-hidden="true">';
            echo '<svg viewBox="0 0 12 12" fill="none"><path d="M2.5 4.25L6 7.75L9.5 4.25" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            echo '</span>';
        }

        echo '</a>';

        if ($has_children) {
            echo '<div class="site-header__dropdown">';
            echo '<div class="site-header__dropdown-panel">';
            echo '<div class="site-header__dropdown-intro">';
            echo '<span class="site-header__dropdown-kicker">' . esc_html($item['kicker'] ?? $item['label']) . '</span>';

            if (!empty($item['description'])) {
                echo '<p class="site-header__dropdown-summary">' . esc_html($item['description']) . '</p>';
            }

            echo '<a href="' . esc_url($item['url']) . '" class="site-header__dropdown-overview">View overview</a>';
            echo '</div>';
            echo '<ul class="site-header__dropdown-list">';

            foreach ($children as $child) {
                $child_active = boilerplate_is_nav_item_active($child, $request_uri);
                $grandchildren = $child['children'] ?? [];
                $has_grandchildren = !empty($grandchildren);

                echo '<li class="site-header__dropdown-item' . ($child_active ? ' is-active' : '') . ($has_grandchildren ? ' has-submenu' : '') . '">';

                if ($has_grandchildren) {
                    echo '<div class="site-header__dropdown-card">';
                    echo '<a href="' . esc_url($child['url']) . '" class="site-header__dropdown-parent-link">';
                    echo '<span class="site-header__dropdown-label">' . esc_html($child['label']) . '</span>';

                    if (!empty($child['description'])) {
                        echo '<span class="site-header__dropdown-text">' . esc_html($child['description']) . '</span>';
                    }

                    echo '</a>';
                    echo '<ul class="site-header__dropdown-sub-list">';

                    foreach ($grandchildren as $grandchild) {
                        $grandchild_path = trailingslashit(wp_parse_url($grandchild['url'] ?? '', PHP_URL_PATH) ?: ($grandchild['url'] ?? ''));
                        $grandchild_active = trailingslashit($request_uri) === $grandchild_path;

                        echo '<li class="site-header__dropdown-sub-item' . ($grandchild_active ? ' is-active' : '') . '">';
                        echo '<a href="' . esc_url($grandchild['url']) . '" class="site-header__dropdown-sub-link">';
                        echo '<span class="site-header__dropdown-sub-label">' . esc_html($grandchild['label']) . '</span>';

                        if (!empty($grandchild['description'])) {
                            echo '<span class="site-header__dropdown-sub-text">' . esc_html($grandchild['description']) . '</span>';
                        }

                        echo '</a>';
                        echo '</li>';
                    }

                    echo '</ul>';
                    echo '</div>';
                }
                else {
                    echo '<a href="' . esc_url($child['url']) . '" class="site-header__dropdown-link">';
                    echo '<span class="site-header__dropdown-label">' . esc_html($child['label']) . '</span>';

                    if (!empty($child['description'])) {
                        echo '<span class="site-header__dropdown-text">' . esc_html($child['description']) . '</span>';
                    }

                    echo '</a>';
                }

                echo '</li>';
            }

            echo '</ul>';
            echo '</div>';
            echo '</div>';
        }

        echo '</li>';
    }

    echo '</ul>';
}

function boilerplate_render_mobile_drawer_nav($items, $request_uri)
{
    echo '<ul class="site-header__drawer-list">';

    foreach ($items as $item) {
        $children = $item['children'] ?? [];
        $has_children = !empty($children);
        $is_active = boilerplate_is_nav_item_active($item, $request_uri);
        $item_classes = 'site-header__drawer-item';

        if ($has_children) {
            $item_classes .= ' has-accordion';
        }

        if ($is_active) {
            $item_classes .= ' is-active';
        }

        echo '<li class="' . esc_attr($item_classes) . '">';
        echo '<div class="site-header__drawer-row">';
        echo '<a href="' . esc_url($item['url']) . '" class="site-header__drawer-link">';
        echo '<span class="site-header__drawer-link-label">' . esc_html($item['label']) . '</span>';

        if (!empty($item['description'])) {
            echo '<span class="site-header__drawer-link-text">' . esc_html($item['description']) . '</span>';
        }

        echo '</a>';

        if ($has_children) {
            echo '<button class="site-header__drawer-toggle" type="button" aria-label="Toggle submenu" aria-expanded="false">';
            echo '<svg viewBox="0 0 12 12" fill="none"><path d="M2.5 4.25L6 7.75L9.5 4.25" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            echo '</button>';
        }

        echo '</div>';

        if ($has_children) {
            echo '<div class="site-header__drawer-sub">';
            echo '<ul class="site-header__drawer-sub-list">';

            foreach ($children as $child) {
                $child_active = boilerplate_is_nav_item_active($child, $request_uri);
                $grandchildren = $child['children'] ?? [];
                $has_grandchildren = !empty($grandchildren);

                echo '<li class="site-header__drawer-sub-item' . ($child_active ? ' is-active' : '') . ($has_grandchildren ? ' has-submenu' : '') . '">';
                echo '<a href="' . esc_url($child['url']) . '" class="site-header__drawer-sub-link">';
                echo '<span class="site-header__drawer-sub-label">' . esc_html($child['label']) . '</span>';

                if (!empty($child['description'])) {
                    echo '<span class="site-header__drawer-sub-text">' . esc_html($child['description']) . '</span>';
                }

                echo '</a>';

                if ($has_grandchildren) {
                    echo '<ul class="site-header__drawer-sub-children">';

                    foreach ($grandchildren as $grandchild) {
                        $grandchild_path = trailingslashit(wp_parse_url($grandchild['url'] ?? '', PHP_URL_PATH) ?: ($grandchild['url'] ?? ''));
                        $grandchild_active = trailingslashit($request_uri) === $grandchild_path;

                        echo '<li class="site-header__drawer-sub-child-item' . ($grandchild_active ? ' is-active' : '') . '">';
                        echo '<a href="' . esc_url($grandchild['url']) . '" class="site-header__drawer-sub-child-link">';
                        echo '<span class="site-header__drawer-sub-child-label">' . esc_html($grandchild['label']) . '</span>';

                        if (!empty($grandchild['description'])) {
                            echo '<span class="site-header__drawer-sub-child-text">' . esc_html($grandchild['description']) . '</span>';
                        }

                        echo '</a>';
                        echo '</li>';
                    }

                    echo '</ul>';
                }

                echo '</li>';
            }

            echo '</ul>';
            echo '</div>';
        }

        echo '</li>';
    }

    echo '</ul>';
}