<?php

function boilerplate_get_practice_area_grid_home_args($post_id = 0, $context = [], $block = [])
{
    $items = apply_filters('boilerplate_home_practice_area_items', [
        [
            'label' => 'Corporate Law',
            'description' => 'Formation, contracts, governance, transactions, and compliance support for growing businesses.',
            'url' => home_url('/practice-areas/corporate-law/'),
        ],
        [
            'label' => 'Criminal Defense',
            'description' => 'Strategic defense, rights protection, and guidance through each stage of the legal process.',
            'url' => home_url('/practice-areas/criminal-defense/'),
        ],
        [
            'label' => 'Family Law',
            'description' => 'Clear counsel for divorce, custody, adoption, support, and sensitive family matters.',
            'url' => home_url('/practice-areas/family-law/'),
        ],
        [
            'label' => 'Personal Injury',
            'description' => 'Representation for people harmed by negligence, accidents, unsafe conditions, or misconduct.',
            'url' => home_url('/practice-areas/personal-injury/'),
        ],
        [
            'label' => 'Business Litigation',
            'description' => 'Focused advocacy for contract disputes, partnership issues, and commercial conflicts.',
            'url' => home_url('/practice-areas/business-litigation/'),
        ],
        [
            'label' => 'Estate Planning',
            'description' => 'Planning documents, asset protection, and practical guidance for families and business owners.',
            'url' => home_url('/practice-areas/estate-planning/'),
        ],
    ]);

    return [
        'eyebrow' => 'Practice Areas',
        'title' => 'Legal services built around the way clients actually need help.',
        'body' => 'Start with the area that best fits your matter, then use the consultation process to clarify the next move.',
        'items' => is_array($items) ? $items : [],
    ];
}

boilerplate_register_homepage_block('practice-area-grid', [
    'label' => 'Practice Area Grid',
    'meta_box_title' => 'Practice Area Grid',
    'description' => 'Displays six practice area boxes beneath the homepage hero.',
    'build_args_callback' => 'boilerplate_get_practice_area_grid_home_args',
]);

function boilerplate_insert_practice_area_grid_after_hero($sequence)
{
    $sequence = is_array($sequence) ? array_values($sequence) : [];
    $sequence = array_values(array_diff($sequence, ['practice-area-grid']));
    $hero_index = array_search('law-hero', $sequence, true);

    if ($hero_index === false) {
        array_unshift($sequence, 'practice-area-grid');
        return $sequence;
    }

    array_splice($sequence, $hero_index + 1, 0, ['practice-area-grid']);
    return $sequence;
}
add_filter('boilerplate_homepage_block_sequence', 'boilerplate_insert_practice_area_grid_after_hero', 20);
