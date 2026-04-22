<?php
$menu_items = function_exists('boilerplate_get_header_menu_items') ? boilerplate_get_header_menu_items() : [];
$site_identity = function_exists('boilerplate_get_site_identity')
    ? boilerplate_get_site_identity()
    : ['name' => get_bloginfo('name'), 'tagline' => get_bloginfo('description')];
$contact_details = function_exists('boilerplate_get_site_contact_details')
    ? boilerplate_get_site_contact_details()
    : ['contact_url' => home_url('/')];

$brand_label = trim((string)($site_identity['name'] ?? ''));
if ($brand_label === '') {
    $brand_label = get_bloginfo('name');
}

$nav_aria_label = $brand_label !== '' ? $brand_label . ' navigation' : 'Primary navigation';
$header_cta_label = boilerplate_get_default_copy('nav_cta', 'Contact');
$header_cta_url = trim((string)($contact_details['contact_url'] ?? home_url('/')));
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="site-shell">
    <header class="site-header">
        <a class="site-header__brand" href="<?php echo esc_url(home_url('/')); ?>">
            <?php echo esc_html($brand_label); ?>
        </a>

        <?php if (!empty($menu_items)) : ?>
            <nav class="site-header__nav" aria-label="<?php echo esc_attr($nav_aria_label); ?>">
                <?php foreach ($menu_items as $item) : ?>
                    <a class="site-header__link" href="<?php echo esc_url($item['url'] ?? home_url('/')); ?>">
                        <?php echo esc_html($item['label'] ?? 'Lorem'); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

        <?php
        get_template_part('components/ui/base-ui-component/base-ui-component', null, [
            'label' => $header_cta_label,
            'variant' => 'accent',
            'url' => $header_cta_url,
        ]);
        ?>
    </header>
