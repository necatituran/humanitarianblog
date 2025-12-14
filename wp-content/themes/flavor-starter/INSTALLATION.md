# HumanitarianBlog Theme - Installation Guide

## 📦 Requirements

- **WordPress:** 6.0 or higher
- **PHP:** 7.4 or higher
- **Composer:** Required for PDF generation feature
- **MySQL:** 5.7 or higher

---

## 🚀 Quick Installation

### Step 1: Upload Theme
```bash
# Upload to WordPress themes directory
wp-content/themes/flavor-starter/
```

### Step 2: Install Dependencies (REQUIRED for PDF Generator)
```bash
cd wp-content/themes/flavor-starter
composer install
```

This will install:
- `mpdf/mpdf` (^8.2) - PDF generation library

### Step 3: Activate Theme
1. Go to WordPress Admin → Appearance → Themes
2. Find "HumanitarianBlog" theme
3. Click "Activate"

### Step 4: Create Bookmarks Page (Optional)
1. Go to WordPress Admin → Pages → Add New
2. Title: "My Bookmarks" (or any name)
3. Template: Select **"Bookmarks Page"**
4. Publish

---

## 🔧 Post-Installation Setup

### 1. Verify PDF Generator
Check if mPDF is installed:
```bash
ls vendor/mpdf/mpdf
```

If not installed:
```bash
composer require mpdf/mpdf
```

### 2. Configure WPML (Optional - Multi-language)
1. Install WPML plugin
2. Theme is already configured for WPML
3. Supported languages: English, Turkish, Arabic (RTL ready)

### 3. Custom Taxonomies
The theme auto-creates:

**Article Types:**
- News
- Opinion
- Analysis
- Report
- Interview
- Feature

**Regions:**
- Middle East
- Africa
- Asia
- Europe
- Americas
- Global

---

## 📁 File Structure

```
flavor-starter/
├── assets/
│   ├── css/
│   │   ├── style.css      (Main styles)
│   │   ├── rtl.css        (RTL support)
│   │   └── print.css      (Print styles)
│   └── js/
│       ├── main.js        (Core features)
│       ├── search.js      (Live search)
│       ├── modals.js      (QR & PDF modals)
│       ├── reading-experience.js
│       ├── audio-player.js
│       └── bookmarks-page.js
├── inc/
│   ├── custom-taxonomies.php
│   ├── admin-simplify.php
│   ├── ajax-handlers.php
│   ├── qr-generator.php   (QR code generation)
│   └── pdf-generator.php  (PDF generation)
├── template-parts/
│   └── [Various template components]
├── vendor/                (Composer dependencies - DO NOT commit)
├── composer.json
├── functions.php
├── style.css
└── page-bookmarks.php
```

---

## ✅ Feature Checklist

After installation, verify these features work:

### Core Features
- [ ] Theme activated successfully
- [ ] Custom taxonomies (Article Types & Regions) created
- [ ] Menu locations available (Primary, Footer, Social)
- [ ] Widget areas available (Sidebar + 4 Footer areas)

### JavaScript Features
- [ ] Mobile menu toggle works
- [ ] Live search works (3+ characters)
- [ ] Back to top button appears on scroll
- [ ] Smooth scroll navigation works

### Offline Features (Phase 6)
- [ ] QR Code modal opens
- [ ] QR code generates successfully
- [ ] PDF modal opens with 3 format options
- [ ] PDF generates and downloads (requires Composer!)
- [ ] Bookmarks page displays saved articles

### Admin Features
- [ ] Author role has simplified admin panel
- [ ] Editor/Admin roles have full access
- [ ] Custom image sizes registered
- [ ] WPML configuration present

---

## 🔒 Security Features

All AJAX endpoints have:
- ✅ Nonce verification
- ✅ Rate limiting
- ✅ Input validation & sanitization
- ✅ XSS prevention

**Rate Limits:**
- Live Search: 10 requests/min per IP
- Newsletter: 3 signups/hour per IP
- QR Code: 20 requests/min per IP
- PDF Generation: 5 requests/hour per IP
- Bookmarks: 30 requests/min per IP

---

## ⚡ Performance Optimizations

- **Caching:**
  - Search results: 5 minutes
  - QR codes: 24 hours
  - PDFs: 24 hours

- **Query Optimization:**
  - `no_found_rows` for faster queries
  - Selective cache updates
  - Throttled scroll events (requestAnimationFrame)

- **Auto Cleanup:**
  - PDFs older than 7 days deleted automatically (daily cron)
  - Invalid bookmarks removed on save (10% chance)

---

## 🐛 Troubleshooting

### PDF Generator Not Working
**Error:** "PDF library not installed"

**Solution:**
```bash
cd wp-content/themes/flavor-starter
composer install
# or
composer require mpdf/mpdf
```

### QR Code Not Generating
**Error:** "Failed to generate QR code"

**Possible causes:**
1. phpqrcode library path changed in WordPress core
2. GD library not installed on server

**Solution:**
Check PHP GD extension:
```bash
php -m | grep -i gd
```

If missing, install GD:
```bash
# Ubuntu/Debian
sudo apt-get install php-gd

# Restart web server
sudo service apache2 restart
```

### Bookmarks Page Not Loading
**Error:** Empty page or "No bookmarks" always shows

**Solution:**
1. Verify template is selected: **"Bookmarks Page"**
2. Check browser localStorage is enabled
3. Check browser console for JavaScript errors

### Rate Limiting Blocks Legitimate Users
**Symptom:** "Too many requests" error

**Solution:**
Clear transients (temporary, will auto-clear anyway):
```php
// In WordPress admin → Tools → WP-CLI or via functions.php temporarily
delete_transient('search_rate_' . md5($_SERVER['REMOTE_ADDR']));
delete_transient('pdf_rate_' . md5($_SERVER['REMOTE_ADDR']));
delete_transient('qr_rate_' . md5($_SERVER['REMOTE_ADDR']));
```

Or wait:
- Search: 1 minute
- Newsletter: 1 hour
- QR: 1 minute
- PDF: 1 hour
- Bookmarks: 1 minute

---

## 📚 Documentation

- **Technical Notes:** `docs/TECHNICAL-NOTES.md`
- **Phase 5 (JavaScript):** `docs/phase5-javascript.md`
- **Phase 6 (Offline):** `docs/phase6-offline.md`
- **README:** `README.md`

---

## 🔮 Future Features (Not Yet Implemented)

These features are documented but not yet implemented:

- Service Worker (PWA offline cache)
- Print Optimization (enhanced @media print)
- Critical CSS extraction
- WebP image support
- CDN integration

---

## 📞 Support

For issues or questions:
1. Check documentation in `docs/` folder
2. Review `TECHNICAL-NOTES.md` for troubleshooting
3. Check GitHub issues (if repository is public)

---

**Last Updated:** 2025-12-14
**Theme Version:** 1.0.0
**Minimum WordPress Version:** 6.0
**Minimum PHP Version:** 7.4
