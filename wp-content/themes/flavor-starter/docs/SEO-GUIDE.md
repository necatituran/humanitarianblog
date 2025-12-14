# SEO Optimization Guide

## Overview

This document outlines SEO best practices, implementation strategies, and optimization techniques for the HumanitarianBlog theme.

---

## SEO Targets

### Search Engine Visibility
- ✅ Crawlable by Google, Bing, DuckDuckGo
- ✅ Mobile-first indexing ready
- ✅ Schema.org markup for rich snippets
- ✅ Social media sharing optimization

### Page Speed (SEO Ranking Factor)
- **Target:** Lighthouse SEO score > 95
- **Target:** Core Web Vitals passing
- **Target:** Mobile-friendly test passing

---

## Technical SEO

### 1. Meta Tags

**Required Tags (Add to header.php):**

```php
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if (is_single() || is_page()) : ?>
        <meta name="description" content="<?php echo esc_attr(wp_trim_words(get_the_excerpt(), 30)); ?>">
        <meta name="author" content="<?php echo esc_attr(get_the_author()); ?>">

        <!-- Article metadata -->
        <?php if (is_single()) : ?>
            <meta property="article:published_time" content="<?php echo esc_attr(get_the_date('c')); ?>">
            <meta property="article:modified_time" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
            <meta property="article:author" content="<?php echo esc_attr(get_the_author()); ?>">

            <?php
            $categories = get_the_category();
            if (!empty($categories)) :
                foreach ($categories as $category) :
            ?>
                <meta property="article:section" content="<?php echo esc_attr($category->name); ?>">
            <?php
                endforeach;
            endif;
            ?>
        <?php endif; ?>

    <?php else : ?>
        <meta name="description" content="<?php bloginfo('description'); ?>">
    <?php endif; ?>

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo esc_url(get_permalink()); ?>">

    <?php wp_head(); ?>
</head>
```

### 2. Open Graph (Social Media)

**Add to functions.php:**

```php
/**
 * Add Open Graph meta tags
 */
function humanitarian_open_graph() {
    if (is_single() || is_page()) {
        global $post;
        ?>
        <!-- Open Graph -->
        <meta property="og:type" content="<?php echo is_single() ? 'article' : 'website'; ?>">
        <meta property="og:title" content="<?php echo esc_attr(get_the_title()); ?>">
        <meta property="og:description" content="<?php echo esc_attr(wp_trim_words(get_the_excerpt(), 30)); ?>">
        <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
        <meta property="og:site_name" content="<?php bloginfo('name'); ?>">
        <meta property="og:locale" content="<?php echo esc_attr(get_locale()); ?>">

        <?php if (has_post_thumbnail()) : ?>
            <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url($post, 'large')); ?>">
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="630">
        <?php endif; ?>

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo esc_attr(get_the_title()); ?>">
        <meta name="twitter:description" content="<?php echo esc_attr(wp_trim_words(get_the_excerpt(), 30)); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <meta name="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url($post, 'large')); ?>">
        <?php endif; ?>
        <?php
    } else {
        ?>
        <!-- Open Graph - Homepage/Archive -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="<?php bloginfo('name'); ?>">
        <meta property="og:description" content="<?php bloginfo('description'); ?>">
        <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>">
        <meta property="og:site_name" content="<?php bloginfo('name'); ?>">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="<?php bloginfo('name'); ?>">
        <meta name="twitter:description" content="<?php bloginfo('description'); ?>">
        <?php
    }
}
add_action('wp_head', 'humanitarian_open_graph', 5);
```

### 3. Schema.org Structured Data

**Article Schema (Add to single.php):**

```php
<?php
/**
 * Output Article schema
 */
function humanitarian_article_schema() {
    if (!is_single()) return;

    global $post;

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => get_the_title(),
        'description' => wp_trim_words(get_the_excerpt(), 30),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => [
            '@type' => 'Person',
            'name' => get_the_author(),
            'url' => get_author_posts_url(get_the_author_meta('ID')),
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => get_theme_file_uri('assets/images/logo.png'),
            ],
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => get_permalink(),
        ],
    ];

    // Add image if available
    if (has_post_thumbnail()) {
        $schema['image'] = [
            '@type' => 'ImageObject',
            'url' => get_the_post_thumbnail_url($post, 'full'),
            'width' => 1200,
            'height' => 630,
        ];
    }

    // Add article section (category)
    $categories = get_the_category();
    if (!empty($categories)) {
        $schema['articleSection'] = $categories[0]->name;
    }

    // Add word count
    $schema['wordCount'] = str_word_count(strip_tags($post->post_content));

    // Add reading time (if custom field exists)
    $reading_time = get_post_meta($post->ID, 'reading_time', true);
    if ($reading_time) {
        $schema['timeRequired'] = 'PT' . $reading_time . 'M';
    }

    ?>
    <script type="application/ld+json">
    <?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
    </script>
    <?php
}
add_action('wp_footer', 'humanitarian_article_schema');
```

**Breadcrumb Schema:**

```php
function humanitarian_breadcrumb_schema() {
    if (is_front_page()) return;

    $items = [];
    $position = 1;

    // Home
    $items[] = [
        '@type' => 'ListItem',
        'position' => $position++,
        'name' => 'Home',
        'item' => home_url('/'),
    ];

    // Category (if applicable)
    if (is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $categories[0]->name,
                'item' => get_category_link($categories[0]->term_id),
            ];
        }
    }

    // Current page
    $items[] = [
        '@type' => 'ListItem',
        'position' => $position,
        'name' => get_the_title(),
        'item' => get_permalink(),
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $items,
    ];

    ?>
    <script type="application/ld+json">
    <?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
    </script>
    <?php
}
add_action('wp_footer', 'humanitarian_breadcrumb_schema');
```

### 4. Sitemap

**WordPress Core Sitemap (WordPress 5.5+):**

WordPress generates sitemaps automatically at: `https://yourdomain.com/wp-sitemap.xml`

**Customize Sitemap:**

```php
// functions.php

// Add custom post types to sitemap
add_filter('wp_sitemaps_add_provider', function($provider, $name) {
    if ('posts' === $name) {
        $provider->object_subtypes = ['post', 'page'];
    }
    return $provider;
}, 10, 2);

// Exclude pages from sitemap
add_filter('wp_sitemaps_posts_query_args', function($args, $post_type) {
    if ('page' === $post_type) {
        $args['post__not_in'] = [123, 456]; // Exclude page IDs
    }
    return $args;
}, 10, 2);
```

**Advanced Sitemap (Yoast SEO or Rank Math):**

For more control, install Yoast SEO or Rank Math plugin.

### 5. Robots.txt

**Create robots.txt (root directory):**

```
User-agent: *
Allow: /
Disallow: /wp-admin/
Disallow: /wp-includes/
Disallow: /wp-content/plugins/
Disallow: /wp-content/cache/
Disallow: /wp-content/themes/flavor-starter/assets/
Disallow: /?s=
Disallow: /search/
Disallow: /author/
Disallow: /cart/
Disallow: /checkout/

# Allow CSS and JS for Google
User-agent: Googlebot
Allow: /wp-content/themes/flavor-starter/assets/css/
Allow: /wp-content/themes/flavor-starter/assets/js/

# Sitemap
Sitemap: https://humanitarian-blog.com/wp-sitemap.xml
```

**Dynamic robots.txt (functions.php):**

```php
add_filter('robots_txt', function($output, $public) {
    if ('0' === $public) {
        // If site is private, block all
        return "User-agent: *\nDisallow: /\n";
    }

    $output .= "User-agent: *\n";
    $output .= "Allow: /\n";
    $output .= "Disallow: /wp-admin/\n";
    $output .= "Disallow: /wp-includes/\n";
    $output .= "Disallow: /search/\n\n";
    $output .= "Sitemap: " . home_url('/wp-sitemap.xml') . "\n";

    return $output;
}, 10, 2);
```

---

## Content SEO

### 1. Title Tags

**Optimal Format:**
- Homepage: `Site Name | Tagline`
- Posts: `Post Title | Site Name`
- Categories: `Category Name | Site Name`

**Implementation (functions.php):**

```php
add_filter('wp_title', function($title, $sep) {
    if (is_feed()) return $title;

    $title .= get_bloginfo('name');

    if (is_home() || is_front_page()) {
        $title .= ' ' . $sep . ' ' . get_bloginfo('description');
    }

    return $title;
}, 10, 2);
```

**Best Practices:**
- Keep under 60 characters
- Include primary keyword
- Make it compelling (click-worthy)

### 2. Meta Descriptions

**Optimal Length:** 150-160 characters

**Implementation:**

```php
function humanitarian_meta_description() {
    if (is_single() || is_page()) {
        $excerpt = wp_trim_words(get_the_excerpt(), 30, '...');
        echo '<meta name="description" content="' . esc_attr($excerpt) . '">';
    } elseif (is_category()) {
        $description = category_description();
        if ($description) {
            echo '<meta name="description" content="' . esc_attr(strip_tags($description)) . '">';
        }
    } else {
        echo '<meta name="description" content="' . esc_attr(get_bloginfo('description')) . '">';
    }
}
add_action('wp_head', 'humanitarian_meta_description', 5);
```

### 3. Heading Structure

**Hierarchy:**
```html
<h1>Article Title</h1> <!-- Only ONE h1 per page -->
  <h2>Main Section</h2>
    <h3>Subsection</h3>
    <h3>Subsection</h3>
  <h2>Main Section</h2>
    <h3>Subsection</h3>
```

**SEO Tips:**
- Only one `<h1>` per page (post title)
- Use `<h2>` for main sections
- Include keywords naturally
- Don't skip levels (h2 → h4)

### 4. Alt Text for Images

**Implementation:**

```php
// When adding images in content
<img src="photo.jpg" alt="Humanitarian aid workers distributing food in Syria">

// In templates
<?php
if (has_post_thumbnail()) {
    the_post_thumbnail('large', [
        'alt' => get_the_title(),
        'loading' => 'lazy',
    ]);
}
?>
```

**Best Practices:**
- Describe what's in the image
- Include keywords naturally
- Keep under 125 characters
- Don't stuff keywords

### 5. Internal Linking

**Automatic Related Posts:**

```php
function humanitarian_related_posts() {
    if (!is_single()) return;

    $categories = get_the_category();
    if (empty($categories)) return;

    $args = [
        'category__in' => [$categories[0]->term_id],
        'post__not_in' => [get_the_ID()],
        'posts_per_page' => 3,
        'orderby' => 'rand',
    ];

    $related = new WP_Query($args);

    if ($related->have_posts()) :
        ?>
        <section class="related-posts">
            <h2>Related Articles</h2>
            <div class="related-grid">
                <?php while ($related->have_posts()) : $related->the_post(); ?>
                    <article class="related-card">
                        <a href="<?php the_permalink(); ?>" rel="bookmark">
                            <?php the_post_thumbnail('card-medium'); ?>
                            <h3><?php the_title(); ?></h3>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>
        <?php
        wp_reset_postdata();
    endif;
}
```

---

## URL Structure

### 1. Permalink Settings

**Recommended Structure:**
```
Settings → Permalinks → Custom Structure
/%category%/%postname%/
```

**Examples:**
- ✅ `https://site.com/news/humanitarian-crisis-syria/`
- ❌ `https://site.com/?p=123`

### 2. Clean URLs

**Best Practices:**
- Use hyphens, not underscores
- Keep URLs short (< 75 characters)
- Include target keyword
- Use lowercase only
- Avoid stop words (a, the, and, etc.)

**Example:**
```
Good: /middle-east/aid-workers-syria/
Bad:  /2025/01/15/humanitarian-aid-workers-deliver-supplies-to-syria/
```

---

## Page Speed (SEO Ranking Factor)

**See:** [PERFORMANCE-OPTIMIZATION.md](PERFORMANCE-OPTIMIZATION.md)

**Quick Wins:**
- ✅ Image optimization (WebP, lazy loading)
- ✅ Minify CSS/JS
- ✅ Enable caching
- ✅ Use CDN
- ✅ Reduce server response time

---

## Mobile-First SEO

### 1. Mobile-Friendly Test

**Google Tool:** https://search.google.com/test/mobile-friendly

**Requirements:**
- ✅ Responsive design
- ✅ Touch targets ≥ 48px
- ✅ Readable font sizes (16px+)
- ✅ No horizontal scrolling
- ✅ Fast loading (< 3s)

### 2. Mobile Usability

**Common Issues:**
- Text too small
- Tap targets too close
- Content wider than screen
- Viewport not set

**Fixes:**

```css
/* Ensure readable text */
body {
    font-size: 16px; /* Never smaller on mobile */
    line-height: 1.6;
}

/* Adequate touch targets */
button, a {
    min-height: 48px;
    min-width: 48px;
    padding: var(--space-3) var(--space-4);
}

/* Prevent horizontal scroll */
body {
    overflow-x: hidden;
}

img {
    max-width: 100%;
    height: auto;
}
```

---

## XML Sitemap Optimization

### 1. Include Important Pages

```php
// Prioritize content in sitemap
add_filter('wp_sitemaps_posts_entry', function($entry, $post) {
    // Higher priority for recent posts
    $days_old = (time() - strtotime($post->post_date)) / DAY_IN_SECONDS;

    if ($days_old < 7) {
        $entry['priority'] = 0.9;
    } elseif ($days_old < 30) {
        $entry['priority'] = 0.7;
    } else {
        $entry['priority'] = 0.5;
    }

    // Change frequency based on update frequency
    $entry['changefreq'] = 'weekly';

    return $entry;
}, 10, 2);
```

### 2. Exclude Low-Value Pages

```php
// Exclude certain pages from sitemap
add_filter('wp_sitemaps_posts_query_args', function($args, $post_type) {
    // Exclude drafts, private, password-protected
    $args['post_status'] = 'publish';
    $args['has_password'] = false;

    return $args;
}, 10, 2);
```

---

## Analytics & Monitoring

### 1. Google Search Console

**Setup:**
1. Verify ownership: Add meta tag to `<head>`
2. Submit sitemap: `/wp-sitemap.xml`
3. Monitor crawl errors
4. Track search performance

**Add Verification Tag (functions.php):**

```php
add_action('wp_head', function() {
    echo '<meta name="google-site-verification" content="YOUR_CODE_HERE">';
});
```

### 2. Track SEO Metrics

**Key Metrics:**
- **Organic Traffic:** Sessions from Google
- **Click-Through Rate (CTR):** Impressions → clicks
- **Average Position:** Where you rank
- **Indexed Pages:** Pages in Google's index

**Tools:**
- Google Search Console
- Google Analytics 4
- Bing Webmaster Tools

---

## SEO Plugins (Optional)

### Recommended Plugins

**Option 1: Yoast SEO** (Most popular)
- ✅ Title/meta tag optimization
- ✅ XML sitemaps
- ✅ Breadcrumbs
- ✅ Schema markup
- ✅ Readability analysis

**Option 2: Rank Math** (Feature-rich)
- ✅ All Yoast features
- ✅ Google Search Console integration
- ✅ 404 monitoring
- ✅ Redirect manager
- ✅ Local SEO

**Option 3: The SEO Framework** (Lightweight)
- ✅ Fast and clean
- ✅ No ads or upsells
- ✅ Automatic optimization

**Recommendation:** Theme includes basic SEO. Add plugin only if you need advanced features.

---

## Local SEO (If Applicable)

**If organization has physical location:**

```php
// Local Business Schema
function humanitarian_local_business_schema() {
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'NGO',
        'name' => 'HumanitarianBlog',
        'url' => home_url('/'),
        'logo' => get_theme_file_uri('assets/images/logo.png'),
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => '+1-555-123-4567',
            'contactType' => 'Customer Service',
        ],
        'sameAs' => [
            'https://facebook.com/humanitarianblog',
            'https://twitter.com/humanitarianblog',
        ],
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
}
add_action('wp_footer', 'humanitarian_local_business_schema');
```

---

## SEO Checklist

### Technical SEO
- [ ] Meta titles optimized (< 60 chars)
- [ ] Meta descriptions added (150-160 chars)
- [ ] Open Graph tags implemented
- [ ] Twitter Card tags implemented
- [ ] Schema.org markup (Article, Breadcrumb)
- [ ] Sitemap submitted to Google
- [ ] Robots.txt configured
- [ ] Canonical URLs set
- [ ] 404 errors fixed

### On-Page SEO
- [ ] One H1 per page
- [ ] Logical heading hierarchy
- [ ] Alt text on all images
- [ ] Internal linking strategy
- [ ] Keyword in first paragraph
- [ ] Readable URLs (no ?p=123)
- [ ] Content length > 300 words

### Mobile SEO
- [ ] Mobile-friendly test passed
- [ ] Touch targets adequate (≥ 48px)
- [ ] Text readable (≥ 16px)
- [ ] No horizontal scroll
- [ ] Fast mobile load time (< 3s)

### Performance SEO
- [ ] Page speed score > 90
- [ ] Core Web Vitals passing
- [ ] Images optimized (WebP)
- [ ] CSS/JS minified
- [ ] Caching enabled
- [ ] CDN configured

### Content SEO
- [ ] Unique, valuable content
- [ ] Target keywords researched
- [ ] Related posts suggested
- [ ] Fresh content published regularly
- [ ] Thin content removed

### Analytics
- [ ] Google Search Console verified
- [ ] Sitemap submitted
- [ ] Google Analytics installed
- [ ] Tracking organic traffic
- [ ] Monitoring rankings

---

**Last Updated:** 2025-12-14
**Phase:** 7 - Production Ready & Polish
**SEO Status:** Documentation Complete, Implementation Pending
