# Template Blueprint

## Default Rule

Do not create a new page template if the requirement can be solved with:

- `home.php`
- `templates/content-shortcode-template.php`
- `templates/content-shortcode-template-child.php`
- a new layout component
- a new content-block component
- a new UI component

## Base Component Rule

Every new component must start from one of these folders:

- `components/layout/base-layout-block/`
- `components/content-blocks/base-content-block/`
- `components/ui/base-ui-component/`

## Directory Rule

Every component directory contains:

- `<name>.php`
- `<name>.css`
- `<name>.js`
- `register.php`

## Naming Rule

- folder: kebab-case
- shortcode: snake_case
- render function: `boilerplate_render_<snake_case>()`
- CSS/JS namespace: the component folder name

## Structured Content Rule

Parsed page templates must continue to use:

- `boilerplate_render_structured_content()`
- `boilerplate_get_parsed_hero_data()`
- `boilerplate_get_parsed_sections()`
- `ContentContext`

## Neutrality Rule

- use placeholder copy only
- no branded fallbacks
- no hardcoded media paths
- no industry-specific assumptions

## Minimal Template Flow

1. call `get_header()`
2. read post content
3. call `boilerplate_render_structured_content()` when the template is parser-driven
4. render a neutral base layout block when a hero shell is needed
5. call `get_footer()`
