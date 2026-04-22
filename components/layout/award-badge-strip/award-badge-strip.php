<?php
wp_enqueue_style(
    'award-badge-strip-css',
    get_template_directory_uri() . '/components/layout/award-badge-strip/award-badge-strip.css',
    array(),
    boilerplate_get_asset_version('components/layout/award-badge-strip/award-badge-strip.css')
);

$eyebrow = trim((string)($args['eyebrow'] ?? 'Awards & Recognition'));
$title = trim((string)($args['title'] ?? 'Recognized for clear counsel and client-focused legal service.'));
$items = is_array($args['items'] ?? null) ? array_values($args['items']) : [];
?>

<section class="award-badge-strip" aria-labelledby="award-badge-strip-title">
    <div class="award-badge-strip__inner">
        <div class="award-badge-strip__header">
            <?php if ($eyebrow !== '') : ?>
                <p class="award-badge-strip__eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($title !== '') : ?>
                <h2 class="award-badge-strip__title" id="award-badge-strip-title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>
        </div>

        <?php if (!empty($items)) : ?>
            <div class="award-badge-strip__items">
                <?php foreach ($items as $item) :
                    $label = trim((string)($item['label'] ?? ''));
                    $note = trim((string)($item['note'] ?? ''));
                    $initials = trim((string)($item['initials'] ?? ''));

                    if ($label === '') {
                        continue;
                    }
                    ?>
                    <article class="award-badge-strip__item">
                        <span class="award-badge-strip__badge" aria-hidden="true"><?php echo esc_html($initials !== '' ? $initials : substr($label, 0, 2)); ?></span>
                        <span class="award-badge-strip__label"><?php echo esc_html($label); ?></span>
                        <?php if ($note !== '') : ?>
                            <span class="award-badge-strip__note"><?php echo esc_html($note); ?></span>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
