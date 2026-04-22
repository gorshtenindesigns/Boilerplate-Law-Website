# Theme Documentation Index

Your Boilerplate WordPress theme is fully documented. Use this index to find the right guide for your task.

## Quick Navigation

### 🏠 Getting Started
- **NEW**: Start here for local setup and testing
- **File**: `LOCAL_SETUP_GUIDE.md`
- **Topics**: Environment setup, testing checklist, debugging

### 🎨 Homepage Design
- **File**: `LAW_FIRM_DESIGN_GUIDE.md`
- **Topics**: Homepage customization, creating hero and services components, using all templates
- **Also read**: `HOMEPAGE_DESIGN_GUIDE.md` for general homepage system

### 📄 All Templates
- **File**: `COMPLETE_TEMPLATE_SYSTEM_GUIDE.md`
- **Topics**: Detailed guide for every template (home, page, single, archive, search, 404)
- **Use when**: Customizing any page type beyond homepage

### ⚙️ Theme Architecture
- **File**: `THEME_ARCHITECTURE.md`
- **Topics**: System overview, component structure, parser contract, base components
- **For**: Understanding how everything fits together

### 🏗️ Component System
- **File**: `TEMPLATE_BLUEPRINT.md`
- **Topics**: Rules for creating components, naming conventions, directory structure
- **Use when**: Creating new components

### 🎯 Render Design
- **File**: `RENDER_DESIGN_GUIDE.md`
- **Topics**: Base components, render functions, generator workflow
- **For**: Understanding the rendering system

### 📋 Create Test Content
- **File**: `CREATE_TEST_CONTENT.md`
- **Topics**: Sample content for 5 pages ready to copy/paste into WordPress
- **Use when**: You need content to see the theme in action

---

## By Task

### I want to...

#### Set up the theme locally
1. Read: `QUICK_START.md` - 5 minute quick reference
2. Read: `LOCAL_SETUP_GUIDE.md` - Detailed setup guide
3. Visit: http://law-by-yan.local

#### Customize the homepage design
1. Read: `LAW_FIRM_DESIGN_GUIDE.md` (first half)
2. Run: `node generate.js` to create components
3. Copy code from guide into component files
4. Register components in `/inc/home-template-meta.php`
5. Create homepage content in WordPress

#### Create custom components
1. Read: `TEMPLATE_BLUEPRINT.md` - Rules and conventions
2. Run: `node generate.js`
3. Choose component type and name
4. Edit the generated PHP, CSS, JS files
5. Component auto-registers via `register.php`

#### Customize pages (About, Services, Team, Contact)
1. Read: `COMPLETE_TEMPLATE_SYSTEM_GUIDE.md` - page.php section
2. Create page in WordPress: Pages → Add New
3. Use heading hierarchy: H1 (title) → H2 (sections) → H3 (details)
4. The parser automatically renders sections as styled blocks

#### Add blog posts and news
1. Read: `COMPLETE_TEMPLATE_SYSTEM_GUIDE.md` - single.php and archive.php sections
2. Create posts in WordPress: Posts → Add New
3. Assign to categories (News, Updates, Insights, etc.)
4. Blog posts use single.php template
5. Archives auto-generate at /category/name

#### Customize colors and typography
1. Edit: `/inc/boilerplate-defaults.php`
2. Change values in `boilerplate_get_design_defaults()`
3. Changes apply globally to all templates

#### Fix 404 page
1. Read: `COMPLETE_TEMPLATE_SYSTEM_GUIDE.md` - 404.php section
2. Edit: `404.php`
3. Customize message, links, styling

#### Understand the system
1. Read: `THEME_ARCHITECTURE.md` - Overall structure
2. Read: `RENDER_DESIGN_GUIDE.md` - Rendering system
3. Read: `TEMPLATE_BLUEPRINT.md` - Component rules

#### Test responsive design
1. Open page in browser
2. Press F12 for DevTools
3. Click device emulation icon
4. Test at 375px (mobile), 768px (tablet), 1200px (desktop)

#### Debug styles or JavaScript issues
1. Press F12 for DevTools
2. Check Elements tab for HTML structure
3. Check Styles tab for CSS being applied
4. Check Console tab for JavaScript errors
5. See `LOCAL_SETUP_GUIDE.md` debugging section

---

## Documentation Files

| File | Purpose | Read Time |
|------|---------|-----------|
| `QUICK_START.md` | 5-minute quick reference | 5 min |
| `LOCAL_SETUP_GUIDE.md` | Complete local setup guide | 10 min |
| `LAW_FIRM_DESIGN_GUIDE.md` | Homepage + all templates for law firm | 15 min |
| `COMPLETE_TEMPLATE_SYSTEM_GUIDE.md` | Detailed guide for every template | 20 min |
| `HOMEPAGE_DESIGN_GUIDE.md` | Homepage design system basics | 10 min |
| `THEME_ARCHITECTURE.md` | System architecture and overview | 10 min |
| `TEMPLATE_BLUEPRINT.md` | Rules for creating components | 8 min |
| `RENDER_DESIGN_GUIDE.md` | Rendering and design patterns | 8 min |
| `CREATE_TEST_CONTENT.md` | Sample content to add | 5 min |
| `HOMEPAGE_CONTENT_TEMPLATE.php` | Copy/paste homepage content | 2 min |

---

## Theme File Structure

```
boilerplate-update/
├── 📚 DOCUMENTATION
│   ├── README.md                           # Main intro
│   ├── QUICK_START.md                      # 5-minute guide
│   ├── LOCAL_SETUP_GUIDE.md                # Setup reference
│   ├── LAW_FIRM_DESIGN_GUIDE.md            # Homepage + templates
│   ├── COMPLETE_TEMPLATE_SYSTEM_GUIDE.md   # All templates detail
│   ├── HOMEPAGE_DESIGN_GUIDE.md            # Homepage system
│   ├── THEME_ARCHITECTURE.md               # System overview
│   ├── TEMPLATE_BLUEPRINT.md               # Component rules
│   ├── RENDER_DESIGN_GUIDE.md              # Rendering system
│   ├── CREATE_TEST_CONTENT.md              # Test content guide
│   ├── HOMEPAGE_CONTENT_TEMPLATE.php       # Copy/paste content
│   └── DOCUMENTATION_INDEX.md              # This file
│
├── 🔧 CONFIGURATION
│   ├── functions.php                       # Main theme file
│   ├── style.css                           # Main stylesheet
│   ├── inc/
│   │   ├── boilerplate-defaults.php        # Colors, copy, spacing
│   │   ├── theme-helpers.php               # Helper functions
│   │   ├── content-page-parser.php         # Content parser
│   │   ├── home-template-meta.php          # Homepage blocks
│   │   ├── header-navigation.php           # Navigation
│   │   └── classes/
│   │       └── Content/
│   │           └── ContentContext.php      # Parser context
│   │
├── 🏠 TEMPLATES
│   ├── home.php                            # Homepage
│   ├── page.php                            # Standard pages
│   ├── single.php                          # Single posts
│   ├── single-post.php                     # Post posts
│   ├── archive.php                         # Archives
│   ├── search.php                          # Search results
│   ├── 404.php                             # Not found
│   ├── index.php                           # Fallback
│   ├── header.php                          # Header
│   ├── footer.php                          # Footer
│   └── templates/                          # Partials
│
├── 🧩 COMPONENTS
│   ├── layout/
│   │   └── base-layout-block/              # Base layout component
│   ├── content-blocks/
│   │   └── base-content-block/             # Base content block
│   └── ui/
│       └── base-ui-component/              # Base UI component
│
├── 🎨 ASSETS
│   ├── css/
│   │   ├── _global.css                     # Global styles
│   │   └── parent-child.css                # Theme styles
│   ├── js/
│   │   ├── index.js                        # Main JS
│   │   ├── lenis-init.js                   # Smooth scroll
│   │   ├── parallax-utility.js             # Parallax
│   │   ├── homepage-reveals.js             # Animations
│   │   └── divider-animations.js           # Dividers
│   └── media/
│       └── placeholder images
│
└── 🚀 GENERATOR
    └── generate.js                         # Component generator
```

---

## Starting Points by Experience

### First Time Using This Theme?
1. Open `QUICK_START.md`
2. Visit http://law-by-yan.local/wp-admin
3. Activate theme: Appearance > Themes > Activate
4. Follow steps in QUICK_START.md
5. View homepage at http://law-by-yan.local

### Want to Customize Homepage?
1. Open `LAW_FIRM_DESIGN_GUIDE.md`
2. Follow Step 1-3 for basic customization
3. Follow Step 4-6 to create custom components
4. Test and iterate

### Need Full Site Design?
1. Start with `LAW_FIRM_DESIGN_GUIDE.md` for homepage
2. Read `COMPLETE_TEMPLATE_SYSTEM_GUIDE.md` for all page types
3. Create pages in WordPress using page.php template
4. Create blog posts using single.php template
5. Build complete site structure

### Troubleshooting Issues?
1. Check `LOCAL_SETUP_GUIDE.md` debugging section
2. Use browser DevTools (F12)
3. Check console for errors
4. Verify theme is activated
5. Test with test content first

### Want to Extend the Theme?
1. Read `THEME_ARCHITECTURE.md` for system overview
2. Read `TEMPLATE_BLUEPRINT.md` for component rules
3. Run `node generate.js` to create new component
4. Edit component files (PHP, CSS, JS)
5. Component auto-registers

---

## Key Concepts

### Templates
WordPress theme files that render different page types:
- `home.php` - Homepage
- `page.php` - Pages
- `single.php` - Posts
- `archive.php` - Category/tag listings
- `search.php` - Search results
- `404.php` - Not found

### Components
Reusable, modular pieces:
- **Layout** - Page shells, heroes, sections
- **Content Blocks** - Parsed section renderers
- **UI** - Buttons, chips, tags

### Parser
Analyzes page content heading structure:
- H1 → Page title/hero
- H2 → Content block sections
- H3 → Details within sections

### CSS Variables
Design tokens for consistency:
- `--boilerplate-color-*` - Colors
- `--boilerplate-font-*` - Typography
- `--boilerplate-*-space` - Spacing

### Defaults
Customizable theme values in `boilerplate-defaults.php`:
- Colors and tokens
- Typography
- Spacing
- Copy/text

---

## Support & Troubleshooting

### Common Issues

**Theme won't activate?**
- Check `boilerplate-defaults.php` for syntax errors
- Clear browser cache and hard refresh
- Check WordPress error logs

**Content doesn't show?**
- Verify page is published (not in draft)
- Check heading hierarchy is correct
- Ensure first heading is H1

**Styles don't apply?**
- Hard refresh browser (Cmd+Shift+R)
- Check CSS file loads in Network tab
- Verify CSS selectors are correct

**JavaScript errors?**
- Check browser console (F12 > Console)
- Verify JS files enqueue in `functions.php`
- Check Network tab for failed JS loads

**Colors/fonts wrong?**
- Edit `boilerplate-defaults.php`
- Hard refresh browser
- Check CSS custom properties are applied

---

## Next Steps

1. **Read** `QUICK_START.md` - Get oriented (5 min)
2. **Visit** http://law-by-yan.local - See current state
3. **Read** `LAW_FIRM_DESIGN_GUIDE.md` - Plan your customization (15 min)
4. **Create** homepage components - `node generate.js`
5. **Add** page content in WordPress
6. **Test** all pages and templates
7. **Customize** colors and copy in defaults
8. **Deploy** to production

Your theme is ready! Start with QUICK_START.md and you'll be running in minutes.
