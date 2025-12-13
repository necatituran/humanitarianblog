# Humanitarian Blog - WordPress Theme

> Premium WordPress theme optimized for humanitarian journalism in conflict zones

[![WordPress](https://img.shields.io/badge/WordPress-6.0+-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-GPL--2.0-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![WPML Ready](https://img.shields.io/badge/WPML-Ready-orange.svg)](https://wpml.org/)

---

## 📖 Overview

**Flavor Starter** is a WordPress theme specifically designed for humanitarian journalism websites operating in challenging environments. It prioritizes accessibility, offline capabilities, and multi-language support for readers in conflict zones and elderly, non-technical writers.

### Key Features

- 🌍 **Multi-language Support** - Arabic (RTL), French, English via WPML
- 📱 **Responsive Design** - Mobile-first approach for all devices
- 📄 **Offline Capabilities** - PDF download, QR codes for article sharing
- 🎙️ **Text-to-Speech** - Browser-based audio playback for articles
- ♿ **Accessibility** - Optimized for elderly writers and limited internet access
- 🎨 **Modern Design** - 2025 design standards with clean typography
- ⚡ **Performance** - Lightweight, fast-loading for slow connections

---

## 🚀 Project Status

| Phase | Status | Description |
|-------|--------|-------------|
| **Phase 1** | ✅ Complete | Theme setup, taxonomies, admin simplification |
| **Phase 2** | 🔄 In Progress | Design system, CSS variables, typography |
| **Phase 3** | ⏳ Pending | Template files (homepage, single, archive) |
| **Phase 4** | ⏳ Pending | Components (cards, modals, forms) |
| **Phase 5** | ⏳ Pending | JavaScript features (search, audio, modals) |
| **Phase 6** | ⏳ Pending | Offline features (PDF, QR codes) |
| **Phase 7** | ⏳ Pending | Admin dashboard & UX enhancements |
| **Phase 8** | ⏳ Pending | Testing & optimization |

**Current Version:** 1.0.0 (Phase 1)
**Last Updated:** 2025-12-14

---

## 📋 Requirements

- **WordPress:** 6.0 or higher
- **PHP:** 7.4 or higher
- **MySQL:** 5.7 or higher
- **Plugins:**
  - WPML (multi-language support) - **REQUIRED**
  - Classic Editor (no Gutenberg) - **REQUIRED**
  - Post Views Counter (trending articles) - Optional
  - Newsletter plugin - Optional

---

## 🛠️ Installation

### 1. Download Theme

```bash
git clone https://github.com/necatituran/humanitarian-blog.git
cd humanitarian-blog
```

### 2. Install Theme

```bash
# Copy theme to WordPress
cp -r wp-content/themes/flavor-starter /path/to/wordpress/wp-content/themes/

# Or upload via WordPress admin
# Go to Appearance → Themes → Add New → Upload Theme
```

### 3. Activate Theme

1. Go to WordPress Admin
2. Navigate to **Appearance → Themes**
3. Find **Flavor Starter - Humanitarian Blog**
4. Click **Activate**

### 4. Install Required Plugins

1. Install **WPML** (multi-language)
2. Install **Classic Editor** (disable Gutenberg)
3. Install optional plugins as needed

### 5. Configure Theme

1. Go to **Posts → Article Types** - Verify 6 types are created
2. Go to **Posts → Regions** - Verify 6 regions are created
3. Go to **Appearance → Menus** - Create Primary, Footer, Social menus
4. Go to **Appearance → Customize** - Set logo, colors, etc.

---

## 📁 Theme Structure

```
wp-content/themes/flavor-starter/
├── style.css                    # Theme header & main stylesheet
├── functions.php                # Theme functions
├── index.php                    # Main template
├── header.php                   # Header template
├── footer.php                   # Footer template
├── wpml-config.xml              # WPML configuration
│
├── /inc/                        # PHP includes
│   ├── custom-taxonomies.php    # Article Types & Regions
│   └── admin-simplify.php       # Admin panel simplification
│
├── /assets/                     # Static assets
│   ├── /css/                    # Stylesheets
│   ├── /js/                     # JavaScript files
│   ├── /fonts/                  # Web fonts
│   └── /images/                 # Theme images
│
├── /template-parts/             # Reusable components
├── /lib/                        # Third-party libraries
├── /languages/                  # Translation files
└── /docs/                       # Documentation
    └── phase1-temel-kurulum.md  # Phase 1 documentation
```

---

## 🎨 Design System

### Color Palette

```css
Primary:   #0D5C63 (Deep Teal)
Accent:    #E8B059 (Warm Gold)
Dark:      #1A1A1A (Almost Black)
Light:     #F9FAFB (Off White)
```

### Typography

- **Headlines:** Source Serif 4 (serif)
- **Body Text:** Inter (sans-serif)
- **Arabic Text:** Amiri (serif), IBM Plex Sans Arabic (sans-serif)

### Responsive Breakpoints

- Mobile: < 640px
- Tablet: ≥ 640px
- Desktop: ≥ 1024px
- Large Desktop: ≥ 1280px

---

## 📚 Custom Taxonomies

### Article Types

- News
- Opinion
- Investigation
- In-Depth Analysis
- Feature
- Breaking

### Regions

- Africa
- Middle East
- Asia
- Europe
- Americas
- Global

---

## 👥 User Roles & Permissions

### Author (Writer)
- ✅ Create draft articles
- ✅ Submit for review
- ❌ Cannot publish directly
- ✅ Simplified admin interface
- ✅ Upload media
- ✅ Edit own posts

### Editor
- ✅ Review submitted articles
- ✅ Publish articles
- ✅ Edit all posts
- ✅ Manage categories/taxonomies
- ✅ Full admin access (except settings)

### Administrator
- ✅ Full site control
- ✅ Manage users
- ✅ Install plugins/themes
- ✅ Configure settings

---

## 🌍 Multi-language Setup

### Supported Languages

1. **English** (default)
2. **Arabic** (RTL support)
3. **French**

### WPML Configuration

The theme includes `wpml-config.xml` which automatically:
- Makes taxonomies translatable
- Sets custom field translation rules
- Configures post type translation

### Translation Workflow

1. Create article in default language (English)
2. Use WPML Translation Management
3. Translate to Arabic/French
4. RTL layout automatically applied for Arabic

---

## 🚧 Development

### Prerequisites

```bash
# Node.js (for future asset compilation)
node -v  # v16+

# Composer (for PHP dependencies)
composer -v
```

### Local Development

```bash
# Using Local by Flywheel (recommended)
# Or any local WordPress environment

# Theme location
cd wp-content/themes/flavor-starter

# Watch for changes (Phase 5+)
npm run watch
```

### Code Standards

- **PHP:** WordPress Coding Standards
- **CSS:** WordPress CSS Coding Standards
- **JavaScript:** ES6+ with WordPress best practices

---

## 📖 Documentation

Full documentation for each phase:

- [Phase 1: Theme Setup](docs/phase1-temel-kurulum.md) ✅
- Phase 2: Design System (coming soon)
- Phase 3: Templates (coming soon)
- Phase 4: Components (coming soon)
- Phase 5: JavaScript (coming soon)
- Phase 6: Offline Features (coming soon)
- Phase 7: Admin Dashboard (coming soon)
- Phase 8: Testing (coming soon)

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📝 License

This theme is licensed under the **GPL v2 or later**.

```
Humanitarian Blog WordPress Theme
Copyright (C) 2025 HumanitarianBlog Team

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
```

---

## 🙏 Credits

### Built With

- [WordPress](https://wordpress.org/) - CMS platform
- [WPML](https://wpml.org/) - Multi-language support
- [mPDF](https://mpdf.github.io/) - PDF generation (Phase 6)
- [phpqrcode](https://sourceforge.net/projects/phpqrcode/) - QR code generation (Phase 6)

### Fonts

- [Source Serif 4](https://fonts.google.com/specimen/Source+Serif+4) by Adobe
- [Inter](https://rsms.me/inter/) by Rasmus Andersson
- [Amiri](https://fonts.google.com/specimen/Amiri) by Khaled Hosny
- [IBM Plex Sans Arabic](https://fonts.google.com/specimen/IBM+Plex+Sans+Arabic) by IBM

### Inspiration

Design inspired by modern humanitarian journalism websites with a focus on readability and accessibility.

---

## 📞 Support

- **Issues:** [GitHub Issues](https://github.com/necatituran/humanitarian-blog/issues)
- **Documentation:** [docs folder](docs/)
- **Email:** [your-email@example.com]

---

## 🗓️ Changelog

### Version 1.0.0 (2025-12-14) - Phase 1

**Added:**
- ✅ Theme structure and core files
- ✅ Custom taxonomies (Article Types, Regions)
- ✅ Admin panel simplification for elderly writers
- ✅ WPML configuration
- ✅ RTL support preparation
- ✅ Basic templates (index, header, footer)
- ✅ Admin styling

**Next:**
- 🔄 Phase 2: Complete design system with CSS variables

---

## 🎯 Roadmap

### Short-term (Q1 2025)
- [ ] Complete Phase 2-4 (Design & Templates)
- [ ] Launch beta version
- [ ] User testing with journalists

### Mid-term (Q2 2025)
- [ ] Complete Phase 5-7 (JavaScript & Admin)
- [ ] PDF/QR offline features
- [ ] Performance optimization

### Long-term (Q3-Q4 2025)
- [ ] Advanced search with filters
- [ ] Custom Gutenberg blocks (optional)
- [ ] AMP support
- [ ] PWA capabilities

---

**Made with ❤️ for humanitarian journalists worldwide**

**Project Repository:** https://github.com/necatituran/humanitarian-blog
**Developer:** Necati Turan
**Version:** 1.0.0 (Phase 1)
**Last Updated:** December 14, 2025
