<?php
wp_enqueue_style(
    'home-content-overview-css',
    get_template_directory_uri() . '/components/layout/home-content-overview/home-content-overview.css',
    array(),
    boilerplate_get_asset_version('components/layout/home-content-overview/home-content-overview.css')
);

$eyebrow = trim((string)($args['eyebrow'] ?? 'Firm Overview'));
$title = trim((string)($args['title'] ?? 'A closer look at how the firm works for clients.'));
$body = trim((string)($args['body'] ?? ''));
$sections = is_array($args['sections'] ?? null) ? array_values($args['sections']) : [];
?>

<section class="home-content-overview" aria-labelledby="home-content-overview-title">
    <div class="home-content-overview__inner">
        <div class="home-content-overview__header">
            <?php if ($eyebrow !== '') : ?>
                <p class="home-content-overview__eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($title !== '') : ?>
                <h2 class="home-content-overview__title" id="home-content-overview-title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>
            <?php if ($body !== '') : ?>
                <p class="home-content-overview__body"><?php echo esc_html($body); ?></p>
            <?php endif; ?>
        </div>

        <?php if (!empty($sections)) : ?>
            <div class="home-content-overview__grid">
                <?php foreach ($sections as $index => $section) :
                    $section_title = trim((string)($section['title'] ?? ''));
                    $summary = trim((string)($section['summary'] ?? ''));
                    $points = is_array($section['points'] ?? null) ? array_values($section['points']) : [];

                    if ($section_title === '') {
                        continue;
                    }
                    ?>
                    <article class="home-content-overview__card">
                        <span class="home-content-overview__number"><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
                        <h3><?php echo esc_html($section_title); ?></h3>
                        <?php if ($summary !== '') : ?>
                            <div class="home-content-overview__summary"><?php echo wp_kses_post(wpautop($summary)); ?></div>
                        <?php endif; ?>

                        <?php if (!empty($points)) : ?>
                            <ul class="home-content-overview__points">
                                <?php foreach (array_slice($points, 0, 4) as $point) : ?>
                                    <li><?php echo esc_html($point); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
