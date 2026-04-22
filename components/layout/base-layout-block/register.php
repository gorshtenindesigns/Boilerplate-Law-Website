<?php

function boilerplate_get_base_layout_block_home_args($post_id, $context = [], $block = [])
{
    $post_id = (int) $post_id;
    $hero_data = !empty($context['hero_data']) && is_array($context['hero_data'])
        ? $context['hero_data']
        : boilerplate_get_parsed_hero_data(get_the_title($post_id), get_the_excerpt($post_id));

    $section_titles = [];

    foreach (boilerplate_get_parsed_sections() as $section) {
        if (empty($section['h2'])) {
            continue;
        }

        $section_titles[] = [
            'label' => wp_strip_all_tags($section['h2']),
            'url' => '#' . sanitize_title($section['h2']),
        ];
    }

    $body = get_post_meta($post_id, 'base_layout_block_body', true);
    if ($body === '') {
        $body = $hero_data['excerpt'] ?? '';
    }
    if ($body === '') {
        $body = '<p>' . esc_html(boilerplate_get_default_copy('home_body')) . '</p>';
    }

    $media_defaults = function_exists('boilerplate_get_theme_media_defaults') ? boilerplate_get_theme_media_defaults() : [];

    return [
        'variant' => 'home',
        'eyebrow' => get_post_meta($post_id, 'base_layout_block_eyebrow', true) ?: boilerplate_get_default_copy('home_eyebrow'),
        'title' => get_post_meta($post_id, 'base_layout_block_title', true) ?: ($hero_data['h1'] ?? boilerplate_get_default_copy('home_title')),
        'body' => wpautop($body),
        'media_image' => get_post_meta($post_id, 'base_layout_block_media_image', true) ?: ($media_defaults['wide_primary'] ?? ''),
        'media_alt' => get_post_meta($post_id, 'base_layout_block_media_alt', true) ?: ($hero_data['h1'] ?? boilerplate_get_default_copy('home_title')),
        'items' => [
            [
                'value' => sprintf('%02d', count($section_titles)),
                'label' => 'Lorem ipsum',
            ],
            [
                'value' => sprintf('%02d', str_word_count(wp_strip_all_tags((string) $body))),
                'label' => 'Dolor sit',
            ],
        ],
        'links' => array_slice($section_titles, 0, 3),
    ];
}

add_filter('boilerplate_homepage_block_sequence', function ($sequence = []) {
    return ['base-layout-block'];
});

boilerplate_register_homepage_block('base-layout-block', [
    'label' => 'Base Layout Block',
    'meta_box_title' => 'Base Layout Block',
    'description' => 'Neutral placeholder meta fields for the homepage base layout block.',
    'build_args_callback' => 'boilerplate_get_base_layout_block_home_args',
    'meta_fields' => [
        ['id' => 'base_layout_block_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'placeholder' => 'Lorem ipsum'],
        ['id' => 'base_layout_block_title', 'label' => 'Title', 'type' => 'text', 'placeholder' => 'Lorem ipsum dolor sit amet'],
        ['id' => 'base_layout_block_body', 'label' => 'Body', 'type' => 'textarea', 'placeholder' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
        ['id' => 'base_layout_block_media_image', 'label' => 'Media Image URL', 'type' => 'url', 'placeholder' => '/wp-content/themes/boilerplate-update/assets/media/2.png'],
        ['id' => 'base_layout_block_media_alt', 'label' => 'Media Alt Text', 'type' => 'text', 'placeholder' => 'Law firm office interior'],
    ],
]);

add_shortcode('base_layout_block', function ($atts, $content = null) {
    $attributes = shortcode_atts([
        'variant' => 'default',
        'eyebrow' => '',
        'title' => '',
        'body' => '',
        'primary_label' => '',
        'primary_url' => '',
        'secondary_label' => '',
        'secondary_url' => '',
        'media_image' => '',
        'media_alt' => '',
    ], $atts);

    $links = [];

    if ($attributes['primary_label'] !== '' && $attributes['primary_url'] !== '') {
        $links[] = [
            'label' => $attributes['primary_label'],
            'url' => $attributes['primary_url'],
        ];
    }

    if ($attributes['secondary_label'] !== '' && $attributes['secondary_url'] !== '') {
        $links[] = [
            'label' => $attributes['secondary_label'],
            'url' => $attributes['secondary_url'],
        ];
    }

    $args = [
        'variant' => $attributes['variant'],
        'eyebrow' => $attributes['eyebrow'],
        'title' => $attributes['title'],
        'body' => $content !== null && trim((string) $content) !== '' ? wpautop($content) : wpautop($attributes['body']),
        'media_image' => $attributes['media_image'],
        'media_alt' => $attributes['media_alt'],
        'links' => $links,
    ];

    ob_start();
    get_template_part('components/layout/base-layout-block/base-layout-block', null, $args);
    return ob_get_clean();
});
