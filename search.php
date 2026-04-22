<?php
get_header();
$media_defaults = function_exists('boilerplate_get_theme_media_defaults') ? boilerplate_get_theme_media_defaults() : [];
?>

<main class="search-shell" id="content">
    <?php
    get_template_part('components/layout/base-layout-block/base-layout-block', null, [
        'variant' => 'search',
        'eyebrow' => boilerplate_get_default_copy('content_eyebrow'),
        'title' => boilerplate_get_default_copy('search_title'),
        'body' => wpautop(boilerplate_get_default_copy('page_summary')),
        'media_image' => $media_defaults['wide_primary'] ?? '',
        'media_alt' => boilerplate_get_default_copy('search_title'),
    ]);
    ?>

    <section class="search-shell__body">
        <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
            <label>
                <span class="screen-reader-text"><?php echo esc_html(boilerplate_get_default_copy('search_title')); ?></span>
                <input type="search" class="search-field" placeholder="<?php echo esc_attr(boilerplate_get_default_copy('search_title')); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s">
            </label>
        </form>

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
