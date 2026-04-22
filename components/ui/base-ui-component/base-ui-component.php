<?php
wp_enqueue_style(
    'base-ui-component-css',
    get_template_directory_uri() . '/components/ui/base-ui-component/base-ui-component.css',
    array(),
    boilerplate_get_asset_version('components/ui/base-ui-component/base-ui-component.css')
);

wp_enqueue_script(
    'base-ui-component-js',
    get_template_directory_uri() . '/components/ui/base-ui-component/base-ui-component.js',
    array(),
    boilerplate_get_asset_version('components/ui/base-ui-component/base-ui-component.js'),
    true
);

$tag = in_array($args['tag'] ?? '', ['span', 'div', 'p', 'button'], true) ? $args['tag'] : 'div';
$label = trim((string)($args['label'] ?? ''));
$variant = sanitize_html_class($args['variant'] ?? 'default');
$url = trim((string)($args['url'] ?? ''));
?>

<?php if ($url !== '') : ?>
    <a class="base-ui-component base-ui-component--<?php echo esc_attr($variant); ?>" href="<?php echo esc_url($url); ?>">
        <?php echo esc_html($label); ?>
    </a>
<?php else : ?>
    <<?php echo esc_attr($tag); ?> class="base-ui-component base-ui-component--<?php echo esc_attr($variant); ?>">
        <?php echo esc_html($label); ?>
    </<?php echo esc_attr($tag); ?>>
<?php endif; ?>
