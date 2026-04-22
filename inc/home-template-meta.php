<?php

function boilerplate_is_home_template_post($post)
{
    $post = get_post($post);

    if (!$post instanceof WP_Post) {
        return false;
    }

    return get_post_meta($post->ID, '_wp_page_template', true) === 'home.php';
}

function boilerplate_homepage_get_active_meta_blocks()
{
    return boilerplate_get_active_homepage_blocks();
}

function boilerplate_homepage_register_meta_boxes()
{
    global $post;

    if (!boilerplate_is_home_template_post($post)) {
        return;
    }

    foreach (boilerplate_homepage_get_active_meta_blocks() as $slug => $block) {
        add_meta_box(
            'boilerplate_homepage_' . sanitize_html_class($slug) . '_meta',
            $block['meta_box_title'] ?: ($block['label'] ?? 'Homepage Block'),
            'boilerplate_homepage_meta_box_callback',
            'page',
            $block['meta_box_context'] ?? 'normal',
            $block['meta_box_priority'] ?? 'default',
            ['block_slug' => $slug]
        );
    }
}
add_action('add_meta_boxes', 'boilerplate_homepage_register_meta_boxes');

function boilerplate_homepage_render_field($post_id, $field)
{
    $field_id = sanitize_key($field['id'] ?? '');

    if ($field_id === '') {
        return;
    }

    $type = $field['type'] ?? 'text';
    $label = $field['label'] ?? $field_id;
    $placeholder = $field['placeholder'] ?? '';
    $description = $field['description'] ?? '';
    $value = get_post_meta($post_id, $field_id, true);

    echo '<div class="boilerplate-home-meta__field">';
    echo '<label for="' . esc_attr($field_id) . '" class="boilerplate-home-meta__label"><strong>' . esc_html($label) . '</strong></label>';

    if ($type === 'textarea') {
        echo '<textarea id="' . esc_attr($field_id) . '" name="' . esc_attr($field_id) . '" rows="4" class="widefat" placeholder="' . esc_attr($placeholder) . '">' . esc_textarea($value) . '</textarea>';
    } else {
        $input_type = $type === 'url' ? 'url' : 'text';
        echo '<input type="' . esc_attr($input_type) . '" id="' . esc_attr($field_id) . '" name="' . esc_attr($field_id) . '" value="' . esc_attr($value) . '" class="widefat" placeholder="' . esc_attr($placeholder) . '" />';
    }

    if ($description !== '') {
        echo '<p class="description">' . esc_html($description) . '</p>';
    }

    echo '</div>';
}

function boilerplate_homepage_meta_box_callback($post, $box)
{
    $block_slug = sanitize_title($box['args']['block_slug'] ?? '');
    $block = boilerplate_get_homepage_block($block_slug);

    wp_nonce_field('boilerplate_home_page_save_meta', 'boilerplate_home_page_meta_nonce');

    if (empty($block)) {
        echo '<p>No settings are registered for this homepage block.</p>';
        return;
    }

    if (!empty($block['description'])) {
        echo '<p>' . esc_html($block['description']) . '</p>';
    }

    $fields = is_array($block['meta_fields'] ?? null) ? $block['meta_fields'] : [];

    if (empty($fields)) {
        echo '<p>This block does not expose extra meta fields.</p>';
        return;
    }

    echo '<div class="boilerplate-home-meta">';

    foreach ($fields as $field) {
        boilerplate_homepage_render_field($post->ID, $field);
    }

    echo '</div>';

    static $printed_styles = false;

    if ($printed_styles) {
        return;
    }

    $printed_styles = true;

    echo '<style>
        .boilerplate-home-meta {
            display: grid;
            gap: 14px;
            margin-top: 12px;
        }
        .boilerplate-home-meta__field {
            padding: 12px;
            border: 1px solid #dcdcde;
            background: #fff;
        }
        .boilerplate-home-meta__label {
            display: block;
            margin-bottom: 8px;
        }
    </style>';
}

function boilerplate_homepage_sanitize_meta_value($raw_value, $field)
{
    $type = $field['type'] ?? 'text';
    $value = is_string($raw_value) ? wp_unslash($raw_value) : $raw_value;

    if ($type === 'textarea') {
        return sanitize_textarea_field((string)$value);
    }

    if ($type === 'url') {
        return esc_url_raw((string)$value);
    }

    return sanitize_text_field((string)$value);
}

function boilerplate_homepage_save_meta($post_id)
{
    if (!isset($_POST['boilerplate_home_page_meta_nonce']) || !wp_verify_nonce($_POST['boilerplate_home_page_meta_nonce'], 'boilerplate_home_page_save_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id) || !boilerplate_is_home_template_post($post_id)) {
        return;
    }

    foreach (boilerplate_homepage_get_active_meta_blocks() as $block) {
        foreach (($block['meta_fields'] ?? []) as $field) {
            $field_id = sanitize_key($field['id'] ?? '');

            if ($field_id === '' || !array_key_exists($field_id, $_POST)) {
                continue;
            }

            $sanitized_value = boilerplate_homepage_sanitize_meta_value($_POST[$field_id], $field);
            update_post_meta($post_id, $field_id, $sanitized_value);
        }
    }
}
add_action('save_post', 'boilerplate_homepage_save_meta');
