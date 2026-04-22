# Boilerplate Law Website Theme

Custom WordPress theme foundation for a modern law firm website. The theme is designed so the homepage and inner pages can be managed from the WordPress editor while still rendering polished, reusable law-firm layouts.

## Current Theme Direction

- Law firm boilerplate theme with a client-centered visual system.
- Classic Editor page content remains the source of truth.
- H1/H2/H3 hierarchy drives custom section rendering.
- Homepage uses dedicated layout components for hero, awards, practice areas, content overview, testimonials, FAQ, CTA, service areas, and footer.
- Inner pages use standardized hero layouts and fallback card content.
- Navigation is generated from WordPress pages, with public-facing cleanup rules.

## Major Work Completed

### Global Header

- Rebuilt the header as a full-width fixed overlay.
- Added a topbar for phone/email contact details.
- Added social icon support.
- Added a `Call Now` action.
- Preserved the main CTA button: `Schedule a Consultation`.
- Improved sticky behavior over hero sections.
- Kept mobile drawer navigation support.
- Removed `Sample Page` and `Portfolio` from public navigation.
- Renamed `Services` to `Practice Areas` in navigation.

### Homepage Hero

- Added a full-width law firm hero with background image support.
- Uses theme media assets such as `1.png`, `2.png`, and `3.png`.
- Added dark overlay treatment for text readability.
- Added descriptive intro text below the H1.
- Added a lead intake form inside the hero.
- Added hero stats:
  - `10+ Years Experience`
  - `500+ Clients Represented`
  - `4 Practice Areas`
  - `95% Client Satisfaction`
- Fixed stat placement and hero spacing.
- Removed forced `100svh` height to prevent large empty bottom gaps.

### Homepage Sections

- Added a six-card Practice Areas grid under the hero.
- Added an Awards & Recognition / badge-style strip.
- Added a content-heavy overview section using card layouts.
- Added homepage testimonials with dummy client data, location, and five-star icons.
- Added an extensive FAQ section.
- Added a CTA banner before the footer.
- Added a Los Angeles service-area map section.
- Added a three-column city card list below the map visual.
- Expanded service-area city coverage.

### Service Area Section

The homepage now includes a designed service-area block for Los Angeles and surrounding communities.

Included city examples:

- Los Angeles
- Beverly Hills
- Santa Monica
- Culver City
- Pasadena
- Glendale
- Burbank
- Long Beach
- West Hollywood
- Hollywood
- Downtown LA
- Westwood
- Brentwood
- Century City
- Encino
- Sherman Oaks
- Studio City
- North Hollywood
- Torrance
- Inglewood
- Manhattan Beach
- Redondo Beach
- Marina del Rey

### Footer

- Rebuilt the footer into a larger law firm footer.
- Added brand/contact area.
- Added menu links.
- Added Practice Areas column.
- Added media/map-style visual area.
- Added social icon support.
- Added bottom legal/disclaimer area.

### Blog Page

- Updated `home.php` so the WordPress posts page renders as a real Blog index.
- Prevented the Blog page from rendering homepage sections.
- Added Blog hero support.
- Added post card grid for real WP Posts.
- Added placeholder blog cards when no posts exist yet.
- Supports featured images, post dates, excerpts, and `Read Article` links.

### About Page

- Updated `page.php` so About no longer inherits homepage content.
- Added standardized hero title: `ABOUT LAW FIRM`.
- Added fallback cards:
  - Client-centered representation
  - Modern legal service
  - Strategic case planning
  - Built for local clients

### Contact Page

- Added standardized hero title: `CONTACT LAW FIRM`.
- Added fallback cards:
  - Start a consultation
  - What to include
  - Confidential intake
  - Next steps

### Practice Areas Page

- Standardized `Services` / `Practice Areas` behavior.
- Practice Areas now acts as the parent page for legal service subpages.
- Added fallback cards for:
  - Practice area parent page
  - Criminal Law
  - DUI Defense
  - Personal Injury
- Child pages can use the same inner-page rendering structure with their own editor content.

### Inner Page Hero System

- Updated page, archive, index, and single templates to use proper H1 hero headings.
- Added page-specific hero labels and summaries.
- Tightened hero padding across inner pages.
- Removed huge spacing caused by forced full-viewport hero heights.
- Preserved overlay behavior for the fixed header.

## Editable Content System

The theme keeps the structured content parser workflow intact.

Editor hierarchy contract:

- First `h1` becomes the hero title.
- Paragraph text before the first `h2` becomes the hero summary.
- Each `h2` becomes a designed section.
- Each `h3` becomes a detail block.
- `h4` to `h6` become nested support headings.

This allows pasted page content to render into custom sections without manually building every page template.

## Important Files

### Templates

- `header.php`
- `footer.php`
- `home.php`
- `page.php`
- `archive.php`
- `index.php`
- `single.php`
- `search.php`
- `404.php`

### Global Styling

- `assets/css/_global.css`

### Homepage Components

- `components/layout/law-hero/`
- `components/layout/award-badge-strip/`
- `components/layout/practice-area-grid/`
- `components/layout/home-content-overview/`
- `components/layout/base-layout-block/`

### Content System

- `inc/content-page-parser.php`
- `inc/content-template-shortcodes.php`
- `inc/content-layout-registry.php`
- `inc/theme-helpers.php`
- `inc/homepage-sections.php`
- `inc/home-template-meta.php`

### Navigation

- `inc/header-navigation.php`
- `inc/header-menu-config.php`

### Defaults

- `inc/boilerplate-defaults.php`

## Media Assets

Theme media defaults are handled through `boilerplate_get_theme_media_defaults()` in `inc/theme-helpers.php`.

Current local media assets:

- `assets/media/1.png`
- `assets/media/2.png`
- `assets/media/3.png`
- `assets/media/Placeholder.png`

## Validation Performed

- PHP lint checks on edited templates.
- JavaScript syntax check on `assets/js/index.js`.
- `git diff --check` for whitespace and patch sanity.
- Git status checks before commits.
- Local URL checks were attempted, but the local site host was not reachable from the shell environment during validation.

## Recent Git History

- `30c6a17 Build out law homepage sections`
- `1969a00 Refine navigation and service areas`
- `7ed06dc Add homepage awards and content overview blocks`
- `44468a3 Polish service area map layout`
- `b1aac54 Improve global header and inner page templates`
- `2271e4e Tighten hero spacing and complete page fallbacks`

## Development Notes

- Keep new visual work full-width unless a repeated card, modal, or tool needs framing.
- Avoid reintroducing forced full-viewport hero heights unless the section has enough content to justify it.
- Use existing component patterns before adding new abstractions.
- Keep public navigation clean through `inc/header-menu-config.php`.
- Keep reusable copy and visual defaults in `inc/boilerplate-defaults.php`.
- For law firm service pages, use `Practice Areas` terminology instead of `Services`.
