<?php
$header_data = function_exists('boilerplate_get_header_navigation_data')
    ? boilerplate_get_header_navigation_data()
    : [
        'request_uri' => wp_parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/',
        'menu_items' => [],
        'site_name' => get_bloginfo('name'),
        'site_tagline' => get_bloginfo('description'),
        'contact_url' => home_url('/'),
        'contact_details' => [],
        'drawer_summary' => get_bloginfo('description'),
    ];

$brand_label = trim((string)($header_data['site_name'] ?? ''));
if ($brand_label === '') {
    $brand_label = get_bloginfo('name');
}

$menu_items = is_array($header_data['menu_items'] ?? null) ? $header_data['menu_items'] : [];
$request_uri = (string)($header_data['request_uri'] ?? '/');
$site_tagline = trim((string)($header_data['site_tagline'] ?? ''));
$drawer_summary = trim((string)($header_data['drawer_summary'] ?? $site_tagline));
$contact_details = is_array($header_data['contact_details'] ?? null) ? $header_data['contact_details'] : [];
$nav_aria_label = $brand_label !== '' ? $brand_label . ' navigation' : 'Primary navigation';
$header_cta_label = function_exists('boilerplate_get_default_copy') ? boilerplate_get_default_copy('nav_cta', 'Contact') : 'Contact';
$header_cta_url = trim((string)($header_data['contact_url'] ?? home_url('/')));
$social_links = function_exists('boilerplate_get_site_social_links') ? boilerplate_get_site_social_links() : [];
$drawer_id = 'site-header-drawer';
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
        <div class="site-header__topbar">
            <div class="site-header__topbar-inner">
                <div class="site-header__topbar-contact">
                    <?php if (!empty($contact_details['phone']) && !empty($contact_details['phone_href'])) : ?>
                        <a href="<?php echo esc_url($contact_details['phone_href']); ?>">Call <?php echo esc_html($contact_details['phone']); ?></a>
                    <?php endif; ?>
                    <?php if (!empty($contact_details['email']) && !empty($contact_details['email_href'])) : ?>
                        <a href="<?php echo esc_url($contact_details['email_href']); ?>"><?php echo esc_html($contact_details['email']); ?></a>
                    <?php endif; ?>
                </div>

                <div class="site-header__topbar-actions">
                    <?php if (!empty($social_links)) : ?>
                        <div class="site-header__socials">
                            <?php foreach ($social_links as $social) : ?>
                                <a class="site-header__social" href="<?php echo esc_url($social['url'] ?? home_url('/')); ?>" aria-label="<?php echo esc_attr($social['label'] ?? 'Social profile'); ?>">
                                    <?php echo wp_kses($social['icon'] ?? '', ['svg' => ['viewBox' => true, 'fill' => true], 'path' => ['d' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true], 'rect' => ['x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'stroke' => true, 'stroke-width' => true], 'circle' => ['cx' => true, 'cy' => true, 'r' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true]]); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($contact_details['phone']) && !empty($contact_details['phone_href'])) : ?>
                        <a class="site-header__call-now" href="<?php echo esc_url($contact_details['phone_href']); ?>">Call Now</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="site-header__bar">
            <a class="site-header__brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr($brand_label . ' home'); ?>">
                <span class="site-header__brand-mark" aria-hidden="true"><?php echo esc_html(substr($brand_label, 0, 1)); ?></span>
                <span class="site-header__brand-text">
                    <span class="site-header__brand-name"><?php echo esc_html($brand_label); ?></span>
                    <?php if ($site_tagline !== '') : ?>
                        <span class="site-header__brand-tagline"><?php echo esc_html($site_tagline); ?></span>
                    <?php endif; ?>
                </span>
            </a>

            <?php if (!empty($menu_items) && function_exists('boilerplate_render_header_nav')) : ?>
                <nav class="site-header__nav" aria-label="<?php echo esc_attr($nav_aria_label); ?>">
                    <?php boilerplate_render_header_nav($menu_items, $request_uri); ?>
                </nav>
            <?php endif; ?>

            <div class="site-header__actions">
                <?php
                get_template_part('components/ui/base-ui-component/base-ui-component', null, [
                    'label' => $header_cta_label,
                    'variant' => 'accent',
                    'url' => $header_cta_url,
                ]);
                ?>
                <button class="site-header__menu-toggle" type="button" aria-controls="<?php echo esc_attr($drawer_id); ?>" aria-expanded="false">
                    <span class="site-header__menu-toggle-line"></span>
                    <span class="site-header__menu-toggle-line"></span>
                    <span class="site-header__menu-toggle-line"></span>
                    <span class="screen-reader-text">Menu</span>
                </button>
            </div>
        </div>

        <div class="site-header__drawer" id="<?php echo esc_attr($drawer_id); ?>" hidden>
            <div class="site-header__drawer-head">
                <span class="site-header__drawer-title"><?php echo esc_html($brand_label); ?></span>
                <?php if ($drawer_summary !== '') : ?>
                    <p class="site-header__drawer-summary"><?php echo esc_html($drawer_summary); ?></p>
                <?php endif; ?>
            </div>

            <?php if (!empty($menu_items) && function_exists('boilerplate_render_mobile_drawer_nav')) : ?>
                <nav class="site-header__drawer-nav" aria-label="<?php echo esc_attr($nav_aria_label); ?>">
                    <?php boilerplate_render_mobile_drawer_nav($menu_items, $request_uri); ?>
                </nav>
            <?php endif; ?>

            <div class="site-header__drawer-action">
                <?php
                get_template_part('components/ui/base-ui-component/base-ui-component', null, [
                    'label' => $header_cta_label,
                    'variant' => 'accent',
                    'url' => $header_cta_url,
                ]);
                ?>
            </div>

            <div class="site-header__drawer-contact">
                <?php if (!empty($contact_details['phone']) && !empty($contact_details['phone_href'])) : ?>
                    <a href="<?php echo esc_url($contact_details['phone_href']); ?>"><?php echo esc_html($contact_details['phone']); ?></a>
                <?php endif; ?>
                <?php if (!empty($contact_details['email']) && !empty($contact_details['email_href'])) : ?>
                    <a href="<?php echo esc_url($contact_details['email_href']); ?>"><?php echo esc_html($contact_details['email']); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </header>
