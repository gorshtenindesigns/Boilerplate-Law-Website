# Quick Start - Testing Your Theme Locally

## Your Local Site URL

**🌐 Visit**: http://law-by-yan.local

**Admin Panel**: http://law-by-yan.local/wp-admin

## Site Credentials

- **Database**: local
- **DB User**: root
- **DB Password**: root
- **Mailpit** (Email testing): http://localhost:10000

## Services Status

Your Local by Flywheel services are currently **RUNNING**:

- ✅ Nginx (Web Server)
- ✅ PHP 8.2.29 (PHP Engine)
- ✅ MySQL 8.0.35 (Database)
- ✅ Mailpit (Email Testing)

## Next Steps: Activate Theme & Test

### 1. Go to WordPress Admin
Open http://law-by-yan.local/wp-admin in your browser

### 2. Activate the Boilerplate Theme
1. Navigate to **Appearance > Themes**
2. Find "Boilerplate Update" theme
3. Click **Activate**

### 3. Create Test Content
1. Go to **Pages** and click **Add New**
2. Enter content with this structure:

```
# Homepage Hero Title

Summary text appears before the first section.

## Section One

This is the first section content. The parser recognizes H2 as section boundaries.

### Subsection

H3 creates disclosure items within sections.

## Section Two

Another section to test multi-section rendering.
```

3. Click **Publish**

### 4. View the Page
1. Go to **Pages** > select your page > **View Page**
2. You should see the theme render with:
   - Hero at top (from H1)
   - Summary text
   - Content blocks for each H2 section
   - Subsections (H3) organized under H2

### 5. Inspect with Browser DevTools
- Press **F12** to open DevTools
- Check **Elements** tab to see rendered HTML
- Check **Styles** tab to see applied CSS
- Check **Console** tab for any JavaScript errors

## Testing Component Rendering

### Test Base Content Block
The base content block renders each H2 section. Check that:
- [ ] H2 headings render with proper styling
- [ ] Content under H2 appears
- [ ] CSS classes are applied correctly

### Test Layout Components
Homepage or hero sections should render with base layout block. Check:
- [ ] Hero section displays
- [ ] Layout spacing is correct
- [ ] Background/foreground contrast is readable

### Test UI Components
Look for buttons, chips, or tags. Verify:
- [ ] Buttons are clickable
- [ ] Hover states work
- [ ] Disabled states (if present) are visible

## Create New Components

To add new custom components to the theme:

```bash
cd /Users/yan/Local\ Sites/law-by-yan/app/public/wp-content/themes/boilerplate-update
node generate.js
```

Follow the prompts to create:
- **Layout** component (page shells, heroes)
- **Content Block** component (section renderers)
- **UI** component (buttons, chips, etc.)

New components automatically register via `register.php`

## Develop & See Changes

### CSS Changes
- Edit files in `assets/css/` or `components/*/`
- Hard refresh browser: **Cmd+Shift+R** (Mac)

### JavaScript Changes
- Edit files in `assets/js/` or `components/*/`
- Check browser console for errors: **F12 > Console**

### PHP Changes
- Edit component `.php` files
- Reload page to see changes

### Content Changes
- Edit in WordPress Admin
- Changes apply immediately

## Troubleshooting

### Theme Won't Activate
- [ ] Check http://law-by-yan.local/wp-admin/tools.php?page=site-health for errors
- [ ] Verify `functions.php` has no syntax errors
- [ ] Check browser console for JavaScript errors

### Content Won't Parse
- [ ] Ensure you used Classic Editor or proper heading structure
- [ ] Check that first heading is H1
- [ ] Sections must start with H2
- [ ] Check DevTools Console for parser errors

### Styles Don't Apply
- [ ] Hard refresh: **Cmd+Shift+R**
- [ ] Check DevTools > Network to see CSS loads
- [ ] Verify CSS file paths in `functions.php`

### JavaScript Doesn't Work
- [ ] Check DevTools > Console for errors
- [ ] Verify JS files enqueue in `functions.php`
- [ ] Check Network tab to see JS loads successfully

## File Locations

- **Theme**: `/Users/yan/Local Sites/law-by-yan/app/public/wp-content/themes/boilerplate-update/`
- **Components**: `components/layout/`, `components/content-blocks/`, `components/ui/`
- **Styles**: `assets/css/`, `components/*/`
- **Scripts**: `assets/js/`, `components/*/`

## Useful WordPress URLs

- **Dashboard**: http://law-by-yan.local/wp-admin
- **Pages**: http://law-by-yan.local/wp-admin/edit.php?post_type=page
- **Posts**: http://law-by-yan.local/wp-admin/edit.php
- **Appearance**: http://law-by-yan.local/wp-admin/themes.php
- **Site Health**: http://law-by-yan.local/wp-admin/tools.php?page=site-health

---

**All set!** Your theme is ready for testing. Start with activating the theme and creating test content.

For detailed guides, see:
- `LOCAL_SETUP_GUIDE.md` - Complete setup reference
- `THEME_ARCHITECTURE.md` - System structure
- `TEMPLATE_BLUEPRINT.md` - Component rules
