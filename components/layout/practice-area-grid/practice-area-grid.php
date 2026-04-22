<?php
wp_enqueue_style(
    'practice-area-grid-css',
    get_template_directory_uri() . '/components/layout/practice-area-grid/practice-area-grid.css',
    array(),
    boilerplate_get_asset_version('components/layout/practice-area-grid/practice-area-grid.css')
);

$eyebrow = trim((string)($args['eyebrow'] ?? 'Practice Areas'));
$title = trim((string)($args['title'] ?? 'Focused legal support for the matters that move your life forward.'));
$body = trim((string)($args['body'] ?? 'Explore core services and find the right starting point for your situation.'));
$items = is_array($args['items'] ?? null) ? array_values($args['items']) : [];
?>

<section class="practice-area-grid" aria-labelledby="practice-area-grid-title">
    <div class="practice-area-grid__inner">
        <div class="practice-area-grid__header">
            <?php if ($eyebrow !== '') : ?>
                <p class="practice-area-grid__eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($title !== '') : ?>
                <h2 class="practice-area-grid__title" id="practice-area-grid-title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>
            <?php if ($body !== '') : ?>
                <p class="practice-area-grid__body"><?php echo esc_html($body); ?></p>
            <?php endif; ?>
        </div>

        <?php if (!empty($items)) : ?>
            <div class="practice-area-grid__items">
                <?php foreach ($items as $index => $item) :
                    $label = trim((string)($item['label'] ?? ''));
                    $description = trim((string)($item['description'] ?? ''));
                    $url = trim((string)($item['url'] ?? ''));

                    if ($label === '') {
                        continue;
                    }

                    $tag = $url !== '' ? 'a' : 'article';
                    ?>
                    <<?php echo esc_attr($tag); ?> class="practice-area-grid__item" <?php if ($url !== '') : ?>href="<?php echo esc_url($url); ?>"<?php endif; ?>>
                        <span class="practice-area-grid__number"><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
                        <span class="practice-area-grid__label"><?php echo esc_html($label); ?></span>
                        <?php if ($description !== '') : ?>
                            <span class="practice-area-grid__description"><?php echo esc_html($description); ?></span>
                        <?php endif; ?>
                    </<?php echo esc_attr($tag); ?>>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
