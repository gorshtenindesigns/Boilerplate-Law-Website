<?php
wp_enqueue_style(
    'law-hero-css',
    get_template_directory_uri() . '/components/layout/law-hero/law-hero.css',
    array(),
    boilerplate_get_asset_version('components/layout/law-hero/law-hero.css')
);

wp_enqueue_script(
    'law-hero-js',
    get_template_directory_uri() . '/components/layout/law-hero/law-hero.js',
    array(),
    boilerplate_get_asset_version('components/layout/law-hero/law-hero.js'),
    true
);

$eyebrow = trim((string)($args['eyebrow'] ?? ''));
$title = trim((string)($args['title'] ?? ''));
$excerpt = (string)($args['excerpt'] ?? '');
$primary_cta_label = trim((string)($args['primary_cta_label'] ?? ''));
$primary_cta_url = trim((string)($args['primary_cta_url'] ?? ''));
$secondary_cta_label = trim((string)($args['secondary_cta_label'] ?? ''));
$secondary_cta_url = trim((string)($args['secondary_cta_url'] ?? ''));
$stats = is_array($args['stats'] ?? null) ? array_values($args['stats']) : [];
$background_image = trim((string)($args['background_image'] ?? ''));
$featured_image = trim((string)($args['featured_image'] ?? ''));
$lead_form_title = trim((string)($args['lead_form_title'] ?? 'Start Your Consultation'));
$lead_form_body = trim((string)($args['lead_form_body'] ?? 'Tell us how to reach you and we will follow up with the next step.'));
$lead_form_action = trim((string)($args['lead_form_action'] ?? $primary_cta_url));
?>

<section class="law-hero" <?php if ($background_image) : ?>style="background-image: url('<?php echo esc_url($background_image); ?>');"<?php endif; ?> data-law-hero-ready="true">
    <div class="law-hero__overlay"></div>
    <div class="law-hero__inner">
        <div class="law-hero__content">
            <?php if ($eyebrow !== '') : ?>
                <p class="law-hero__eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <?php endif; ?>

            <?php if ($title !== '') : ?>
                <h1 class="law-hero__title"><?php echo esc_html($title); ?></h1>
            <?php endif; ?>

            <?php if ($excerpt !== '') : ?>
                <div class="law-hero__excerpt">
                    <?php echo wp_kses_post($excerpt); ?>
                </div>
            <?php endif; ?>

            <?php if ($primary_cta_label !== '' || $secondary_cta_label !== '') : ?>
                <div class="law-hero__actions">
                    <?php if ($primary_cta_label !== '' && $primary_cta_url !== '') : ?>
                        <a class="law-hero__cta law-hero__cta--primary" href="<?php echo esc_url($primary_cta_url); ?>">
                            <?php echo esc_html($primary_cta_label); ?>
                        </a>
                    <?php endif; ?>

                    <?php if ($secondary_cta_label !== '' && $secondary_cta_url !== '') : ?>
                        <a class="law-hero__cta law-hero__cta--secondary" href="<?php echo esc_url($secondary_cta_url); ?>">
                            <?php echo esc_html($secondary_cta_label); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($lead_form_action !== '' || !empty($stats)) : ?>
            <aside class="law-hero__side" aria-label="Consultation request">
                <?php if ($lead_form_action !== '') : ?>
                    <form class="law-hero__lead-form" action="<?php echo esc_url($lead_form_action); ?>" method="post">
                        <div class="law-hero__lead-header">
                            <?php if ($lead_form_title !== '') : ?>
                                <h2 class="law-hero__lead-title"><?php echo esc_html($lead_form_title); ?></h2>
                            <?php endif; ?>
                            <?php if ($lead_form_body !== '') : ?>
                                <p class="law-hero__lead-body"><?php echo esc_html($lead_form_body); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="law-hero__lead-fields">
                            <label class="law-hero__field">
                                <span>Name</span>
                                <input type="text" name="lead_name" autocomplete="name">
                            </label>
                            <label class="law-hero__field">
                                <span>Phone</span>
                                <input type="tel" name="lead_phone" autocomplete="tel">
                            </label>
                            <label class="law-hero__field law-hero__field--wide">
                                <span>Email</span>
                                <input type="email" name="lead_email" autocomplete="email">
                            </label>
                            <label class="law-hero__field law-hero__field--wide">
                                <span>How can we help?</span>
                                <textarea name="lead_message" rows="3"></textarea>
                            </label>
                        </div>

                        <button class="law-hero__submit" type="submit">Request Consultation</button>
                    </form>
                <?php endif; ?>

                <?php if (!empty($stats)) : ?>
                    <div class="law-hero__stats">
                        <?php foreach ($stats as $stat) :
                            $stat_value = trim((string)($stat['value'] ?? ''));
                            $stat_label = trim((string)($stat['label'] ?? ''));

                            if ($stat_value === '' && $stat_label === '') {
                                continue;
                            }
                            ?>
                            <div class="law-hero__stat-item">
                                <?php if ($stat_value !== '') : ?>
                                    <span class="law-hero__stat-value" data-count="<?php echo esc_attr($stat_value); ?>">
                                        <?php echo esc_html($stat_value); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($stat_label !== '') : ?>
                                    <span class="law-hero__stat-label"><?php echo esc_html($stat_label); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </aside>
        <?php endif; ?>

        <?php if ($featured_image !== '') : ?>
            <div class="law-hero__image">
                <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr($title); ?>" class="law-hero__image-tag" />
            </div>
        <?php endif; ?>
    </div>
</section>
