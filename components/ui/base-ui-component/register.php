<?php

add_shortcode('base_ui_component', function ($atts, $content = null) {
    $attributes = shortcode_atts([
        'label' => '',
        'variant' => 'default',
        'url' => '',
        'tag' => 'div',
    ], $atts);

    if ($attributes['label'] === '' && $content !== null) {
        $attributes['label'] = trim(wp_strip_all_tags((string) $content));
    }

    ob_start();
    get_template_part('components/ui/base-ui-component/base-ui-component', null, $attributes);
    return ob_get_clean();
});
