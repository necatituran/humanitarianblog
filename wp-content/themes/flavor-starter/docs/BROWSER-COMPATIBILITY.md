# Browser Compatibility Guide

## Overview

This document outlines browser support targets, known issues, and progressive enhancement strategies for the HumanitarianBlog theme.

---

## Browser Support Matrix

### Fully Supported (Grade A)

| Browser | Version | Notes |
|---------|---------|-------|
| Chrome | Last 2 versions | Full feature support |
| Firefox | Last 2 versions | Full feature support |
| Safari | Last 2 versions | Full feature support |
| Edge | Last 2 versions | Chromium-based |
| Safari iOS | Last 2 versions | Mobile Safari |
| Chrome Android | Last 2 versions | Mobile Chrome |

### Partially Supported (Grade B)

| Browser | Version | Notes |
|---------|---------|-------|
| Edge Legacy | 18+ | No backdrop-filter support |
| Firefox ESR | Latest | May lack newest CSS features |
| Samsung Internet | 14+ | Chromium-based, generally good |

### Gracefully Degraded (Grade C)

| Browser | Version | Notes |
|---------|---------|-------|
| IE 11 | N/A | **NOT SUPPORTED** - Theme requires modern JavaScript |
| Opera Mini | All | Limited JavaScript, basic layout only |

---

## Feature Support

### CSS Features

#### CSS Grid
**Usage:** Bookmarks page layout

**Support:**
- ✅ Chrome 57+
- ✅ Firefox 52+
- ✅ Safari 10.1+
- ✅ Edge 16+

**Fallback:**
```css
/* Fallback for non-Grid browsers */
@supports not (display: grid) {
    .bookmarks-grid {
        display: flex;
        flex-wrap: wrap;
    }
    .bookmark-card {
        flex: 0 0 calc(33.333% - var(--space-6));
    }
}
```

#### CSS Custom Properties (Variables)
**Usage:** Design system (colors, spacing, typography)

**Support:**
- ✅ Chrome 49+
- ✅ Firefox 31+
- ✅ Safari 9.1+
- ✅ Edge 15+

**Fallback:**
```css
/* Provide static fallback */
.button {
    background: #e74c3c; /* Fallback */
    background: var(--color-primary); /* Modern */
}
```

#### Backdrop Filter (Blur)
**Usage:** Modal backdrops, menu overlay

**Support:**
- ✅ Chrome 76+
- ✅ Safari 9+ (with -webkit-)
- ✅ Edge 79+
- ❌ Firefox (behind flag)

**Fallback:**
```css
.modal-backdrop {
    background: rgba(0, 0, 0, 0.5); /* Always works */
    backdrop-filter: blur(8px); /* Progressive enhancement */
    -webkit-backdrop-filter: blur(8px); /* Safari */
}
```

#### CSS Transforms & Transitions
**Usage:** Hover effects, animations

**Support:**
- ✅ All modern browsers
- ⚠️ Need vendor prefixes for older Safari

**Implementation:**
```css
.bookmark-card {
    transition: transform 0.3s ease;
    -webkit-transition: transform 0.3s ease; /* Safari 8 */
}

.bookmark-card:hover {
    transform: translateY(-4px);
    -webkit-transform: translateY(-4px); /* Safari 8 */
}
```

#### Flexbox
**Usage:** Navigation, button groups, card internals

**Support:**
- ✅ All modern browsers
- ⚠️ IE 11 has bugs (but not supported anyway)

---

### JavaScript Features

#### Fetch API
**Usage:** AJAX requests (search, PDF, QR, bookmarks)

**Support:**
- ✅ Chrome 42+
- ✅ Firefox 39+
- ✅ Safari 10.1+
- ✅ Edge 14+

**Polyfill (if needed):**
```javascript
// Add to functions.php if supporting older browsers
wp_enqueue_script('fetch-polyfill', 'https://cdn.jsdelivr.net/npm/whatwg-fetch@3.6.2/dist/fetch.umd.js');
```

#### localStorage
**Usage:** Bookmarks storage

**Support:**
- ✅ All modern browsers
- ⚠️ Safari Private Mode throws errors

**Graceful Degradation:**
```javascript
// Already implemented in bookmarks.js
try {
    localStorage.setItem('test', 'test');
    localStorage.removeItem('test');
} catch (e) {
    console.warn('localStorage not available');
    // Fall back to cookies or disable feature
}
```

#### IntersectionObserver
**Usage:** Lazy loading images (if implemented)

**Support:**
- ✅ Chrome 51+
- ✅ Firefox 55+
- ✅ Safari 12.1+
- ✅ Edge 15+

**Polyfill:**
```javascript
// Include in functions.php if needed
wp_enqueue_script('intersection-observer-polyfill', 'https://polyfill.io/v3/polyfill.min.js?features=IntersectionObserver');
```

#### requestAnimationFrame
**Usage:** Scroll event throttling

**Support:**
- ✅ All modern browsers

**Fallback:**
```javascript
const raf = window.requestAnimationFrame ||
            window.webkitRequestAnimationFrame ||
            function(callback) { setTimeout(callback, 16); };
```

---

## Browser-Specific Issues & Fixes

### Safari Issues

#### Issue 1: Date Input Styling
**Problem:** Safari doesn't style `<input type="date">` consistently

**Fix:**
```css
input[type="date"] {
    -webkit-appearance: none;
    appearance: none;
}
```

#### Issue 2: Smooth Scroll
**Problem:** `scroll-behavior: smooth` not supported in Safari < 15.4

**Fix:**
```javascript
// Polyfill smooth scroll
if (!('scrollBehavior' in document.documentElement.style)) {
    // Use smooth-scroll polyfill or custom implementation
}
```

#### Issue 3: Safe Area Insets (iOS)
**Problem:** Content hidden by notch or home indicator

**Fix:**
```css
@supports (padding: env(safe-area-inset-bottom)) {
    .reading-toolbar-mobile {
        padding-bottom: calc(var(--space-4) + env(safe-area-inset-bottom));
    }
}
```

---

### Firefox Issues

#### Issue 1: Backdrop Filter Not Supported
**Problem:** `backdrop-filter` behind flag in Firefox

**Fix:**
```css
/* Enhance background opacity for Firefox */
@-moz-document url-prefix() {
    .modal-backdrop {
        background: rgba(0, 0, 0, 0.7); /* Darker without blur */
    }
}
```

#### Issue 2: Font Rendering
**Problem:** Fonts may render differently than Chrome

**Fix:**
```css
body {
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
}
```

---

### Chrome Issues

#### Issue 1: Autofill Styling
**Problem:** Chrome adds blue background to autofilled inputs

**Fix:**
```css
input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0 1000px white inset;
    box-shadow: 0 0 0 1000px white inset;
}
```

#### Issue 2: Select Arrow
**Problem:** Default select arrow hard to style

**Fix:**
```css
select {
    -webkit-appearance: none;
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg>...</svg>');
}
```

---

### Edge Issues

#### Issue 1: Edge Legacy (18 and below)
**Problem:** Pre-Chromium Edge has limited CSS support

**Status:** Not prioritized (Edge auto-updates to Chromium)

**Note:** If you must support Edge Legacy:
- Provide static color fallbacks for CSS variables
- Test Grid layout carefully
- Avoid backdrop-filter

---

### Mobile Browser Issues

#### iOS Safari

**Issue 1: 100vh Bug**
**Problem:** `height: 100vh` includes browser chrome, causing overflow

**Fix:**
```css
.modal {
    height: 100vh;
    height: -webkit-fill-available; /* iOS fix */
}
```

**Issue 2: Touch Event Delays**
**Problem:** 300ms delay on tap events

**Fix:**
```css
button, a {
    touch-action: manipulation; /* Remove delay */
}
```

**Issue 3: Fixed Position and Keyboard**
**Problem:** Fixed elements jump when keyboard opens

**Fix:**
```javascript
// Detect keyboard open
window.addEventListener('resize', () => {
    const isKeyboardOpen = window.innerHeight < window.screen.height;
    document.body.classList.toggle('keyboard-open', isKeyboardOpen);
});
```

#### Android Chrome

**Issue 1: Address Bar Hiding**
**Problem:** Address bar hides on scroll, changing viewport height

**Fix:**
```css
/* Use dvh (dynamic viewport height) when supported */
.modal {
    height: 100vh;
    height: 100dvh; /* CSS Values 4 */
}
```

#### Samsung Internet

**Issue 2: Custom Fonts**
**Problem:** Font loading may be delayed

**Fix:**
```css
@font-face {
    font-family: 'CustomFont';
    src: url('font.woff2') format('woff2');
    font-display: swap; /* Show fallback immediately */
}
```

---

## Progressive Enhancement Strategy

### Core Functionality (All Browsers)
- Read articles
- Navigate between pages
- View images
- Submit forms

### Enhanced Functionality (Modern Browsers)
- Live search with AJAX
- Modal interactions
- Bookmark saving (localStorage)
- Smooth scrolling
- Reading progress bar

### Advanced Functionality (Latest Browsers)
- Backdrop blur effects
- CSS Grid layouts
- Advanced animations
- Service Worker (PWA) - Phase 8

---

## Feature Detection

### CSS Feature Queries

```css
/* Use Grid if supported, Flexbox if not */
.bookmarks-grid {
    display: flex;
    flex-wrap: wrap;
}

@supports (display: grid) {
    .bookmarks-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
}
```

### JavaScript Feature Detection

```javascript
// Check localStorage
const hasLocalStorage = (() => {
    try {
        localStorage.setItem('test', 'test');
        localStorage.removeItem('test');
        return true;
    } catch (e) {
        return false;
    }
})();

// Check Fetch API
const hasFetch = typeof fetch !== 'undefined';

// Provide fallback
if (!hasFetch) {
    // Use XMLHttpRequest or disable feature
}
```

---

## Testing Checklist

### Desktop Browsers

**Chrome (Latest)**
- [ ] All CSS Grid layouts work
- [ ] Modals display correctly
- [ ] Backdrop blur works
- [ ] AJAX requests successful
- [ ] Animations smooth
- [ ] No console errors

**Firefox (Latest)**
- [ ] Grid layouts identical to Chrome
- [ ] Modals work (check backdrop without blur)
- [ ] Font rendering acceptable
- [ ] AJAX requests successful
- [ ] No console errors

**Safari (Latest - macOS)**
- [ ] Grid layouts work
- [ ] Backdrop blur works (with -webkit-)
- [ ] Smooth scroll polyfill if needed
- [ ] Date inputs styled correctly
- [ ] No console errors

**Edge (Latest)**
- [ ] Identical to Chrome (Chromium-based)
- [ ] No rendering differences
- [ ] No console errors

### Mobile Browsers

**Safari iOS**
- [ ] 100vh bug fixed
- [ ] Safe area insets respected
- [ ] Touch events responsive (no 300ms delay)
- [ ] Fixed elements don't jump with keyboard
- [ ] Modals work in portrait and landscape

**Chrome Android**
- [ ] Address bar hiding handled
- [ ] Touch targets adequate (min 44x44px)
- [ ] No horizontal scroll
- [ ] Forms usable

**Samsung Internet**
- [ ] Fonts load correctly
- [ ] Animations work
- [ ] localStorage works

---

## Automated Testing

### BrowserStack
Use BrowserStack for real device testing:

```bash
# Test URLs
https://humanitarian-blog.local/
https://humanitarian-blog.local/bookmarks/
https://humanitarian-blog.local/sample-post/
```

**Devices to Test:**
- iPhone 14 (Safari iOS 16)
- Samsung Galaxy S21 (Chrome Android)
- iPad Pro (Safari iOS)
- Windows 11 (Chrome, Firefox, Edge)
- macOS (Safari, Chrome, Firefox)

### Lighthouse CI

```bash
# Run Lighthouse for each browser
npm install -g @lhci/cli

lhci autorun --collect.url=https://humanitarian-blog.local
```

---

## Browser Bug Reporting

If you find a browser-specific bug:

1. **Document the issue:**
   - Browser name and version
   - Operating system
   - Steps to reproduce
   - Expected vs actual behavior

2. **Create a minimal test case:**
   - Isolate the bug in a CodePen or JSFiddle

3. **Check browser bug trackers:**
   - Chrome: https://bugs.chromium.org/
   - Firefox: https://bugzilla.mozilla.org/
   - Safari: https://bugs.webkit.org/

4. **Implement a workaround:**
   - Document in this file
   - Add code comments
   - Consider user impact

---

## Browser Support Decision Matrix

When deciding whether to support a feature:

| Browser Share | Support Level | Action |
|---------------|---------------|--------|
| > 10% | Full support | Implement fully, test thoroughly |
| 5-10% | Partial support | Provide fallbacks, test minimally |
| 1-5% | Graceful degradation | Basic functionality only |
| < 1% | No support | Document, provide basic HTML |

**Current Target:** Browsers with > 1% global market share (2025)

---

## Resources

- **Can I Use:** https://caniuse.com/
- **MDN Browser Compatibility:** https://developer.mozilla.org/
- **Autoprefixer:** https://autoprefixer.github.io/
- **Polyfill.io:** https://polyfill.io/
- **BrowserStack:** https://www.browserstack.com/
- **LambdaTest:** https://www.lambdatest.com/

---

**Last Updated:** 2025-12-14
**Phase:** 7 - Production Ready & Polish
**Browsers Tested:** Chrome ✅, Firefox ✅, Safari ⏳, Edge ✅
