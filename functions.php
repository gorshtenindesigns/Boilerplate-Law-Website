<?php

require_once get_template_directory() . '/inc/classes/Content/ContentContext.php';
require_once get_template_directory() . '/inc/boilerplate-defaults.php';
require_once get_template_directory() . '/inc/theme-helpers.php';
require_once get_template_directory() . '/inc/header-menu-config.php';
require_once get_template_directory() . '/inc/header-navigation.php';
require_once get_template_directory() . '/inc/content-layout-registry.php';
require_once get_template_directory() . '/inc/content-page-parser.php';
require_once get_template_directory() . '/inc/create-test-content.php';

add_action('after_setup_theme', function () {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'boilerplate-style',
        get_stylesheet_uri(),
        array(),
        boilerplate_get_asset_version('style.css')
    );

    wp_enqueue_style(
        'boilerplate-global',
        get_template_directory_uri() . '/assets/css/_global.css',
        array('boilerplate-style'),
        boilerplate_get_asset_version('assets/css/_global.css')
    );

    wp_add_inline_style('boilerplate-global', boilerplate_get_theme_css_variables());

    wp_enqueue_script(
        'boilerplate-index',
        get_template_directory_uri() . '/assets/js/index.js',
        array(),
        boilerplate_get_asset_version('assets/js/index.js'),
        true
    );
}, 20);

add_action('init', function () {
    if (is_admin()) {
        return;
    }

    remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
    remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
}, 20);

add_action('wp_enqueue_scripts', function () {
    if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return;
    }

    foreach ([
        'global-styles',
        'wp-block-library',
        'wp-block-library-theme',
        'classic-theme-styles',
        'navigation-style-handle',
    ] as $handle) {
        wp_dequeue_style($handle);
        wp_deregister_style($handle);
    }
}, 100);

$component_registers = array_merge(
    glob(get_template_directory() . '/components/layout/*/register.php') ?: [],
    glob(get_template_directory() . '/components/content-blocks/*/register.php') ?: [],
    glob(get_template_directory() . '/components/ui/*/register.php') ?: []
);

foreach ($component_registers as $file) {
    require_once $file;
}

$home_meta_path = get_template_directory() . '/inc/home-template-meta.php';
if (file_exists($home_meta_path)) {
    require_once $home_meta_path;
}

require_once get_template_directory() . '/inc/content-template-shortcodes.php';

// Auto-create test content on theme activation
add_action('after_switch_theme', 'create_boilerplate_test_content');

add_filter('content_save_pre', function ($content) {
    return boilerplate_strip_content_page_shortcodes($content);
}, 20);
