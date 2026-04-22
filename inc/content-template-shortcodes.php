<?php
/**
 * Structured content shortcode bootstrap.
 */

use LLG\Boilerplate\Content\ContentContext;

boilerplate_register_default_content_layouts();

function boilerplate_render_content_page_toc($sections)
{
    $toc_items = [];

    foreach ($sections as $section) {
        if (empty($section['h2'])) {
            continue;
        }

        $toc_items[] = [
            'label' => wp_strip_all_tags($section['h2']),
            'slug' => sanitize_title($section['h2']),
        ];
    }

    if (empty($toc_items)) {
        return;
    }

    echo '<nav class="content-page-layout__toc" aria-label="Lorem ipsum">';
    echo '<ul class="content-page-layout__toc-list">';

    foreach ($toc_items as $item) {
        echo '<li class="content-page-layout__toc-item">';
        echo '<a class="content-page-layout__toc-link" href="#' . esc_attr($item['slug']) . '">' . esc_html($item['label']) . '</a>';
        echo '</li>';
    }

    echo '</ul>';
    echo '</nav>';
}

function boilerplate_render_parsed_content_page($content_context, $layout = '', $inject_components = [])
{
    $sections = [];
    $layout_key = sanitize_key($layout ?: 'parent');
    $injected_components = is_array($inject_components) ? $inject_components : [];

    if ($content_context instanceof ContentContext) {
        $sections = $content_context->getSections();
        $layout_key = sanitize_key($layout ?: $content_context->getLayout() ?: 'parent');
        if (empty($injected_components)) {
            $injected_components = $content_context->getInjectComponents();
        }
        boilerplate_set_content_context($content_context);
    } elseif (is_array($content_context)) {
        $sections = is_array($content_context['sections'] ?? null) ? $content_context['sections'] : [];

        $fallback_context = boilerplate_get_content_context();
        if (!$fallback_context instanceof ContentContext) {
            $fallback_context = new ContentContext();
        }

        $fallback_context->setHero($content_context['hero'] ?? []);
        $fallback_context->setSections($sections);
        $fallback_context->setLayout($layout_key);
        $fallback_context->setInjectComponents($injected_components);

        boilerplate_set_content_context($fallback_context);
    }

    $should_render_toc = ($layout_key !== 'home');

    ob_start();

    echo '<div class="content-page-layout">';

    if ($should_render_toc) {
        boilerplate_render_content_page_toc($sections);
    }

    echo '<div class="content-page-sections">';

    foreach ($sections as $index => $section) {
        $is_last = ($index === count($sections) - 1);
        $injected_slug = isset($injected_components[$index]) ? sanitize_title($injected_components[$index]) : '';

        if ($layout_key === 'home') {
            boilerplate_render_homepage_section($section, $index, $is_last);
        } else {
            boilerplate_render_content_template_section($section, $index, $is_last, $layout_key ?: 'parent');
        }

        if ($injected_slug !== '') {
            echo '<div class="content-block-appended-component">';
            boilerplate_render_component($injected_slug);
            echo '</div>';
        }

        if (!empty($section['append_component'])) {
            echo '<div class="content-block-appended-component">';
            boilerplate_render_component($section['append_component']);
            echo '</div>';
        }
    }

    echo '</div>';
    echo '</div>';

    return ob_get_clean();
}

add_shortcode('append_component', function ($atts) {
    $atts = shortcode_atts([
        'slug' => '',
    ], $atts);

    $slug = sanitize_title($atts['slug']);

    if ($slug === '') {
        return '';
    }

    return '<div data-append-component="' . esc_attr($slug) . '"></div>';
});

add_shortcode('content_page', function ($atts, $content = null) {
    if (empty($content)) {
        return '';
    }

    $atts = shortcode_atts([
        'layout' => '',
    ], $atts);

    $layout = sanitize_key($atts['layout']);

    $content = do_shortcode($content);
    $content = boilerplate_sanitize_content_page_html($content);

    $prepared_content = boilerplate_prepare_content_page_markup($content);
    $content_context = boilerplate_get_content_context();

    if (!$content_context instanceof ContentContext) {
        $content_context = new ContentContext();
    }

    if ($layout !== '') {
        $content_context->setLayout($layout);
    }

    if ($content_context->getPostId() === null) {
        $post_id = function_exists('get_the_ID') ? (int) get_the_ID() : 0;
        if ($post_id <= 0 && function_exists('get_queried_object_id')) {
            $post_id = (int) get_queried_object_id();
        }
        $content_context->setPostId($post_id > 0 ? $post_id : null);
    }

    $parsed_context = boilerplate_parse_content_page($content, $prepared_content, $content_context);

    if (!$parsed_context instanceof ContentContext) {
        return $prepared_content;
    }

    if ($layout !== '') {
        $parsed_context->setLayout($layout);
    }

    boilerplate_set_content_context($parsed_context);

    return boilerplate_render_parsed_content_page($parsed_context, $layout);
});
