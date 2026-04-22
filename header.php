<?php
$menu_items = function_exists('boilerplate_get_header_menu_items') ? boilerplate_get_header_menu_items() : [];
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
            <?php echo esc_html(boilerplate_get_default_copy('site_label')); ?>
        </a>

        <?php if (!empty($menu_items)) : ?>
            <nav class="site-header__nav" aria-label="Lorem ipsum">
                <?php foreach ($menu_items as $item) : ?>
                    <a class="site-header__link" href="<?php echo esc_url($item['url'] ?? home_url('/')); ?>">
                        <?php echo esc_html($item['label'] ?? 'Lorem'); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

        <?php
        get_template_part('components/ui/base-ui-component/base-ui-component', null, [
            'label' => boilerplate_get_default_copy('nav_cta'),
            'variant' => 'accent',
            'url' => '#content',
        ]);
        ?>
    </header>
