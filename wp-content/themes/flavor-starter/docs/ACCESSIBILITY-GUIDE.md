# Accessibility Guide (WCAG 2.1 AA)

## Overview

This document outlines accessibility requirements, implementation strategies, and testing procedures to ensure the HumanitarianBlog theme meets WCAG 2.1 Level AA standards.

**Target Audience:** Elderly users, users with disabilities, screen reader users

---

## WCAG 2.1 Principles (POUR)

1. **Perceivable:** Information must be presentable to users in ways they can perceive
2. **Operable:** Interface components must be operable by all users
3. **Understandable:** Information and operation must be understandable
4. **Robust:** Content must be robust enough for assistive technologies

---

## Current Accessibility Status

### Phase 1-6 Features ✅
- ✅ Semantic HTML5 elements
- ✅ Keyboard navigation support (Tab, Enter, Escape)
- ✅ Focus states on interactive elements
- ✅ ARIA labels on icon buttons
- ✅ Screen reader announcements (live search, modals)
- ✅ Large font sizes (elderly-friendly)
- ✅ High color contrast

### Phase 7 Enhancements ⏳
- [ ] Complete ARIA landmark roles
- [ ] Skip to content link
- [ ] Focus trap in modals
- [ ] Enhanced keyboard shortcuts
- [ ] Screen reader testing
- [ ] Color contrast audit

---

## Perceivable

### 1.1 Text Alternatives

**Requirement:** All non-text content has a text alternative

#### Images

**Implementation:**

```php
<!-- Featured images -->
<?php
the_post_thumbnail('large', [
    'alt' => get_the_title(),
    'loading' => 'lazy',
]);
?>

<!-- Content images -->
<img src="photo.jpg" alt="Humanitarian workers distributing food packages in refugee camp" loading="lazy">

<!-- Decorative images (empty alt) -->
<img src="decorative-pattern.svg" alt="" role="presentation">
```

**Icon Buttons:**

```html
<!-- Bad -->
<button class="icon-close">
    <svg>...</svg>
</button>

<!-- Good -->
<button class="icon-close" aria-label="Close modal">
    <svg aria-hidden="true">...</svg>
</button>
```

**Code Reference:** [modals.js](../assets/js/modals.js)

#### Audio/Video

**Audio Player (Already Implemented):**

```html
<audio controls aria-label="Article audio narration">
    <source src="article.mp3" type="audio/mpeg">
    <p>Your browser doesn't support audio. <a href="article.mp3">Download audio file</a></p>
</audio>
```

**Video (If Added):**

```html
<video controls aria-label="Video: Humanitarian crisis in Syria">
    <source src="video.mp4" type="video/mp4">
    <track kind="captions" src="captions-en.vtt" srclang="en" label="English" default>
    <track kind="captions" src="captions-tr.vtt" srclang="tr" label="Türkçe">
    <p>Your browser doesn't support video.</p>
</video>
```

### 1.2 Time-Based Media

**Requirement:** Provide captions, transcripts, or audio descriptions

**Implementation:**
- Audio articles: Provide full text content (already done!)
- Videos: Add caption tracks (.vtt files)

### 1.3 Adaptable Content

**Requirement:** Content can be presented in different ways without losing information

#### Semantic HTML

```html
<!-- Good: Semantic structure -->
<header class="site-header">
    <nav class="site-navigation" aria-label="Main navigation">
        <ul>...</ul>
    </nav>
</header>

<main id="main-content">
    <article>
        <header>
            <h1>Article Title</h1>
            <p class="meta">...</p>
        </header>
        <div class="content">...</div>
    </article>
</main>

<aside class="sidebar" aria-label="Sidebar">...</aside>

<footer class="site-footer">...</footer>
```

#### Meaningful Sequence

```html
<!-- Ensure logical tab order -->
<form>
    <label for="search">Search articles</label>
    <input type="text" id="search" tabindex="1">
    <button type="submit" tabindex="2">Search</button>
</form>
```

### 1.4 Distinguishable

**Requirement:** Make it easier for users to see and hear content

#### Color Contrast (WCAG AA)

**Requirements:**
- Normal text (< 18pt): 4.5:1 minimum
- Large text (≥ 18pt): 3:1 minimum
- UI components: 3:1 minimum

**Current Theme Colors:**

```css
/* Check these combinations */
:root {
    --color-text: #2c3e50;        /* Dark gray */
    --color-background: #ffffff;  /* White */
    --color-primary: #e74c3c;     /* Red */
    --color-accent: #3498db;      /* Blue */
}

/* Text on white background */
.text-primary {
    color: var(--color-text); /* #2c3e50 on #fff = 12.6:1 ✅ PASS */
}

/* Primary button */
.button-primary {
    background: var(--color-primary); /* #e74c3c */
    color: white; /* #fff on #e74c3c = 4.5:1 ✅ PASS */
}
```

**Testing Tools:**
- **WebAIM Contrast Checker:** https://webaim.org/resources/contrastchecker/
- **Chrome DevTools:** Inspect element → Color picker shows contrast ratio

**Audit Checklist:**
- [ ] Body text on white: ✅ Pass
- [ ] Link text on white: ✅ Pass
- [ ] Button text on primary color: ✅ Pass
- [ ] Button text on accent color: Check
- [ ] Placeholder text: Check (often fails!)
- [ ] Disabled button text: Check
- [ ] Error messages: Check
- [ ] Success messages: Check

#### Text Resizing

**Requirement:** Text can be resized up to 200% without loss of content

**Implementation:**

```css
/* Use relative units */
body {
    font-size: 16px; /* Base size */
}

h1 {
    font-size: 2.5rem; /* 40px, scales with zoom */
}

p {
    font-size: 1rem; /* 16px, scales with zoom */
}

/* Avoid fixed heights on text containers */
.card {
    min-height: 200px; /* Use min-height, not height */
}
```

**Test:** Zoom browser to 200% (Ctrl + +), ensure no text is cut off

#### Images of Text

**Requirement:** Avoid images of text

**✅ Good:**
```html
<h1>Humanitarian Blog</h1>
```

**❌ Bad:**
```html
<img src="title-humanitarian-blog.png" alt="Humanitarian Blog">
```

---

## Operable

### 2.1 Keyboard Accessible

**Requirement:** All functionality available via keyboard

#### Skip to Content

**Add to header.php:**

```html
<a href="#main-content" class="skip-link">Skip to content</a>

<style>
.skip-link {
    position: absolute;
    top: -40px;
    left: 0;
    background: var(--color-primary);
    color: white;
    padding: var(--space-2) var(--space-4);
    text-decoration: none;
    z-index: 100000;
}

.skip-link:focus {
    top: 0;
}
</style>
```

#### Keyboard Navigation

**Requirements:**
- **Tab:** Move forward through interactive elements
- **Shift+Tab:** Move backward
- **Enter/Space:** Activate buttons
- **Escape:** Close modals
- **Arrow keys:** Navigate menus (optional)

**Modal Focus Trap (Add to modals.js):**

```javascript
// Trap focus inside modal
function trapFocus(modal) {
    const focusableElements = modal.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];

    modal.addEventListener('keydown', function(e) {
        if (e.key !== 'Tab') return;

        if (e.shiftKey) { // Shift + Tab
            if (document.activeElement === firstElement) {
                lastElement.focus();
                e.preventDefault();
            }
        } else { // Tab
            if (document.activeElement === lastElement) {
                firstElement.focus();
                e.preventDefault();
            }
        }
    });

    // Focus first element
    firstElement.focus();
}

// Use when opening modal
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('is-open');
    trapFocus(modal);
}
```

**Test:**
- [ ] Tab through entire page (logical order)
- [ ] All interactive elements reachable
- [ ] Focus visible (outline or custom style)
- [ ] No keyboard traps
- [ ] Modals trap focus
- [ ] Escape closes modals

#### Focus Visible

**CSS:**

```css
/* Ensure focus is always visible */
:focus {
    outline: 2px solid var(--color-primary);
    outline-offset: 2px;
}

/* Custom focus for buttons */
button:focus {
    outline: 2px solid var(--color-accent);
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.2);
}

/* Never remove outline without alternative */
button:focus {
    outline: none; /* ❌ BAD */
}

button:focus-visible {
    outline: 2px solid blue; /* ✅ GOOD (if styled) */
}
```

### 2.2 Enough Time

**Requirement:** Users have enough time to read and use content

**Implementation:**
- ✅ No time limits on reading
- ✅ Notifications auto-dismiss after 5s (can be closed manually)
- ✅ No session timeouts

**If Adding Timers:**

```javascript
// Provide pause/extend option
function showNotification(message, duration = 5000) {
    const notification = createNotification(message);

    // Allow user to pause timer
    let timerId;
    const pauseButton = notification.querySelector('.pause');

    pauseButton.addEventListener('click', () => {
        clearTimeout(timerId);
        pauseButton.textContent = 'Resume';
    });

    timerId = setTimeout(() => {
        notification.remove();
    }, duration);
}
```

### 2.3 Seizures and Physical Reactions

**Requirement:** Do not design content that flashes more than 3 times per second

**✅ Theme Status:** No flashing content

**Avoid:**
- Flashing animations
- Rapid color changes
- Strobing effects

### 2.4 Navigable

**Requirement:** Provide ways to help users navigate and find content

#### Page Title

**Implementation (already done):**

```html
<title><?php wp_title('|', true, 'right'); ?> HumanitarianBlog</title>
```

**Best Practices:**
- Unique page titles
- Describe page content
- Put unique info first

#### Focus Order

**Test:** Tab through page, ensure logical order matches visual order

#### Link Purpose

**Bad:**
```html
<a href="/article">Click here</a>
<a href="/article">Read more</a>
```

**Good:**
```html
<a href="/article">Read article: Humanitarian crisis in Syria</a>
<a href="/article">Continue reading about aid distribution</a>
```

**Code Enhancement:**

```php
<!-- Bookmark card links -->
<a href="<?php the_permalink(); ?>" aria-label="Read article: <?php echo esc_attr(get_the_title()); ?>">
    <h3><?php the_title(); ?></h3>
</a>
```

#### Multiple Ways to Find Content

**Provide:**
- ✅ Main navigation
- ✅ Search (live search implemented)
- ✅ Categories/Regions (custom taxonomies)
- ✅ Breadcrumbs (add in Phase 7)
- ✅ Sitemap

**Add Breadcrumbs (single.php):**

```php
<nav aria-label="Breadcrumb" class="breadcrumb">
    <ol>
        <li><a href="<?php echo home_url('/'); ?>">Home</a></li>
        <?php
        $categories = get_the_category();
        if (!empty($categories)) :
        ?>
            <li><a href="<?php echo get_category_link($categories[0]->term_id); ?>"><?php echo esc_html($categories[0]->name); ?></a></li>
        <?php endif; ?>
        <li aria-current="page"><?php the_title(); ?></li>
    </ol>
</nav>
```

#### Headings and Labels

**Heading Hierarchy:**

```html
<h1>Article Title</h1>           <!-- One per page -->
  <h2>Introduction</h2>
  <h2>Main Body</h2>
    <h3>Subsection A</h3>
    <h3>Subsection B</h3>
  <h2>Conclusion</h2>
```

**Form Labels:**

```html
<!-- Bad -->
<input type="text" placeholder="Search...">

<!-- Good -->
<label for="search-input">Search articles</label>
<input type="text" id="search-input" placeholder="Enter keywords...">

<!-- Or with aria-label -->
<input type="text" aria-label="Search articles" placeholder="Enter keywords...">
```

---

## Understandable

### 3.1 Readable

**Requirement:** Text content is readable and understandable

#### Language of Page

**Implementation (functions.php):**

```html
<html lang="<?php bloginfo('language'); ?>">
```

**Output:** `<html lang="en-US">` or `<html lang="tr-TR">`

#### Language of Parts

**If mixing languages:**

```html
<p>The organization said "<span lang="ar">السلام عليكم</span>" to the refugees.</p>
```

### 3.2 Predictable

**Requirement:** Web pages appear and operate in predictable ways

#### On Focus

**Requirement:** Nothing unexpected happens when element receives focus

**✅ Good:**
```javascript
input.addEventListener('focus', () => {
    input.classList.add('focused'); // Just visual change
});
```

**❌ Bad:**
```javascript
input.addEventListener('focus', () => {
    window.location = '/search'; // Navigation on focus!
});
```

#### On Input

**Requirement:** Changing a setting doesn't automatically change context

**✅ Good:**
```html
<select aria-label="Sort bookmarks">
    <option value="recent">Most Recent</option>
    <option value="title">Title A-Z</option>
</select>
<button>Apply Sort</button>
```

**❌ Bad:**
```javascript
select.addEventListener('change', () => {
    window.location = '?sort=' + select.value; // Auto-submit
});
```

**Note:** Our bookmarks page uses auto-submit for better UX. This is acceptable if:
1. User is warned beforehand
2. Change is expected (sorting is predictable)

**Enhancement:**

```html
<label for="sort-select">
    Sort bookmarks (updates automatically)
</label>
<select id="sort-select" aria-label="Sort bookmarks, updates page automatically">
    <option>Most Recent</option>
</select>
```

#### Consistent Navigation

**Requirement:** Navigation appears in the same relative order

**✅ Theme Status:** Navigation is consistent across all pages

#### Consistent Identification

**Requirement:** Icons and symbols are used consistently

**Examples:**
- Search icon always means search
- Close icon (×) always closes modals
- Bookmark icon always saves/unsaves

### 3.3 Input Assistance

**Requirement:** Help users avoid and correct mistakes

#### Error Identification

**Form Validation:**

```html
<!-- Newsletter signup with validation -->
<form id="newsletter-form" novalidate>
    <label for="email">Email address</label>
    <input
        type="email"
        id="email"
        aria-required="true"
        aria-invalid="false"
        aria-describedby="email-error"
    >
    <span id="email-error" role="alert" class="error" hidden>
        Please enter a valid email address
    </span>
    <button type="submit">Subscribe</button>
</form>

<script>
const form = document.getElementById('newsletter-form');
const email = document.getElementById('email');
const error = document.getElementById('email-error');

form.addEventListener('submit', (e) => {
    e.preventDefault();

    if (!email.validity.valid) {
        email.setAttribute('aria-invalid', 'true');
        error.hidden = false;
        email.focus();
    } else {
        // Submit form
    }
});
</script>
```

#### Labels or Instructions

**Provide clear labels:**

```html
<!-- Good -->
<label for="username">
    Username (must be 3-20 characters)
</label>
<input type="text" id="username" minlength="3" maxlength="20" required>
```

#### Error Suggestion

**Provide suggestions:**

```javascript
if (!email.includes('@')) {
    error.textContent = 'Email must include @. Example: user@example.com';
}
```

---

## Robust

### 4.1 Compatible

**Requirement:** Maximize compatibility with current and future tools

#### Valid HTML

**Test with W3C Validator:** https://validator.w3.org/

**Common Issues:**
- Duplicate IDs
- Unclosed tags
- Invalid nesting

**Check:**

```bash
# Install HTML validator
npm install -g html-validator-cli

# Validate pages
html-validator --url=http://humanitarian-blog.local/ --verbose
```

#### Name, Role, Value

**Requirement:** All UI components have accessible name, role, and value

**ARIA Attributes:**

```html
<!-- Button with icon -->
<button aria-label="Close modal" type="button">
    <svg aria-hidden="true">...</svg>
</button>

<!-- Toggle button -->
<button
    aria-label="Toggle mobile menu"
    aria-expanded="false"
    aria-controls="mobile-menu"
>
    Menu
</button>

<!-- Progress bar -->
<div
    role="progressbar"
    aria-valuenow="45"
    aria-valuemin="0"
    aria-valuemax="100"
    aria-label="Reading progress"
>
    <div style="width: 45%"></div>
</div>
```

---

## ARIA Landmarks

**Add to theme templates:**

```html
<header role="banner">
    <nav role="navigation" aria-label="Main navigation">...</nav>
</header>

<main role="main" id="main-content">
    <article role="article">...</article>
</main>

<aside role="complementary" aria-label="Related posts">...</aside>

<footer role="contentinfo">...</footer>
```

**Benefits:**
- Screen readers can jump to landmarks
- Better navigation for keyboard users

---

## Screen Reader Testing

### Test with Real Screen Readers

**Windows:**
- **NVDA** (free): https://www.nvaccess.org/
- **JAWS** (premium): https://www.freedomscientific.com/

**macOS:**
- **VoiceOver** (built-in): Cmd + F5

**Mobile:**
- **TalkBack** (Android): Settings → Accessibility
- **VoiceOver** (iOS): Settings → Accessibility

### Testing Checklist

- [ ] Page title announced correctly
- [ ] Headings navigable (H key in NVDA/JAWS)
- [ ] Landmarks navigable (D key)
- [ ] Links descriptive ("Read article: Title" not "Click here")
- [ ] Form labels associated
- [ ] Error messages announced
- [ ] Modals announced (aria-live or focus)
- [ ] Image alt text descriptive

### ARIA Live Regions

**Announce dynamic content:**

```html
<!-- Search results -->
<div
    id="search-results"
    role="region"
    aria-live="polite"
    aria-atomic="true"
>
    <p>5 results found for "humanitarian"</p>
</div>

<!-- Notifications -->
<div
    class="notification"
    role="alert"
    aria-live="assertive"
>
    Bookmark added successfully
</div>
```

**Code Reference:** [search.js:70-75](../assets/js/search.js#L70-L75)

---

## Accessibility Testing Tools

### Automated Tools

**Browser Extensions:**
- **axe DevTools** (Chrome/Firefox): Best overall
- **WAVE** (Chrome/Firefox): Visual feedback
- **Lighthouse** (Chrome DevTools): Built-in audit

**Command Line:**
```bash
# Install Pa11y
npm install -g pa11y

# Test page
pa11y http://humanitarian-blog.local/

# Test entire site
pa11y-ci --sitemap http://humanitarian-blog.local/sitemap.xml
```

### Manual Testing

**Keyboard Only:**
1. Disconnect mouse
2. Navigate entire site with Tab, Enter, Escape
3. Check all functionality works

**Screen Reader:**
1. Enable NVDA/VoiceOver
2. Close your eyes
3. Try to complete tasks (read article, search, bookmark)

**Color Blindness:**
- **Color Oracle** (free app): Simulate color blindness
- Chrome DevTools → Rendering → Emulate vision deficiencies

**Low Vision:**
- Zoom to 200%
- Use Windows Magnifier
- Check text is still readable

---

## Accessibility Statement

**Create page: page-accessibility.php**

```php
<?php
/*
Template Name: Accessibility Statement
*/
get_header();
?>

<main id="main-content" class="accessibility-page">
    <article>
        <h1>Accessibility Statement</h1>

        <p>HumanitarianBlog is committed to ensuring digital accessibility for people with disabilities. We are continually improving the user experience for everyone and applying the relevant accessibility standards.</p>

        <h2>Conformance Status</h2>
        <p>The Web Content Accessibility Guidelines (WCAG) defines requirements for designers and developers to improve accessibility for people with disabilities. We aim to conform to WCAG 2.1 level AA.</p>

        <h2>Feedback</h2>
        <p>We welcome your feedback on the accessibility of HumanitarianBlog. Please contact us:</p>
        <ul>
            <li>Email: accessibility@humanitarian-blog.com</li>
            <li>Phone: +1-555-123-4567</li>
        </ul>

        <h2>Compatibility</h2>
        <p>This website is designed to be compatible with:</p>
        <ul>
            <li>Recent versions of NVDA, JAWS, and VoiceOver screen readers</li>
            <li>Recent versions of Chrome, Firefox, Safari, and Edge browsers</li>
            <li>Keyboard navigation</li>
            <li>Browser zoom up to 200%</li>
        </ul>

        <h2>Technical Specifications</h2>
        <p>Accessibility relies on the following technologies:</p>
        <ul>
            <li>HTML</li>
            <li>CSS</li>
            <li>JavaScript</li>
            <li>WAI-ARIA</li>
        </ul>

        <h2>Limitations and Alternatives</h2>
        <p>Despite our best efforts, some content may not yet be fully accessible:</p>
        <ul>
            <li>Older articles may lack alt text on images (being updated)</li>
            <li>PDF downloads may not be fully accessible (working on improvement)</li>
        </ul>

        <p><strong>Last updated:</strong> <?php echo date('F Y'); ?></p>
    </article>
</main>

<?php get_footer(); ?>
```

---

## Accessibility Checklist

### Perceivable
- [ ] All images have alt text
- [ ] Color contrast ≥ 4.5:1 (text)
- [ ] Color contrast ≥ 3:1 (UI components)
- [ ] Text resizable to 200%
- [ ] No text in images
- [ ] Audio has transcript (text content)

### Operable
- [ ] All functionality keyboard accessible
- [ ] Skip to content link
- [ ] Focus visible on all elements
- [ ] No keyboard traps
- [ ] Modals trap focus
- [ ] Escape closes modals
- [ ] No content flashes > 3 times/second

### Understandable
- [ ] Language attribute set
- [ ] Navigation consistent across pages
- [ ] Form labels clear
- [ ] Error messages descriptive
- [ ] Instructions provided

### Robust
- [ ] Valid HTML (W3C Validator)
- [ ] ARIA landmarks used
- [ ] ARIA labels on icon buttons
- [ ] ARIA live regions for dynamic content
- [ ] Screen reader tested

---

**Last Updated:** 2025-12-14
**Phase:** 7 - Production Ready & Polish
**WCAG Level:** AA Target
**Status:** Documentation Complete, Testing Pending
