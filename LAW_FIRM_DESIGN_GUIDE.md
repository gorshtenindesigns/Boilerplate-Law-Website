# Law Firm Homepage - Visual Development Guide

This guide builds on the complete template system to customize your law firm homepage and all other site pages.

## Your Design Direction

Based on your screenshot, you have:

- **Clean, professional aesthetic** with excellent typography hierarchy
- **Blue accent color** (#2563eb) for primary CTAs
- **Card-based layout** for organizing content sections
- **Collapsible detail items** (H3s appear to collapse/expand)
- **Stats display** in the hero section
- **White background** with subtle gray surface elements

## Template System Overview

Your theme includes these native templates for different page types:

| Template | Used For | URL Example |
|----------|----------|-------------|
| `home.php` | Homepage | law-by-yan.local |
| `page.php` | About, Services, Team, Info pages | law-by-yan.local/about |
| `single.php` | Blog posts, case studies, news | law-by-yan.local/blog/post-title |
| `archive.php` | Category, tag archives | law-by-yan.local/category/updates |
| `search.php` | Search results | law-by-yan.local/?s=search-term |
| `404.php` | Page not found | Any non-existent URL |

Each template uses the base layout component and renders content based on heading hierarchy and page type.

**For comprehensive template documentation, see**: `COMPLETE_TEMPLATE_SYSTEM_GUIDE.md`

## Next Steps to Customize

### Step 1: Update Firm Info in Theme Defaults

Open `/inc/boilerplate-defaults.php` and update:

```php
'copy' => [
    'site_label' => 'Law By Yan',                    // Your firm name
    'site_summary' => 'Specializing in corporate and personal legal services.',
    'nav_cta' => 'Schedule Consultation',             // Header CTA
    'home_title' => 'The Modern Law Firm',           // Main hero title
    'home_body' => 'Expert legal services tailored to your needs.',
    'content_eyebrow' => 'Key Insight',              // Section eyebrow
    'footer_note' => '© 2024 Law By Yan. All rights reserved.',
],
```

### Step 2: Customize Colors (Optional)

If you want to change the blue accent color, edit the same file:

```php
'colors' => [
    'accent' => '#2563eb',       // Change this to your brand color
    'accent_soft' => '#dbeafe',  // Light version of accent
    // ... rest of colors
],
```

### Step 3: Create Custom Layout Components

Your homepage needs a few custom components. Create them with the generator:

```bash
cd /Users/yan/Local\ Sites/law-by-yan/app/public/wp-content/themes/boilerplate-update
node generate.js
```

#### Create Component 1: Hero with Stats

```
Choose: layout
Name: hero-with-stats
```

Then update the files:

**File**: `components/layout/hero-with-stats/hero-with-stats.php`

```php
<?php
wp_enqueue_style(
    'hero-with-stats-css',
    get_template_directory_uri() . '/components/layout/hero-with-stats/hero-with-stats.css',
    array(),
    boilerplate_get_asset_version('components/layout/hero-with-stats/hero-with-stats.css')
);

$title = $args['title'] ?? 'The Modern Law Firm';
$subtitle = $args['subtitle'] ?? 'Expert legal services tailored to your needs.';
$cta_text = $args['cta_text'] ?? 'Schedule Consultation';
$cta_url = $args['cta_url'] ?? '#contact';
$stats = $args['stats'] ?? [
    ['value' => '25+', 'label' => 'Years Experience'],
    ['value' => '500+', 'label' => 'Clients Served'],
];
?>

<section class="hero-with-stats">
    <div class="hero-with-stats__inner">
        <div class="hero-with-stats__content">
            <p class="hero-with-stats__eyebrow"><?php echo esc_html(boilerplate_get_default_copy('home_eyebrow', 'Welcome')); ?></p>
            <h1 class="hero-with-stats__title"><?php echo esc_html($title); ?></h1>
            <p class="hero-with-stats__subtitle"><?php echo esc_html($subtitle); ?></p>
            
            <a href="<?php echo esc_url($cta_url); ?>" class="hero-with-stats__cta">
                <?php echo esc_html($cta_text); ?>
            </a>
        </div>

        <div class="hero-with-stats__stats">
            <?php foreach ($stats as $stat): ?>
                <div class="hero-with-stats__stat-item">
                    <div class="hero-with-stats__stat-value"><?php echo esc_html($stat['value']); ?></div>
                    <div class="hero-with-stats__stat-label"><?php echo esc_html($stat['label']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
```

**File**: `components/layout/hero-with-stats/hero-with-stats.css`

```css
.hero-with-stats {
    padding: var(--boilerplate-section-space) var(--boilerplate-page-gutter);
    background: var(--boilerplate-color-surface);
    border: 1px solid var(--boilerplate-color-border);
    border-radius: var(--boilerplate-radius);
}

.hero-with-stats__inner {
    max-width: var(--boilerplate-page-width);
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: center;
}

.hero-with-stats__eyebrow {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--boilerplate-color-accent);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}

.hero-with-stats__title {
    font-size: clamp(2rem, 5vw, 3.5rem);
    line-height: 1.2;
    color: var(--boilerplate-color-text);
    margin-bottom: 1rem;
    font-weight: 700;
}

.hero-with-stats__subtitle {
    font-size: 1.125rem;
    color: var(--boilerplate-color-text-muted);
    line-height: 1.6;
    margin-bottom: 2rem;
}

.hero-with-stats__cta {
    display: inline-block;
    padding: 0.875rem 2rem;
    background: var(--boilerplate-color-accent);
    color: white;
    border-radius: var(--boilerplate-radius);
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
}

.hero-with-stats__cta:hover,
.hero-with-stats__cta:focus-visible {
    background: var(--boilerplate-color-accent);
    opacity: 0.9;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    transform: translateY(-2px);
}

.hero-with-stats__stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.hero-with-stats__stat-item {
    padding: 1.5rem;
    background: var(--boilerplate-color-surface-alt);
    border-radius: var(--boilerplate-radius);
    text-align: center;
}

.hero-with-stats__stat-value {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 700;
    color: var(--boilerplate-color-accent);
    line-height: 1;
    margin-bottom: 0.5rem;
}

.hero-with-stats__stat-label {
    font-size: 0.875rem;
    color: var(--boilerplate-color-text-muted);
    font-weight: 500;
}

@media (max-width: 768px) {
    .hero-with-stats__inner {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .hero-with-stats {
        padding: 2rem var(--boilerplate-page-gutter);
    }

    .hero-with-stats__title {
        font-size: 2rem;
    }

    .hero-with-stats__stats {
        grid-template-columns: 1fr 1fr;
    }
}
```

**File**: `components/layout/hero-with-stats/hero-with-stats.js`

```javascript
// Add interactive effects if needed
document.addEventListener('DOMContentLoaded', function() {
    const stats = document.querySelectorAll('.hero-with-stats__stat-item');
    
    stats.forEach((stat, index) => {
        stat.style.animationDelay = `${index * 0.1}s`;
        stat.classList.add('fade-in');
    });
});
```

#### Create Component 2: Services Preview

```bash
node generate.js
# Choose: layout
# Name: services-preview
```

**File**: `components/layout/services-preview/services-preview.php`

```php
<?php
wp_enqueue_style(
    'services-preview-css',
    get_template_directory_uri() . '/components/layout/services-preview/services-preview.css',
    array(),
    boilerplate_get_asset_version('components/layout/services-preview/services-preview.css')
);

$title = $args['title'] ?? 'Our Practice Areas';
$services = $args['services'] ?? [
    ['title' => 'Corporate Law', 'description' => 'Business formation, contracts, and transactions'],
    ['title' => 'Criminal Defense', 'description' => 'Defense representation and legal strategy'],
    ['title' => 'Family Law', 'description' => 'Divorce, custody, and family matters'],
    ['title' => 'Personal Injury', 'description' => 'Injury claims and negligence cases'],
];
?>

<section class="services-preview">
    <div class="services-preview__container">
        <header class="services-preview__header">
            <h2 class="services-preview__title"><?php echo esc_html($title); ?></h2>
            <p class="services-preview__description">
                <?php echo esc_html($args['description'] ?? 'Comprehensive legal services across multiple practice areas'); ?>
            </p>
        </header>

        <div class="services-preview__grid">
            <?php foreach ($services as $service): ?>
                <article class="services-preview__item">
                    <h3 class="services-preview__item-title"><?php echo esc_html($service['title']); ?></h3>
                    <p class="services-preview__item-description"><?php echo esc_html($service['description']); ?></p>
                    <a href="#services" class="services-preview__item-link">Learn More →</a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
```

**File**: `components/layout/services-preview/services-preview.css`

```css
.services-preview {
    padding: var(--boilerplate-section-space) var(--boilerplate-page-gutter);
}

.services-preview__container {
    max-width: var(--boilerplate-page-width);
    margin: 0 auto;
}

.services-preview__header {
    text-align: center;
    margin-bottom: var(--boilerplate-section-space);
}

.services-preview__title {
    font-size: clamp(1.75rem, 4vw, 2.5rem);
    color: var(--boilerplate-color-text);
    margin-bottom: 0.5rem;
}

.services-preview__description {
    font-size: 1.125rem;
    color: var(--boilerplate-color-text-muted);
    max-width: 600px;
    margin: 0 auto;
}

.services-preview__grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.services-preview__item {
    padding: 2rem;
    background: var(--boilerplate-color-surface);
    border: 1px solid var(--boilerplate-color-border);
    border-radius: var(--boilerplate-radius);
    transition: all 0.3s ease;
}

.services-preview__item:hover {
    border-color: var(--boilerplate-color-accent);
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.1);
    transform: translateY(-4px);
}

.services-preview__item-title {
    font-size: 1.25rem;
    color: var(--boilerplate-color-text);
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.services-preview__item-description {
    color: var(--boilerplate-color-text-muted);
    margin-bottom: 1rem;
    line-height: 1.6;
}

.services-preview__item-link {
    color: var(--boilerplate-color-accent);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s;
    display: inline-block;
}

.services-preview__item-link:hover {
    color: var(--boilerplate-color-text);
    transform: translateX(4px);
}

@media (max-width: 768px) {
    .services-preview {
        padding: 2rem var(--boilerplate-page-gutter);
    }

    .services-preview__grid {
        grid-template-columns: 1fr;
    }
}
```

### Step 4: Register Components for Homepage

Edit `/inc/home-template-meta.php` and add:

```php
// Register custom homepage blocks
add_action('init', function() {
    // Hero with stats
    boilerplate_register_homepage_block('hero-with-stats', [
        'render_type' => 'layout',
        'priority' => 10,
        'args' => [
            'title' => 'The Modern Law Firm: Structure, Services, and Client-Centered Practice',
            'subtitle' => 'Introduction to the Legal Industry',
            'cta_text' => 'Schedule Consultation',
            'cta_url' => '/contact',
            'stats' => [
                ['value' => '25+', 'label' => 'Years Experience'],
                ['value' => '500+', 'label' => 'Clients Served'],
            ],
        ],
    ]);

    // Services preview
    boilerplate_register_homepage_block('services-preview', [
        'render_type' => 'layout',
        'priority' => 20,
        'args' => [
            'title' => 'Our Practice Areas',
            'description' => 'Comprehensive legal services across multiple practice areas',
            'services' => [
                ['title' => 'Corporate Law', 'description' => 'Business formation, contracts, and transactions'],
                ['title' => 'Criminal Defense', 'description' => 'Defense representation and legal strategy'],
                ['title' => 'Family Law', 'description' => 'Divorce, custody, and family matters'],
                ['title' => 'Personal Injury', 'description' => 'Injury claims and negligence cases'],
            ],
        ],
    ]);
});
```

### Step 5: Create Homepage Content

1. Open WordPress Admin: http://law-by-yan.local/wp-admin
2. Go to **Pages** → **Add New**
3. Title: "Home"
4. In the editor, paste the content from `HOMEPAGE_CONTENT_TEMPLATE.php`
5. Set Permalink (slug) to "home"
6. Publish the page

### Step 6: Set as Homepage

1. Go to **Settings** → **Reading**
2. Under "Your homepage displays", select "A static page"
3. Choose your "Home" page from the dropdown
4. Save changes

## Testing Your Homepage

1. **Visit Frontend**: http://law-by-yan.local
2. **Inspect with DevTools** (F12):
   - Check Elements tab to see component structure
   - Check Styles to see CSS applied
   - Check Console for any JavaScript errors
3. **Test Responsive**:
   - DevTools → Device Emulation
   - Test at 375px (mobile), 768px (tablet), 1200px (desktop)
4. **Test Interactions**:
   - Hover over buttons and service cards
   - Check that colors and shadows work
   - Test CTA button clicks

## Styling Deep Dive

### Typography Scale

The homepage uses a responsive typography scale:

```css
/* Headings */
h1: clamp(2rem, 5vw, 3.5rem)        /* Hero title: 32px to 56px */
h2: clamp(1.75rem, 4vw, 2.5rem)     /* Section title: 28px to 40px */
h3: 1.25rem                          /* Subsection: 20px */

/* Body text */
p: 1rem                              /* Regular: 16px */
small: 0.875rem                      /* Small: 14px */

/* Line height */
body { line-height: 1.6; }          /* 60% extra space */
h1, h2, h3 { line-height: 1.2; }   /* Tighter headings */
```

### Spacing Scale

Uses CSS custom properties for consistency:

```css
--boilerplate-page-gutter: clamp(1rem, 3vw, 2rem)           /* Page margins */
--boilerplate-section-space: clamp(3rem, 8vw, 6rem)         /* Between sections */
```

This means spacing automatically adjusts based on viewport size.

### Color Usage

```css
/* Text hierarchy */
Primary text:   var(--boilerplate-color-text)         /* #172033 */
Secondary text: var(--boilerplate-color-text-muted)   /* #536079 */
Accent:         var(--boilerplate-color-accent)       /* #2563eb */

/* Backgrounds */
Page:           var(--boilerplate-color-background)   /* #f5f7fb */
Cards/Surface:  var(--boilerplate-color-surface)      /* #ffffff */
Alt surface:    var(--boilerplate-color-surface-alt)  /* #e8edf5 */

/* Borders */
Border:         var(--boilerplate-color-border)       /* #cbd5e1 */
```

## Using All Native Templates for Your Law Firm

Beyond the homepage, your theme includes templates for all page types. Here's how to use them:

### Pages (About, Services, Team, Info)

**Template**: `page.php`  
**URLs**: /about, /services, /team, /case-studies, etc.

Use pages for static content with structured sections:

1. Create page in WordPress: Pages → Add New
2. Write content with heading hierarchy:
   ```
   # Page Title (H1)
   
   Summary text before sections.
   
   ## First Section (H2)
   
   ### Subsection (H3)
   
   Content here...
   
   ## Next Section (H2)
   ```
3. The parser converts H2 sections into styled content blocks

**Law Firm Page Examples**:
- `/about` - About the firm, history, mission
- `/services` - List of practice areas with descriptions
- `/team` - Attorneys and staff directory
- `/case-studies` - Notable cases and client results
- `/process` - How you work with clients
- `/faqs` - Frequently asked questions
- `/contact` - Contact information and form

### Blog Posts & News

**Template**: `single.php`  
**URLs**: /blog/post-title, /news/article-name

Use posts for time-stamped content:

1. Create post in WordPress: Posts → Add New
2. Add featured image (optional)
3. Write content (no heading structure needed)
4. Assign to categories (Legal Updates, News, Insights, etc.)
5. Publish

**Law Firm Post Examples**:
- "Recent Changes in Corporate Law"
- "Tips for Small Business Owners"
- "Case Study: Successfully Resolved Dispute"
- "Firm News: New Attorney Joins Team"
- "Legal Alert: New Regulations"

### Categories & Archives

**Template**: `archive.php`  
**URLs**: /category/legal-updates, /category/news, /tag/corporate-law

Archives automatically list all posts in a category:

1. Create categories in WordPress: Posts → Categories
2. Assign posts to categories
3. Archives generate automatically
4. Visit: /category/your-category-name

**Law Firm Categories**:
- Legal Updates
- News & Press
- Insights & Tips
- Case Studies
- Events

### Search Results

**Template**: `search.php`  
**URL**: /?s=search-term

Search functionality is built in:

1. User enters search term in header search box
2. Results display with post title, date, excerpt
3. Customize search form and results layout in `search.php`

### 404 Page Not Found

**Template**: `404.php`  
**Triggered**: Visiting any non-existent URL

Default 404 shows helpful message and link to homepage:

1. Visit any non-existent URL to see 404
2. Customize message, links, and design in `404.php`
3. Optionally add search form or featured content

**Customized 404 for Law Firm**:
- Message: "We're sorry, the page you're looking for doesn't exist"
- Links: Homepage, Search, Contact Us
- Maybe: Featured services or recent blog posts

## Complete Site Content Structure

Here's a recommended content structure for your law firm site:

```
Homepage (home.php)
├── Hero Section
├── Services Preview
├── Recent Blog Posts
└── Call to Action

About Page (page.php)
├── Firm History
├── Team
└── Core Values

Services Pages (page.php)
├── Corporate Law (with subsections)
├── Criminal Defense (with subsections)
├── Family Law (with subsections)
└── Personal Injury (with subsections)

Blog / News (single.php + archive.php)
├── Legal Updates (category)
├── News (category)
├── Insights (category)
└── Case Studies (category)

Team Directory (page.php or custom layout)
├── Attorney Profiles
├── Staff Bios
└── Contact Info

Contact Page (page.php)
├── Contact Form
├── Office Locations
└── Hours

404 Error (404.php)
└── Helpful Navigation
```

## Implementation Checklist

- [ ] **Homepage** - Create hero and services components
- [ ] **About Page** - page.php with firm history and mission
- [ ] **Services Pages** - page.php for each practice area
- [ ] **Team Page** - page.php with attorney profiles
- [ ] **Blog Categories** - Posts with categories and tags
- [ ] **First Blog Post** - single.php template
- [ ] **Contact Page** - page.php with contact info
- [ ] **Test Search** - Search for a blog post
- [ ] **Test Archive** - Visit /category/news
- [ ] **Test 404** - Visit non-existent URL
- [ ] **Customize 404** - Edit 404.php if needed
- [ ] **Mobile Test** - All templates on mobile view
- [ ] **Verify Links** - Navigation between all pages

## Cross-Template Styling

All templates use the same design system:

- **Colors** - Defined in `boilerplate-defaults.php`
- **Typography** - Font families and sizes consistent
- **Spacing** - Uses CSS custom properties for responsive sizing
- **Components** - Same base layout block across all templates

This means once you customize the defaults, your entire site (homepage, pages, blog, archives, 404) automatically inherits the design!

## Next: Create Complete Content

With all templates available, your complete site structure includes:

1. **Homepage** with custom blocks and content
2. **Info Pages** for About, Services, Team, Contact
3. **Blog Section** for news and updates
4. **Archives** for categories and tags
5. **Search** for finding content
6. **404** for error handling

Start with the homepage, then create supporting pages and blog posts to fill out your complete site.

Your law firm homepage is ready to customize! Start by creating the components above, then fine-tune the CSS to match your exact design. See `COMPLETE_TEMPLATE_SYSTEM_GUIDE.md` for full template customization details.

