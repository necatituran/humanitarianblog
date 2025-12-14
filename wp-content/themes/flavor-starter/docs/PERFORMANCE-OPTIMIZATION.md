# Performance Optimization Guide

## Overview

This document outlines performance optimization strategies, benchmarks, and monitoring for the HumanitarianBlog theme.

---

## Performance Targets

### Core Web Vitals (Google)

| Metric | Good | Needs Improvement | Poor | Target |
|--------|------|-------------------|------|--------|
| **LCP** (Largest Contentful Paint) | < 2.5s | 2.5s - 4.0s | > 4.0s | < 2.0s |
| **FID** (First Input Delay) | < 100ms | 100ms - 300ms | > 300ms | < 50ms |
| **CLS** (Cumulative Layout Shift) | < 0.1 | 0.1 - 0.25 | > 0.25 | < 0.05 |

### Additional Metrics

| Metric | Target |
|--------|--------|
| **FCP** (First Contentful Paint) | < 1.8s |
| **TTI** (Time to Interactive) | < 3.8s |
| **TBT** (Total Blocking Time) | < 200ms |
| **Speed Index** | < 3.4s |

---

## Current Performance Status

### Phase 6 Optimizations Implemented

✅ **Caching:**
- Search results: 5 minutes (transient)
- QR codes: 24 hours (transient)
- PDFs: 24 hours (transient)

✅ **Query Optimization:**
- `no_found_rows => true` for faster queries
- Selective cache updates
- Minimal post fields in search

✅ **JavaScript Performance:**
- Throttled scroll events with `requestAnimationFrame`
- Debounced search input (300ms)
- Event delegation for dynamic elements

✅ **Rate Limiting:**
- Prevents server overload
- Controls resource consumption

---

## CSS Optimization

### 1. Critical CSS

**Problem:** Large CSS file blocks rendering

**Solution:** Extract critical CSS for above-the-fold content

```php
// Add to functions.php
function humanitarian_critical_css() {
    ?>
    <style>
    /* Critical CSS: Header, hero, above-fold content */
    body { margin: 0; font-family: var(--font-primary); }
    .site-header { background: white; padding: 1rem; }
    .hero { min-height: 400px; }
    /* ... more critical styles ... */
    </style>
    <?php
}
add_action('wp_head', 'humanitarian_critical_css', 1);
```

**Load full CSS asynchronously:**
```php
function humanitarian_async_css() {
    wp_enqueue_style('theme-styles', get_stylesheet_uri(), [], '1.0.0', 'all');

    // Make non-critical
    add_filter('style_loader_tag', function($html, $handle) {
        if ('theme-styles' === $handle) {
            $html = str_replace("rel='stylesheet'", "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $html);
        }
        return $html;
    }, 10, 2);
}
```

### 2. Minification

**Current:** Unminified CSS (1313 lines)

**Action:** Minify for production

```bash
# Using cssnano
npm install cssnano postcss-cli

npx postcss assets/css/style.css --use cssnano -o assets/css/style.min.css
```

**Enqueue minified version:**
```php
// functions.php
$css_file = WP_DEBUG ? 'style.css' : 'style.min.css';
wp_enqueue_style('theme-styles', get_theme_file_uri("assets/css/{$css_file}"));
```

**Expected Savings:** 30-40% file size reduction

### 3. Remove Unused CSS

**Tools:**
- PurgeCSS
- UnCSS
- Chrome DevTools Coverage

**Implementation:**
```bash
# Install PurgeCSS
npm install --save-dev @fullhuman/postcss-purgecss

# Add to postcss.config.js
module.exports = {
    plugins: [
        require('@fullhuman/postcss-purgecss')({
            content: ['./**/*.php'],
            safelist: ['is-open', 'is-active', 'menu-open']
        })
    ]
}
```

### 4. CSS File Structure

**Current:** Single large file (52KB unminified)

**Optimization Options:**

**Option A: Code Splitting**
```php
// Conditional loading
if (is_page_template('page-bookmarks.php')) {
    wp_enqueue_style('bookmarks-css', get_theme_file_uri('assets/css/bookmarks.css'));
}

if (is_single()) {
    wp_enqueue_style('reading-css', get_theme_file_uri('assets/css/reading.css'));
}
```

**Option B: Keep Single File**
- Easier maintenance
- Better caching (one file)
- HTTP/2 makes multiple files less costly

**Recommendation:** Keep single file, but minify and gzip

---

## JavaScript Optimization

### 1. Code Splitting

**Current:** 6 separate JS files (good!)

**Further Optimization:**
```php
// Conditional loading
function humanitarian_conditional_scripts() {
    // Main JS: All pages
    wp_enqueue_script('humanitarian-main', get_theme_file_uri('assets/js/main.js'), [], '1.0.0', true);

    // Search: Only if search box present
    if (is_active_sidebar('header-search')) {
        wp_enqueue_script('humanitarian-search', get_theme_file_uri('assets/js/search.js'), [], '1.0.0', true);
    }

    // Modals: Only on single posts
    if (is_single()) {
        wp_enqueue_script('humanitarian-modals', get_theme_file_uri('assets/js/modals.js'), [], '1.0.0', true);
    }

    // Reading: Only on single posts
    if (is_single()) {
        wp_enqueue_script('humanitarian-reading', get_theme_file_uri('assets/js/reading-experience.js'), [], '1.0.0', true);
        wp_enqueue_script('humanitarian-audio', get_theme_file_uri('assets/js/audio-player.js'), [], '1.0.0', true);
    }

    // Bookmarks page: Only on bookmarks template
    if (is_page_template('page-bookmarks.php')) {
        wp_enqueue_script('humanitarian-bookmarks-page', get_theme_file_uri('assets/js/bookmarks-page.js'), [], '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'humanitarian_conditional_scripts');
```

### 2. Minification

**Tools:**
- Terser (recommended)
- UglifyJS
- Google Closure Compiler

**Implementation:**
```bash
# Install Terser
npm install terser -g

# Minify all JS files
terser assets/js/main.js -o assets/js/main.min.js -c -m
terser assets/js/search.js -o assets/js/search.min.js -c -m
terser assets/js/modals.js -o assets/js/modals.min.js -c -m
# ... repeat for all files
```

**Automated Build:**
```json
// package.json
{
    "scripts": {
        "build:js": "terser assets/js/*.js -o assets/js/dist/ -c -m",
        "build:css": "postcss assets/css/style.css -u cssnano -o assets/css/style.min.css",
        "build": "npm run build:js && npm run build:css"
    }
}
```

### 3. Defer/Async Loading

**Current:** Scripts loaded in footer (good!)

**Enhancement:**
```php
// Defer non-critical scripts
add_filter('script_loader_tag', function($tag, $handle) {
    $defer_scripts = ['humanitarian-search', 'humanitarian-modals'];

    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }

    return $tag;
}, 10, 2);
```

### 4. Remove Unused JavaScript

**Check with Chrome DevTools:**
1. Open DevTools → Coverage tab
2. Reload page
3. Check unused bytes

**Common Culprits:**
- Unused event listeners
- Dead code paths
- Unused polyfills

---

## Image Optimization

### 1. Responsive Images

**Current:** WordPress default srcset (good!)

**Enhancement:** Art direction
```php
// Add to functions.php
add_theme_support('post-thumbnails');

// Custom image sizes
add_image_size('hero-large', 1920, 1080, true);
add_image_size('card-medium', 600, 400, true);
add_image_size('card-small', 400, 300, true);
add_image_size('thumbnail-tiny', 150, 150, true);

// Use in templates
the_post_thumbnail('hero-large', [
    'loading' => 'lazy',
    'sizes' => '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw',
]);
```

### 2. Lazy Loading

**Native Lazy Loading:**
```php
// Already implemented in WordPress 5.5+
<img src="image.jpg" loading="lazy" alt="Description">
```

**Enhanced with IntersectionObserver:**
```javascript
// Add to main.js for older browsers
if ('IntersectionObserver' in window) {
    const images = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
}
```

### 3. Image Format Optimization

**Current:** JPG/PNG

**WebP Support:**
```php
// Add WebP support
add_filter('wp_handle_upload_prefilter', function($file) {
    // Convert to WebP on upload (requires GD/Imagick with WebP)
    // Or use plugin: WebP Express, EWWW Image Optimizer
    return $file;
});

// Output WebP with fallback
function humanitarian_responsive_image($attachment_id, $size = 'full') {
    $image_src = wp_get_attachment_image_src($attachment_id, $size);
    $image_webp = wp_get_attachment_image_src($attachment_id, $size);

    ?>
    <picture>
        <source type="image/webp" srcset="<?php echo esc_url($image_webp[0]); ?>">
        <img src="<?php echo esc_url($image_src[0]); ?>" alt="" loading="lazy">
    </picture>
    <?php
}
```

**Expected Savings:** 25-35% file size vs JPG

### 4. Image Compression

**Tools:**
- **ImageOptim** (macOS)
- **TinyPNG** (online)
- **EWWW Image Optimizer** (WordPress plugin)
- **ShortPixel** (WordPress plugin)

**Recommended Settings:**
- JPG: 80-85% quality
- PNG: Use TinyPNG
- WebP: 75-80% quality

---

## Database Optimization

### 1. Query Optimization

**Current Status:** ✅ Already optimized in Phase 6

**Further Improvements:**
```php
// Index custom meta keys
global $wpdb;
$wpdb->query("CREATE INDEX idx_reading_time ON {$wpdb->postmeta}(meta_key, meta_value) WHERE meta_key = 'reading_time'");
```

### 2. Object Caching

**Install Redis or Memcached:**
```php
// wp-config.php
define('WP_CACHE', true);

// Install plugin: Redis Object Cache or W3 Total Cache
```

**Expected Improvement:** 30-50% faster page loads

### 3. Database Cleanup

**Schedule Cleanup:**
```php
// Add to functions.php
function humanitarian_db_cleanup() {
    global $wpdb;

    // Delete old transients
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' AND option_value < UNIX_TIMESTAMP()");

    // Delete post revisions older than 30 days
    $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type = 'revision' AND post_modified < DATE_SUB(NOW(), INTERVAL 30 DAY)");
}

// Run weekly
if (!wp_next_scheduled('humanitarian_db_cleanup')) {
    wp_schedule_event(time(), 'weekly', 'humanitarian_db_cleanup');
}
add_action('humanitarian_db_cleanup', 'humanitarian_db_cleanup');
```

---

## Server-Side Optimization

### 1. Page Caching

**Recommended Plugins:**
- **WP Rocket** (premium, best performance)
- **W3 Total Cache** (free, complex)
- **WP Super Cache** (free, simple)

**Configuration:**
```php
// wp-config.php
define('WP_CACHE', true);

// Exclude dynamic pages
// WP Rocket: Settings → Advanced → Never cache pages with these strings
// /bookmarks/ (dynamic user content)
```

### 2. GZIP Compression

**Apache (.htaccess):**
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>
```

**Nginx:**
```nginx
gzip on;
gzip_types text/plain text/css text/xml text/javascript application/javascript application/json;
gzip_min_length 1000;
```

**Expected Savings:** 70-80% file size reduction

### 3. Browser Caching

**Apache (.htaccess):**
```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
</IfModule>
```

### 4. CDN Integration

**Recommended CDNs:**
- **Cloudflare** (free tier available)
- **StackPath** (premium)
- **BunnyCDN** (affordable)

**Benefits:**
- Faster global load times
- Reduced server load
- DDoS protection
- Free SSL

---

## WordPress-Specific Optimizations

### 1. Disable Unnecessary Features

```php
// functions.php
// Disable emoji scripts
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Disable embeds
remove_action('wp_head', 'wp_oembed_add_discovery_links');

// Disable XML-RPC (if not needed)
add_filter('xmlrpc_enabled', '__return_false');

// Limit post revisions
define('WP_POST_REVISIONS', 5);

// Increase autosave interval
define('AUTOSAVE_INTERVAL', 300); // 5 minutes
```

### 2. Optimize WordPress Query

```php
// Limit posts per page
add_action('pre_get_posts', function($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_home()) {
        $query->set('posts_per_page', 10); // Lower = faster
    }
});
```

### 3. Heartbeat API Control

```php
// Reduce Heartbeat frequency
add_filter('heartbeat_settings', function($settings) {
    $settings['interval'] = 60; // Default is 15 seconds
    return $settings;
});

// Disable on front-end
add_action('init', function() {
    if (!is_admin()) {
        wp_deregister_script('heartbeat');
    }
});
```

---

## Monitoring & Testing

### 1. Performance Monitoring Tools

**Free Tools:**
- **Google Lighthouse** (Chrome DevTools)
- **PageSpeed Insights** (Google)
- **GTmetrix**
- **WebPageTest**
- **Pingdom**

**Premium Tools:**
- **New Relic** (APM)
- **Datadog** (monitoring)
- **Scout APM** (WordPress-specific)

### 2. Real User Monitoring (RUM)

**Google Analytics 4:**
```javascript
// Already tracked automatically:
// - Page load time
// - First contentful paint
// - Largest contentful paint
```

**Custom Events:**
```javascript
// Track custom performance metrics
window.addEventListener('load', () => {
    const loadTime = performance.now();

    // Send to Google Analytics
    gtag('event', 'timing_complete', {
        'name': 'load',
        'value': Math.round(loadTime),
        'event_category': 'Performance'
    });
});
```

### 3. Performance Budget

**Set Limits:**
| Resource | Budget |
|----------|--------|
| Total page size | < 1.5MB |
| CSS | < 50KB (minified + gzipped) |
| JavaScript | < 100KB (minified + gzipped) |
| Images | < 500KB per page |
| Requests | < 50 |

**Enforce with Lighthouse CI:**
```json
// lighthouserc.json
{
    "ci": {
        "assert": {
            "assertions": {
                "first-contentful-paint": ["error", {"maxNumericValue": 1800}],
                "largest-contentful-paint": ["error", {"maxNumericValue": 2500}],
                "cumulative-layout-shift": ["error", {"maxNumericValue": 0.1}],
                "total-byte-weight": ["error", {"maxNumericValue": 1500000}]
            }
        }
    }
}
```

---

## Optimization Checklist

### Phase 7 Tasks

**CSS:**
- [ ] Minify CSS for production
- [ ] Extract critical CSS
- [ ] Remove unused CSS (PurgeCSS)
- [ ] Enable GZIP compression
- [ ] Set cache headers

**JavaScript:**
- [ ] Minify all JS files
- [ ] Implement conditional loading
- [ ] Defer non-critical scripts
- [ ] Remove unused code

**Images:**
- [ ] Implement WebP format
- [ ] Compress all images (TinyPNG)
- [ ] Verify lazy loading works
- [ ] Set proper image sizes

**Server:**
- [ ] Install caching plugin (WP Rocket)
- [ ] Configure object cache (Redis)
- [ ] Enable GZIP compression
- [ ] Set browser cache headers
- [ ] Configure CDN (Cloudflare)

**Database:**
- [ ] Optimize database tables
- [ ] Clean up transients
- [ ] Index custom meta keys
- [ ] Limit post revisions

**WordPress:**
- [ ] Disable emojis
- [ ] Disable embeds
- [ ] Control Heartbeat API
- [ ] Limit posts per page

**Testing:**
- [ ] Run Lighthouse audit (score > 90)
- [ ] Test on 3G connection
- [ ] Check Core Web Vitals
- [ ] Monitor real user metrics

---

## Expected Results

### Before Optimization (Estimated)
- **Page Size:** 2.5MB
- **Requests:** 65
- **Load Time:** 4.2s (3G)
- **Lighthouse Score:** 75

### After Optimization (Target)
- **Page Size:** 1.2MB (-52%)
- **Requests:** 35 (-46%)
- **Load Time:** 2.1s (3G) (-50%)
- **Lighthouse Score:** 95 (+27%)

---

**Last Updated:** 2025-12-14
**Phase:** 7 - Production Ready & Polish
**Status:** Documentation Complete, Implementation Pending
