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
    ?>
    <main class="template-shell template-shell--page" id="content">
        <?php
        get_template_part('components/layout/base-layout-block/base-layout-block', null, [
            'variant' => 'page',
            'eyebrow' => boilerplate_get_default_copy('content_eyebrow'),
            'title' => $hero_data['h1'] ?? get_the_title(),
            'body' => $hero_data['excerpt'] !== '' ? $hero_data['excerpt'] : wpautop(boilerplate_get_default_copy('page_summary')),
        ]);
        ?>

        <section class="template-shell__content">
            <?php echo $parsed_content; ?>
        </section>
    </main>
<?php
endwhile;

get_footer();
