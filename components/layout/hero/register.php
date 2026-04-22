<?php
/**
 * Hero Block Registration
 * Registers the hero layout component with the theme system
 */

function boilerplate_register_hero_block()
{
    boilerplate_register_homepage_block(
        'hero',
        [
            'label' => 'Hero Section',
            'description' => 'Large hero banner with title and excerpt for the homepage',
            'render_type' => 'layout',
            'meta_box_title' => 'Hero Section Settings',
            'meta_box_context' => 'normal',
            'meta_box_priority' => 'high',
            'build_args_callback' => 'boilerplate_build_hero_block_args',
            'meta_fields' => [
                [
                    'id' => '_hero_eyebrow',
                    'label' => 'Eyebrow Text',
                    'type' => 'text',
                    'placeholder' => 'e.g., Featured',
                    'description' => 'Small text displayed above the main title',
                ],
                [
                    'id' => '_hero_background_color',
                    'label' => 'Background Color',
                    'type' => 'text',
                    'placeholder' => '#f5f7fb',
                    'description' => 'Hex color code for the hero background',
                ],
                [
                    'id' => '_hero_text_color',
                    'label' => 'Text Color',
                    'type' => 'text',
                    'placeholder' => '#172033',
                    'description' => 'Hex color code for the main text',
                ],
                [
                    'id' => '_hero_accent_color',
                    'label' => 'Accent Color',
                    'type' => 'text',
                    'placeholder' => '#2563eb',
                    'description' => 'Hex color code for the eyebrow accent',
                ],
            ],
        ]
    );
}
add_action('after_setup_theme', 'boilerplate_register_hero_block');

/**
 * Build Hero Block Arguments
 * Prepares the hero data from post content and meta
 */
function boilerplate_build_hero_block_args($post_id = 0, $context = [], $block = [])
{
    $post_id = (int)$post_id ?: get_the_ID();
    $hero_data = boilerplate_get_parsed_hero_data(get_the_title($post_id), get_the_excerpt($post_id));

    return [
        'eyebrow' => get_post_meta($post_id, '_hero_eyebrow', true) ?: boilerplate_get_default_copy('home_eyebrow', 'Featured'),
        'title' => $hero_data['h1'] ?? get_the_title($post_id),
        'excerpt' => $hero_data['excerpt'] ?? get_the_excerpt($post_id),
        'background_color' => get_post_meta($post_id, '_hero_background_color', true) ?: '#f5f7fb',
        'text_color' => get_post_meta($post_id, '_hero_text_color', true) ?: '#172033',
        'accent_color' => get_post_meta($post_id, '_hero_accent_color', true) ?: '#2563eb',
    ];
}

/**
 * Add Hero Block to Homepage Sequence
 * Ensures the hero block appears first on the homepage
 */
function boilerplate_add_hero_to_homepage_sequence($sequence)
{
    // Add hero block to the beginning of the sequence
    return array_merge(['hero'], (array)$sequence);
}
add_filter('boilerplate_homepage_block_sequence', 'boilerplate_add_hero_to_homepage_sequence');
