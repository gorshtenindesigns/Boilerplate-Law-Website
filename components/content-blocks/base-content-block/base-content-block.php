<?php

function boilerplate_render_base_content_block($section, $index = 0, $is_last = false, $args = [])
{
    if (empty($section) || !is_array($section)) {
        return;
    }

    wp_enqueue_style(
        'base-content-block-css',
        get_template_directory_uri() . '/components/content-blocks/base-content-block/base-content-block.css',
        array(),
        boilerplate_get_asset_version('components/content-blocks/base-content-block/base-content-block.css')
    );

    wp_enqueue_script(
        'base-content-block-js',
        get_template_directory_uri() . '/components/content-blocks/base-content-block/base-content-block.js',
        array(),
        boilerplate_get_asset_version('components/content-blocks/base-content-block/base-content-block.js'),
        true
    );

    $section_title = trim((string) ($section['h2'] ?? ''));
    $display_title = $section_title;
    if (strcasecmp($section_title, 'Client Experience in Law Firms') === 0) {
        $display_title = 'A Better Client Experience From First Call to Resolution';
    }
    $section_id = $section_title !== '' ? sanitize_title($section_title) : wp_unique_id('section-');
    $section_index = max(0, (int) $index);
    $layout_variant = 'feature-split';
    if ($section_index % 3 === 1) {
        $layout_variant = 'dark-spotlight';
    } elseif ($section_index % 3 === 2 || $is_last) {
        $layout_variant = 'card-grid';
    }
    $intro_html = '';
    $media_blocks = [];
    $detail_blocks = [];
    static $auto_media_index = 0;

    foreach (($section['blocks'] ?? []) as $block) {
        $type = $block['type'] ?? '';

        if ($type === 'text' && !empty($block['content'])) {
            $intro_html .= '<div class="base-content-block__copy">' . wp_kses_post($block['content']) . '</div>';
            continue;
        }

        if ($type === 'heading' && !empty($block['content'])) {
            $level = in_array($block['level'] ?? '', ['h3', 'h4', 'h5', 'h6'], true) ? $block['level'] : 'h3';
            $intro_html .= '<' . esc_attr($level) . ' class="base-content-block__heading">' . wp_kses_post($block['content']) . '</' . esc_attr($level) . '>';
            continue;
        }

        if ($type === 'image' && !empty($block['src'])) {
            $media_blocks[] = $block['src'];
            continue;
        }

        if ($type === 'accordion' && !empty($block['data']) && is_array($block['data'])) {
            $detail_blocks[] = $block['data'];
        }
    }

    if (empty($media_blocks) && function_exists('boilerplate_get_theme_media_defaults')) {
        $media_defaults = boilerplate_get_theme_media_defaults();
        $auto_media = array_values(array_filter([
            $media_defaults['wide_secondary'] ?? '',
            $media_defaults['wide_primary'] ?? '',
            $media_defaults['square'] ?? '',
        ]));

        if (!empty($auto_media) && $section_title !== '') {
            $media_blocks[] = $auto_media[$auto_media_index % count($auto_media)];
            $auto_media_index++;
        }
    }

    echo '<section id="' . esc_attr($section_id) . '" class="base-content-block base-content-block--' . esc_attr($layout_variant) . '">';
    echo '<div class="base-content-block__inner">';
    echo '<div class="base-content-block__header">';

    if ($section_title !== '') {
        echo '<h2 class="base-content-block__title">' . wp_kses_post($display_title) . '</h2>';
    }

    if ($intro_html !== '') {
        echo '<div class="base-content-block__intro">' . wp_kses_post($intro_html) . '</div>';
    }

    echo '</div>';

    if (!empty($media_blocks)) {
        echo '<div class="base-content-block__media-grid">';

        foreach ($media_blocks as $media_url) {
            echo '<div class="base-content-block__media">';
            echo '<img src="' . esc_url($media_url) . '" alt="" loading="lazy">';
            echo '</div>';
        }

        echo '</div>';
    }

    if (!empty($detail_blocks)) {
        echo '<div class="base-content-block__details">';

        foreach ($detail_blocks as $detail_index => $detail) {
            $detail_title = trim((string) ($detail['title'] ?? ''));
            $detail_body = (string) ($detail['content'] ?? '');

            echo '<article class="base-content-block__detail">';
            echo '<div class="base-content-block__summary">';
            echo '<span class="base-content-block__icon" aria-hidden="true">' . boilerplate_get_content_feature_icon_svg($detail_index) . '</span>';
            echo '<span class="base-content-block__summary-label">' . esc_html($detail_title !== '' ? $detail_title : boilerplate_get_default_copy('content_eyebrow')) . '</span>';
            echo '</div>';
            echo '<div class="base-content-block__detail-body">';

            if ($detail_body !== '') {
                echo '<div class="base-content-block__detail-copy">' . wp_kses_post($detail_body) . '</div>';
            }

            foreach (($detail['subheadings'] ?? []) as $subheading) {
                $level = in_array($subheading['level'] ?? '', ['h4', 'h5', 'h6'], true) ? $subheading['level'] : 'h4';
                $sub_title = trim((string) ($subheading['title'] ?? ''));
                $sub_text = (string) ($subheading['text'] ?? '');

                if ($sub_title !== '') {
                    echo '<' . esc_attr($level) . ' class="base-content-block__subheading">' . wp_kses_post($sub_title) . '</' . esc_attr($level) . '>';
                }

                if ($sub_text !== '') {
                    echo '<div class="base-content-block__subcopy">' . wp_kses_post($sub_text) . '</div>';
                }
            }

            echo '</div>';
            echo '</article>';
        }

        echo '</div>';
    }

    echo '</div>';
    echo '</section>';
}

function boilerplate_get_content_feature_icon_svg($index)
{
    $icons = [
        '<svg viewBox="0 0 24 24" fill="none"><path d="M12 3l7 4v5c0 4.5-2.9 7.8-7 9-4.1-1.2-7-4.5-7-9V7l7-4z" stroke="currentColor" stroke-width="1.8"/><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        '<svg viewBox="0 0 24 24" fill="none"><path d="M7 4h10v16H7z" stroke="currentColor" stroke-width="1.8"/><path d="M9.5 8h5M9.5 12h5M9.5 16h3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
        '<svg viewBox="0 0 24 24" fill="none"><path d="M12 4l2.2 4.5 5 .7-3.6 3.5.8 5-4.4-2.4-4.4 2.4.8-5L4.8 9.2l5-.7L12 4z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
        '<svg viewBox="0 0 24 24" fill="none"><path d="M5 19h14M7 19V9l5-4 5 4v10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 19v-5h4v5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
    ];

    return $icons[$index % count($icons)];
}
