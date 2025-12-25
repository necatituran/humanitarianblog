# WordPress Implementation Guide - Multilingual Setup

## Overview
This guide explains how to implement the Arabic and French translations in WordPress for the Humanitarian Blog.

---

## Prerequisites

### Required Plugins
1. **Polylang** or **WPML** (choose one)
   - Polylang: Free, lightweight, recommended for this project
   - WPML: Premium, more features, better for complex sites

2. **RTL Support Plugin** (for Arabic)
   - Usually built into WordPress
   - May need "RTL Tester" for development

### Installation Steps

#### Option A: Using Polylang (Recommended)

1. **Install Polylang**
   ```
   WordPress Admin → Plugins → Add New
   Search: "Polylang"
   Install and Activate
   ```

2. **Configure Languages**
   ```
   Settings → Languages → Add New Language

   Add Arabic:
   - Language: العربية (Arabic)
   - Language code: ar
   - Locale: ar
   - Text direction: RTL ✓
   - Flag: Saudi Arabia or Arabic League

   Add French:
   - Language: Français (French)
   - Language code: fr
   - Locale: fr_FR
   - Text direction: LTR
   - Flag: France
   ```

3. **Set Default Language**
   ```
   Settings → Languages → Default Language: English
   ```

4. **Enable Language Switcher**
   ```
   Appearance → Menus → Language Switcher
   Or
   Appearance → Widgets → Language Switcher Widget
   ```

---

## Step-by-Step Content Implementation

### Part 1: Creating Translated Pages

#### Example: About Us Page

**Step 1: Create English Page (if not exists)**
```
Pages → Add New
Title: About Us
Content: [Copy from pages-about-us-translations.md - English section]
Publish
```

**Step 2: Create Arabic Version**
```
1. On the About Us page, find the Polylang language box (right sidebar)
2. Click the "+" icon next to Arabic flag
3. New page opens:
   Title: من نحن
   Slug: من-نحن
   Content: [Copy from pages-about-us-translations.md - Arabic section]
4. Publish
```

**Step 3: Create French Version**
```
1. Return to English "About Us" page
2. Click the "+" icon next to French flag
3. New page opens:
   Title: À propos de nous
   Slug: a-propos-de-nous
   Content: [Copy from pages-about-us-translations.md - French section]
4. Publish
```

**Repeat for all 5 pages:**
1. ✅ About Us / من نحن / À propos de nous
2. ✅ Contact / اتصل بنا / Contactez-nous
3. ✅ Write for Us / انشر معنا / Publiez avec nous
4. ✅ Privacy Policy / سياسة الخصوصية / Politique de confidentialité
5. ✅ Terms of Service / شروط الخدمة / Conditions d'utilisation

---

### Part 2: Creating Translated Articles

#### Example: Article #1 - Nutrition Services

**Step 1: Ensure Categories and Tags Exist**
```
Posts → Categories
Create if not exists:
- Technical Guides (English)
- الأدلة التقنية (Arabic) - linked to Technical Guides
- Guides techniques (French) - linked to Technical Guides

Posts → Tags
Create translated versions:
- Nutrition / التغذية / Nutrition
- Emergency / الطوارئ / Urgence
- NGO / منظمة غير حكومية / ONG
```

**Step 2: Create English Post**
```
Posts → Add New
Title: Strengthening Nutrition Services in Emergency Projects (Part 1)
Category: Technical Guides
Tags: Nutrition, Emergency, NGO
Content: [Article body - needs to be obtained from WordPress database]
Language: English (set in Polylang box)
Publish
```

**Step 3: Create Arabic Translation**
```
1. Find the English post in Posts list
2. Click the "+" icon next to Arabic flag in Polylang column
3. New post opens:
   Title: تعزيز خدمات التغذية في مشاريع الطوارئ (الجزء 1)
   Slug: تعزيز-خدمات-التغذية-مشاريع-الطوارئ-جزء-1
   Category: الأدلة التقنية (auto-selected if linked)
   Tags: التغذية، الطوارئ، منظمة غير حكومية
   Content: [Translated article body]
   Featured Image: Same as English version
4. Publish
```

**Step 4: Create French Translation**
```
1. Return to English post
2. Click "+" icon next to French flag
3. New post opens:
   Title: Renforcer les services de nutrition dans les projets d'urgence (Partie 1)
   Slug: renforcer-services-nutrition-projets-urgence-partie-1
   Category: Guides techniques
   Tags: Nutrition, Urgence, ONG
   Content: [Translated article body]
   Featured Image: Same as English version
4. Publish
```

**Repeat for all 26 articles** (see TRANSLATION-SUMMARY.md for full list)

---

## Part 3: Menu Configuration

### Create Multilingual Menus

**Step 1: Create English Menu**
```
Appearance → Menus → Create Menu
Name: Main Menu (English)
Add items:
- Home
- About Us
- Technical Guides (category)
- Aid and Policy (category)
- Environment and Conflict (category)
- Stories from the Field (category)
- Syria (category)
- Contact
- Write for Us

Assign to: Primary Menu
Language: English (Polylang)
Save
```

**Step 2: Create Arabic Menu**
```
Appearance → Menus → Create Menu
Name: القائمة الرئيسية (Arabic)
Add items (Arabic pages):
- الرئيسية
- من نحن
- الأدلة التقنية
- المساعدات والسياسة
- البيئة والصراع
- قصص من الميدان
- سوريا
- اتصل بنا
- انشر معنا

Assign to: Primary Menu
Language: Arabic (Polylang)
Save
```

**Step 3: Create French Menu**
```
Appearance → Menus → Create Menu
Name: Menu Principal (French)
Add items (French pages):
- Accueil
- À propos de nous
- Guides techniques
- Aide et politique
- Environnement et conflit
- Récits du terrain
- Syrie
- Contactez-nous
- Publiez avec nous

Assign to: Primary Menu
Language: French (Polylang)
Save
```

---

## Part 4: RTL Support for Arabic

### Theme Modifications

**Option 1: Automatic (if theme supports)**
Most modern WordPress themes automatically detect RTL and load rtl.css

**Option 2: Manual CSS Addition**
Add to theme's `style.css` or create `rtl.css`:

```css
/* RTL Styles for Arabic */
body.rtl {
    direction: rtl;
    text-align: right;
}

body.rtl .site-header,
body.rtl .site-nav,
body.rtl .site-main,
body.rtl .site-footer {
    direction: rtl;
}

body.rtl .menu {
    text-align: right;
}

body.rtl .menu li {
    float: right;
}

/* Adjust your specific theme elements */
body.rtl .container {
    direction: rtl;
}
```

**Option 3: Use Plugin**
```
Install: "RTL Tester" or "RightToLeft"
This auto-generates RTL styles
```

---

## Part 5: Language Switcher

### Add Language Switcher to Header

**Method 1: Widget**
```
Appearance → Widgets
Add "Language Switcher" widget to Header area
Options:
☑ Show names
☑ Show flags
☐ Force home link
☐ Hide current language
```

**Method 2: Menu Item**
```
Appearance → Menus
Add "Language Switcher" to menu
Customize:
- Dropdown or List
- Show flags: Yes
- Show names: Yes
```

**Method 3: Shortcode** (for custom placement)
```
[polylang_langswitcher]

Or in PHP template:
<?php pll_the_languages(); ?>
```

---

## Part 6: SEO Configuration

### Yoast SEO or Rank Math Configuration

**For Each Translated Page/Post:**
1. Meta Title (translated)
2. Meta Description (translated)
3. Focus Keyword (in target language)
4. URL structure:
   - English: /about-us/
   - Arabic: /ar/من-نحن/ or /ar/about-us/
   - French: /fr/a-propos-de-nous/

**Polylang URL Structure:**
```
Settings → Languages → URL modifications
Choose:
○ The language is set from content
● The language is set from the directory name in URLs
  Example: /ar/page-name/
○ The language is set from the subdomain
```

---

## Part 7: Testing Checklist

### Page Testing
- [ ] All 5 pages exist in English, Arabic, French
- [ ] Language switcher appears on all pages
- [ ] Clicking language switcher goes to correct translation
- [ ] Arabic pages display RTL correctly
- [ ] All internal links work in each language
- [ ] Images display correctly on all language versions

### Article Testing
- [ ] All 26 articles exist in all 3 languages
- [ ] Categories are properly linked across languages
- [ ] Tags are properly linked across languages
- [ ] Featured images show on all versions
- [ ] Archive pages work for each language
- [ ] Search works in each language

### Navigation Testing
- [ ] Main menu shows in correct language
- [ ] Footer menu shows in correct language
- [ ] Breadcrumbs show in correct language
- [ ] Category archives accessible
- [ ] Tag archives accessible

### Technical Testing
- [ ] hreflang tags present in HTML (for SEO)
- [ ] Correct lang attribute in <html> tag
- [ ] Sitemap includes all languages
- [ ] Robots.txt allows all languages
- [ ] 404 page shows in correct language

---

## Part 8: Common Issues & Solutions

### Issue 1: Arabic Text Shows Broken/Reversed
**Solution:**
```
1. Check database charset: utf8mb4
2. Ensure WordPress charset is UTF-8
3. Add to wp-config.php:
   define('DB_CHARSET', 'utf8mb4');
   define('DB_COLLATE', 'utf8mb4_unicode_ci');
```

### Issue 2: Language Switcher Not Appearing
**Solution:**
```
1. Appearance → Widgets → Check Language Switcher placement
2. Polylang → Settings → Check "Show language switcher" is enabled
3. Clear cache (if using caching plugin)
```

### Issue 3: Translations Not Linked
**Solution:**
```
1. Edit the post/page
2. Check Polylang box on right sidebar
3. Manually link translations by clicking + icon
4. Save
```

### Issue 4: RTL Not Working
**Solution:**
```
1. Settings → Languages → Check "Text direction" is set to RTL for Arabic
2. Add <html dir="rtl" lang="ar"> manually if needed
3. Use RTL Tester plugin to debug
```

### Issue 5: Wrong Language Showing
**Solution:**
```
1. Check Polylang → Settings → Default language
2. Check browser language detection settings
3. Clear cookies and cache
4. Test in incognito mode
```

---

## Part 9: Bulk Operations (Optional)

### Using Polylang Import/Export

**Export Translations:**
```
Polylang → Settings → Import/Export
Export strings for translation
Use with .po/.mo files
```

**Import Translations:**
```
Use Poedit or similar tool
Import .po files
Upload to wp-content/languages/
```

---

## Part 10: Maintenance

### Regular Tasks
- [ ] Keep Polylang updated
- [ ] Test language switcher after theme updates
- [ ] Verify RTL styling after CSS changes
- [ ] Check hreflang tags after URL structure changes
- [ ] Update sitemaps when adding new content

### When Adding New Content
1. Create in default language (English)
2. Immediately create translations
3. Link all versions via Polylang
4. Add to appropriate menus
5. Test language switcher

---

## Resources

### Official Documentation
- Polylang: https://polylang.pro/documentation/
- WPML: https://wpml.org/documentation/
- WordPress RTL: https://codex.wordpress.org/Right_to_Left_Language_Support

### Helpful Plugins
- **Polylang**: Multilingual management
- **RTL Tester**: Debug RTL issues
- **Loco Translate**: Translate theme/plugin strings
- **Yoast SEO**: Multilingual SEO
- **WPGlobus**: Alternative to Polylang

---

**Last Updated:** December 22, 2025
**Prepared By:** Claude Code Assistant
**Project:** Humanitarian Blog Translation Implementation
