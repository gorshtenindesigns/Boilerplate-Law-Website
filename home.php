<?php
/**
 * Template Name: Home Page
 */

get_header();

if (is_home() && !is_front_page()) :
    $media_defaults = function_exists('boilerplate_get_theme_media_defaults') ? boilerplate_get_theme_media_defaults() : [];
    $posts_page_id = (int)get_option('page_for_posts');
    $blog_title = $posts_page_id > 0 ? get_the_title($posts_page_id) : 'Blog';
    $blog_body = $posts_page_id > 0 ? get_the_excerpt($posts_page_id) : '';
    if ($blog_body === '') {
        $blog_body = 'Read legal insights, firm updates, and practical guidance for clients preparing for important decisions.';
    }
    ?>
    <main class="archive-shell archive-shell--blog" id="content">
        <?php
        get_template_part('components/layout/base-layout-block/base-layout-block', null, [
            'variant' => 'archive',
            'eyebrow' => 'Legal Insights',
            'title' => $blog_title !== '' ? $blog_title : 'Blog',
            'heading_level' => '1',
            'body' => wpautop($blog_body),
            'media_image' => $media_defaults['wide_secondary'] ?? '',
            'media_alt' => $blog_title !== '' ? $blog_title : 'Blog',
        ]);
        ?>

        <section class="archive-shell__body">
            <div class="post-list post-list--cards">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <article <?php post_class('post-list__item'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <a class="post-list__media" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                                    <?php the_post_thumbnail('large'); ?>
                                </a>
                            <?php endif; ?>
                            <div class="post-list__content">
                                <span class="post-list__meta"><?php echo esc_html(get_the_date()); ?></span>
                                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <?php if (get_the_excerpt() !== '') : ?>
                                    <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 24, '...')); ?></p>
                                <?php endif; ?>
                                <a class="post-list__read-more" href="<?php the_permalink(); ?>">Read Article</a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php else : ?>
                    <p><?php echo esc_html(boilerplate_get_default_copy('empty_results', 'No posts found.')); ?></p>
                <?php endif; ?>
            </div>

            <?php the_posts_pagination([
                'mid_size' => 1,
                'prev_text' => 'Previous',
                'next_text' => 'Next',
            ]); ?>
        </section>
    </main>
    <?php
    get_footer();
    return;
endif;

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
