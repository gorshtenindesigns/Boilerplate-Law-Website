# Boilerplate Update Theme

This theme is now organized around a neutral, append-only boilerplate structure.

## Core Rules

- No client, brand, or industry-specific theme copy.
- No hardcoded media fallbacks.
- Classic Editor remains the content source of truth.
- Parsed section rendering stays compatible with `ContentContext`.
- New components start from three base templates only.

## Folder Structure

```text
boilerplate-update/
├── assets/
│   ├── css/
│   ├── js/
│   └── media/
├── components/
│   ├── layout/
│   ├── content-blocks/
│   └── ui/
├── inc/
├── templates/
├── 404.php
├── archive.php
├── footer.php
├── functions.php
├── generate.js
├── header.php
├── home.php
├── index.php
├── page.php
├── search.php
├── single.php
├── single-post.php
├── style.css
└── docs...
```

## Base Templates

- `components/layout/base-layout-block/`
- `components/content-blocks/base-content-block/`
- `components/ui/base-ui-component/`

Each base template contains:

- `<name>.php`
- `<name>.css`
- `<name>.js`
- `register.php`

## Structured Content

The parser contract remains:

- first `h1` => hero title
- text before first `h2` => hero summary
- each `h2` => section
- each `h3` => detail block
- `h4` to `h6` => nested support headings

`base-content-block` is now the default renderer for parsed sections.

## Generator

Run:

```bash
node generate.js
```

The generator now:

- asks for `layout`, `content-block`, or `ui`
- creates files in the matching subfolder
- uses the matching base template as its source
- scaffolds shortcode support
- registers a content-block renderer automatically

## Native Templates

The theme includes neutral native templates for:

- `404.php`
- `page.php`
- `single.php`
- `single-post.php`
- `archive.php`
- `search.php`

## Defaults

Neutral copy and design tokens live in:

- `inc/boilerplate-defaults.php`

Use that file before adding any new hardcoded labels, colors, or spacing values.
