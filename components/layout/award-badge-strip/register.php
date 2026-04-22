<?php

function boilerplate_get_award_badge_strip_home_args($post_id = 0, $context = [], $block = [])
{
    $items = apply_filters('boilerplate_home_award_badges', [
        ['label' => 'As Seen On', 'note' => 'Press feature placeholder', 'initials' => 'PR'],
        ['label' => 'Top Rated Counsel', 'note' => 'Award logo placeholder', 'initials' => 'TR'],
        ['label' => 'Client Choice', 'note' => 'Recognition placeholder', 'initials' => 'CC'],
        ['label' => 'Legal Excellence', 'note' => 'Badge logo placeholder', 'initials' => 'LE'],
        ['label' => 'Community Advocate', 'note' => 'Association placeholder', 'initials' => 'CA'],
    ]);

    return [
        'eyebrow' => 'Awards & Recognition',
        'title' => 'Badge-ready space for press mentions, awards, and trusted legal associations.',
        'items' => is_array($items) ? $items : [],
    ];
}

boilerplate_register_homepage_block('award-badge-strip', [
    'label' => 'Awards Badge Strip',
    'meta_box_title' => 'Awards Badge Strip',
    'description' => 'Displays circular badge cards for awards, recognition, or press logos.',
    'build_args_callback' => 'boilerplate_get_award_badge_strip_home_args',
]);

function boilerplate_insert_award_badge_strip_after_hero($sequence)
{
    $sequence = is_array($sequence) ? array_values($sequence) : [];
    $sequence = array_values(array_diff($sequence, ['award-badge-strip']));
    $hero_index = array_search('law-hero', $sequence, true);

    if ($hero_index === false) {
        array_unshift($sequence, 'award-badge-strip');
        return $sequence;
    }

    array_splice($sequence, $hero_index + 1, 0, ['award-badge-strip']);
    return $sequence;
}
add_filter('boilerplate_homepage_block_sequence', 'boilerplate_insert_award_badge_strip_after_hero', 30);
