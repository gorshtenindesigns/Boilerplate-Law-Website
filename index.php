<?php
get_header();
?>

<main class="archive-shell" id="content">
    <?php
    get_template_part('components/layout/base-layout-block/base-layout-block', null, [
        'variant' => 'index',
        'eyebrow' => boilerplate_get_default_copy('content_eyebrow'),
        'title' => boilerplate_get_default_copy('archive_title'),
        'heading_level' => '1',
        'body' => wpautop(boilerplate_get_default_copy('page_summary')),
    ]);
    ?>

    <section class="archive-shell__body">
        <div class="post-list">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('post-list__item'); ?>>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <span class="post-list__meta"><?php echo esc_html(get_the_date()); ?></span>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <p><?php echo esc_html(boilerplate_get_default_copy('empty_results')); ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
