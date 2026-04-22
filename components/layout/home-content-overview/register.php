<?php

function boilerplate_get_home_content_overview_args($post_id = 0, $context = [], $block = [])
{
    $parsed_sections = boilerplate_get_parsed_sections();
    $sections = [];

    foreach ($parsed_sections as $section) {
        $title = trim((string)($section['h2'] ?? ''));
        if ($title === '') {
            continue;
        }

        $summary_chunks = [];
        $points = [];

        foreach (($section['blocks'] ?? []) as $block_data) {
            $type = $block_data['type'] ?? '';

            if ($type === 'text' && !empty($block_data['content'])) {
                $summary_chunks[] = wp_trim_words(wp_strip_all_tags($block_data['content']), 42, '...');
            }

            if ($type === 'accordion' && !empty($block_data['data']) && is_array($block_data['data'])) {
                $point_title = trim((string)($block_data['data']['title'] ?? ''));
                if ($point_title !== '') {
                    $points[] = $point_title;
                }

                foreach (($block_data['data']['subheadings'] ?? []) as $subheading) {
                    $sub_title = trim((string)($subheading['title'] ?? ''));
                    if ($sub_title !== '') {
                        $points[] = $sub_title;
                    }
                }
            }
        }

        $sections[] = [
            'title' => $title,
            'summary' => implode(' ', array_filter($summary_chunks)),
            'points' => array_values(array_unique($points)),
        ];
    }

    return [
        'eyebrow' => 'Firm Guide',
        'title' => 'Detailed guidance shaped directly from your page content.',
        'body' => 'Paste a structured H1, H2, and H3 hierarchy into the editor and this section turns the content into a dense, scannable overview.',
        'sections' => $sections,
    ];
}

boilerplate_register_homepage_block('home-content-overview', [
    'label' => 'Home Content Overview',
    'meta_box_title' => 'Home Content Overview',
    'description' => 'Builds a detailed card section from the homepage H2/H3 editor content.',
    'build_args_callback' => 'boilerplate_get_home_content_overview_args',
]);

function boilerplate_insert_home_content_overview($sequence)
{
    $sequence = is_array($sequence) ? array_values($sequence) : [];
    $sequence = array_values(array_diff($sequence, ['base-layout-block', 'home-content-overview']));
    $practice_index = array_search('practice-area-grid', $sequence, true);

    if ($practice_index === false) {
        $sequence[] = 'home-content-overview';
        return $sequence;
    }

    array_splice($sequence, $practice_index + 1, 0, ['home-content-overview']);
    return $sequence;
}
add_filter('boilerplate_homepage_block_sequence', 'boilerplate_insert_home_content_overview', 40);
