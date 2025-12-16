# Humanitarian Blog - Uygulama Planı

**Tarih:** 16 Aralık 2024
**Proje:** User Management, Posts Panel, Author Pages, Multi-Language System

---

## GENEL BAKIŞ

| Modül | Durum | Öncelik |
|-------|-------|---------|
| 1. Email Verification | Planlandı | Yüksek |
| 2. Modern User Panel | Planlandı | Orta |
| 3. Post Email Notifications | Planlandı | Orta |
| 4. Posts → Articles Rename | Planlandı | Düşük |
| 5. My Articles Filter | Planlandı | Düşük |
| 6. Modern Admin Panel | Planlandı | Orta |
| 7. Author Page Redesign | Planlandı | Yüksek |
| 8. Multi-Language System | Planlandı | **Kritik** |

---

## 1. EMAIL VERIFICATION SİSTEMİ

### Gereksinimler
- SiteGround üzerinden SMTP email (asıl domain)
- Plugin olmadan, kendi kodumuuzla

### Mimari

```
KAYIT AKIŞI:
┌─────────────────────────────────────────────────────────────┐
│ 1. Kullanıcı kayıt formunu doldurur                        │
│ 2. Sistem kullanıcıyı oluşturur (email_verified = 0)       │
│ 3. Benzersiz token oluşturulur ve user_meta'ya kaydedilir  │
│ 4. Doğrulama emaili gönderilir (SiteGround SMTP)           │
│ 5. Kullanıcı linke tıklar                                   │
│ 6. Token doğrulanır, email_verified = 1 yapılır            │
│ 7. Kullanıcı giriş yapabilir                               │
└─────────────────────────────────────────────────────────────┘
```

### Dosya Değişiklikleri

#### A) `inc/email-verification.php` (YENİ)
```php
<?php
/**
 * Email Verification System
 * Plugin olmadan, SiteGround SMTP ile
 */

// Token oluştur ve kaydet
function humanitarian_generate_verification_token($user_id) {
    $token = wp_generate_password(32, false);
    $expiry = time() + (24 * 60 * 60); // 24 saat geçerli

    update_user_meta($user_id, '_email_verification_token', $token);
    update_user_meta($user_id, '_email_verification_expiry', $expiry);
    update_user_meta($user_id, '_email_verified', 0);

    return $token;
}

// Doğrulama emaili gönder
function humanitarian_send_verification_email($user_id) {
    $user = get_userdata($user_id);
    $token = humanitarian_generate_verification_token($user_id);

    $verify_url = add_query_arg([
        'action' => 'verify_email',
        'user_id' => $user_id,
        'token' => $token
    ], home_url('/'));

    $subject = __('Hesabınızı Doğrulayın - Humanitarian Blog', 'humanitarianblog');

    $message = sprintf(
        __("Merhaba %s,\n\nHumanitarian Blog'a hoş geldiniz!\n\nHesabınızı aktifleştirmek için aşağıdaki linke tıklayın:\n\n%s\n\nBu link 24 saat geçerlidir.\n\nTeşekkürler,\nHumanitarian Blog Ekibi", 'humanitarianblog'),
        $user->display_name,
        $verify_url
    );

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: Humanitarian Blog <noreply@yourdomain.com>'
    ];

    return wp_mail($user->user_email, $subject, nl2br($message), $headers);
}

// Token doğrula
function humanitarian_verify_email_token() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'verify_email') {
        return;
    }

    $user_id = absint($_GET['user_id'] ?? 0);
    $token = sanitize_text_field($_GET['token'] ?? '');

    if (!$user_id || !$token) {
        wp_redirect(home_url('/login/?verify=invalid'));
        exit;
    }

    $stored_token = get_user_meta($user_id, '_email_verification_token', true);
    $expiry = get_user_meta($user_id, '_email_verification_expiry', true);

    // Token eşleşmesi kontrolü
    if ($token !== $stored_token) {
        wp_redirect(home_url('/login/?verify=invalid'));
        exit;
    }

    // Süre kontrolü
    if (time() > $expiry) {
        wp_redirect(home_url('/login/?verify=expired'));
        exit;
    }

    // Başarılı - hesabı aktifleştir
    update_user_meta($user_id, '_email_verified', 1);
    delete_user_meta($user_id, '_email_verification_token');
    delete_user_meta($user_id, '_email_verification_expiry');

    wp_redirect(home_url('/login/?verify=success'));
    exit;
}
add_action('template_redirect', 'humanitarian_verify_email_token');

// Doğrulanmamış kullanıcı girişini engelle
function humanitarian_check_email_verified($user, $password) {
    if (is_wp_error($user)) {
        return $user;
    }

    $is_verified = get_user_meta($user->ID, '_email_verified', true);

    // Admin ve editörler hariç
    if (in_array('administrator', $user->roles) || in_array('editor', $user->roles)) {
        return $user;
    }

    if ($is_verified != 1) {
        return new WP_Error(
            'email_not_verified',
            __('Lütfen email adresinizi doğrulayın. Doğrulama emaili gönderildi.', 'humanitarianblog')
        );
    }

    return $user;
}
add_filter('wp_authenticate_user', 'humanitarian_check_email_verified', 10, 2);

// Doğrulama emailini yeniden gönder
function humanitarian_resend_verification() {
    if (!isset($_POST['resend_verification'])) {
        return;
    }

    check_admin_referer('resend_verification_nonce');

    $email = sanitize_email($_POST['user_email']);
    $user = get_user_by('email', $email);

    if ($user) {
        humanitarian_send_verification_email($user->ID);
    }

    wp_redirect(home_url('/login/?verify=resent'));
    exit;
}
add_action('admin_post_nopriv_resend_verification', 'humanitarian_resend_verification');
```

#### B) `wp-config.php` SMTP Ayarları (SiteGround için)
```php
// SiteGround SMTP Configuration
define('SMTP_HOST', 'mail.yourdomain.com');
define('SMTP_PORT', 465);
define('SMTP_USER', 'noreply@yourdomain.com');
define('SMTP_PASS', 'your_email_password');
define('SMTP_SECURE', 'ssl');
define('SMTP_FROM', 'noreply@yourdomain.com');
define('SMTP_FROM_NAME', 'Humanitarian Blog');
```

#### C) `functions.php` SMTP Hook
```php
// SMTP Configuration for SiteGround
add_action('phpmailer_init', 'humanitarian_smtp_config');
function humanitarian_smtp_config($phpmailer) {
    if (defined('SMTP_HOST')) {
        $phpmailer->isSMTP();
        $phpmailer->Host = SMTP_HOST;
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = SMTP_USER;
        $phpmailer->Password = SMTP_PASS;
        $phpmailer->SMTPSecure = SMTP_SECURE;
        $phpmailer->Port = SMTP_PORT;
        $phpmailer->From = SMTP_FROM;
        $phpmailer->FromName = SMTP_FROM_NAME;
    }
}
```

---

## 2. MODERN USER PANEL (page-my-account.php)

### Mevcut Durum
- Tabs: Bookmarks, Edit Profile, Settings
- Basit tasarım

### Güncellenecek Özellikler
- Dashboard sidebar layout
- Modern card-based içerik
- Avatar upload
- Activity feed
- Email verification status gösterimi

### CSS Güncellemeleri
```css
/* Modern Account Dashboard */
.account-dashboard {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.account-sidebar {
    background: var(--surface-elevated);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.account-nav-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1rem;
    border-radius: 8px;
    transition: all 0.2s;
}

.account-nav-item:hover,
.account-nav-item.active {
    background: var(--primary-light);
    color: var(--primary);
}

.account-content {
    background: var(--surface-elevated);
    border-radius: 12px;
    padding: 2rem;
}
```

---

## 3. POST EMAIL NOTIFICATIONS

### Sistem Tasarımı

```
┌─────────────────────────────────────────────────────────────┐
│ Post Editor - Email Notification Meta Box                   │
├─────────────────────────────────────────────────────────────┤
│ 📧 Email Bildirimi                                          │
│                                                              │
│ ☑️ Bu makaleyi abonelere gönder                             │
│                                                              │
│ Hedef Kitle:                                                │
│ ○ Tüm aboneler                                              │
│ ○ Belirli bölge: [Dropdown: Middle East, Africa...]        │
│ ○ Belirli kategori: [Dropdown: News, Opinion...]           │
│                                                              │
│ Önizleme:                                                    │
│ [Subject: Yeni Makale: {title}]                             │
│ [Gönderilecek: ~1,250 abone]                                │
└─────────────────────────────────────────────────────────────┘
```

### Dosya: `inc/post-notifications.php` (YENİ)
```php
<?php
/**
 * Post Email Notification System
 */

// Meta box ekle
function humanitarian_notification_meta_box() {
    add_meta_box(
        'post_notification',
        __('📧 Email Bildirimi', 'humanitarianblog'),
        'humanitarian_notification_meta_box_html',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'humanitarian_notification_meta_box');

// Meta box HTML
function humanitarian_notification_meta_box_html($post) {
    wp_nonce_field('notification_meta_box', 'notification_nonce');
    $send_notification = get_post_meta($post->ID, '_send_notification', true);
    $notification_target = get_post_meta($post->ID, '_notification_target', true) ?: 'all';

    // Abone sayısı
    global $wpdb;
    $subscriber_count = $wpdb->get_var(
        "SELECT COUNT(*) FROM {$wpdb->prefix}humanitarian_newsletters"
    );
    ?>
    <p>
        <label>
            <input type="checkbox" name="send_notification" value="1"
                <?php checked($send_notification, 1); ?>>
            <?php _e('Bu makaleyi abonelere gönder', 'humanitarianblog'); ?>
        </label>
    </p>

    <p>
        <label><?php _e('Hedef Kitle:', 'humanitarianblog'); ?></label><br>
        <select name="notification_target" style="width:100%;">
            <option value="all" <?php selected($notification_target, 'all'); ?>>
                <?php _e('Tüm aboneler', 'humanitarianblog'); ?>
            </option>
            <!-- Region options -->
            <?php
            $regions = get_terms(['taxonomy' => 'region', 'hide_empty' => false]);
            foreach ($regions as $region) {
                printf(
                    '<option value="region_%s" %s>%s: %s</option>',
                    $region->term_id,
                    selected($notification_target, 'region_' . $region->term_id, false),
                    __('Bölge', 'humanitarianblog'),
                    $region->name
                );
            }
            ?>
        </select>
    </p>

    <p class="description">
        <?php printf(__('Gönderilecek: ~%d abone', 'humanitarianblog'), $subscriber_count); ?>
    </p>
    <?php
}

// Post yayınlandığında email gönder
function humanitarian_send_post_notification($new_status, $old_status, $post) {
    // Sadece yeni yayınlanan postlar
    if ($new_status !== 'publish' || $old_status === 'publish') {
        return;
    }

    if ($post->post_type !== 'post') {
        return;
    }

    // Notification checkbox kontrolü
    $send_notification = get_post_meta($post->ID, '_send_notification', true);
    if (!$send_notification) {
        return;
    }

    // Daha önce gönderilmiş mi?
    if (get_post_meta($post->ID, '_notification_sent', true)) {
        return;
    }

    // Aboneleri al
    global $wpdb;
    $subscribers = $wpdb->get_col(
        "SELECT email FROM {$wpdb->prefix}humanitarian_newsletters"
    );

    if (empty($subscribers)) {
        return;
    }

    // Email içeriği
    $subject = sprintf(__('Yeni Makale: %s', 'humanitarianblog'), $post->post_title);

    $excerpt = wp_trim_words($post->post_content, 50);
    $permalink = get_permalink($post->ID);
    $thumbnail = get_the_post_thumbnail_url($post->ID, 'medium');

    $message = humanitarian_get_email_template([
        'title' => $post->post_title,
        'excerpt' => $excerpt,
        'permalink' => $permalink,
        'thumbnail' => $thumbnail
    ]);

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: Humanitarian Blog <noreply@yourdomain.com>'
    ];

    // Batch gönderim (her seferde 50)
    $batches = array_chunk($subscribers, 50);
    foreach ($batches as $batch) {
        foreach ($batch as $email) {
            wp_mail($email, $subject, $message, $headers);
        }
        sleep(1); // Rate limiting
    }

    // İşaretleme
    update_post_meta($post->ID, '_notification_sent', 1);
    update_post_meta($post->ID, '_notification_sent_date', current_time('mysql'));
}
add_action('transition_post_status', 'humanitarian_send_post_notification', 10, 3);
```

---

## 4. POSTS → ARTICLES RENAME

### Dosya: `inc/admin-simplify.php` (GÜNCELLEME)

```php
// Posts → Articles rename
add_filter('register_post_type_args', 'humanitarian_rename_posts_to_articles', 10, 2);
function humanitarian_rename_posts_to_articles($args, $post_type) {
    if ($post_type !== 'post') {
        return $args;
    }

    $args['labels'] = [
        'name'               => __('Articles', 'humanitarianblog'),
        'singular_name'      => __('Article', 'humanitarianblog'),
        'add_new'            => __('Add New', 'humanitarianblog'),
        'add_new_item'       => __('Add New Article', 'humanitarianblog'),
        'edit_item'          => __('Edit Article', 'humanitarianblog'),
        'new_item'           => __('New Article', 'humanitarianblog'),
        'view_item'          => __('View Article', 'humanitarianblog'),
        'view_items'         => __('View Articles', 'humanitarianblog'),
        'search_items'       => __('Search Articles', 'humanitarianblog'),
        'not_found'          => __('No articles found', 'humanitarianblog'),
        'not_found_in_trash' => __('No articles found in Trash', 'humanitarianblog'),
        'all_items'          => __('All Articles', 'humanitarianblog'),
        'archives'           => __('Article Archives', 'humanitarianblog'),
        'attributes'         => __('Article Attributes', 'humanitarianblog'),
        'menu_name'          => __('Articles', 'humanitarianblog'),
    ];

    $args['menu_icon'] = 'dashicons-media-document';

    return $args;
}
```

---

## 5. MY ARTICLES FILTER BUTTON

### Dosya: `inc/admin-simplify.php` (GÜNCELLEME)

```php
// "My Articles" filter button
add_action('restrict_manage_posts', 'humanitarian_my_articles_button');
function humanitarian_my_articles_button($post_type) {
    if ($post_type !== 'post') {
        return;
    }

    $current_user_id = get_current_user_id();
    $is_filtered = isset($_GET['author']) && $_GET['author'] == $current_user_id;

    $all_url = admin_url('edit.php?post_type=post');
    $my_url = add_query_arg('author', $current_user_id, $all_url);

    ?>
    <div class="alignleft actions" style="margin-right: 8px;">
        <a href="<?php echo esc_url($all_url); ?>"
           class="button <?php echo !$is_filtered ? 'button-primary' : ''; ?>">
            <?php _e('All Articles', 'humanitarianblog'); ?>
        </a>
        <a href="<?php echo esc_url($my_url); ?>"
           class="button <?php echo $is_filtered ? 'button-primary' : ''; ?>">
            📝 <?php _e('My Articles', 'humanitarianblog'); ?>
        </a>
    </div>
    <?php
}
```

---

## 6. MODERN ADMIN PANEL

### Dosya: `assets/css/admin-modern.css` (YENİ)

```css
/* Modern Admin Panel for Non-Technical Editors */

/* ===== TYPOGRAPHY ===== */
#wpcontent {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* ===== POST LIST TABLE ===== */
.wp-list-table {
    border-spacing: 0 8px !important;
    border-collapse: separate !important;
}

.wp-list-table thead th {
    background: transparent;
    border-bottom: 2px solid #e0e0e0;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
    color: #666;
}

.wp-list-table tbody tr {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    transition: all 0.2s ease;
}

.wp-list-table tbody tr:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

.wp-list-table td {
    padding: 16px 12px !important;
    vertical-align: middle;
}

/* ===== STATUS BADGES ===== */
.post-state {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.post-state-draft {
    background: #fff3cd;
    color: #856404;
}

.post-state-pending {
    background: #cce5ff;
    color: #004085;
}

.post-state-publish {
    background: #d4edda;
    color: #155724;
}

/* ===== ACTION BUTTONS ===== */
.row-actions {
    display: flex;
    gap: 8px;
    padding-top: 8px !important;
}

.row-actions a {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background: #f0f0f0;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
    transition: all 0.2s;
}

.row-actions a:hover {
    background: #0073aa;
    color: #fff !important;
}

.row-actions .trash a:hover {
    background: #dc3545;
}

/* ===== THUMBNAIL COLUMN ===== */
.column-thumbnail {
    width: 80px;
}

.column-thumbnail img {
    width: 60px;
    height: 45px;
    object-fit: cover;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* ===== FILTER BUTTONS ===== */
.tablenav .actions .button {
    border-radius: 6px;
    font-weight: 500;
}

.tablenav .actions .button-primary {
    background: linear-gradient(135deg, #2271b1, #135e96);
    border: none;
}

/* ===== GUTENBERG EDITOR ===== */
.edit-post-header {
    background: #fff;
    border-bottom: 1px solid #e0e0e0;
}

.editor-post-title__input {
    font-size: 2.5rem !important;
    font-weight: 700 !important;
}

/* ===== PUBLISH PANEL ===== */
.editor-post-publish-panel {
    background: #f8f9fa;
}

.editor-post-publish-button {
    font-size: 14px !important;
    padding: 12px 24px !important;
    border-radius: 8px !important;
}

/* ===== META BOXES ===== */
.postbox {
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    box-shadow: none;
}

.postbox .hndle {
    border-bottom: 1px solid #e0e0e0;
    padding: 12px 16px;
}

/* ===== DASHBOARD CARDS ===== */
.welcome-panel {
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

/* ===== MY ARTICLES BUTTON ===== */
.my-articles-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #2271b1, #135e96);
    color: #fff;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.my-articles-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(34, 113, 177, 0.3);
    color: #fff;
}
```

---

## 7. AUTHOR PAGE REDESIGN

### Dosya: `author.php` (GÜNCELLEME)

```php
<?php
/**
 * Modern Author Archive Template
 * Cover image + circular avatar + social links + articles grid
 */

get_header();

$author = get_queried_object();
$author_id = $author->ID;

// Author meta
$cover_image = get_user_meta($author_id, 'cover_image', true);
$user_title = get_user_meta($author_id, 'user_title', true);
$location = get_user_meta($author_id, 'location', true);
$website = get_user_meta($author_id, 'website', true);
$twitter = get_user_meta($author_id, 'twitter', true);
$linkedin = get_user_meta($author_id, 'linkedin', true);
$facebook = get_user_meta($author_id, 'facebook', true);
$instagram = get_user_meta($author_id, 'instagram', true);

// Stats
$post_count = count_user_posts($author_id, 'post', true);
$total_views = humanitarian_get_author_total_views($author_id);
?>

<main class="author-page">
    <!-- Cover Section -->
    <section class="author-hero">
        <?php if ($cover_image): ?>
            <div class="author-cover" style="background-image: url('<?php echo esc_url($cover_image); ?>')"></div>
        <?php else: ?>
            <div class="author-cover author-cover--gradient"></div>
        <?php endif; ?>

        <div class="author-hero-content container">
            <div class="author-avatar-wrapper">
                <?php echo get_avatar($author_id, 150, '', $author->display_name, ['class' => 'author-avatar']); ?>
            </div>

            <h1 class="author-name"><?php echo esc_html($author->display_name); ?></h1>

            <?php if ($user_title): ?>
                <p class="author-title"><?php echo esc_html($user_title); ?></p>
            <?php endif; ?>

            <?php if ($author->description): ?>
                <p class="author-bio"><?php echo esc_html($author->description); ?></p>
            <?php endif; ?>

            <!-- Stats -->
            <div class="author-stats">
                <?php if ($location): ?>
                    <span class="stat-item">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                        </svg>
                        <?php echo esc_html($location); ?>
                    </span>
                <?php endif; ?>

                <span class="stat-item">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                        <path d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                    <?php printf(_n('%d Article', '%d Articles', $post_count, 'humanitarianblog'), $post_count); ?>
                </span>

                <?php if ($total_views > 0): ?>
                    <span class="stat-item">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                        <?php echo number_format($total_views); ?> <?php _e('Views', 'humanitarianblog'); ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Social Links -->
            <div class="author-social">
                <?php if ($twitter): ?>
                    <a href="https://twitter.com/<?php echo esc_attr($twitter); ?>" target="_blank" rel="noopener" class="social-link twitter" title="Twitter">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
                        </svg>
                    </a>
                <?php endif; ?>

                <?php if ($linkedin): ?>
                    <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener" class="social-link linkedin" title="LinkedIn">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                        </svg>
                    </a>
                <?php endif; ?>

                <?php if ($facebook): ?>
                    <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener" class="social-link facebook" title="Facebook">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                        </svg>
                    </a>
                <?php endif; ?>

                <?php if ($instagram): ?>
                    <a href="https://instagram.com/<?php echo esc_attr($instagram); ?>" target="_blank" rel="noopener" class="social-link instagram" title="Instagram">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                        </svg>
                    </a>
                <?php endif; ?>

                <?php if ($website): ?>
                    <a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener" class="social-link website" title="Website">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z"/>
                        </svg>
                    </a>
                <?php endif; ?>

                <a href="mailto:<?php echo esc_attr($author->user_email); ?>" class="social-link email" title="Email">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Articles Section -->
    <section class="author-articles section">
        <div class="container">
            <h2 class="section-title">
                <?php printf(__('Articles by %s', 'humanitarianblog'), $author->display_name); ?>
            </h2>

            <div class="articles-grid">
                <?php if (have_posts()): ?>
                    <?php while (have_posts()): the_post(); ?>
                        <?php get_template_part('template-parts/content-card'); ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-articles"><?php _e('No articles found.', 'humanitarianblog'); ?></p>
                <?php endif; ?>
            </div>

            <?php the_posts_pagination([
                'mid_size' => 2,
                'prev_text' => '← ' . __('Previous', 'humanitarianblog'),
                'next_text' => __('Next', 'humanitarianblog') . ' →'
            ]); ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
```

### Author Page CSS (style.css'e eklenecek)
```css
/* ===== AUTHOR PAGE ===== */
.author-hero {
    position: relative;
    padding-top: 200px;
    padding-bottom: 3rem;
    margin-bottom: 3rem;
}

.author-cover {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 250px;
    background-size: cover;
    background-position: center;
}

.author-cover--gradient {
    background: linear-gradient(135deg, #1a365d 0%, #2c5282 50%, #3182ce 100%);
}

.author-cover::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(to top, var(--surface-primary), transparent);
}

.author-hero-content {
    position: relative;
    text-align: center;
}

.author-avatar-wrapper {
    margin-bottom: 1.5rem;
}

.author-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 4px solid #fff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    object-fit: cover;
}

.author-name {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.author-title {
    font-size: 1.125rem;
    color: var(--primary);
    font-weight: 500;
    margin-bottom: 1rem;
}

.author-bio {
    max-width: 600px;
    margin: 0 auto 1.5rem;
    color: var(--text-secondary);
    line-height: 1.7;
}

.author-stats {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9375rem;
}

.stat-item svg {
    opacity: 0.7;
}

.author-social {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--surface-secondary);
    color: var(--text-secondary);
    transition: all 0.2s;
}

.social-link:hover {
    transform: translateY(-2px);
}

.social-link.twitter:hover { background: #1da1f2; color: #fff; }
.social-link.linkedin:hover { background: #0077b5; color: #fff; }
.social-link.facebook:hover { background: #1877f2; color: #fff; }
.social-link.instagram:hover { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); color: #fff; }
.social-link.website:hover { background: var(--primary); color: #fff; }
.social-link.email:hover { background: #ea4335; color: #fff; }

.author-articles .section-title {
    margin-bottom: 2rem;
}

.author-articles .articles-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
}

@media (max-width: 992px) {
    .author-articles .articles-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .author-hero {
        padding-top: 150px;
    }

    .author-cover {
        height: 180px;
    }

    .author-avatar {
        width: 120px;
        height: 120px;
    }

    .author-name {
        font-size: 1.75rem;
    }

    .author-stats {
        gap: 1rem;
    }

    .author-articles .articles-grid {
        grid-template-columns: 1fr;
    }
}
```

---

## 8. MULTI-LANGUAGE SYSTEM (OTOMATİK ÇEVİRİ)

### Seçilen Yaklaşım: Polylang + DeepL API

```
YAZI AKIŞI:
┌─────────────────────────────────────────────────────────────┐
│ 1. Fransız yazar makaleyi Fransızca yazar                   │
│ 2. "Yayınla" butonuna basar                                 │
│ 3. Sistem otomatik olarak:                                  │
│    - Makale İngilizce'ye çevrilir (DeepL API)              │
│    - Makale Arapça'ya çevrilir (DeepL API)                 │
│    - Her dil için ayrı post oluşturulur                    │
│    - Polylang ile dil ilişkisi kurulur                     │
│ 4. Editör çevirileri gözden geçirebilir (opsiyonel)        │
└─────────────────────────────────────────────────────────────┘
```

### Gerekli Kurulumlar

1. **Polylang Plugin** (Ücretsiz)
   - Çoklu dil altyapısı
   - URL yapısı (site.com/en/, site.com/ar/, site.com/fr/)
   - Admin panel dil yönetimi

2. **DeepL API** (Ücretli - ~$25/ay)
   - Yüksek kalite çeviri
   - API key gerekli
   - Arapça dahil 29 dil desteği

### Dosya: `inc/auto-translate.php` (YENİ)

```php
<?php
/**
 * Automatic Translation System
 * Polylang + DeepL API Integration
 */

// DeepL API Configuration
define('DEEPL_API_KEY', 'your-api-key-here');
define('DEEPL_API_URL', 'https://api-free.deepl.com/v2/translate');

// Desteklenen diller
define('SUPPORTED_LANGUAGES', ['en', 'ar', 'fr']);
define('DEFAULT_LANGUAGE', 'en');

/**
 * DeepL ile metin çevir
 */
function humanitarian_translate_text($text, $target_lang, $source_lang = null) {
    if (empty($text) || empty(DEEPL_API_KEY)) {
        return $text;
    }

    // DeepL dil kodları
    $deepl_codes = [
        'en' => 'EN',
        'ar' => 'AR',
        'fr' => 'FR'
    ];

    $params = [
        'auth_key' => DEEPL_API_KEY,
        'text' => $text,
        'target_lang' => $deepl_codes[$target_lang] ?? 'EN'
    ];

    if ($source_lang) {
        $params['source_lang'] = $deepl_codes[$source_lang] ?? null;
    }

    $response = wp_remote_post(DEEPL_API_URL, [
        'body' => $params,
        'timeout' => 30
    ]);

    if (is_wp_error($response)) {
        error_log('DeepL API Error: ' . $response->get_error_message());
        return $text;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($body['translations'][0]['text'])) {
        return $body['translations'][0]['text'];
    }

    return $text;
}

/**
 * Post yayınlandığında otomatik çeviri oluştur
 */
function humanitarian_auto_translate_post($post_id, $post, $update) {
    // Sadece yeni postlar
    if ($update) {
        return;
    }

    // Post tipi kontrolü
    if ($post->post_type !== 'post') {
        return;
    }

    // Polylang aktif mi?
    if (!function_exists('pll_get_post_language')) {
        return;
    }

    // Mevcut dil
    $current_lang = pll_get_post_language($post_id);

    if (!$current_lang) {
        $current_lang = pll_default_language();
    }

    // Diğer dillere çevir
    foreach (SUPPORTED_LANGUAGES as $target_lang) {
        if ($target_lang === $current_lang) {
            continue;
        }

        // Çevrilmiş post zaten var mı?
        $translations = pll_get_post_translations($post_id);
        if (isset($translations[$target_lang])) {
            continue;
        }

        // Çeviri oluştur
        humanitarian_create_translation($post_id, $post, $current_lang, $target_lang);
    }
}
add_action('wp_insert_post', 'humanitarian_auto_translate_post', 10, 3);

/**
 * Çevrilmiş post oluştur
 */
function humanitarian_create_translation($original_id, $original_post, $source_lang, $target_lang) {
    // Başlık çevir
    $translated_title = humanitarian_translate_text(
        $original_post->post_title,
        $target_lang,
        $source_lang
    );

    // İçerik çevir
    $translated_content = humanitarian_translate_text(
        $original_post->post_content,
        $target_lang,
        $source_lang
    );

    // Özet çevir
    $translated_excerpt = '';
    if ($original_post->post_excerpt) {
        $translated_excerpt = humanitarian_translate_text(
            $original_post->post_excerpt,
            $target_lang,
            $source_lang
        );
    }

    // Yeni post oluştur
    $translated_post = [
        'post_title'   => $translated_title,
        'post_content' => $translated_content,
        'post_excerpt' => $translated_excerpt,
        'post_status'  => 'draft', // İnceleme için draft
        'post_type'    => 'post',
        'post_author'  => $original_post->post_author,
    ];

    $translated_id = wp_insert_post($translated_post);

    if ($translated_id && !is_wp_error($translated_id)) {
        // Polylang dil ayarla
        pll_set_post_language($translated_id, $target_lang);

        // Çeviri ilişkisi kur
        $translations = pll_get_post_translations($original_id);
        $translations[$target_lang] = $translated_id;
        $translations[$source_lang] = $original_id;
        pll_save_post_translations($translations);

        // Featured image kopyala
        $thumbnail_id = get_post_thumbnail_id($original_id);
        if ($thumbnail_id) {
            set_post_thumbnail($translated_id, $thumbnail_id);
        }

        // Kategorileri çevir/kopyala
        $categories = wp_get_post_categories($original_id);
        foreach ($categories as $cat_id) {
            $translated_cat = pll_get_term($cat_id, $target_lang);
            if ($translated_cat) {
                wp_set_post_categories($translated_id, [$translated_cat], true);
            }
        }

        // Custom meta kopyala
        $meta_keys = ['_subtitle', '_is_featured', '_is_editors_pick', '_photo_caption'];
        foreach ($meta_keys as $key) {
            $value = get_post_meta($original_id, $key, true);
            if ($value) {
                // Metin meta'ları çevir
                if ($key === '_subtitle' || $key === '_photo_caption') {
                    $value = humanitarian_translate_text($value, $target_lang, $source_lang);
                }
                update_post_meta($translated_id, $key, $value);
            }
        }

        // Çeviri flag'i
        update_post_meta($translated_id, '_auto_translated', 1);
        update_post_meta($translated_id, '_translation_source', $original_id);
        update_post_meta($translated_id, '_translation_date', current_time('mysql'));

        // Admin'e bildirim
        humanitarian_notify_translation_ready($translated_id, $target_lang);
    }

    return $translated_id;
}

/**
 * Çeviri hazır bildirimi
 */
function humanitarian_notify_translation_ready($post_id, $lang) {
    $admin_email = get_option('admin_email');
    $post = get_post($post_id);
    $edit_link = get_edit_post_link($post_id);

    $lang_names = [
        'en' => 'English',
        'ar' => 'Arabic',
        'fr' => 'French'
    ];

    $subject = sprintf(
        '[%s] New %s translation ready for review',
        get_bloginfo('name'),
        $lang_names[$lang]
    );

    $message = sprintf(
        "A new %s translation has been automatically created:\n\n" .
        "Title: %s\n" .
        "Edit: %s\n\n" .
        "Please review and publish when ready.",
        $lang_names[$lang],
        $post->post_title,
        $edit_link
    );

    wp_mail($admin_email, $subject, $message);
}

/**
 * Header'a dil değiştirici ekle
 */
function humanitarian_language_switcher() {
    if (!function_exists('pll_the_languages')) {
        return;
    }

    $languages = pll_the_languages([
        'raw' => 1,
        'hide_if_empty' => 0
    ]);

    if (empty($languages)) {
        return;
    }

    $current_lang = pll_current_language('slug');

    ?>
    <div class="language-switcher">
        <button class="lang-toggle" aria-label="<?php _e('Change language', 'humanitarianblog'); ?>">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z"/>
            </svg>
            <span><?php echo strtoupper($current_lang); ?></span>
            <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                <path d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
            </svg>
        </button>

        <div class="lang-dropdown">
            <?php foreach ($languages as $lang): ?>
                <a href="<?php echo esc_url($lang['url']); ?>"
                   class="lang-option <?php echo $lang['current_lang'] ? 'active' : ''; ?>"
                   lang="<?php echo esc_attr($lang['locale']); ?>"
                   hreflang="<?php echo esc_attr($lang['slug']); ?>">
                    <span class="lang-flag"><?php echo humanitarian_get_flag_emoji($lang['slug']); ?></span>
                    <span class="lang-name"><?php echo esc_html($lang['name']); ?></span>
                    <?php if ($lang['current_lang']): ?>
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * Dil bayrağı emoji
 */
function humanitarian_get_flag_emoji($lang) {
    $flags = [
        'en' => '🇬🇧',
        'ar' => '🇸🇦',
        'fr' => '🇫🇷'
    ];
    return $flags[$lang] ?? '🌐';
}

/**
 * Admin'de çeviri durumu göster
 */
function humanitarian_translation_status_column($columns) {
    $columns['translations'] = __('Translations', 'humanitarianblog');
    return $columns;
}
add_filter('manage_posts_columns', 'humanitarian_translation_status_column');

function humanitarian_translation_status_content($column, $post_id) {
    if ($column !== 'translations') {
        return;
    }

    if (!function_exists('pll_get_post_translations')) {
        echo '—';
        return;
    }

    $translations = pll_get_post_translations($post_id);
    $current_lang = pll_get_post_language($post_id);

    $output = [];
    foreach (SUPPORTED_LANGUAGES as $lang) {
        if ($lang === $current_lang) {
            continue;
        }

        if (isset($translations[$lang])) {
            $status = get_post_status($translations[$lang]);
            $icon = $status === 'publish' ? '✅' : '⏳';
            $output[] = sprintf('%s %s', $icon, strtoupper($lang));
        } else {
            $output[] = sprintf('❌ %s', strtoupper($lang));
        }
    }

    echo implode(' | ', $output);
}
add_action('manage_posts_custom_column', 'humanitarian_translation_status_content', 10, 2);
```

### Language Switcher CSS
```css
/* ===== LANGUAGE SWITCHER ===== */
.language-switcher {
    position: relative;
}

.lang-toggle {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--surface-secondary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.lang-toggle:hover {
    background: var(--surface-tertiary);
}

.lang-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 0.5rem;
    min-width: 180px;
    background: var(--surface-elevated);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s;
    z-index: 1000;
}

.language-switcher:hover .lang-dropdown,
.language-switcher:focus-within .lang-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.lang-option {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    text-decoration: none;
    color: var(--text-primary);
    transition: background 0.2s;
}

.lang-option:first-child {
    border-radius: 12px 12px 0 0;
}

.lang-option:last-child {
    border-radius: 0 0 12px 12px;
}

.lang-option:hover {
    background: var(--surface-secondary);
}

.lang-option.active {
    background: var(--primary-light);
    color: var(--primary);
}

.lang-flag {
    font-size: 1.25rem;
}

.lang-name {
    flex: 1;
}

/* RTL Support */
[dir="rtl"] .lang-dropdown {
    right: auto;
    left: 0;
}
```

---

## KURULUM ADIMLARI

### Aşama 1: Temel Kurulum
```bash
1. SiteGround'da email hesabı oluştur (noreply@domain.com)
2. wp-config.php'ye SMTP ayarlarını ekle
3. Polylang plugin kur ve aktif et
4. DeepL API hesabı oluştur ve API key al
```

### Aşama 2: Dosya Oluşturma
```
1. inc/email-verification.php oluştur
2. inc/post-notifications.php oluştur
3. inc/auto-translate.php oluştur
4. assets/css/admin-modern.css oluştur
5. author.php güncelle
6. inc/admin-simplify.php güncelle
```

### Aşama 3: functions.php Güncellemeleri
```php
// Yeni dosyaları dahil et
require_once HUMANITARIAN_THEME_DIR . '/inc/email-verification.php';
require_once HUMANITARIAN_THEME_DIR . '/inc/post-notifications.php';
require_once HUMANITARIAN_THEME_DIR . '/inc/auto-translate.php';

// Admin CSS yükle
add_action('admin_enqueue_scripts', function() {
    wp_enqueue_style('admin-modern', HUMANITARIAN_THEME_URI . '/assets/css/admin-modern.css');
});
```

### Aşama 4: Polylang Yapılandırma
```
1. Languages > Settings > URL modifications: "The language is set from the directory name"
2. Dilleri ekle: English (en), Arabic (ar), French (fr)
3. Varsayılan dil: English
4. String translations: Tüm theme stringlerini çevir
```

### Aşama 5: Test
```
1. Yeni kullanıcı kaydı test et (email doğrulama)
2. Yeni makale yayınla (otomatik çeviri)
3. Dil değiştirici test et
4. Admin panel görünümü kontrol et
5. Author sayfası kontrol et
```

---

## MALİYET ÖZETİ

| Hizmet | Aylık Maliyet |
|--------|---------------|
| SiteGround Email | Dahil (hosting paketi) |
| Polylang | Ücretsiz |
| DeepL API Free | Ücretsiz (500,000 karakter/ay) |
| DeepL API Pro | ~$25/ay (sınırsız) |

**Başlangıç önerisi:** DeepL Free ile başlayın, ihtiyaç arttıkça Pro'ya geçin.

---

## SONRAKİ ADIMLAR

Bu planı onaylıyor musunuz? Onaylandığında implementasyona başlayabilirim.

**Öncelik sırası:**
1. Email Verification (hemen gerekli)
2. Posts → Articles + My Articles Button (hızlı)
3. Modern Admin CSS (orta)
4. Author Page Redesign (orta)
5. Multi-Language System (en kapsamlı)
6. Post Email Notifications (son)
