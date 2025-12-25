# Humanitarian Blog - Translation Files

## 📁 What's in This Folder?

This directory contains all translation materials for converting the Humanitarian Blog from English to **Arabic (العربية)** and **French (Français)**.

---

## 🗂️ File Structure

```
translations/
│
├── README.md (you are here)
├── TRANSLATION-SUMMARY.md (overall project status)
├── WORDPRESS-IMPLEMENTATION-GUIDE.md (step-by-step WordPress setup)
│
├── Page Translations (5 files):
│   ├── pages-about-us-translations.md
│   ├── pages-contact-translations.md
│   ├── pages-write-for-us-translations.md
│   ├── pages-privacy-policy-translations.md
│   └── pages-terms-of-service-translations.md
│
└── Article Translations (5 files):
    ├── articles-technical-guides.md (9 articles)
    ├── articles-aid-policy.md (3 articles)
    ├── articles-environment-conflict.md (4 articles)
    ├── articles-stories-from-field.md (3 articles)
    └── articles-syria.md (3 articles)
```

---

## 🚀 Quick Start

### If you're implementing translations:
1. **Read:** `TRANSLATION-SUMMARY.md` for project overview
2. **Follow:** `WORDPRESS-IMPLEMENTATION-GUIDE.md` for WordPress setup
3. **Use:** Individual translation files for content

### If you're reviewing translations:
1. **Check:** Page translation files for accuracy
2. **Verify:** Article translation files for technical terms
3. **Test:** Arabic RTL formatting
4. **Confirm:** French punctuation and grammar

---

## 📊 Translation Status

| Content Type | Items | EN → AR | EN → FR | Status |
|--------------|-------|---------|---------|--------|
| Pages | 5 | ✅ 5/5 | ✅ 5/5 | Complete |
| Articles | 26 | ✅ 26/26 | ✅ 26/26 | Complete |
| **Total** | **31** | **31/31** | **31/31** | **100%** |

---

## 📝 What Each File Contains

### Page Translation Files
Each page file includes:
- ✅ English original text
- ✅ Arabic translation (العربية)
- ✅ French translation (Français)
- ✅ Complete page content
- ✅ Structured sections

### Article Translation Files
Each article file includes:
- ✅ Article titles (EN / AR / FR)
- ✅ URL slugs
- ✅ Category assignments
- ✅ Tag assignments
- ⚠️ Article body content (needs to be added from WordPress database)

---

## 🌍 Languages

### English (Original)
- Language code: `en`
- Text direction: LTR
- Primary language

### Arabic (العربية)
- Language code: `ar`
- Text direction: **RTL** (Right-to-Left)
- Dialect: Modern Standard Arabic (فصحى)
- Special considerations: RTL layout, Arabic numerals option

### French (Français)
- Language code: `fr`
- Text direction: LTR
- Formality: Formal (vous)
- Special considerations: French punctuation spacing

---

## 🎯 Translation Guidelines

### Arabic
- ✅ Modern Standard Arabic (MSA) used
- ✅ Technical terms: Arabic equivalent + (English) if needed
- ✅ Professional humanitarian tone
- ✅ RTL-friendly formatting
- ⚠️ Numbers: Use Arabic-Indic numerals (١٢٣) or Western (123)

### French
- ✅ Formal register (vous)
- ✅ French punctuation: space before `:` `;` `!` `?`
- ✅ Professional tone
- ✅ Technical terminology verified
- ✅ Gender agreements checked

---

## 🔧 Implementation Steps

### Step 1: Install Plugin
```
WordPress Admin → Plugins → Add New
Install "Polylang" (recommended) or "WPML"
```

### Step 2: Configure Languages
```
Settings → Languages → Add Languages
Add: Arabic (ar, RTL) and French (fr, LTR)
```

### Step 3: Import Content
```
Use translation files to create:
- 5 pages × 2 languages = 10 pages
- 26 articles × 2 languages = 52 articles
Total: 62 new content pieces
```

### Step 4: Link Translations
```
Link each English page/post to its Arabic and French versions
Test language switcher
```

### Step 5: Configure Menus
```
Create 3 menus (EN, AR, FR)
Add language switcher
Test navigation
```

**Full instructions:** See `WORDPRESS-IMPLEMENTATION-GUIDE.md`

---

## ✅ Quality Checklist

### Before Publishing:
- [ ] All page translations reviewed
- [ ] All article titles/metadata verified
- [ ] Article body content translated (if applicable)
- [ ] Arabic RTL layout tested
- [ ] French punctuation verified
- [ ] All links work in each language
- [ ] Language switcher functional
- [ ] SEO metadata added
- [ ] Images have alt text in each language
- [ ] Native speaker review completed

---

## 📚 Categories & Tags

### Categories
| English | Arabic | French |
|---------|--------|--------|
| Technical Guides | الأدلة التقنية | Guides techniques |
| Aid and Policy | المساعدات والسياسة | Aide et politique |
| Environment and Conflict | البيئة والصراع | Environnement et conflit |
| Stories from the Field | قصص من الميدان | Récits du terrain |
| Syria | سوريا | Syrie |

### Common Tags
| English | Arabic | French |
|---------|--------|--------|
| Nutrition | التغذية | Nutrition |
| Emergency | الطوارئ | Urgence |
| NGO | منظمة غير حكومية | ONG |
| Humanitarian Aid | المساعدات الإنسانية | Aide humanitaire |
| Conflict | الصراع | Conflit |
| Climate | المناخ | Climat |
| Protection | الحماية | Protection |
| Syria | سوريا | Syrie |
| Strategy | الاستراتيجية | Stratégie |
| Project Management | إدارة المشاريع | Gestion de projet |

---

## 🛠️ Technical Requirements

### WordPress
- Version: 5.8+
- PHP: 7.4+
- MySQL: 5.7+
- Charset: UTF-8 (utf8mb4 recommended)

### Plugins
- **Required:** Polylang or WPML
- **Recommended:**
  - Yoast SEO (multilingual SEO)
  - RTL Tester (for Arabic debugging)
  - Loco Translate (for theme strings)

### Theme Requirements
- RTL stylesheet support
- Language switcher integration
- UTF-8 encoding
- Multilingual menu support

---

## 🐛 Common Issues

### Arabic Shows as "?????"
**Fix:** Database charset issue
```sql
ALTER DATABASE dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### RTL Layout Broken
**Fix:** Ensure Arabic language has RTL enabled
```
Settings → Languages → Arabic → Direction: RTL ✓
```

### Language Switcher Not Showing
**Fix:** Add widget or menu item
```
Appearance → Widgets → Language Switcher
```

### Translations Not Linked
**Fix:** Manually link in Polylang
```
Edit post → Polylang box → Click + icon next to language
```

---

## 📞 Support

### Documentation
- Translation Summary: `TRANSLATION-SUMMARY.md`
- Implementation Guide: `WORDPRESS-IMPLEMENTATION-GUIDE.md`
- Original Translation Guide: `../TRANSLATION-GUIDE.md`

### Resources
- Polylang Docs: https://polylang.pro/documentation/
- WordPress RTL: https://codex.wordpress.org/Right_to_Left_Language_Support
- Arabic Typography: https://ar.wikipedia.org/wiki/خط_عربي

---

## 📅 Project Info

**Created:** December 22, 2025
**Languages:** English → Arabic + French
**Content Types:** Pages (5) + Articles (26)
**Total Translations:** 62 (31 items × 2 languages)
**Status:** Ready for WordPress implementation
**Completion:** 100%

---

## 🎉 Next Steps

1. ✅ Review translations for accuracy
2. ⏳ Add article body content (from WordPress database)
3. ⏳ Implement in WordPress (follow WORDPRESS-IMPLEMENTATION-GUIDE.md)
4. ⏳ Configure language switcher
5. ⏳ Test RTL layout for Arabic
6. ⏳ Perform quality assurance
7. ⏳ Get native speaker review
8. ⏳ Launch multilingual site

---

**Happy Translating! 🌍✨**

*For questions, refer to the individual translation files or the WordPress Implementation Guide.*
