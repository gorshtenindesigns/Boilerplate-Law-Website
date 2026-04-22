<?php
/**
 * Template Name: Content Shortcode Template Child
 * Template Post Type: page
 */

get_header();

$content = get_post_field('post_content', get_the_ID());
$parsed_content = boilerplate_render_structured_content($content, [
    'layout' => 'child',
    'inject_components' => [],
    'post_id' => get_the_ID(),
]);
$hero_data = boilerplate_get_parsed_hero_data(get_the_title(), '');
?>

<main class="template-shell template-shell--child" id="content">
    <?php
    get_template_part('components/layout/base-layout-block/base-layout-block', null, [
        'variant' => 'child',
        'eyebrow' => boilerplate_get_default_copy('content_eyebrow'),
        'title' => $hero_data['h1'] ?? get_the_title(),
        'body' => $hero_data['excerpt'] !== '' ? $hero_data['excerpt'] : wpautop(boilerplate_get_default_copy('page_summary')),
    ]);
    ?>

    <?php if (!empty(trim($parsed_content))) : ?>
        <section class="template-shell__content">
            <?php echo $parsed_content; ?>
        </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
