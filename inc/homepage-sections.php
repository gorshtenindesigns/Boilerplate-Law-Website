<?php

function boilerplate_get_homepage_testimonials()
{
    return apply_filters('boilerplate_homepage_testimonials', [
        [
            'name' => 'Marissa L.',
            'location' => 'Los Angeles, CA',
            'quote' => 'The team explained every step, answered questions quickly, and made a stressful process feel organized from the first call.',
        ],
        [
            'name' => 'David R.',
            'location' => 'Beverly Hills, CA',
            'quote' => 'I appreciated the clear strategy and consistent updates. I never felt like my case was sitting in the dark.',
        ],
        [
            'name' => 'Nina P.',
            'location' => 'Santa Monica, CA',
            'quote' => 'Professional, direct, and easy to work with. They helped me understand my options before making a decision.',
        ],
        [
            'name' => 'Carlos M.',
            'location' => 'Pasadena, CA',
            'quote' => 'The consultation gave me clarity, and the follow-through after that was exactly what I needed.',
        ],
    ]);
}

function boilerplate_get_homepage_faqs()
{
    return apply_filters('boilerplate_homepage_faqs', [
        [
            'question' => 'What should I bring to an initial consultation?',
            'answer' => 'Bring any documents, notices, contracts, photos, messages, or timelines related to your matter. If you are unsure what matters, bring what you have and the attorney can help sort it.',
        ],
        [
            'question' => 'How quickly can I speak with someone?',
            'answer' => 'Most inquiry requests are reviewed promptly during business hours. Urgent matters should include the deadline, hearing date, or time-sensitive issue in the message.',
        ],
        [
            'question' => 'Do you handle matters across Los Angeles?',
            'answer' => 'Yes. The firm supports clients in Los Angeles and surrounding communities, including Beverly Hills, Santa Monica, Pasadena, Glendale, Burbank, Culver City, and Long Beach.',
        ],
        [
            'question' => 'How are fees discussed?',
            'answer' => 'Fees depend on the matter type and scope of work. During consultation, the attorney can explain available arrangements and what costs may be expected before work begins.',
        ],
        [
            'question' => 'Can I start if I do not know what kind of case I have?',
            'answer' => 'Yes. Many clients begin with a situation rather than a legal label. The consultation is designed to identify the issue, risks, options, and practical next steps.',
        ],
        [
            'question' => 'Will my consultation be confidential?',
            'answer' => 'Information shared for the purpose of seeking legal advice is treated with care. The attorney can explain confidentiality and representation terms before moving forward.',
        ],
    ]);
}

function boilerplate_render_star_icons($count = 5)
{
    $count = max(1, min(5, (int) $count));
    $svg = '<svg viewBox="0 0 20 20" aria-hidden="true"><path d="M10 1.8l2.5 5.1 5.6.8-4.1 4 .9 5.6L10 14.7l-5 2.6.9-5.6-4.1-4 5.6-.8L10 1.8z" fill="currentColor"/></svg>';

    for ($i = 0; $i < $count; $i++) {
        echo $svg;
    }
}

function boilerplate_render_homepage_testimonials()
{
    $testimonials = boilerplate_get_homepage_testimonials();

    if (empty($testimonials)) {
        return;
    }
    ?>
    <section class="home-testimonials" aria-labelledby="home-testimonials-title">
        <div class="home-testimonials__inner">
            <div class="home-testimonials__header">
                <p class="home-testimonials__eyebrow">Client Testimonials</p>
                <h2 class="home-testimonials__title" id="home-testimonials-title">Clients choose clarity, communication, and steady advocacy.</h2>
            </div>

            <div class="home-testimonials__grid">
                <?php foreach ($testimonials as $testimonial) : ?>
                    <article class="home-testimonials__card">
                        <div class="home-testimonials__stars" aria-label="5 out of 5 stars">
                            <?php boilerplate_render_star_icons(5); ?>
                        </div>
                        <blockquote class="home-testimonials__quote">
                            <?php echo esc_html($testimonial['quote'] ?? ''); ?>
                        </blockquote>
                        <footer class="home-testimonials__person">
                            <strong><?php echo esc_html($testimonial['name'] ?? 'Client'); ?></strong>
                            <span><?php echo esc_html($testimonial['location'] ?? 'Los Angeles, CA'); ?></span>
                        </footer>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

function boilerplate_render_homepage_faq()
{
    $faqs = boilerplate_get_homepage_faqs();

    if (empty($faqs)) {
        return;
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(function ($faq) {
            return [
                '@type' => 'Question',
                'name' => wp_strip_all_tags($faq['question'] ?? ''),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => wp_strip_all_tags($faq['answer'] ?? ''),
                ],
            ];
        }, $faqs),
    ];
    ?>
    <section class="home-faq" aria-labelledby="home-faq-title">
        <div class="home-faq__inner">
            <div class="home-faq__header">
                <p class="home-faq__eyebrow">Questions Clients Ask</p>
                <h2 class="home-faq__title" id="home-faq-title">Helpful answers before the first conversation.</h2>
                <p class="home-faq__body">Use these common questions as a starting point. Every matter is different, so consultation is where the details become clear.</p>
            </div>

            <div class="home-faq__grid">
                <?php foreach ($faqs as $faq) : ?>
                    <article class="home-faq__item">
                        <h3><?php echo esc_html($faq['question'] ?? ''); ?></h3>
                        <p><?php echo esc_html($faq['answer'] ?? ''); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <script type="application/ld+json"><?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
    <?php
}
