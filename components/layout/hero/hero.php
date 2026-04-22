<?php
wp_enqueue_style(
    'hero-css',
    get_template_directory_uri() . '/components/layout/hero/hero.css',
    array(),
    boilerplate_get_asset_version('components/layout/hero/hero.css')
);

wp_enqueue_script(
    'hero-js',
    get_template_directory_uri() . '/components/layout/hero/hero.js',
    array(),
    boilerplate_get_asset_version('components/layout/hero/hero.js'),
    true
);

$eyebrow = trim((string)($args['eyebrow'] ?? ''));
$title = trim((string)($args['title'] ?? ''));
$excerpt = trim((string)($args['excerpt'] ?? ''));
$background_color = trim((string)($args['background_color'] ?? '#f5f7fb'));
$text_color = trim((string)($args['text_color'] ?? '#172033'));
$accent_color = trim((string)($args['accent_color'] ?? '#2563eb'));
?>

<section class="hero" style="background-color: <?php echo esc_attr($background_color); ?>; color: <?php echo esc_attr($text_color); ?>;">
    <div class="hero__inner">
        <?php if ($eyebrow !== '') : ?>
            <p class="hero__eyebrow" style="color: <?php echo esc_attr($accent_color); ?>;">
                <?php echo esc_html($eyebrow); ?>
            </p>
        <?php endif; ?>

        <?php if ($title !== '') : ?>
            <h1 class="hero__title">
                <?php echo esc_html($title); ?>
            </h1>
        <?php endif; ?>

        <?php if ($excerpt !== '') : ?>
            <div class="hero__excerpt">
                <?php echo wp_kses_post($excerpt); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
