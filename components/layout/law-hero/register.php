<?php
/**
 * Law Hero Block Registration
 * Registers the custom hero layout component with the theme system
 */

/**
 * Build Hero Block Arguments
 * Prepares hero data from post content and meta
 */
function law_hero_build_args($post_id = 0, $context = [], $block = [])
{
    $post_id = (int)$post_id ?: get_the_ID();

    // Get hero data from context or parse from post
    $hero_data = [];
    if (is_array($context) && isset($context['hero_data']) && is_array($context['hero_data'])) {
        $hero_data = $context['hero_data'];
    } else {
        $hero_data = boilerplate_get_parsed_hero_data(get_the_title($post_id), get_the_excerpt($post_id));
    }

    // Get contact details
    $contact_details = boilerplate_get_site_contact_details();
    $contact_url = isset($contact_details['contact_url']) && $contact_details['contact_url'] ? $contact_details['contact_url'] : home_url('/');

    // Build stats array with customizable values
    $stats = [
        [
            'value' => get_post_meta($post_id, 'law_hero_stat_1_value', true) ?: '10+',
            'label' => get_post_meta($post_id, 'law_hero_stat_1_label', true) ?: 'Years Experience',
        ],
        [
            'value' => get_post_meta($post_id, 'law_hero_stat_2_value', true) ?: '500+',
            'label' => get_post_meta($post_id, 'law_hero_stat_2_label', true) ?: 'Clients Represented',
        ],
        [
            'value' => get_post_meta($post_id, 'law_hero_stat_3_value', true) ?: '4',
            'label' => get_post_meta($post_id, 'law_hero_stat_3_label', true) ?: 'Practice Areas',
        ],
        [
            'value' => get_post_meta($post_id, 'law_hero_stat_4_value', true) ?: '95%',
            'label' => get_post_meta($post_id, 'law_hero_stat_4_label', true) ?: 'Client Satisfaction',
        ],
    ];

    // Get featured image URL if available
    $featured_image = '';
    if (has_post_thumbnail($post_id)) {
        $featured_image = get_the_post_thumbnail_url($post_id, 'large');
    }

    return [
        'eyebrow' => get_post_meta($post_id, 'law_hero_eyebrow', true) ?: boilerplate_get_default_copy('home_eyebrow', 'Client-Centered Legal Practice'),
        'title' => $hero_data['h1'] ?? boilerplate_get_default_copy('home_title', get_the_title($post_id)),
        'excerpt' => $hero_data['excerpt'] ?? boilerplate_get_default_copy('home_body', get_the_excerpt($post_id)),
        'primary_cta_label' => get_post_meta($post_id, 'law_hero_cta_label', true) ?: boilerplate_get_default_copy('nav_cta', 'Schedule a Consultation'),
        'primary_cta_url' => get_post_meta($post_id, 'law_hero_cta_url', true) ?: $contact_url,
        'secondary_cta_label' => get_post_meta($post_id, 'law_hero_secondary_cta_label', true) ?: 'View Practice Areas',
        'secondary_cta_url' => get_post_meta($post_id, 'law_hero_secondary_cta_url', true) ?: home_url('/practice-areas/'),
        'stats' => $stats,
        'background_image' => get_post_meta($post_id, 'law_hero_background_image', true) ?: '',
        'featured_image' => $featured_image,
    ];
}

/**
 * Register the Law Hero homepage block
 */
function boilerplate_register_law_hero_block()
{
    boilerplate_register_homepage_block(
        'law-hero',
        [
            'label' => 'Law Hero',
            'meta_box_title' => 'Hero Section',
            'description' => 'Controls the full-width hero section at the top of the homepage. Title and excerpt come from the page editor H1 and intro text.',
            'render_type' => 'layout',
            'build_args_callback' => 'law_hero_build_args',
            'meta_fields' => [
                [
                    'id' => 'law_hero_eyebrow',
                    'label' => 'Eyebrow Text',
                    'type' => 'text',
                    'placeholder' => 'Client-Centered Legal Practice',
                    'description' => 'Small text displayed above the main title',
                ],
                [
                    'id' => 'law_hero_cta_label',
                    'label' => 'Primary CTA Label',
                    'type' => 'text',
                    'placeholder' => 'Schedule a Consultation',
                    'description' => 'Text for the primary call-to-action button',
                ],
                [
                    'id' => 'law_hero_cta_url',
                    'label' => 'Primary CTA URL',
                    'type' => 'url',
                    'placeholder' => '/contact/',
                    'description' => 'URL for the primary CTA button',
                ],
                [
                    'id' => 'law_hero_secondary_cta_label',
                    'label' => 'Secondary CTA Label',
                    'type' => 'text',
                    'placeholder' => 'View Practice Areas',
                    'description' => 'Text for the secondary call-to-action button',
                ],
                [
                    'id' => 'law_hero_secondary_cta_url',
                    'label' => 'Secondary CTA URL',
                    'type' => 'url',
                    'placeholder' => '/practice-areas/',
                    'description' => 'URL for the secondary CTA button',
                ],
                [
                    'id' => 'law_hero_background_image',
                    'label' => 'Background Image URL',
                    'type' => 'url',
                    'placeholder' => 'https://example.com/background.jpg',
                    'description' => 'Full URL to a background image for the hero section',
                ],
                [
                    'id' => 'law_hero_stat_1_value',
                    'label' => 'Stat 1: Value',
                    'type' => 'text',
                    'placeholder' => '10+',
                    'description' => 'First statistic value (e.g., "10+")',
                ],
                [
                    'id' => 'law_hero_stat_1_label',
                    'label' => 'Stat 1: Label',
                    'type' => 'text',
                    'placeholder' => 'Years Experience',
                    'description' => 'Label for first statistic',
                ],
                [
                    'id' => 'law_hero_stat_2_value',
                    'label' => 'Stat 2: Value',
                    'type' => 'text',
                    'placeholder' => '500+',
                    'description' => 'Second statistic value',
                ],
                [
                    'id' => 'law_hero_stat_2_label',
                    'label' => 'Stat 2: Label',
                    'type' => 'text',
                    'placeholder' => 'Clients Represented',
                    'description' => 'Label for second statistic',
                ],
                [
                    'id' => 'law_hero_stat_3_value',
                    'label' => 'Stat 3: Value',
                    'type' => 'text',
                    'placeholder' => '4',
                    'description' => 'Third statistic value',
                ],
                [
                    'id' => 'law_hero_stat_3_label',
                    'label' => 'Stat 3: Label',
                    'type' => 'text',
                    'placeholder' => 'Practice Areas',
                    'description' => 'Label for third statistic',
                ],
                [
                    'id' => 'law_hero_stat_4_value',
                    'label' => 'Stat 4: Value',
                    'type' => 'text',
                    'placeholder' => '95%',
                    'description' => 'Fourth statistic value',
                ],
                [
                    'id' => 'law_hero_stat_4_label',
                    'label' => 'Stat 4: Label',
                    'type' => 'text',
                    'placeholder' => 'Client Satisfaction',
                    'description' => 'Label for fourth statistic',
                ],
            ],
        ]
    );
}
add_action('after_setup_theme', 'boilerplate_register_law_hero_block');

/**
 * Set law-hero as the first block in the homepage sequence
 */
function boilerplate_set_law_hero_first($sequence)
{
    $sequence = is_array($sequence) ? $sequence : [];
    // Remove law-hero from sequence if it exists
    $sequence = array_diff($sequence, ['law-hero']);
    // Add it to the beginning
    return array_merge(['law-hero'], $sequence);
}
add_filter('boilerplate_homepage_block_sequence', 'boilerplate_set_law_hero_first');
