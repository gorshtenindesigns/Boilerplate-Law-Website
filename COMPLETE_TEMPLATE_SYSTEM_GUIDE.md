# Complete Template System Guide

This guide covers all template files in the Boilerplate theme, including native WordPress templates and their customization.

## Template Hierarchy Overview

```
boilerplate-update/
├── home.php              # Homepage (custom)
├── page.php              # Standard pages
├── single.php            # Single posts & custom post types
├── single-post.php       # Post-specific template (delegates to single.php)
├── archive.php           # Category, tag, post type archives
├── search.php            # Search results
├── 404.php               # Page not found
├── index.php             # Fallback template
├── header.php            # Header/navigation (included by all)
├── footer.php            # Footer (included by all)
└── templates/            # Additional template partials
```

## Template Flow

Every template follows this pattern:

```php
<?php
get_header();  // Renders <header> with nav

// Template-specific content here

get_footer();  // Renders <footer>
?>
```

## Detailed Template Guide

### 1. Home Page (`home.php`)

**Purpose**: Homepage with homepage block registry system

**Features**:
- Renders registered homepage blocks (layout components)
- Displays parsed content sections below blocks
- Uses `boilerplate_get_active_homepage_blocks()` for block management
- Ideal for hero sections, featured content, services preview

**To Customize**:

1. Edit `/inc/home-template-meta.php` to register blocks
2. Create layout components: `node generate.js` → layout
3. Add homepage content in WordPress with proper heading hierarchy

**Homepage Block Registration Example**:

```php
// In inc/home-template-meta.php
add_action('init', function() {
    boilerplate_register_homepage_block('hero-with-stats', [
        'render_type' => 'layout',
        'priority' => 10,
        'args' => [
            'title' => 'Your Title',
            'stats' => [...],
        ],
    ]);
});
```

**URL**: http://law-by-yan.local (or whatever slug you set)

---

### 2. Standard Page (`page.php`)

**Purpose**: Generic page template for about, services, team, etc.

**Features**:
- Displays page title with hero layout block
- Parses page content into structured sections
- Uses heading hierarchy: H1 (title) → H2 (sections) → H3 (details)
- Perfect for legal service pages, firm information

**Current Structure**:

```php
get_header();

// Renders hero block with title + excerpt
get_template_part('components/layout/base-layout-block/base-layout-block', null, [
    'variant' => 'page',
    'title' => get_the_title(),
    'body' => get_the_excerpt(),
]);

// Renders parsed content sections
echo $parsed_content;

get_footer();
```

**To Customize Page Design**:

1. Edit page content in WordPress (Pages → Edit)
2. Use proper heading hierarchy
3. Add images, lists, formatted text
4. The parser automatically converts H2→content blocks

**Creating Legal Service Pages**:

Example page structure for "Corporate Law Services":

```
# Corporate Law Services

We help businesses navigate complex corporate matters.

## Practice Overview

Description of corporate law services.

### Our Approach

Explain your methodology.

### Experience

Detail your credentials.

## Services Offered

### Business Formation

Contract formation and business structure.

### M&A Transactions

Mergers and acquisitions support.

### Contracts & Agreements

Contract drafting and negotiation.

## Client Results

Share case studies and outcomes.

## Contact Us

Call to action to book consultation.
```

**URLs Generated**:
- /about
- /services/corporate-law
- /team
- /case-studies
- etc. (any regular WordPress page)

---

### 3. Single Post (`single.php`)

**Purpose**: Individual blog post, press release, or custom post type

**Features**:
- Displays post title with hero layout block
- Shows full post content (not parsed)
- Ideal for blog articles, news, updates
- Includes date metadata

**Current Structure**:

```php
get_header();

// Hero block with title + excerpt
get_template_part('components/layout/base-layout-block/base-layout-block', null, [
    'variant' => 'single',
    'title' => get_the_title(),
    'body' => get_the_excerpt(),
]);

// Full post content
echo the_content();

get_footer();
```

**To Customize Blog Design**:

1. Create blog posts in WordPress (Posts → Add New)
2. Add featured image (optional)
3. Write content in Classic or Block editor
4. Content displays exactly as written (no parsing)

**Blog Post Example**:

```
Title: Understanding Modern Legal Practice

Content:

Legal practice has evolved dramatically over the past two decades. 
Technology has transformed how attorneys work, communicate with clients, 
and manage cases.

[Include formatted text, images, quotes, lists]

Contact us for consultation on your legal matter.
```

**URLs Generated**:
- /blog/post-title
- /news/press-release
- /articles/any-article

---

### 4. Post Template (`single-post.php`)

**Purpose**: Post type specific template

**Current Implementation**: Simply delegates to `single.php`

```php
<?php
require get_template_directory() . '/single.php';
```

**Why**:
- Keeps all single-post styling consistent
- Can be customized separately if needed
- Follows WordPress template hierarchy

**To Customize**:
If you need post-specific styling different from other post types, you can add custom code here instead of requiring single.php.

---

### 5. Archive Page (`archive.php`)

**Purpose**: Category, tag, and post type archive listings

**Features**:
- Lists all posts in category/tag/post type
- Shows post title, date, and link
- Pagination ready
- Clean list layout

**Current Structure**:

```php
get_header();

// Header section with title
get_template_part('components/layout/base-layout-block/base-layout-block', null, [
    'variant' => 'archive',
    'title' => get_the_archive_title(),
    'body' => get_the_archive_description(),
]);

// Post list
while (have_posts()) :
    the_post();
    // Display post title, date, link
endwhile;

get_footer();
```

**To Customize Archive Display**:

Edit `archive.php` to customize how posts are displayed:

```php
<?php
// After the archive header, customize the post list:

<section class="archive-shell__body">
    <div class="post-grid">  <!-- Changed from post-list -->
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('post-grid__item'); ?>>
                    <!-- Display post thumbnail -->
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-grid__image">
                            <?php the_post_thumbnail('medium'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <h3><?php the_title(); ?></h3>
                    <p class="post-grid__excerpt"><?php the_excerpt(); ?></p>
                    <p class="post-grid__meta"><?php echo esc_html(get_the_date()); ?></p>
                    <a href="<?php the_permalink(); ?>" class="post-grid__link">Read More →</a>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p><?php echo esc_html(boilerplate_get_default_copy('empty_results')); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
```

**URLs Generated**:
- /category/legal-updates (category archives)
- /tag/corporate-law (tag archives)
- /blog (post type archives)

---

### 6. Search Results (`search.php`)

**Purpose**: Display results from site search

**Features**:
- Search form for new search
- List of matching posts
- Shows no results message if empty
- Pagination for large result sets

**Current Structure**:

```php
get_header();

// Header with search title
get_template_part('components/layout/base-layout-block/base-layout-block', null, [
    'variant' => 'search',
    'title' => boilerplate_get_default_copy('search_title'),
]);

// Search form
?>
<form method="get" action="<?php echo esc_url(home_url('/')); ?>">
    <input type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>">
</form>
<?php

// Search results
while (have_posts()) :
    the_post();
    // Display post title, date, link
endwhile;

get_footer();
```

**To Customize Search**:

Edit `search.php` to improve search result styling:

```php
<?php
get_header();
?>

<main class="search-shell" id="content">
    <?php
    get_template_part('components/layout/base-layout-block/base-layout-block', null, [
        'variant' => 'search',
        'title' => 'Search Results',
        'body' => sprintf('Found %d results for "%s"', $wp_query->found_posts, get_search_query()),
    ]);
    ?>

    <section class="search-shell__body">
        <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
            <input 
                type="search" 
                class="search-field" 
                placeholder="Search..." 
                value="<?php echo esc_attr(get_search_query()); ?>" 
                name="s"
            >
            <button type="submit" class="search-submit">Search</button>
        </form>

        <div class="post-list">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('post-list__item'); ?>>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p class="post-list__excerpt"><?php the_excerpt(); ?></p>
                        <span class="post-list__meta"><?php echo esc_html(get_the_date()); ?></span>
                    </article>
                <?php endwhile; ?>
                
                <!-- Pagination -->
                <div class="post-list__pagination">
                    <?php the_posts_pagination(); ?>
                </div>
            <?php else : ?>
                <p><?php echo esc_html(boilerplate_get_default_copy('empty_results')); ?></p>
                <p>Try searching for different keywords.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
```

**URL**: /?s=search+term (or /search-results)

---

### 7. 404 Page (`404.php`)

**Purpose**: Handle page not found errors

**Features**:
- Friendly 404 message
- Link back to homepage or search
- Maintains site navigation
- Professional error handling

**Current Structure**:

```php
get_header();

// Error message
get_template_part('components/layout/base-layout-block/base-layout-block', null, [
    'variant' => 'error',
    'title' => boilerplate_get_default_copy('not_found_title'),
    'body' => boilerplate_get_default_copy('not_found_body'),
    'links' => [
        [
            'label' => 'Back to Home',
            'url' => home_url('/'),
        ],
    ],
]);

get_footer();
```

**To Customize 404**:

Edit `404.php` for custom 404 experience:

```php
<?php
get_header();
?>

<main class="site-main error-404-shell" id="content">
    <section class="error-404-shell__content">
        <div class="error-404-shell__inner">
            <h1 class="error-404-shell__title">404</h1>
            <h2 class="error-404-shell__heading">Page Not Found</h2>
            <p class="error-404-shell__message">
                We're sorry, but the page you're looking for doesn't exist. 
                It may have been moved or deleted.
            </p>
            
            <div class="error-404-shell__actions">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="error-404-shell__link">
                    Return to Homepage
                </a>
                <a href="<?php echo esc_url(home_url('/?s=')); ?>" class="error-404-shell__link">
                    Search the Site
                </a>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="error-404-shell__link">
                    Contact Us
                </a>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
```

**CSS for 404**:

```css
.error-404-shell {
    padding: var(--boilerplate-section-space) var(--boilerplate-page-gutter);
    text-align: center;
}

.error-404-shell__inner {
    max-width: 600px;
    margin: 0 auto;
}

.error-404-shell__title {
    font-size: 6rem;
    font-weight: 700;
    color: var(--boilerplate-color-accent);
    line-height: 1;
    margin-bottom: 1rem;
}

.error-404-shell__heading {
    font-size: 2rem;
    color: var(--boilerplate-color-text);
    margin-bottom: 1rem;
}

.error-404-shell__message {
    color: var(--boilerplate-color-text-muted);
    font-size: 1.125rem;
    margin-bottom: 2rem;
}

.error-404-shell__actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.error-404-shell__link {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    background: var(--boilerplate-color-accent);
    color: white;
    border-radius: var(--boilerplate-radius);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.error-404-shell__link:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .error-404-shell__title {
        font-size: 4rem;
    }

    .error-404-shell__actions {
        flex-direction: column;
    }
}
```

**URL**: Any non-existent page triggers this

---

### 8. Index Page (`index.php`)

**Purpose**: Fallback template if no specific template exists

**Note**: This theme doesn't include a custom `index.php` because all cases are covered by specific templates (home.php, single.php, archive.php, etc.)

---

## Template Copy/Defaults

All templates use copy from `boilerplate_get_default_copy()`. Customize in `/inc/boilerplate-defaults.php`:

```php
'copy' => [
    // Archive templates
    'archive_title' => 'Lorem ipsum dolor',
    
    // Search template
    'search_title' => 'Lorem ipsum',
    
    // Single post
    'single_title' => 'Lorem ipsum dolor sit amet',
    
    // 404 page
    'not_found_title' => 'Lorem ipsum dolor sit amet',
    'not_found_body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...',
    
    // Empty results
    'empty_results' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    
    // Page summary
    'page_summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    
    // Content eyebrow
    'content_eyebrow' => 'Lorem ipsum',
]
```

Update these to match your firm's messaging.

---

## Template Customization Workflow

### For Pages (About, Services, Team, etc.):

1. **Create page in WordPress**: Pages → Add New
2. **Write content with headings**:
   - H1: Page title (optional, uses page title by default)
   - H2: Main sections
   - H3: Subsections
3. **Publish and view**: The page.php template renders it with proper structure

### For Blog Posts & News:

1. **Create post in WordPress**: Posts → Add New
2. **Add featured image** (optional)
3. **Write content** (no heading structure needed)
4. **Publish and view**: The single.php template renders it with date/author info

### For Archive Pages (Categories, Tags):

1. **Create categories or tags** in WordPress
2. **Assign posts** to categories/tags
3. **View archives**: /category/your-category or /tag/your-tag
4. **Customize archive.php** if you want different layout

### For Homepage:

1. **Create layout components**: `node generate.js` → layout
2. **Register in home-template-meta.php**
3. **Create homepage content**: Pages → Add New → set as static homepage
4. **The home.php template** renders blocks + parsed content

---

## Template Structure Summary

| Template | Purpose | Use Case | Customization |
|----------|---------|----------|---------------|
| `home.php` | Homepage with blocks | First impression | Create layout components, register blocks |
| `page.php` | Standard pages | About, Services, Team, Pages | Edit page content in WordPress |
| `single.php` | Individual posts | Blog posts, case studies | Edit post content in WordPress |
| `single-post.php` | Post type specific | Delegates to single.php | Rarely customized |
| `archive.php` | Category/tag listings | Blog archive, news | Customize post list display |
| `search.php` | Search results | Search functionality | Customize search form, results display |
| `404.php` | Page not found | Error handling | Customize error message, links |

---

## Creating Law Firm Specific Templates

### Blog/Updates Template

Create a blog listing component:

```bash
node generate.js
# Select: layout
# Name: blog-recent
```

Then display recent posts with custom styling.

### Services Grid Template

Create services overview:

```bash
node generate.js
# Select: layout
# Name: services-directory
```

Display all services with descriptions and links.

### Team Gallery Template

Create team display:

```bash
node generate.js
# Select: layout
# Name: team-gallery
```

Show attorneys with photos and bios.

### Testimonials Template

Create client testimonials:

```bash
node generate.js
# Select: layout
# Name: client-testimonials
```

Display case outcomes and client feedback.

---

## Complete Template Checklist

- [ ] Customize copy in `boilerplate-defaults.php`
- [ ] Create homepage blocks (hero, services, testimonials)
- [ ] Create home page content in WordPress
- [ ] Create About page in WordPress (page.php)
- [ ] Create Services page in WordPress (page.php)
- [ ] Create Team page in WordPress (page.php)
- [ ] Create blog posts (single.php)
- [ ] Assign posts to categories
- [ ] Test archives (/category/*, /tag/*)
- [ ] Test search functionality
- [ ] Test 404 page (visit non-existent URL)
- [ ] Verify all templates load correctly
- [ ] Test responsive design on all templates
- [ ] Customize 404 page if needed
- [ ] Customize search.php if needed
- [ ] Customize archive.php if needed

Your complete template system is set up and ready to customize!
