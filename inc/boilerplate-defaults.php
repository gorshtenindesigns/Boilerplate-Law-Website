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
            'accent' => '#1a3a5c',
            'accent_soft' => '#dce8f5',
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
            'site_label' => 'Law By Yan',
            'site_summary' => 'Expert legal representation across corporate law, criminal defense, family law, and personal injury. Client-centered, transparent, and results-driven.',
            'nav_cta' => 'Schedule a Consultation',
            'home_eyebrow' => 'Client-Centered Legal Practice',
            'home_title' => 'The Modern Law Firm: Structure, Services, and Client-Centered Practice',
            'home_body' => 'We provide expert legal representation across corporate law, criminal defense, family law, and personal injury. Our firm combines deep legal expertise with transparent, client-first service delivery.',
            'home_secondary_title' => 'How We Work',
            'home_secondary_body' => 'From the initial consultation through resolution, we keep clients informed, costs transparent, and outcomes focused.',
            'content_eyebrow' => 'Practice Areas',
            'page_summary' => 'Learn about our specialized legal services and how we can help your business or personal matter.',
            'empty_results' => 'No practice areas found.',
            'not_found_title' => 'Page not found',
            'not_found_body' => 'The page you requested could not be found. Please return to the homepage or use the site navigation.',
            'search_title' => 'Search results',
            'archive_title' => 'Latest legal insights',
            'single_title' => 'Article',
            'footer_note' => '© 2025 Law By Yan. All rights reserved.',
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
