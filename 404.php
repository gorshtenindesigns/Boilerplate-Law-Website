<?php
get_header();
?>

<main class="site-main" id="content">
    <?php
    get_template_part('components/layout/base-layout-block/base-layout-block', null, [
        'variant' => 'error',
        'eyebrow' => boilerplate_get_default_copy('content_eyebrow'),
        'title' => boilerplate_get_default_copy('not_found_title'),
        'body' => wpautop(boilerplate_get_default_copy('not_found_body')),
        'links' => [
            [
                'label' => boilerplate_get_default_copy('nav_cta'),
                'url' => home_url('/'),
            ],
        ],
    ]);
    ?>
</main>

<?php get_footer(); ?>
