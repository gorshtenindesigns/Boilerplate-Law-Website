<?php
get_header();

while (have_posts()) :
    the_post();
    $content = get_post_field('post_content', get_the_ID());
    $parsed_content = boilerplate_render_structured_content($content, [
        'layout' => 'generic',
        'inject_components' => [],
        'post_id' => get_the_ID(),
    ]);
    $hero_data = boilerplate_get_parsed_hero_data(get_the_title(), get_the_excerpt());
    $display_title = trim((string)($hero_data['h1'] ?? get_the_title()));
    if (strtolower($display_title) === 'services' || get_post_field('post_name', get_the_ID()) === 'services') {
        $display_title = 'Practice Areas';
    }
    $media_defaults = function_exists('boilerplate_get_theme_media_defaults') ? boilerplate_get_theme_media_defaults() : [];
    ?>
    <main class="template-shell template-shell--page" id="content">
        <?php
        get_template_part('components/layout/base-layout-block/base-layout-block', null, [
            'variant' => 'page',
            'eyebrow' => boilerplate_get_default_copy('content_eyebrow'),
            'title' => $display_title,
            'body' => $hero_data['excerpt'] !== '' ? $hero_data['excerpt'] : wpautop(boilerplate_get_default_copy('page_summary')),
            'media_image' => has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : ($media_defaults['wide_primary'] ?? ''),
            'media_alt' => $display_title,
        ]);
        ?>

        <section class="template-shell__content">
            <?php echo $parsed_content; ?>
        </section>
    </main>
<?php
endwhile;

get_footer();
