<?php
/**
 * Structured parser helpers for the [content_page] shortcode.
 */

use LLG\Boilerplate\Content\ContentContext;

function boilerplate_log_content_page_parse_issue($stage, $message, $context = [])
{
    do_action('boilerplate_content_page_parse_issue', $stage, $message, $context);

    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[boilerplate content parser][' . $stage . '] ' . $message);
    }
}

function boilerplate_content_page_get_inner_html($node)
{
    $html = '';

    foreach ($node->childNodes as $child) {
        $html .= $node->ownerDocument->saveHTML($child);
    }

    return trim($html);
}

function boilerplate_content_page_get_clean_text($node)
{
    return trim($node->textContent);
}

function boilerplate_content_page_allowed_tags()
{
    return '<p><a><ul><ol><li><br><strong><em><b><i>';
}

function boilerplate_sanitize_content_page_html($content)
{
    $allowed_html = wp_kses_allowed_html('post');

    foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $tag) {
        if (!isset($allowed_html[$tag]) || !is_array($allowed_html[$tag])) {
            $allowed_html[$tag] = [];
        }

        $allowed_html[$tag]['data-layout'] = true;
    }

    if (!isset($allowed_html['div']) || !is_array($allowed_html['div'])) {
        $allowed_html['div'] = [];
    }

    $allowed_html['div']['data-append-component'] = true;

    return wp_kses($content, $allowed_html);
}

function boilerplate_content_page_get_heading_layout_key($node)
{
    if (!($node instanceof DOMElement) || !$node->hasAttribute('data-layout')) {
        return '';
    }

    return boilerplate_normalize_content_layout_key($node->getAttribute('data-layout'));
}

function boilerplate_prepare_content_context($context = null)
{
    if ($context instanceof ContentContext) {
        return $context;
    }

    $post_id = function_exists('get_the_ID') ? (int) get_the_ID() : 0;
    if ($post_id <= 0 && function_exists('get_queried_object_id')) {
        $post_id = (int) get_queried_object_id();
    }

    return new ContentContext([
        'postId' => $post_id > 0 ? $post_id : null,
    ]);
}

function boilerplate_content_context_from_parsed_data(array $parsed_data, $context = null)
{
    $content_context = boilerplate_prepare_content_context($context);

    $content_context->setHero($parsed_data['hero'] ?? []);
    $content_context->setSections($parsed_data['sections'] ?? []);

    return $content_context;
}

function boilerplate_normalize_content_page_markup($content)
{
    if (!is_string($content) || $content === '') {
        return '';
    }

    $pre_dom = new DOMDocument();
    $previous_errors = libxml_use_internal_errors(true);
    $loaded = $pre_dom->loadHTML('<?xml encoding="utf-8" ?><div>' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $errors = libxml_get_errors();
    libxml_clear_errors();
    libxml_use_internal_errors($previous_errors);

    if (!$loaded) {
        boilerplate_log_content_page_parse_issue('normalize', 'Unable to normalize incoming content markup.', [
            'errors' => $errors,
        ]);
        return $content;
    }

    $pre_wrapper = $pre_dom->getElementsByTagName('div')->item(0);
    if (!$pre_wrapper) {
        return $content;
    }

    $children = [];
    foreach ($pre_wrapper->childNodes as $child) {
        $children[] = $child;
    }

    $inline_tags = ['span', 'a', 'strong', 'em', 'b', 'i'];
    $groups = [];
    $current_group = [];

    foreach ($children as $i => $child) {
        $is_inline = ($child->nodeType === XML_ELEMENT_NODE && in_array(strtolower($child->tagName), $inline_tags, true));
        $is_text = ($child->nodeType === XML_TEXT_NODE);

        if ($is_inline) {
            $current_group[] = $i;
        } elseif ($is_text && !empty($current_group)) {
            $current_group[] = $i;
        } elseif ($is_text && trim($child->textContent) !== '') {
            $current_group[] = $i;
        } else {
            if (!empty($current_group)) {
                $groups[] = $current_group;
                $current_group = [];
            }
        }
    }

    if (!empty($current_group)) {
        $groups[] = $current_group;
    }

    foreach (array_reverse($groups) as $group) {
        $p = $pre_dom->createElement('p');
        $first_node = $children[$group[0]];
        $pre_wrapper->insertBefore($p, $first_node);

        foreach ($group as $idx) {
            $node = $children[$idx];

            if ($node->nodeType === XML_ELEMENT_NODE && strtolower($node->tagName) === 'span') {
                while ($node->firstChild) {
                    $p->appendChild($node->firstChild);
                }
                $pre_wrapper->removeChild($node);
                continue;
            }

            $p->appendChild($node);
        }
    }

    $normalized_content = '';
    foreach ($pre_wrapper->childNodes as $child) {
        $normalized_content .= $pre_dom->saveHTML($child);
    }

    return $normalized_content;
}

function boilerplate_prepare_content_page_markup($content)
{
    if (!is_string($content) || $content === '') {
        return '';
    }

    return wpautop(boilerplate_normalize_content_page_markup($content));
}

function boilerplate_parse_content_page($content, $prepared_content = null, $context = null)
{
    if (!is_string($content) || trim($content) === '') {
        return false;
    }

    $cache_key = 'parser_v1_' . md5($content);
    $cached = wp_cache_get($cache_key, 'boilerplate_content_page');

    if (is_array($cached)) {
        return boilerplate_content_context_from_parsed_data($cached, $context);
    }

    $prepared_markup = is_string($prepared_content) && $prepared_content !== ''
        ? $prepared_content
        : boilerplate_prepare_content_page_markup($content);

    $previous_errors = libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $html_to_parse = '<div>' . $prepared_markup . '</div>';
    $loaded = $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html_to_parse, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $errors = libxml_get_errors();
    libxml_clear_errors();
    libxml_use_internal_errors($previous_errors);

    if (!$loaded) {
        boilerplate_log_content_page_parse_issue('parse', 'Unable to load prepared content into DOMDocument.', [
            'errors' => $errors,
        ]);
        return false;
    }

    $wrapper = $dom->getElementsByTagName('div')->item(0);
    if (!$wrapper) {
        boilerplate_log_content_page_parse_issue('wrapper', 'Prepared content did not yield a wrapper node.');
        return false;
    }

    $block_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'img', 'figure', 'picture'];
    $top_children = [];
    foreach ($wrapper->childNodes as $child) {
        $top_children[] = $child;
    }

    foreach ($top_children as $child) {
        if ($child->nodeType !== XML_ELEMENT_NODE || strtolower($child->tagName) !== 'div') {
            continue;
        }

        $has_blocks = false;
        foreach ($block_tags as $block_tag) {
            if ($child->getElementsByTagName($block_tag)->length > 0) {
                $has_blocks = true;
                break;
            }
        }

        if (!$has_blocks) {
            continue;
        }

        $fragment = $dom->createDocumentFragment();
        while ($child->firstChild) {
            $fragment->appendChild($child->firstChild);
        }
        $wrapper->replaceChild($fragment, $child);
    }

    $parsed_data = [
        'hero' => [
            'h1' => '',
            'excerpt' => '',
        ],
        'sections' => [],
    ];

    $current_section = null;
    $current_accordion = null;
    $current_subheading = null;
    $hero_h1_found = false;
    $allowed_tags = boilerplate_content_page_allowed_tags();

    foreach ($wrapper->childNodes as $node) {
        if ($node->nodeType !== XML_ELEMENT_NODE) {
            continue;
        }

        $tag = strtolower($node->tagName);

        if ($tag === 'div' && $node->hasAttribute('data-append-component')) {
            $component_slug = sanitize_title($node->getAttribute('data-append-component'));
            if (!empty($component_slug)) {
                if ($current_section === null) {
                    $current_section = ['h2' => '', 'blocks' => []];
                }
                $current_section['append_component'] = $component_slug;
            }
            continue;
        }

        if ($tag === 'h1' && !$hero_h1_found) {
            $parsed_data['hero']['h1'] = boilerplate_content_page_get_clean_text($node);
            $hero_h1_found = true;
            continue;
        }

        if (($tag === 'p' || $tag === 'div') && $current_section === null) {
            $parsed_data['hero']['excerpt'] .= '<p>' . strip_tags(boilerplate_content_page_get_inner_html($node), $allowed_tags) . '</p>';
            continue;
        }

        if ($tag === 'h2') {
            if ($current_section !== null) {
                if ($current_accordion !== null) {
                    if ($current_subheading !== null) {
                        $current_accordion['subheadings'][] = $current_subheading;
                        $current_subheading = null;
                    }
                    $current_section['blocks'][] = ['type' => 'accordion', 'data' => $current_accordion];
                    $current_accordion = null;
                }
                $parsed_data['sections'][] = $current_section;
            }

            $current_section = [
                'h2' => boilerplate_content_page_get_clean_text($node),
                'blocks' => [],
            ];

            $layout_key = boilerplate_content_page_get_heading_layout_key($node);
            if ($layout_key !== '') {
                $current_section['layout_key'] = $layout_key;
            }

            continue;
        }

        if ($tag === 'img') {
            $src = $node->getAttribute('src');
            if ($current_accordion !== null) {
                $current_accordion['content'] .= '<img src="' . esc_url($src) . '" />';
            } else {
                if ($current_section === null) {
                    $current_section = ['h2' => '', 'blocks' => []];
                }
                $current_section['blocks'][] = ['type' => 'image', 'src' => $src];
            }
            continue;
        }

        if ($tag === 'p' || $tag === 'figure') {
            $imgs = $node->getElementsByTagName('img');
            $had_image = false;

            while ($imgs->length > 0) {
                $img_node = $imgs->item(0);
                $src = $img_node->getAttribute('src');

                if ($current_accordion !== null) {
                    $current_accordion['content'] .= '<img src="' . esc_url($src) . '" />';
                } else {
                    if ($current_section === null) {
                        $current_section = ['h2' => '', 'blocks' => []];
                    }
                    $current_section['blocks'][] = ['type' => 'image', 'src' => $src];
                }

                $img_node->parentNode->removeChild($img_node);
                $had_image = true;
            }

            if ($had_image && trim($node->textContent) === '') {
                continue;
            }
        }

        if ($tag === 'h3') {
            if ($current_section === null) {
                $current_section = ['h2' => '', 'blocks' => []];
            }

            if ($current_accordion !== null) {
                if ($current_subheading !== null) {
                    $current_accordion['subheadings'][] = $current_subheading;
                    $current_subheading = null;
                }
                $current_section['blocks'][] = ['type' => 'accordion', 'data' => $current_accordion];
            }

            $current_accordion = [
                'title' => boilerplate_content_page_get_clean_text($node),
                'content' => '',
                'subheadings' => [],
            ];
            continue;
        }

        if (in_array($tag, ['h3', 'h4', 'h5', 'h6'], true)) {
            if ($current_accordion !== null) {
                if ($current_subheading !== null) {
                    $current_accordion['subheadings'][] = $current_subheading;
                }
                $current_subheading = [
                    'level' => $tag,
                    'title' => boilerplate_content_page_get_clean_text($node),
                    'text' => '',
                ];
            } else {
                if ($current_section === null) {
                    $current_section = ['h2' => '', 'blocks' => []];
                }
                $current_section['blocks'][] = [
                    'type' => 'heading',
                    'level' => $tag,
                    'content' => boilerplate_content_page_get_clean_text($node),
                ];
            }
            continue;
        }

        $clean_html = strip_tags(boilerplate_content_page_get_inner_html($node), $allowed_tags);
        $formatted_html = ($tag === 'p') ? '<p>' . $clean_html . '</p>' : $clean_html;

        if ($current_accordion !== null && $current_subheading === null) {
            $current_accordion['content'] .= $formatted_html;
        } elseif ($current_subheading !== null) {
            $current_subheading['text'] .= $formatted_html;
        } else {
            if ($current_section === null) {
                $current_section = ['h2' => '', 'blocks' => []];
            }

            $last_idx = count($current_section['blocks']) - 1;
            if ($last_idx >= 0 && ($current_section['blocks'][$last_idx]['type'] ?? '') === 'text') {
                $current_section['blocks'][$last_idx]['content'] .= $formatted_html;
            } else {
                $current_section['blocks'][] = ['type' => 'text', 'content' => $formatted_html];
            }
        }
    }

    if ($current_section !== null) {
        if ($current_accordion !== null) {
            if ($current_subheading !== null) {
                $current_accordion['subheadings'][] = $current_subheading;
            }
            $current_section['blocks'][] = ['type' => 'accordion', 'data' => $current_accordion];
        }
        $parsed_data['sections'][] = $current_section;
    }

    wp_cache_set($cache_key, $parsed_data, 'boilerplate_content_page', HOUR_IN_SECONDS);

    return boilerplate_content_context_from_parsed_data($parsed_data, $context);
}
