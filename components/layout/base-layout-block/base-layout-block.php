<?php
wp_enqueue_style(
    'base-layout-block-css',
    get_template_directory_uri() . '/components/layout/base-layout-block/base-layout-block.css',
    array(),
    boilerplate_get_asset_version('components/layout/base-layout-block/base-layout-block.css')
);

wp_enqueue_script(
    'base-layout-block-js',
    get_template_directory_uri() . '/components/layout/base-layout-block/base-layout-block.js',
    array(),
    boilerplate_get_asset_version('components/layout/base-layout-block/base-layout-block.js'),
    true
);

$variant = sanitize_html_class($args['variant'] ?? 'default');
$eyebrow = trim((string)($args['eyebrow'] ?? ''));
$title = trim((string)($args['title'] ?? ''));
$body = (string)($args['body'] ?? '');
$items = is_array($args['items'] ?? null) ? array_values($args['items']) : [];
$links = is_array($args['links'] ?? null) ? array_values($args['links']) : [];
?>

<section class="base-layout-block base-layout-block--<?php echo esc_attr($variant); ?>">
    <div class="base-layout-block__inner">
        <div class="base-layout-block__content">
            <?php if ($eyebrow !== '') : ?>
                <p class="base-layout-block__eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <?php endif; ?>

            <?php if ($title !== '') : ?>
                <h2 class="base-layout-block__title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <?php if ($body !== '') : ?>
                <div class="base-layout-block__body">
                    <?php echo wp_kses_post($body); ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($items) || !empty($links)) : ?>
            <div class="base-layout-block__aside">
                <?php if (!empty($items)) : ?>
                    <div class="base-layout-block__meta-grid">
                        <?php foreach ($items as $item) :
                            $item_label = trim((string)($item['label'] ?? ''));
                            $item_value = trim((string)($item['value'] ?? ''));

                            if ($item_label === '' && $item_value === '') {
                                continue;
                            }
                            ?>
                            <article class="base-layout-block__meta-item">
                                <?php if ($item_value !== '') : ?>
                                    <span class="base-layout-block__meta-value"><?php echo esc_html($item_value); ?></span>
                                <?php endif; ?>
                                <?php if ($item_label !== '') : ?>
                                    <span class="base-layout-block__meta-label"><?php echo esc_html($item_label); ?></span>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($links)) : ?>
                    <div class="base-layout-block__actions">
                        <?php foreach ($links as $link) :
                            $label = trim((string)($link['label'] ?? ''));
                            $url = trim((string)($link['url'] ?? ''));

                            if ($label === '' || $url === '') {
                                continue;
                            }
                            ?>
                            <a class="base-layout-block__action" href="<?php echo esc_url($url); ?>">
                                <?php echo esc_html($label); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
