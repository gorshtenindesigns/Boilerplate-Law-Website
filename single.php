<?php
get_header();

while (have_posts()) :
    the_post();
    ?>
    <main class="single-shell" id="content">
        <?php
        get_template_part('components/layout/base-layout-block/base-layout-block', null, [
            'variant' => 'single',
            'eyebrow' => boilerplate_get_default_copy('content_eyebrow'),
            'title' => get_the_title() ?: boilerplate_get_default_copy('single_title'),
            'body' => get_the_excerpt() !== '' ? get_the_excerpt() : wpautop(boilerplate_get_default_copy('page_summary')),
        ]);
        ?>

        <article <?php post_class('single-shell__body entry-content'); ?>>
            <?php the_content(); ?>
        </article>
    </main>
<?php
endwhile;

get_footer();
