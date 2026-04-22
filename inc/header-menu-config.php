<?php
/**
 * Header menu configuration.
 *
 * Return an empty array by default so the theme falls back to
 * boilerplate_get_primary_navigation_items(), which auto-builds
 * navigation from published pages.
 */

function boilerplate_get_header_menu_config()
{
    return apply_filters('boilerplate_header_menu_config', []);
}
