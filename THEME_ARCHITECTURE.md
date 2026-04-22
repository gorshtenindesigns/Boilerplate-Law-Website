# Theme Architecture

## Overview

The theme is now built around a neutral base system:

- one base layout component
- one base parsed content-block renderer
- one base UI component

The parser and `ContentContext` remain active.

## Active Runtime Flow

1. `functions.php` loads defaults, helpers, parser files, and register files.
2. `home.php` uses the homepage block registry and parsed content.
3. parent and child page templates call `boilerplate_render_structured_content()`.
4. `base-content-block` renders parsed `h2` sections.
5. native WordPress templates use neutral markup and base components.

## Component Paths

- layout components: `components/layout/<name>/`
- content blocks: `components/content-blocks/<name>/`
- ui components: `components/ui/<name>/`

## Base Components

### Base Layout Block

- path: `components/layout/base-layout-block/`
- role: neutral non-semantic layout shell
- used for homepage block scaffolding and template hero shells

### Base Content Block

- path: `components/content-blocks/base-content-block/`
- role: parsed section renderer
- render function: `boilerplate_render_base_content_block($section)`

### Base UI Component

- path: `components/ui/base-ui-component/`
- role: neutral reusable UI starter

## Parser Contract

- first `h1` => hero/overview title
- pre-`h2` text => summary
- `h2` => section root
- `h3` => detail/disclosure item
- `h4` to `h6` => nested support headings

## Registry

`inc/content-layout-registry.php` now normalizes legacy layout keys into `base-content-block`.

That preserves parser compatibility while the old `design-*` directories stay deleted.

## Homepage

Homepage rendering still uses the homepage block registry, but the sequence is now forced to:

- `base-layout-block`

That keeps the meta-box workflow available without carrying the old homepage-specific component set.

## Native Templates

Neutral templates now exist for:

- `404.php`
- `page.php`
- `single.php`
- `single-post.php`
- `archive.php`
- `search.php`

## Defaults

`inc/boilerplate-defaults.php` is the source for:

- placeholder copy
- neutral color tokens
- typography defaults
- spacing tokens

## Generator

`generate.js` scaffolds new components from the correct base template and writes them into the correct category directory.
