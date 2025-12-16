# User Authentication System Documentation

## Overview

Frontend user authentication system for the Humanitarian Blog. Provides a separate login experience for readers while writers/editors continue to use wp-admin.

## Architecture

### User Roles
- **Subscriber (Reader)**: Frontend login/register, bookmarks, profile management
- **Editor/Admin (Writer)**: wp-admin access with full editing capabilities

### System Flow
```
Reader → /login/ → /my-account/
Writer → /wp-admin/ → Dashboard
```

## Created Files

### Page Templates

#### 1. `page-login.php`
- **Template Name**: Login Page
- **URL**: `/login/`
- **Features**:
  - Email/username login
  - Password show/hide toggle
  - "Remember me" checkbox
  - "Forgot password" link
  - Redirect to /my-account/ after login
  - Member benefits sidebar
  - Social login placeholder (Google - disabled)

#### 2. `page-register.php`
- **Template Name**: Register Page
- **URL**: `/register/`
- **Features**:
  - First name, last name fields
  - Email address
  - Username (letters, numbers, underscores only)
  - Password with strength indicator
  - Password confirmation
  - Terms of Service agreement checkbox
  - Newsletter subscription opt-in
  - "Why Join Us?" benefits sidebar

#### 3. `page-my-account.php`
- **Template Name**: My Account Page
- **URL**: `/my-account/`
- **Tabs**:
  - **Bookmarks** (default): Grid of saved articles with remove button
  - **Profile**: Edit first/last name, display name, email, bio
  - **Settings**: Change password, email preferences
- **Sidebar**: User avatar, name, email, navigation

## Modified Files

### `header.php`
Added user authentication UI to top bar:
- **Logged-in users**:
  - Bookmark icon with count badge
  - User dropdown menu (avatar, name, arrow)
  - Dropdown items: My Bookmarks, Edit Profile, Settings, Dashboard (editors only), Sign Out
- **Logged-out users**:
  - "Sign In" button with user icon

### `inc/ajax-handlers.php`
Added handlers for:

#### `frontend_register` (admin_post)
- Validates: first name, last name, email, username, password
- Rate limiting: 5 registrations/hour per IP
- Creates user with subscriber role
- Auto-login after registration
- Redirects to /my-account/?welcome=true

#### `update_frontend_profile` (admin_post)
- Updates: first name, last name, display name, email, bio
- Email uniqueness validation
- Redirects to /my-account/?tab=profile&updated=true

#### `change_frontend_password` (admin_post)
- Verifies current password
- Validates new password (min 8 chars)
- Re-authenticates user after change
- Redirects to /my-account/?tab=settings&password_updated=true

#### `update_email_prefs` (admin_post)
- Updates: newsletter_subscribed, comment_notifications user meta
- Redirects to /my-account/?tab=settings&prefs_updated=true

#### `toggle_bookmark` (AJAX)
- Adds/removes post ID from user's bookmarked_posts meta
- Returns: bookmarked status, count, message

### `assets/js/main.js`
Added `initUserDropdown()` function:
- Toggle dropdown on click
- Close on click outside
- Close on Escape key
- Close on menu item click
- Aria-expanded attribute management

### `style.css`
Added sections:
- **AUTHENTICATION PAGES**: Auth page layout, forms, messages, benefits sidebar
- **MY ACCOUNT PAGE**: Account layout, sidebar, navigation, bookmarks grid, profile form, settings
- **HEADER USER DROPDOWN**: Bookmark icon, badge, dropdown toggle, menu styles
- **AUTH PAGES RESPONSIVE**: Mobile adaptations

## CSS Classes Reference

### Auth Pages
```css
.auth-page          /* Main container */
.auth-container     /* Grid layout */
.auth-card          /* Form card */
.auth-header        /* Title and subtitle */
.auth-form          /* Form styles */
.auth-message       /* Success/error messages */
.auth-footer        /* Links below form */
.auth-benefits      /* Benefits sidebar */
```

### My Account
```css
.account-page       /* Main container */
.account-layout     /* Grid: sidebar + content */
.account-sidebar    /* User card + navigation */
.account-nav-item   /* Navigation links */
.account-content    /* Main content area */
.bookmarks-grid     /* Saved articles grid */
.bookmark-card      /* Individual bookmark */
.profile-form       /* Edit profile form */
.settings-section   /* Settings blocks */
```

### Header Elements
```css
.top-bar-bookmark   /* Bookmark icon */
.bookmark-badge     /* Count badge */
.user-dropdown      /* Dropdown container */
.user-dropdown-toggle /* Avatar + name button */
.user-dropdown-menu /* Dropdown menu */
.top-bar-login      /* Sign In button */
```

## Database

### User Meta Keys
- `bookmarked_posts`: Array of post IDs
- `newsletter_subscribed`: '1' or '0'
- `comment_notifications`: '1' or '0'

## WordPress Setup Required

1. **Enable Registration**:
   - Settings > General > "Anyone can register" = checked
   - Default role = Subscriber

2. **Create Pages**:
   - Page: "Login" → Template: "Login Page" → Slug: login
   - Page: "Register" → Template: "Register Page" → Slug: register
   - Page: "My Account" → Template: "My Account Page" → Slug: my-account

## Security Features

- CSRF protection via WordPress nonces
- Rate limiting on registration
- Password minimum length (8 characters)
- Input sanitization
- Email validation and uniqueness check
- Redirect protection for logged-in/out states

## Future Enhancements

- [ ] Google OAuth integration
- [ ] Email verification on registration
- [ ] Password reset via email
- [ ] Reading history tracking
- [ ] Profile avatar upload
- [ ] Account deletion option
