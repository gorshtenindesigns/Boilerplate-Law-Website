<?php
$footer_navigation = function_exists('boilerplate_get_footer_navigation_groups')
    ? boilerplate_get_footer_navigation_groups()
    : ['primary' => [], 'secondary' => []];
?>
    <footer class="site-footer">
        <p><?php echo esc_html(boilerplate_get_default_copy('footer_note')); ?></p>

        <?php if (!empty($footer_navigation['primary'])) : ?>
            <nav class="site-footer__nav" aria-label="Lorem ipsum">
                <?php foreach ($footer_navigation['primary'] as $link) : ?>
                    <a class="site-footer__link" href="<?php echo esc_url($link['url'] ?? home_url('/')); ?>">
                        <?php echo esc_html($link['label'] ?? 'Lorem'); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>
    </footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
