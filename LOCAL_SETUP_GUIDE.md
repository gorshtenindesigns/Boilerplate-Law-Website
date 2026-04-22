# Local Setup & Testing Guide

This guide helps you set up the Boilerplate theme locally for design testing and development.

## Environment

- **Local Environment**: Local by Flywheel
- **Location**: `/Users/yan/Local Sites/law-by-yan/app/public/wp-content/themes/boilerplate-update`
- **Theme**: Already in correct directory for WordPress to recognize

## Prerequisites

Before starting, ensure you have:

- WordPress running locally via Local by Flywheel
- Node.js installed (for the generator script)
- A code editor (VS Code recommended)

## Quick Start

### 1. Verify Theme Installation

1. Open WordPress Admin Dashboard for your local site
2. Navigate to **Appearance > Themes**
3. Look for "Boilerplate Update" theme
4. Click **Activate** to set it as your active theme

### 2. Create Test Content

You need content to test the theme's rendering. Create test pages:

#### Option A: Via WordPress Admin
1. Go to **Pages** or **Posts**
2. Create new content with this structure:

```
# Page Title (H1)

This is introductory text before the first section.

## First Section (H2)

Section content here. Can include:

### Subsection Header (H3)

Details about the section.

#### Additional Info (H4)

Supporting details.

## Second Section (H2)

More content to test multi-section rendering.
```

#### Option B: Via Classic Editor
- Use **Add Block > Classic** to write content in the classic editor
- The parser recognizes `h1`, `h2`, `h3`, `h4-h6` hierarchy
- Structured content follows the **Parser Contract** (see THEME_ARCHITECTURE.md)

### 3. View Test Pages

1. Save and publish your test page
2. View the page on the frontend to see theme rendering
3. Inspect using browser DevTools (F12)

## Component Development Workflow

### Viewing Base Components

Base components already exist and are ready for extension:

1. **Layout Component**: `components/layout/base-layout-block/`
   - Used for page shells, hero wrappers, section intros
   - View: `base-layout-block.php`, `base-layout-block.css`, `base-layout-block.js`

2. **Content Block Component**: `components/content-blocks/base-content-block/`
   - Used for parsed sections (h2 blocks)
   - View: `base-content-block.php`, `base-content-block.css`, `base-content-block.js`

3. **UI Component**: `components/ui/base-ui-component/`
   - Used for reusable UI elements (buttons, chips, tags)
   - View: `base-ui-component.php`, `base-ui-component.css`, `base-ui-component.js`

### Creating New Components

To scaffold a new component:

```bash
cd /Users/yan/Local Sites/law-by-yan/app/public/wp-content/themes/boilerplate-update
node generate.js
```

Follow the prompts:
1. Choose component type: `layout`, `content-block`, or `ui`
2. Enter component name (e.g., "hero section", "card")
3. Generator creates component with boilerplate structure

**Result**: New component in `components/<type>/<kebab-case-name>/`

## Testing Checklist

### Frontend Display
- [ ] Theme activates without errors
- [ ] Pages display with correct structure
- [ ] CSS styles apply correctly
- [ ] JavaScript interactivity works (if added)
- [ ] Hero sections render (if using layout components)
- [ ] Parsed sections display (if using content blocks)

### Content Rendering
- [ ] H1 renders as page title/hero
- [ ] H2 sections render with base-content-block
- [ ] H3 subsections appear
- [ ] Pre-h2 text displays as summary
- [ ] Images embed properly

### Component Functionality
- [ ] Base layout block renders correctly
- [ ] Base content block displays sections
- [ ] UI components appear without style conflicts
- [ ] Custom JS initializes properly

### Responsive Design
- [ ] Desktop display (1200px+)
- [ ] Tablet display (768px - 1199px)
- [ ] Mobile display (< 768px)
- [ ] Touch interactions work on mobile

## CSS & JavaScript Workflow

### Global Styles
- **File**: `assets/css/_global.css`
- **Purpose**: Base typography, spacing, colors
- **How**: Enqueued in `functions.php`, loaded on all pages

### Component Styles
- **Location**: `components/<type>/<name>/<name>.css`
- **Namespace**: Use component folder name as class prefix
- **Example**: Component `hero-section` uses `.hero-section { }`

### JavaScript Initialization
- **Main Entry**: `assets/js/index.js`
- **Utilities**: 
  - `assets/js/parallax-utility.js`
  - `assets/js/lenis-init.js`
  - `assets/js/homepage-reveals.js`
  - `assets/js/divider-animations.js`

### Testing Styles
1. Open browser DevTools (F12)
2. Use **Inspector** to select elements
3. Check **Styles** panel for active CSS
4. Use **Console** to test JavaScript

## Template Files for Testing

Navigate to these to see different template structures:

- **Homepage**: `home.php` - Uses homepage block registry
- **Standard Page**: `page.php` - Uses `boilerplate_render_structured_content()`
- **Single Post**: `single-post.php` - Individual post template
- **Archive**: `archive.php` - Category/tag listings
- **Search**: `search.php` - Search results
- **404**: `404.php` - Not found page

## Debugging Tips

### Check Console Errors
1. Open browser DevTools (F12)
2. Go to **Console** tab
3. Look for JavaScript errors in red
4. Check **Network** tab for failed asset loads

### Check PHP Errors
1. In WordPress Admin, go to **Tools > Site Health**
2. Look for PHP errors or warnings
3. Check theme activity log in browser console

### Verify Asset Loading
1. DevTools > **Network** tab
2. Refresh page
3. Check all CSS and JS files load successfully (Status 200)
4. Look for 404 errors

### Debug Parser Issues
1. Edit `inc/content-page-parser.php`
2. Add debug output to verify parsed sections
3. Check `ContentContext` class in `inc/classes/Content/ContentContext.php`

## Design Testing Workflow

### 1. Create Design Test Page
```
# Full Width Hero

Test text for summary.

## Design Test 1: Typography

This section tests all heading levels and text styles.

### Heading 3 - Subsection

#### Heading 4 - Details

##### Heading 5 - Support

###### Heading 6 - Minor

## Design Test 2: Content Blocks

Test the base-content-block component rendering with various content types.

## Design Test 3: UI Components

Test buttons, chips, tags, and other small reusable elements.
```

### 2. Create Component Demo Pages
For each new component, create a dedicated page showcasing:
- All component states (normal, hover, active)
- Different content variations
- Responsive behavior
- Accessibility features

### 3. Mobile Testing
- Use Chrome DevTools device emulation or
- Use [Responsively App](https://responsively.app/) for multi-device testing

## Common Tasks

### Disable Global Styles
The theme automatically removes WordPress block library styles. To verify:
1. Check DevTools Network tab - `wp-block-library-*.css` should not load
2. Check `functions.php` line 48-58 for the dequeue logic

### Test Placeholder Copy
All placeholder text uses `boilerplate_get_default()` function. Find defaults in:
- `inc/boilerplate-defaults.php`

### Edit Theme Colors/Typography
Edit `inc/boilerplate-defaults.php` to change:
- Color tokens
- Typography defaults
- Spacing tokens

Changes apply globally via CSS variables added to `wp_add_inline_style()` in `functions.php`

### Create Custom Component
1. Run `node generate.js`
2. Select component type
3. Edit the generated files:
   - `<name>.php` - HTML markup
   - `<name>.css` - Styles
   - `<name>.js` - Interactivity
4. Test on a page

## Performance Testing

### Check Asset Size
```bash
du -sh /Users/yan/Local Sites/law-by-yan/app/public/wp-content/themes/boilerplate-update/assets/
```

### Check Frontend Performance
1. DevTools > **Lighthouse** tab
2. Run audit for Performance, Accessibility, Best Practices
3. Address any warnings

## File Structure Reference

```
boilerplate-update/
├── assets/                      # Global assets
│   ├── css/                     # Global stylesheets
│   ├── js/                      # Global JavaScript utilities
│   └── media/                   # Placeholder images
├── components/                  # Theme components
│   ├── layout/                  # Page shell components
│   ├── content-blocks/          # Section renderers
│   └── ui/                      # Small reusable UI
├── inc/                         # PHP includes
│   ├── classes/                 # PHP classes
│   ├── boilerplate-defaults.php # Theme defaults
│   ├── theme-helpers.php        # Helper functions
│   ├── content-page-parser.php  # Content parser
│   └── ...
├── templates/                   # Template partials
├── functions.php                # Theme functions
├── home.php                     # Homepage template
├── page.php                     # Page template
├── single.php                   # Single post
├── archive.php                  # Archive template
├── search.php                   # Search template
├── 404.php                      # 404 template
└── style.css                    # Theme main stylesheet
```

## Troubleshooting

### Theme Not Activating
- [ ] Verify file in correct WordPress theme directory
- [ ] Check for PHP syntax errors in `functions.php`
- [ ] Check WordPress error logs

### Content Not Rendering
- [ ] Verify page content uses correct heading hierarchy
- [ ] Check that parser is reading h2 sections correctly
- [ ] Inspect page source to see parsed HTML

### Styles Not Applying
- [ ] Hard refresh browser (Cmd+Shift+R on Mac)
- [ ] Check DevTools to see if CSS file loads
- [ ] Verify CSS selector specificity
- [ ] Check for CSS class namespace conflicts

### JavaScript Not Working
- [ ] Check browser console for errors
- [ ] Verify JavaScript files enqueue in `functions.php`
- [ ] Check DevTools Network tab for failed JS loads
- [ ] Test in browser console directly

## Next Steps

1. **Activate theme** in WordPress Admin
2. **Create test content** with proper heading hierarchy
3. **View pages** and inspect with DevTools
4. **Test base components** rendering correctly
5. **Create new component** using generator for customization
6. **Build and test** your custom sections

---

For more info, see:
- `THEME_ARCHITECTURE.md` - System structure
- `TEMPLATE_BLUEPRINT.md` - Component rules
- `RENDER_DESIGN_GUIDE.md` - Design patterns
