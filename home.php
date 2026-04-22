<?php
/**
 * Template Name: Home Page
 */

get_header();

$content = get_post_field('post_content', get_the_ID());
$parsed_content = boilerplate_render_structured_content($content, [
    'layout' => 'home',
    'inject_components' => [],
    'post_id' => get_the_ID(),
]);

$hero_data = boilerplate_get_parsed_hero_data(get_the_title(), get_the_excerpt());
$homepage_context = [
    'hero_data' => $hero_data,
];
$homepage_blocks = boilerplate_get_active_homepage_blocks();
?>

<main class="site-main site-main--home" id="content">
    <?php foreach ($homepage_blocks as $block_slug => $block_config) : ?>
        <?php
        $block_args = boilerplate_get_homepage_block_render_args($block_slug, get_the_ID(), $homepage_context);
        $block_type = $block_config['render_type'] ?? 'layout';
        boilerplate_render_component($block_slug, $block_args, $block_type);
        ?>
    <?php endforeach; ?>

    <?php if (!empty(trim($parsed_content))) : ?>
        <section class="template-shell template-shell--home">
            <?php echo $parsed_content; ?>
        </section>
    <?php endif; ?>

    <?php
    if (function_exists('boilerplate_render_homepage_testimonials')) {
        boilerplate_render_homepage_testimonials();
    }

    if (function_exists('boilerplate_render_homepage_faq')) {
        boilerplate_render_homepage_faq();
    }
    ?>
</main>

<?php get_footer(); ?>
