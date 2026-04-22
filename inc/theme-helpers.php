<?php
/**
 * Shared theme helpers for component rendering and structured content parsing.
 */

use LLG\Boilerplate\Content\ContentContext;

function boilerplate_strip_content_page_shortcodes($content)
{
    if (!is_string($content) || $content === '') {
        return '';
    }

    $content = preg_replace('/\[content_page\b[^\]]*\]/i', '', $content);
    $content = preg_replace('/\[\/content_page\]/i', '', $content);

    return is_string($content) ? trim($content) : '';
}

function boilerplate_get_component_template_path($slug, $type = 'layout')
{
    $safe_type = $type === 'ui' ? 'ui' : 'layout';
    $safe_slug = sanitize_title($slug);

    if (empty($safe_slug)) {
        return '';
    }

    return 'components/' . $safe_type . '/' . $safe_slug . '/' . $safe_slug;
}

function boilerplate_component_exists($slug, $type = 'layout')
{
    $template_path = boilerplate_get_component_template_path($slug, $type);

    if (empty($template_path)) {
        return false;
    }

    return file_exists(get_template_directory() . '/' . $template_path . '.php');
}

function boilerplate_render_component($slug, $args = [], $type = 'layout')
{
    if (!boilerplate_component_exists($slug, $type)) {
        return false;
    }

    get_template_part(boilerplate_get_component_template_path($slug, $type), null, $args);
    return true;
}

function boilerplate_get_asset_version($relative_path)
{
    $asset_path = ltrim((string)$relative_path, '/');

    if ($asset_path === '') {
        $theme = wp_get_theme();
        return $theme ? $theme->get('Version') : null;
    }

    $absolute_path = get_theme_file_path($asset_path);

    if (is_string($absolute_path) && $absolute_path !== '' && file_exists($absolute_path)) {
        return (string) filemtime($absolute_path);
    }

    $theme = wp_get_theme();
    return $theme ? $theme->get('Version') : null;
}

function boilerplate_get_theme_media_url($filename)
{
    $safe_filename = ltrim((string) $filename, '/');

    if ($safe_filename === '') {
        return '';
    }

    $relative_path = 'assets/media/' . basename($safe_filename);

    if (!file_exists(get_theme_file_path($relative_path))) {
        return '';
    }

    return get_theme_file_uri($relative_path);
}

function boilerplate_get_theme_media_defaults()
{
    return [
        'hero' => boilerplate_get_theme_media_url('1.png'),
        'wide_primary' => boilerplate_get_theme_media_url('2.png'),
        'wide_secondary' => boilerplate_get_theme_media_url('3.png'),
        'square' => boilerplate_get_theme_media_url('Placeholder.png'),
    ];
}

function boilerplate_get_homepage_block_registry()
{
    if (!isset($GLOBALS['boilerplate_homepage_block_registry']) || !is_array($GLOBALS['boilerplate_homepage_block_registry'])) {
        $GLOBALS['boilerplate_homepage_block_registry'] = [];
    }

    return $GLOBALS['boilerplate_homepage_block_registry'];
}

function boilerplate_register_homepage_block($slug, $args = [])
{
    $safe_slug = sanitize_title($slug);

    if ($safe_slug === '') {
        return false;
    }

    $defaults = [
        'label' => ucwords(str_replace('-', ' ', $safe_slug)),
        'description' => '',
        'meta_box_title' => '',
        'meta_box_context' => 'normal',
        'meta_box_priority' => 'default',
        'render_type' => 'layout',
        'build_args_callback' => '',
        'meta_fields' => [],
    ];

    $registry = boilerplate_get_homepage_block_registry();
    $entry = wp_parse_args($args, $defaults);
    $entry['render_type'] = $entry['render_type'] === 'ui' ? 'ui' : 'layout';
    $entry['meta_fields'] = is_array($entry['meta_fields']) ? array_values($entry['meta_fields']) : [];

    if ($entry['meta_box_title'] === '') {
        $entry['meta_box_title'] = $entry['label'];
    }

    $registry[$safe_slug] = $entry;
    $GLOBALS['boilerplate_homepage_block_registry'] = $registry;

    return true;
}

function boilerplate_get_registered_homepage_blocks()
{
    $registry = apply_filters('boilerplate_homepage_block_registry', boilerplate_get_homepage_block_registry());

    return is_array($registry) ? $registry : [];
}

function boilerplate_get_homepage_block($slug)
{
    $safe_slug = sanitize_title($slug);

    if ($safe_slug === '') {
        return [];
    }

    $registry = boilerplate_get_registered_homepage_blocks();

    if (empty($registry[$safe_slug]) || !is_array($registry[$safe_slug])) {
        return [];
    }

    return $registry[$safe_slug];
}

function boilerplate_get_homepage_block_sequence()
{
    $sequence = apply_filters('boilerplate_homepage_block_sequence', [
        'base-layout-block',
    ]);

    if (!is_array($sequence)) {
        return [];
    }

    $normalized = [];

    foreach ($sequence as $slug) {
        $safe_slug = sanitize_title($slug);

        if ($safe_slug === '' || in_array($safe_slug, $normalized, true)) {
            continue;
        }

        $normalized[] = $safe_slug;
    }

    return $normalized;
}

function boilerplate_get_active_homepage_blocks()
{
    $registry = boilerplate_get_registered_homepage_blocks();
    $active_blocks = [];

    foreach (boilerplate_get_homepage_block_sequence() as $slug) {
        if (empty($registry[$slug]) || !is_array($registry[$slug])) {
            continue;
        }

        $active_blocks[$slug] = $registry[$slug];
    }

    return $active_blocks;
}

function boilerplate_get_homepage_block_render_args($slug, $post_id = 0, $context = [])
{
    $block = boilerplate_get_homepage_block($slug);

    if (empty($block)) {
        return [];
    }

    $callback = $block['build_args_callback'] ?? '';

    if (is_string($callback) && $callback !== '' && is_callable($callback)) {
        $args = call_user_func($callback, (int)$post_id, is_array($context) ? $context : [], $block);
        return is_array($args) ? $args : [];
    }

    return [];
}

function boilerplate_content_context_store($context = null, $reset = false)
{
    static $stored_context = null;

    if ($reset) {
        $stored_context = null;
        return null;
    }

    if ($context instanceof ContentContext) {
        $stored_context = $context;
    }

    if (!$stored_context instanceof ContentContext) {
        $stored_context = new ContentContext();
    }

    return $stored_context;
}

function boilerplate_get_content_context()
{
    return boilerplate_content_context_store();
}

function boilerplate_set_content_context(ContentContext $context)
{
    return boilerplate_content_context_store($context);
}

function boilerplate_prepare_structured_content($content, $args = [])
{
    if (!is_string($content)) {
        return '';
    }

    $content = boilerplate_strip_content_page_shortcodes($content);
    if ($content === '') {
        return '';
    }

    $layout = '';
    if (!empty($args['layout']) && is_string($args['layout'])) {
        $layout = sanitize_key($args['layout']);
    }

    $open_tag = '[content_page';
    if ($layout !== '') {
        $open_tag .= ' layout="' . esc_attr($layout) . '"';
    }
    $open_tag .= ']';

    return $open_tag . "\n" . $content . "\n[/content_page]";
}

function boilerplate_render_structured_content($content, $args = [])
{
    $prepared_content = boilerplate_prepare_structured_content($content, $args);
    if ($prepared_content === '') {
        return '';
    }

    $layout = '';
    if (!empty($args['layout']) && is_string($args['layout'])) {
        $layout = sanitize_key($args['layout']);
    }

    $inject_components = !empty($args['inject_components']) && is_array($args['inject_components'])
        ? $args['inject_components']
        : [];

    $post_id = 0;
    if (!empty($args['post_id'])) {
        $post_id = (int) $args['post_id'];
    } elseif (function_exists('get_the_ID')) {
        $post_id = (int) get_the_ID();
    }

    if ($post_id <= 0 && function_exists('get_queried_object_id')) {
        $post_id = (int) get_queried_object_id();
    }

    $context = !empty($args['context']) && $args['context'] instanceof ContentContext
        ? $args['context']
        : boilerplate_get_content_context();

    if (!$context instanceof ContentContext) {
        $context = new ContentContext();
    }

    $context->setLayout($layout);
    $context->setInjectComponents($inject_components);
    $context->setPostId($post_id > 0 ? $post_id : null);

    boilerplate_set_content_context($context);

    $rendered = do_shortcode($prepared_content);

    return $rendered;
}

function boilerplate_get_parsed_hero_data($fallback_title = '', $fallback_excerpt = '')
{
    $hero_data = [];
    $context = boilerplate_get_content_context();

    if ($context instanceof ContentContext) {
        $hero_data = $context->getHero();
    }

    if (!is_array($hero_data)) {
        $hero_data = $GLOBALS['content_page_hero_data'] ?? [];
    }

    return [
        'h1' => !empty($hero_data['h1']) ? $hero_data['h1'] : $fallback_title,
        'excerpt' => !empty($hero_data['excerpt']) ? $hero_data['excerpt'] : $fallback_excerpt,
    ];
}

function boilerplate_get_parsed_sections()
{
    $sections = [];
    $context = boilerplate_get_content_context();

    if ($context instanceof ContentContext) {
        $sections = $context->getSections();
    }

    if (!is_array($sections)) {
        $sections = $GLOBALS['content_page_sections_data'] ?? [];
    }

    return is_array($sections) ? $sections : [];
}

function boilerplate_get_site_identity()
{
    $identity = apply_filters('boilerplate_site_identity', [
        'name' => get_bloginfo('name'),
        'tagline' => get_bloginfo('description'),
    ]);

    return [
        'name' => trim((string)($identity['name'] ?? '')),
        'tagline' => trim((string)($identity['tagline'] ?? '')),
    ];
}

function boilerplate_get_page_summary($post, $word_limit = 18)
{
    $page = get_post($post);

    if (!$page instanceof WP_Post) {
        return '';
    }

    $excerpt = trim(wp_strip_all_tags((string)$page->post_excerpt));
    if ($excerpt !== '') {
        return wp_trim_words($excerpt, $word_limit, '...');
    }

    $content = boilerplate_strip_content_page_shortcodes((string)$page->post_content);
    $content = trim(preg_replace('/\s+/', ' ', wp_strip_all_tags($content)));

    if ($content === '') {
        return '';
    }

    return wp_trim_words($content, $word_limit, '...');
}

function boilerplate_get_contact_page_url()
{
    $configured_id = (int)get_theme_mod('boilerplate_contact_page_id', 0);
    if ($configured_id > 0) {
        $configured_page = get_post($configured_id);
        if ($configured_page instanceof WP_Post && $configured_page->post_status === 'publish') {
            return get_permalink($configured_page);
        }
    }

    $filtered_url = trim((string)apply_filters('boilerplate_contact_page_url', ''));
    if ($filtered_url !== '') {
        return $filtered_url;
    }

    $candidate_paths = [
        'contact-us',
        'contact',
    ];

    foreach ($candidate_paths as $candidate_path) {
        $page = get_page_by_path($candidate_path);
        if ($page instanceof WP_Post && $page->post_status === 'publish') {
            return get_permalink($page);
        }
    }

    $contact_pages = get_posts([
        'post_type' => 'page',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'orderby' => 'menu_order title',
        'order' => 'ASC',
        's' => 'contact',
    ]);

    if (!empty($contact_pages[0])) {
        return get_permalink($contact_pages[0]);
    }

    return home_url('/');
}

function boilerplate_get_phone_href($phone)
{
    $digits = preg_replace('/[^0-9+]/', '', (string)$phone);
    return $digits !== '' ? 'tel:' . $digits : '';
}

function boilerplate_get_site_contact_details()
{
    $default_email = sanitize_email((string)get_theme_mod('boilerplate_contact_email', ''));
    if ($default_email === '') {
        $default_email = sanitize_email((string)get_option('admin_email'));
    }

    $details = apply_filters('boilerplate_contact_details', [
        'email' => $default_email,
        'phone' => (string)get_theme_mod('boilerplate_contact_phone', ''),
        'address' => (string)get_theme_mod('boilerplate_contact_address', ''),
        'address_url' => (string)get_theme_mod('boilerplate_contact_address_url', ''),
        'contact_url' => boilerplate_get_contact_page_url(),
    ]);

    $email = sanitize_email((string)($details['email'] ?? ''));
    $phone = trim((string)($details['phone'] ?? ''));
    $address = trim((string)($details['address'] ?? ''));
    $address_url = trim((string)($details['address_url'] ?? ''));
    $contact_url = trim((string)($details['contact_url'] ?? ''));

    if ($address !== '' && $address_url === '') {
        $address_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($address);
    }

    if ($contact_url === '') {
        $contact_url = home_url('/');
    }

    return [
        'email' => $email,
        'email_href' => $email !== '' ? 'mailto:' . antispambot($email) : '',
        'phone' => $phone,
        'phone_href' => boilerplate_get_phone_href($phone),
        'address' => $address,
        'address_href' => $address_url,
        'contact_url' => $contact_url,
    ];
}

function boilerplate_get_social_icon_svg($network)
{
    $icons = [
        'facebook' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 22v-8h2.7l.4-3.1h-3.1V8.9c0-.9.3-1.5 1.6-1.5H16.7V4.6c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.4-4 4.1v2.3H7.7V14h2.6v8h3.2z"/></svg>',
        'instagram' => '<svg viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="4.2" stroke="currentColor" stroke-width="1.8"/><circle cx="17.4" cy="6.6" r="1.2" fill="currentColor"/></svg>',
        'linkedin' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.4 8.9A1.9 1.9 0 106.4 5a1.9 1.9 0 000 3.9zM4.8 10.6H8V20H4.8v-9.4zM10.1 10.6h3v1.3h.1c.4-.8 1.5-1.7 3.1-1.7 3.3 0 3.9 2.1 3.9 4.9V20H17v-4.3c0-1 0-2.4-1.5-2.4s-1.8 1.1-1.8 2.3V20h-3.2v-9.4z"/></svg>',
    ];

    return $icons[$network] ?? '';
}

function boilerplate_get_site_social_links()
{
    $raw_links = apply_filters('boilerplate_social_links', [
        'facebook' => (string)get_theme_mod('boilerplate_social_facebook', ''),
        'instagram' => (string)get_theme_mod('boilerplate_social_instagram', ''),
        'linkedin' => (string)get_theme_mod('boilerplate_social_linkedin', ''),
    ]);

    $links = [];

    foreach ($raw_links as $network => $url) {
        $safe_network = sanitize_key((string)$network);
        $safe_url = trim((string)$url);

        if ($safe_url === '') {
            continue;
        }

        $links[] = [
            'label' => ucwords(str_replace('-', ' ', $safe_network)),
            'url' => $safe_url,
            'icon' => boilerplate_get_social_icon_svg($safe_network),
        ];
    }

    return $links;
}

function boilerplate_build_navigation_item($page)
{
    $page = get_post($page);

    if (!$page instanceof WP_Post || $page->post_status !== 'publish') {
        return [];
    }

    $children = get_pages([
        'parent' => $page->ID,
        'sort_column' => 'menu_order,post_title',
        'sort_order' => 'ASC',
        'post_status' => 'publish',
    ]);

    $item = [
        'label' => get_the_title($page),
        'url' => get_permalink($page),
        'kicker' => get_the_title($page),
        'description' => boilerplate_get_page_summary($page),
        'children' => [],
    ];

    foreach ($children as $child) {
        $item['children'][] = [
            'label' => get_the_title($child),
            'url' => get_permalink($child),
            'description' => boilerplate_get_page_summary($child),
        ];
    }

    if (empty($item['children'])) {
        unset($item['children']);
    }

    return $item;
}

function boilerplate_get_primary_navigation_items()
{
    $items = [];
    $front_page_id = (int)get_option('page_on_front');
    $front_summary = '';
    $home_label = 'Home';

    if ($front_page_id > 0) {
        $front_page = get_post($front_page_id);
        if ($front_page instanceof WP_Post && $front_page->post_status === 'publish') {
            $front_summary = boilerplate_get_page_summary($front_page);
            $front_title = trim((string)get_the_title($front_page));
            if ($front_title !== '') {
                $home_label = $front_title;
            }
        }
    }

    $items[] = [
        'label' => $home_label,
        'url' => home_url('/'),
        'kicker' => $home_label,
        'description' => $front_summary,
    ];

    $pages = get_pages([
        'parent' => 0,
        'sort_column' => 'menu_order,post_title',
        'sort_order' => 'ASC',
        'post_status' => 'publish',
    ]);

    foreach ($pages as $page) {
        if ((int)$page->ID === $front_page_id) {
            continue;
        }

        if (in_array($page->post_name, ['privacy-policy'], true)) {
            continue;
        }

        $item = boilerplate_build_navigation_item($page);
        if (!empty($item)) {
            $items[] = $item;
        }
    }

    return apply_filters('boilerplate_primary_navigation_items', $items);
}

function boilerplate_get_footer_navigation_groups()
{
    $navigation_items = boilerplate_get_primary_navigation_items();
    $primary = [];
    $secondary = [];

    foreach ($navigation_items as $item) {
        if (count($primary) < 5) {
            $primary[] = [
                'label' => $item['label'] ?? '',
                'url' => $item['url'] ?? '',
            ];
        }

        foreach (($item['children'] ?? []) as $child) {
            if (count($secondary) >= 6) {
                break 2;
            }

            $secondary[] = [
                'label' => $child['label'] ?? '',
                'url' => $child['url'] ?? '',
            ];
        }
    }

    if (empty($secondary)) {
        foreach (array_slice($primary, 1) as $link) {
            if (count($secondary) >= 4) {
                break;
            }

            $secondary[] = $link;
        }
    }

    return [
        'primary' => array_values(array_filter($primary, function ($link) {
            return !empty($link['label']) && !empty($link['url']);
        })),
        'secondary' => array_values(array_filter($secondary, function ($link) {
            return !empty($link['label']) && !empty($link['url']);
        })),
    ];
}
