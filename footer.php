<?php
$footer_navigation = function_exists('boilerplate_get_footer_navigation_groups')
    ? boilerplate_get_footer_navigation_groups()
    : ['primary' => [], 'secondary' => []];

$site_identity = function_exists('boilerplate_get_site_identity')
    ? boilerplate_get_site_identity()
    : ['name' => get_bloginfo('name'), 'tagline' => get_bloginfo('description')];
$site_name = trim((string)($site_identity['name'] ?? get_bloginfo('name')));
$site_tagline = trim((string)($site_identity['tagline'] ?? ''));
$contact_details = function_exists('boilerplate_get_site_contact_details')
    ? boilerplate_get_site_contact_details()
    : ['contact_url' => home_url('/')];
$social_links = function_exists('boilerplate_get_site_social_links') ? boilerplate_get_site_social_links() : [];
$media_defaults = function_exists('boilerplate_get_theme_media_defaults') ? boilerplate_get_theme_media_defaults() : [];
$contact_url = trim((string)($contact_details['contact_url'] ?? home_url('/')));
$phone = trim((string)($contact_details['phone'] ?? ''));
$phone_href = trim((string)($contact_details['phone_href'] ?? ''));
$email = trim((string)($contact_details['email'] ?? ''));
$email_href = trim((string)($contact_details['email_href'] ?? ''));
$address = trim((string)($contact_details['address'] ?? ''));
$address_href = trim((string)($contact_details['address_href'] ?? ''));
$footer_note = boilerplate_get_default_copy('footer_note', trim((string)($site_identity['tagline'] ?? '')));
if ($footer_note === '') {
    $footer_note = $site_name;
}
$service_areas = [
    'Los Angeles',
    'Beverly Hills',
    'Santa Monica',
    'Culver City',
    'Pasadena',
    'Glendale',
    'Burbank',
    'Long Beach',
];
?>
    <section class="site-service-map" aria-labelledby="site-service-map-title">
        <div class="site-service-map__inner">
            <div class="site-service-map__header">
                <p class="site-service-map__eyebrow">Service Areas</p>
                <h2 class="site-service-map__title" id="site-service-map-title">Serving Los Angeles and surrounding communities.</h2>
                <p class="site-service-map__body">A regional practice presence for clients across the LA area, from consultation through resolution.</p>
            </div>

            <div class="site-service-map__layout">
                <div class="site-service-map__visual" aria-hidden="true">
                    <div class="site-service-map__pin"><strong>Los Angeles</strong><span>Central matters</span></div>
                    <div class="site-service-map__pin"><strong>Beverly Hills</strong><span>Westside clients</span></div>
                    <div class="site-service-map__pin"><strong>Pasadena</strong><span>San Gabriel Valley</span></div>
                    <div class="site-service-map__pin"><strong>Santa Monica</strong><span>Coastal cases</span></div>
                    <div class="site-service-map__pin"><strong>Long Beach</strong><span>South Bay access</span></div>
                </div>

                <div class="site-service-map__areas">
                    <?php foreach ($service_areas as $service_area) : ?>
                        <span class="site-service-map__area"><?php echo esc_html($service_area); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="site-cta" aria-labelledby="site-cta-title">
        <div class="site-cta__inner">
            <div class="site-cta__content">
                <p class="site-cta__eyebrow"><?php echo esc_html(boilerplate_get_default_copy('home_eyebrow', 'Client-Centered Legal Practice')); ?></p>
                <h2 class="site-cta__title" id="site-cta-title">Ready to talk through your legal options?</h2>
                <p class="site-cta__body">Schedule a confidential consultation and get a clear next step for your matter.</p>
            </div>
            <div class="site-cta__actions">
                <a class="site-cta__button site-cta__button--primary" href="<?php echo esc_url($contact_url); ?>">
                    <?php echo esc_html(boilerplate_get_default_copy('nav_cta', 'Schedule a Consultation')); ?>
                </a>
                <?php if ($phone !== '' && $phone_href !== '') : ?>
                    <a class="site-cta__button site-cta__button--secondary" href="<?php echo esc_url($phone_href); ?>">
                        <?php echo esc_html($phone); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="site-footer">
        <div class="site-footer__inner">
            <div class="site-footer__brand-block">
                <a class="site-footer__brand" href="<?php echo esc_url(home_url('/')); ?>">
                    <span class="site-footer__brand-mark" aria-hidden="true"><?php echo esc_html(substr($site_name, 0, 1)); ?></span>
                    <span>
                        <span class="site-footer__brand-name"><?php echo esc_html($site_name); ?></span>
                        <?php if ($site_tagline !== '') : ?>
                            <span class="site-footer__brand-tagline"><?php echo esc_html($site_tagline); ?></span>
                        <?php endif; ?>
                    </span>
                </a>

                <div class="site-footer__contact-list">
                    <?php if ($address !== '') : ?>
                        <a class="site-footer__contact-item" href="<?php echo esc_url($address_href !== '' ? $address_href : home_url('/')); ?>">
                            <span class="site-footer__contact-kicker">Address</span>
                            <span><?php echo esc_html($address); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if ($phone !== '' && $phone_href !== '') : ?>
                        <a class="site-footer__contact-item" href="<?php echo esc_url($phone_href); ?>">
                            <span class="site-footer__contact-kicker">Phone</span>
                            <span><?php echo esc_html($phone); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if ($email !== '' && $email_href !== '') : ?>
                        <a class="site-footer__contact-item" href="<?php echo esc_url($email_href); ?>">
                            <span class="site-footer__contact-kicker">Email</span>
                            <span><?php echo esc_html($email); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($footer_navigation['primary'])) : ?>
                <nav class="site-footer__nav" aria-label="<?php echo esc_attr($site_name . ' footer navigation'); ?>">
                    <h2 class="site-footer__heading">Our Menu</h2>
                    <?php foreach ($footer_navigation['primary'] as $link) : ?>
                        <a class="site-footer__link" href="<?php echo esc_url($link['url'] ?? home_url('/')); ?>">
                            <?php echo esc_html($link['label'] ?? 'Lorem'); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>

            <?php if (!empty($footer_navigation['secondary'])) : ?>
                <nav class="site-footer__nav" aria-label="<?php echo esc_attr($site_name . ' secondary footer navigation'); ?>">
                    <h2 class="site-footer__heading">Practice Areas</h2>
                    <?php foreach ($footer_navigation['secondary'] as $link) : ?>
                        <a class="site-footer__link" href="<?php echo esc_url($link['url'] ?? home_url('/')); ?>">
                            <?php echo esc_html($link['label'] ?? 'Lorem'); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>

            <div class="site-footer__media">
                <?php if (!empty($media_defaults['square'])) : ?>
                    <img src="<?php echo esc_url($media_defaults['square']); ?>" alt="" loading="lazy">
                <?php endif; ?>
                <?php if (!empty($social_links)) : ?>
                    <div class="site-footer__socials">
                        <?php foreach ($social_links as $social) : ?>
                            <a class="site-footer__social" href="<?php echo esc_url($social['url'] ?? home_url('/')); ?>" aria-label="<?php echo esc_attr($social['label'] ?? 'Social profile'); ?>">
                                <?php echo wp_kses($social['icon'] ?? '', ['svg' => ['viewBox' => true, 'fill' => true], 'path' => ['d' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true], 'rect' => ['x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'stroke' => true, 'stroke-width' => true], 'circle' => ['cx' => true, 'cy' => true, 'r' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true]]); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="site-footer__bottom">
            <p><?php echo esc_html($footer_note); ?></p>
        </div>
    </footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
