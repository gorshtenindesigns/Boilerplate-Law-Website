<?php
/**
 * Neutral content layout registry.
 *
 * Keeps legacy keys compatible by mapping them to the single base content block.
 */

function boilerplate_normalize_content_layout_key($key)
{
    $layout_key = sanitize_key($key);

    if ($layout_key === '') {
        return '';
    }

    $aliases = [
        'alpha' => 'base-content-block',
        'beta' => 'base-content-block',
        'gamma' => 'base-content-block',
        'delta' => 'base-content-block',
        'epsilon' => 'base-content-block',
        'contact' => 'base-content-block',
        'cta' => 'base-content-block',
        'design-1' => 'base-content-block',
        'design-2' => 'base-content-block',
        'design-3' => 'base-content-block',
        'design-4' => 'base-content-block',
        'design-5' => 'base-content-block',
        'design-6' => 'base-content-block',
        'design-7' => 'base-content-block',
        'design-8' => 'base-content-block',
        'design-cta' => 'base-content-block',
        'parent-section' => 'base-content-block',
        'parent-alpha' => 'base-content-block',
        'parent-beta' => 'base-content-block',
        'parent-gamma' => 'base-content-block',
        'parent-delta' => 'base-content-block',
        'parent-epsilon' => 'base-content-block',
        'parent-contact' => 'base-content-block',
        'child-section' => 'base-content-block',
    ];

    return $aliases[$layout_key] ?? $layout_key;
}

function boilerplate_render_section_fallback($section, $variant = 'default')
{
    if (function_exists('boilerplate_render_base_content_block')) {
        boilerplate_render_base_content_block($section);
        return;
    }

    $section_id = !empty($section['h2']) ? sanitize_title($section['h2']) : '';

    echo '<section ' . ($section_id !== '' ? 'id="' . esc_attr($section_id) . '" ' : '') . 'class="base-content-block base-content-block--fallback">';
    echo '<div class="base-content-block__inner">';

    if (!empty($section['h2'])) {
        echo '<h2 class="base-content-block__title">' . wp_kses_post($section['h2']) . '</h2>';
    }

    foreach (($section['blocks'] ?? []) as $block) {
        if (($block['type'] ?? '') === 'text') {
            echo '<div class="base-content-block__copy">' . wp_kses_post($block['content'] ?? '') . '</div>';
        }
    }

    echo '</div>';
    echo '</section>';
}

function boilerplate_get_content_layout_registry()
{
    if (!isset($GLOBALS['boilerplate_content_layout_registry']) || !is_array($GLOBALS['boilerplate_content_layout_registry'])) {
        $GLOBALS['boilerplate_content_layout_registry'] = [
            'layouts' => [],
            'contexts' => [],
        ];
    }

    return $GLOBALS['boilerplate_content_layout_registry'];
}

function boilerplate_get_filtered_content_layout_registry()
{
    $registry = apply_filters('boilerplate_content_layout_registry', boilerplate_get_content_layout_registry());

    if (!is_array($registry)) {
        return [
            'layouts' => [],
            'contexts' => [],
        ];
    }

    $registry['layouts'] = is_array($registry['layouts'] ?? null) ? $registry['layouts'] : [];
    $registry['contexts'] = is_array($registry['contexts'] ?? null) ? $registry['contexts'] : [];

    return $registry;
}

function boilerplate_register_content_layout($key, $args = [])
{
    $layout_key = sanitize_key($key);

    if ($layout_key === '') {
        return false;
    }

    $defaults = [
        'label' => '',
        'render_function' => '',
        'signature' => 'section',
        'render_args' => [],
        'fallback_layout' => '',
    ];

    $registry = boilerplate_get_content_layout_registry();
    $registry['layouts'][$layout_key] = wp_parse_args($args, $defaults);
    $GLOBALS['boilerplate_content_layout_registry'] = $registry;

    return true;
}

function boilerplate_register_content_layout_context($key, $args = [])
{
    $context_key = sanitize_key($key);

    if ($context_key === '') {
        return false;
    }

    $defaults = [
        'resolver' => '',
        'default_layout' => 'base-content-block',
        'fallback_layout' => 'base-content-block',
        'last_layout' => 'base-content-block',
    ];

    $registry = boilerplate_get_content_layout_registry();
    $registry['contexts'][$context_key] = wp_parse_args($args, $defaults);
    $GLOBALS['boilerplate_content_layout_registry'] = $registry;

    return true;
}

function boilerplate_get_registered_content_layout($key)
{
    $layout_key = sanitize_key($key);
    $registry = boilerplate_get_filtered_content_layout_registry();

    if ($layout_key === '' || empty($registry['layouts'][$layout_key]) || !is_array($registry['layouts'][$layout_key])) {
        return [];
    }

    return $registry['layouts'][$layout_key];
}

function boilerplate_get_registered_content_layout_context($key)
{
    $context_key = sanitize_key($key);
    $registry = boilerplate_get_filtered_content_layout_registry();

    if ($context_key === '' || empty($registry['contexts'][$context_key]) || !is_array($registry['contexts'][$context_key])) {
        return [];
    }

    return $registry['contexts'][$context_key];
}

function boilerplate_get_explicit_section_layout_key($section)
{
    if (!is_array($section)) {
        return '';
    }

    foreach (['layout_key', 'layout_slug', 'design_key', 'design_slug', 'renderer'] as $candidate_key) {
        if (empty($section[$candidate_key]) || !is_string($section[$candidate_key])) {
            continue;
        }

        $layout_key = boilerplate_normalize_content_layout_key($section[$candidate_key]);
        if ($layout_key !== '') {
            return $layout_key;
        }
    }

    return '';
}

function boilerplate_resolve_standard_content_layout_key($section, $index, $is_last, $context)
{
    $explicit_layout = boilerplate_get_explicit_section_layout_key($section);

    if ($explicit_layout !== '') {
        return $explicit_layout;
    }

    if ($is_last && !empty($context['last_layout'])) {
        return boilerplate_normalize_content_layout_key($context['last_layout']);
    }

    if (!empty($context['default_layout'])) {
        return boilerplate_normalize_content_layout_key($context['default_layout']);
    }

    return boilerplate_normalize_content_layout_key($context['fallback_layout'] ?? '');
}

function boilerplate_resolve_content_layout_key($context_key, $section, $index, $is_last)
{
    $context = boilerplate_get_registered_content_layout_context($context_key);

    if (empty($context)) {
        return 'base-content-block';
    }

    $resolver = $context['resolver'] ?? '';
    if (is_callable($resolver)) {
        return (string) call_user_func($resolver, $section, $index, $is_last, $context);
    }

    return boilerplate_normalize_content_layout_key($context['default_layout'] ?? 'base-content-block');
}

function boilerplate_call_registered_content_layout($layout, $section, $index, $is_last, $args = [])
{
    if (empty($layout) || !is_array($layout)) {
        return false;
    }

    $callback = $layout['render_function'] ?? '';
    if (!is_callable($callback)) {
        return false;
    }

    call_user_func($callback, $section, $index, $is_last, $args);
    return true;
}

function boilerplate_render_registered_content_layout($layout_key, $section, $index, $is_last)
{
    $layout = boilerplate_get_registered_content_layout($layout_key);

    if (boilerplate_call_registered_content_layout($layout, $section, $index, $is_last)) {
        return true;
    }

    return false;
}

function boilerplate_render_content_layout_for_context($context_key, $section, $index, $is_last)
{
    $resolved_layout_key = boilerplate_resolve_content_layout_key($context_key, $section, $index, $is_last);

    if ($resolved_layout_key !== '' && boilerplate_render_registered_content_layout($resolved_layout_key, $section, $index, $is_last)) {
        return;
    }

    boilerplate_render_section_fallback($section, $context_key ?: 'default');
}

function boilerplate_render_homepage_section($section, $index, $is_last)
{
    boilerplate_render_content_layout_for_context('home', $section, $index, $is_last);
}

function boilerplate_render_content_template_section($section, $index, $is_last, $layout = 'parent')
{
    $context_key = sanitize_key($layout ?: 'parent');
    if ($context_key === '') {
        $context_key = 'generic';
    }

    boilerplate_render_content_layout_for_context($context_key, $section, $index, $is_last);
}

function boilerplate_register_default_content_layouts()
{
    static $registered = false;

    if ($registered) {
        return;
    }

    $registered = true;

    boilerplate_register_content_layout('base-content-block', [
        'label' => 'Base Content Block',
        'render_function' => 'boilerplate_render_base_content_block',
        'signature' => 'section',
        'fallback_layout' => 'base-content-block',
    ]);

    foreach (['home', 'parent', 'child', 'generic'] as $context_key) {
        boilerplate_register_content_layout_context($context_key, [
            'resolver' => 'boilerplate_resolve_standard_content_layout_key',
            'default_layout' => 'base-content-block',
            'fallback_layout' => 'base-content-block',
            'last_layout' => 'base-content-block',
        ]);
    }
}
