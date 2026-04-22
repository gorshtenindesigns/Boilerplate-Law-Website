<?php
$footer_navigation = function_exists('boilerplate_get_footer_navigation_groups')
    ? boilerplate_get_footer_navigation_groups()
    : ['primary' => [], 'secondary' => []];

$site_identity = function_exists('boilerplate_get_site_identity')
    ? boilerplate_get_site_identity()
    : ['name' => get_bloginfo('name'), 'tagline' => get_bloginfo('description')];
$site_name = trim((string)($site_identity['name'] ?? get_bloginfo('name')));
$footer_note = boilerplate_get_default_copy('footer_note', trim((string)($site_identity['tagline'] ?? '')));
if ($footer_note === '') {
    $footer_note = $site_name;
}
?>
    <footer class="site-footer">
        <p><?php echo esc_html($footer_note); ?></p>

        <?php if (!empty($footer_navigation['primary'])) : ?>
            <nav class="site-footer__nav" aria-label="<?php echo esc_attr($site_name . ' footer navigation'); ?>">
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
