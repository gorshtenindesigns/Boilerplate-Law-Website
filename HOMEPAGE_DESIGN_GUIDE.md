# Homepage Design Development Guide

## Current Design Overview

Your homepage features:
- **Hero Section**: "The Modern Law Firm: Structure, Services, and Client-Centered Practice"
- **Stats Grid**: Two metrics (00 - Lorem Ipsum, 15 - Dolor sit)
- **Content Sections**: Multiple H2 sections with collapsible details
- **Clean Typography**: Professional, readable headings and body text
- **Blue Accent Color**: Primary CTA buttons (#2563eb)
- **Neutral Background**: Light gray page background

## Homepage Structure

The homepage renders from `home.php` which:

1. Calls `get_header()` - Renders navigation and branding
2. Retrieves homepage blocks from registry
3. Renders each block using `boilerplate_render_component()`
4. Renders parsed content sections below

## Current Homepage Block System

Homepage blocks are defined in WordPress post meta. The theme looks for:

```php
$homepage_blocks = boilerplate_get_active_homepage_blocks();
```

These are configured in `inc/home-template-meta.php`.

## Customizing Homepage Design

### 1. Update Brand & Copy

Edit `/inc/boilerplate-defaults.php`:

```php
'copy' => [
    'site_label' => 'Law By Yan',  // Your firm name
    'site_summary' => 'Your firm description...',
    'nav_cta' => 'Book Consultation',  // CTA button text
    'home_eyebrow' => 'Welcome',
    'home_title' => 'The Modern Law Firm',
    'home_body' => 'Your firm description...',
    // ... more copy
],
```

### 2. Update Colors & Typography

Same file, `boilerplate_get_design_defaults()`:

```php
'colors' => [
    'background' => '#f5f7fb',      // Page background
    'surface' => '#ffffff',          // Card/component background
    'accent' => '#2563eb',           // Primary blue
    'accent_soft' => '#dbeafe',      // Light blue
    'text' => '#172033',             // Dark text
    'text_muted' => '#536079',       // Gray text
    'border' => '#cbd5e1',           // Border color
],
'typography' => [
    'sans' => '"Montserrat", "Helvetica Neue", Arial, sans-serif',
    'mono' => 'monospace',
],
'spacing' => [
    'page_width' => '72rem',         // Max content width
    'page_gutter' => 'clamp(1rem, 3vw, 2rem)',
    'section_space' => 'clamp(3rem, 8vw, 6rem)',
    'radius' => '1.25rem',           // Border radius
],
```

### 3. Create Homepage Blocks

Homepage blocks are layout components rendered before the main content. Create new ones:

```bash
cd /Users/yan/Local\ Sites/law-by-yan/app/public/wp-content/themes/boilerplate-update
node generate.js
# Select: layout
# Name: hero-services-intro
```

This creates:
- `components/layout/hero-services-intro/hero-services-intro.php`
- `components/layout/hero-services-intro/hero-services-intro.css`
- `components/layout/hero-services-intro/hero-services-intro.js`
- `components/layout/hero-services-intro/register.php`

### 4. Register Homepage Block

After creating, register in `inc/home-template-meta.php`:

```php
// Example registration
boilerplate_register_homepage_block(
    'hero-services-intro',
    [
        'render_type' => 'layout',
        'priority' => 10,
    ]
);
```

## Content Section Styling

Content sections (H2 blocks) render using `base-content-block` component.

### H2 Section Structure

```
# Hero Title (H1)

Summary text before first section

## Section One (H2)

### Subsection (H3)

- Bullet point
- Another point

## Section Two (H2)

...more content
```

### CSS Classes Available

- `.base-content-block` - Main section wrapper
- `.base-content-block__title` - H2 heading
- `.base-content-block__intro` - Text/content before H3
- `.base-content-block__heading` - H3/H4 headings
- `.base-content-block__copy` - Body text
- `.base-content-block__detail-block` - Accordion/detail items

## Styling Options

### Edit Component Styles

**Base Content Block**: `components/content-blocks/base-content-block/base-content-block.css`

```css
.base-content-block {
  padding: var(--boilerplate-section-space);
  border: 1px solid var(--boilerplate-color-border);
  border-radius: var(--boilerplate-radius);
  background: var(--boilerplate-color-surface);
}

.base-content-block__title {
  font-size: clamp(1.75rem, 4vw, 2.5rem);
  color: var(--boilerplate-color-text);
  margin-bottom: 1rem;
}
```

**Base Layout Block**: `components/layout/base-layout-block/base-layout-block.css`

### Global Styles

**File**: `assets/css/_global.css`

This file contains:
- Header/footer styling
- Main content container styling
- Typography defaults
- Responsive utilities

## Creating Custom Homepage Layout

### Option 1: Create Hero Block

```bash
node generate.js
# Select: layout
# Name: hero-intro
```

Edit `components/layout/hero-intro/hero-intro.php`:

```php
<?php
// Your custom hero markup
$title = $args['title'] ?? 'Default Title';
$subtitle = $args['subtitle'] ?? '';
$cta_label = $args['cta_label'] ?? 'Get Started';
$cta_url = $args['cta_url'] ?? '#';
$stats = $args['stats'] ?? [];
?>

<section class="hero-intro">
  <div class="hero-intro__inner">
    <div class="hero-intro__content">
      <h1 class="hero-intro__title"><?php echo esc_html($title); ?></h1>
      
      <?php if ($subtitle): ?>
        <p class="hero-intro__subtitle"><?php echo esc_html($subtitle); ?></p>
      <?php endif; ?>
      
      <a href="<?php echo esc_url($cta_url); ?>" class="hero-intro__cta">
        <?php echo esc_html($cta_label); ?>
      </a>
    </div>

    <?php if (!empty($stats)): ?>
      <div class="hero-intro__stats">
        <?php foreach ($stats as $stat): ?>
          <div class="hero-intro__stat">
            <div class="hero-intro__stat-value"><?php echo esc_html($stat['value']); ?></div>
            <div class="hero-intro__stat-label"><?php echo esc_html($stat['label']); ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
```

Edit `components/layout/hero-intro/hero-intro.css`:

```css
.hero-intro {
  padding: var(--boilerplate-section-space);
  background: linear-gradient(135deg, var(--boilerplate-color-surface), var(--boilerplate-color-surface-alt));
  border-radius: var(--boilerplate-radius);
}

.hero-intro__inner {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 3rem;
  align-items: center;
}

.hero-intro__title {
  font-size: clamp(2rem, 5vw, 3.5rem);
  line-height: 1.2;
  color: var(--boilerplate-color-text);
  margin-bottom: 1rem;
}

.hero-intro__subtitle {
  font-size: 1.125rem;
  color: var(--boilerplate-color-text-muted);
  margin-bottom: 1.5rem;
}

.hero-intro__cta {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  background: var(--boilerplate-color-accent);
  color: white;
  border-radius: var(--boilerplate-radius);
  text-decoration: none;
  font-weight: 600;
  transition: background 0.2s;
}

.hero-intro__cta:hover {
  background: var(--boilerplate-color-accent-soft);
  color: var(--boilerplate-color-text);
}

.hero-intro__stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

.hero-intro__stat {
  text-align: center;
  padding: 1rem;
}

.hero-intro__stat-value {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--boilerplate-color-accent);
  line-height: 1;
  margin-bottom: 0.5rem;
}

.hero-intro__stat-label {
  font-size: 0.875rem;
  color: var(--boilerplate-color-text-muted);
}

@media (max-width: 768px) {
  .hero-intro__inner {
    grid-template-columns: 1fr;
  }

  .hero-intro__stats {
    grid-template-columns: 1fr 1fr;
  }
}
```

### Option 2: Create Services Grid

```bash
node generate.js
# Select: layout
# Name: services-grid
```

Edit `components/layout/services-grid/services-grid.php`:

```php
<?php
$title = $args['title'] ?? 'Services';
$services = $args['services'] ?? [];
?>

<section class="services-grid">
  <div class="services-grid__header">
    <h2 class="services-grid__title"><?php echo esc_html($title); ?></h2>
  </div>

  <div class="services-grid__list">
    <?php foreach ($services as $service): ?>
      <article class="services-grid__item">
        <h3 class="services-grid__item-title">
          <?php echo esc_html($service['title'] ?? ''); ?>
        </h3>
        <p class="services-grid__item-body">
          <?php echo esc_html($service['description'] ?? ''); ?>
        </p>
      </article>
    <?php endforeach; ?>
  </div>
</section>
```

Edit `components/layout/services-grid/services-grid.css`:

```css
.services-grid {
  display: grid;
  gap: var(--boilerplate-section-space);
}

.services-grid__header {
  text-align: center;
}

.services-grid__title {
  font-size: clamp(2rem, 4vw, 2.5rem);
  color: var(--boilerplate-color-text);
}

.services-grid__list {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
}

.services-grid__item {
  padding: 2rem;
  background: var(--boilerplate-color-surface);
  border: 1px solid var(--boilerplate-color-border);
  border-radius: var(--boilerplate-radius);
  transition: all 0.3s;
}

.services-grid__item:hover {
  border-color: var(--boilerplate-color-accent);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
}

.services-grid__item-title {
  font-size: 1.25rem;
  color: var(--boilerplate-color-text);
  margin-bottom: 0.75rem;
}

.services-grid__item-body {
  color: var(--boilerplate-color-text-muted);
  line-height: 1.6;
}
```

## File Structure for Homepage Design

```
components/layout/
├── base-layout-block/          # Default layout component
├── hero-intro/                 # Hero section (create new)
├── services-grid/              # Services grid (create new)
└── testimonials/               # Testimonials (create new)

components/content-blocks/
├── base-content-block/         # Default content section renderer
└── legal-detail-block/         # Law-specific detail block (optional)

components/ui/
├── base-ui-component/          # Buttons, links, CTAs
└── service-badge/              # Service tags (optional)

assets/css/
├── _global.css                 # Global site styles
├── parent-child.css            # Parent/child theme styles
└── theme-specific.css          # Your law firm specific styles

inc/
├── boilerplate-defaults.php    # Colors, typography, copy
├── home-template-meta.php      # Homepage block registry
└── ...
```

## Next Steps

1. **Update Defaults**: Edit `inc/boilerplate-defaults.php` with your firm info
2. **Create Hero Block**: `node generate.js` → layout → hero-intro
3. **Style Components**: Edit CSS files for custom design
4. **Add Homepage Content**: Create WordPress page with proper heading hierarchy
5. **Test Responsive**: Use browser DevTools device emulation

## Design Tokens Reference

All design values use CSS custom properties (variables):

```css
/* Colors */
--boilerplate-color-background    /* #f5f7fb */
--boilerplate-color-surface       /* #ffffff */
--boilerplate-color-accent        /* #2563eb */
--boilerplate-color-text          /* #172033 */
--boilerplate-color-text-muted    /* #536079 */
--boilerplate-color-border        /* #cbd5e1 */

/* Typography */
--boilerplate-font-sans           /* Montserrat */
--boilerplate-font-mono           /* monospace */

/* Spacing */
--boilerplate-page-width          /* 72rem */
--boilerplate-page-gutter         /* 1rem - 2rem responsive */
--boilerplate-section-space       /* 3rem - 6rem responsive */
--boilerplate-radius              /* 1.25rem */
```

Use these in your component CSS for consistent theming.

## Quick Customization Checklist

- [ ] Update site label and copy in `boilerplate-defaults.php`
- [ ] Change accent color from `#2563eb` to your brand color
- [ ] Update fonts in typography settings
- [ ] Create hero-intro layout component
- [ ] Create services-grid layout component
- [ ] Add homepage content with proper heading structure
- [ ] Test on mobile and tablet views
- [ ] Fine-tune spacing and padding

---

Your homepage design system is ready! Start with the defaults file and work your way through component creation for a fully customized law firm site.
