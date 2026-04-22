<?php

function boilerplate_render_base_content_block($section)
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
    $section_id = $section_title !== '' ? sanitize_title($section_title) : wp_unique_id('section-');
    $intro_html = '';
    $media_blocks = [];
    $detail_blocks = [];

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

    echo '<section id="' . esc_attr($section_id) . '" class="base-content-block">';
    echo '<div class="base-content-block__inner">';
    echo '<div class="base-content-block__header">';

    if ($section_title !== '') {
        echo '<p class="base-content-block__eyebrow">' . esc_html(boilerplate_get_default_copy('content_eyebrow')) . '</p>';
        echo '<h2 class="base-content-block__title">' . wp_kses_post($section_title) . '</h2>';
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

        foreach ($detail_blocks as $detail) {
            $detail_title = trim((string) ($detail['title'] ?? ''));
            $detail_body = (string) ($detail['content'] ?? '');

            echo '<details class="base-content-block__detail">';
            echo '<summary class="base-content-block__summary">';
            echo '<span class="base-content-block__summary-label">' . esc_html($detail_title !== '' ? $detail_title : boilerplate_get_default_copy('content_eyebrow')) . '</span>';
            echo '</summary>';
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
            echo '</details>';
        }

        echo '</div>';
    }

    echo '</div>';
    echo '</section>';
}
