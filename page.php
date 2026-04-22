<?php
get_header();

while (have_posts()) :
    the_post();
    $post_id = get_the_ID();
    $post_slug = get_post_field('post_name', $post_id);
    $content = get_post_field('post_content', get_the_ID());
    $is_known_inner_page = in_array($post_slug, ['about', 'about-us', 'contact', 'contact-us', 'services', 'practice-areas'], true);
    $looks_like_homepage_content = stripos((string)$content, boilerplate_get_default_copy('home_title')) !== false;

    if ($is_known_inner_page && $looks_like_homepage_content) {
        $content = '';
    }

    $parsed_content = boilerplate_render_structured_content($content, [
        'layout' => 'generic',
        'inject_components' => [],
        'post_id' => $post_id,
    ]);
    $hero_data = boilerplate_get_parsed_hero_data(get_the_title(), get_the_excerpt());
    $display_title = trim((string)($hero_data['h1'] ?? get_the_title()));
    $hero_eyebrow = 'Law Firm';
    $fallback_body = boilerplate_get_default_copy('page_summary');

    if (in_array($post_slug, ['about', 'about-us'], true)) {
        $display_title = 'ABOUT LAW FIRM';
        $hero_eyebrow = 'About Us';
        $fallback_body = 'Learn about the firm, our client-first approach, and the standards that guide every consultation, strategy session, and case resolution.';
    } elseif (in_array($post_slug, ['contact', 'contact-us'], true)) {
        $display_title = 'CONTACT LAW FIRM';
        $hero_eyebrow = 'Contact';
        $fallback_body = 'Reach out to schedule a consultation, ask a question, or start a confidential conversation about your legal matter.';
    } elseif (strtolower($display_title) === 'services' || in_array($post_slug, ['services', 'practice-areas'], true)) {
        $display_title = 'Practice Areas';
        $hero_eyebrow = 'Legal Services';
        $fallback_body = 'Explore the firm’s core practice areas and the focused representation available for individuals, families, and businesses.';
    }

    $media_defaults = function_exists('boilerplate_get_theme_media_defaults') ? boilerplate_get_theme_media_defaults() : [];
    ?>
    <main class="template-shell template-shell--page" id="content">
        <?php
        get_template_part('components/layout/base-layout-block/base-layout-block', null, [
            'variant' => 'page',
            'eyebrow' => $hero_eyebrow,
            'title' => $display_title,
            'heading_level' => '1',
            'body' => $hero_data['excerpt'] !== '' ? $hero_data['excerpt'] : wpautop($fallback_body),
            'media_image' => has_post_thumbnail() ? get_the_post_thumbnail_url($post_id, 'large') : ($media_defaults['wide_primary'] ?? ''),
            'media_alt' => $display_title,
        ]);
        ?>

        <section class="template-shell__content">
            <?php if (!empty(trim($parsed_content))) : ?>
                <?php echo $parsed_content; ?>
            <?php elseif (in_array($post_slug, ['about', 'about-us'], true)) : ?>
                <article class="entry-content entry-content--cards">
                    <section class="inner-card">
                        <h2>Client-centered representation</h2>
                        <p>Our firm is built around clear communication, practical strategy, and steady guidance from intake through resolution.</p>
                    </section>
                    <section class="inner-card">
                        <h2>Modern legal service</h2>
                        <p>We combine careful legal analysis with responsive systems that keep clients informed and prepared at every stage.</p>
                    </section>
                </article>
            <?php elseif (in_array($post_slug, ['contact', 'contact-us'], true)) : ?>
                <article class="entry-content entry-content--cards">
                    <section class="inner-card">
                        <h2>Start a consultation</h2>
                        <p>Use the contact form, call the office, or send an email and the team will follow up with next steps.</p>
                    </section>
                    <section class="inner-card">
                        <h2>What to include</h2>
                        <p>Share the type of matter, important dates, and the best way to reach you so the first response is useful and focused.</p>
                    </section>
                </article>
            <?php elseif (in_array($post_slug, ['services', 'practice-areas'], true)) : ?>
                <article class="entry-content entry-content--cards">
                    <section class="inner-card">
                        <h2>Practice area parent page</h2>
                        <p>This page introduces the firm’s legal services. Child pages such as Criminal Law, DUI, Family Law, and Personal Injury can use the same inner-page structure with their own edited content.</p>
                    </section>
                </article>
            <?php endif; ?>
        </section>
    </main>
<?php
endwhile;

get_footer();
