<?php
/**
 * Neutral theme defaults used by base templates and generated components.
 */

function boilerplate_get_design_defaults()
{
    return apply_filters('boilerplate_design_defaults', [
        'colors' => [
            'background' => '#f5f7fb',
            'surface' => '#ffffff',
            'surface_alt' => '#e8edf5',
            'text' => '#172033',
            'text_muted' => '#536079',
            'border' => '#cbd5e1',
            'accent' => '#2563eb',
            'accent_soft' => '#dbeafe',
        ],
        'typography' => [
            'sans' => '"Montserrat", "Helvetica Neue", Arial, sans-serif',
            'mono' => '"SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace',
        ],
        'spacing' => [
            'page_width' => '72rem',
            'page_gutter' => 'clamp(1rem, 3vw, 2rem)',
            'section_space' => 'clamp(3rem, 8vw, 6rem)',
            'radius' => '1.25rem',
        ],
        'copy' => [
            'site_label' => '',
            'site_summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'nav_cta' => 'Contact',
            'home_eyebrow' => 'Featured',
            'home_title' => 'Build your firm’s digital presence with confidence',
            'home_body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'home_secondary_title' => 'Dolor sit amet',
            'home_secondary_body' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
            'content_eyebrow' => 'Overview',
            'page_summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'empty_results' => 'No content found yet.',
            'not_found_title' => 'Page not found',
            'not_found_body' => 'The page you requested could not be found. Please return to the homepage or use the site navigation.',
            'search_title' => 'Search results',
            'archive_title' => 'Latest articles',
            'single_title' => 'Article',
            'footer_note' => '',
        ],
    ]);
}

function boilerplate_get_default_copy($key, $fallback = '')
{
    $defaults = boilerplate_get_design_defaults();
    $copy = $defaults['copy'] ?? [];

    if (isset($copy[$key]) && is_string($copy[$key]) && $copy[$key] !== '') {
        return $copy[$key];
    }

    return $fallback;
}

function boilerplate_get_theme_css_variables()
{
    $defaults = boilerplate_get_design_defaults();
    $colors = is_array($defaults['colors'] ?? null) ? $defaults['colors'] : [];
    $typography = is_array($defaults['typography'] ?? null) ? $defaults['typography'] : [];
    $spacing = is_array($defaults['spacing'] ?? null) ? $defaults['spacing'] : [];

    $vars = [
        '--boilerplate-color-background' => $colors['background'] ?? '#f5f7fb',
        '--boilerplate-color-surface' => $colors['surface'] ?? '#ffffff',
        '--boilerplate-color-surface-alt' => $colors['surface_alt'] ?? '#e8edf5',
        '--boilerplate-color-text' => $colors['text'] ?? '#172033',
        '--boilerplate-color-text-muted' => $colors['text_muted'] ?? '#536079',
        '--boilerplate-color-border' => $colors['border'] ?? '#cbd5e1',
        '--boilerplate-color-accent' => $colors['accent'] ?? '#2563eb',
        '--boilerplate-color-accent-soft' => $colors['accent_soft'] ?? '#dbeafe',
        '--boilerplate-font-sans' => $typography['sans'] ?? '"Montserrat", "Helvetica Neue", Arial, sans-serif',
        '--boilerplate-font-mono' => $typography['mono'] ?? 'monospace',
        '--boilerplate-page-width' => $spacing['page_width'] ?? '72rem',
        '--boilerplate-page-gutter' => $spacing['page_gutter'] ?? 'clamp(1rem, 3vw, 2rem)',
        '--boilerplate-section-space' => $spacing['section_space'] ?? 'clamp(3rem, 8vw, 6rem)',
        '--boilerplate-radius' => $spacing['radius'] ?? '1.25rem',
    ];

    $chunks = [];

    foreach ($vars as $name => $value) {
        $chunks[] = $name . ': ' . $value . ';';
    }

    return ':root{' . implode('', $chunks) . '}';
}
