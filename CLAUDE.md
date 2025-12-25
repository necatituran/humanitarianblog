# HumanitarianBlog - Proje Dokümantasyonu

## SSH Bağlantısı (SiteGround Production)

### Bağlantı Bilgileri
```
Host: siteground
Hostname: es27.siteground.eu
User: u2152-rcvjilujpo4b
Port: 18765
Key: ~/.ssh/siteground_key
```

### Hızlı SSH Komutları
```bash
# SSH bağlantısı
ssh siteground

# WordPress dizinine git
cd www/humanitarianblog.org/public_html

# WP-CLI komutları
wp option get siteurl
wp user list
wp plugin list
wp cache flush
wp sg purge  # SiteGround cache temizleme
wp rewrite flush
```

### Dosya Transferi (SCP)
```bash
# Local'den production'a dosya yükle
scp dosya.php siteground:www/humanitarianblog.org/public_html/wp-content/themes/humanitarian-theme-new/

# Tema klasörüne yükle
scp inc/simple-language.php siteground:www/humanitarianblog.org/public_html/wp-content/themes/humanitarian-theme-new/inc/

# Production'dan local'e indir
scp siteground:www/humanitarianblog.org/public_html/dosya.php ./
```

### Production Dizinleri
```
WordPress root:  www/humanitarianblog.org/public_html/
Tema:            www/humanitarianblog.org/public_html/wp-content/themes/humanitarian-theme-new/
Uploads:         www/humanitarianblog.org/public_html/wp-content/uploads/
```

### Production Kullanıcıları
| Kullanıcı | Email | Rol | Şifre |
|-----------|-------|-----|-------|
| admin | info@humanitarianblog.org | Administrator | humanitarian2025 |
| SamAlsarori | samalsarore@gmail.com | Administrator | humanitarian2025 |
| Author1 | author1_sampleText@gmail.com | Author | humanitarian2025 |

---

## Tech Stack

| Teknoloji | Versiyon/Detay |
|-----------|----------------|
| WordPress | 6.x |
| PHP | 7.4+ |
| Tema | humanitarian-theme-new (custom) |
| Database | MySQL |
| Production | SiteGround (es27.siteground.eu) |
| Local Dev | Local by Flywheel |
| Google Fonts | DM Sans, Playfair Display, Merriweather |

## Dosya Yapısı

```
wp-content/
├── themes/
│   └── humanitarian-theme-new/     # AKTİF TEMA
│       ├── assets/
│       │   ├── css/                # Stil dosyaları
│       │   ├── js/                 # JavaScript dosyaları
│       │   └── images/             # Tema görselleri
│       ├── inc/                    # PHP modülleri
│       │   ├── simple-language.php # Çoklu dil sistemi
│       │   ├── ajax-handlers.php   # AJAX işlemleri
│       │   ├── custom-taxonomies.php
│       │   ├── admin-simplify.php
│       │   └── ...
│       ├── template-parts/
│       │   ├── cards/
│       │   ├── hero/
│       │   ├── sections/
│       │   └── content/
│       ├── languages/
│       └── translations/
├── plugins/
│   └── sg-cachepress/              # SiteGround cache (aktif)
└── uploads/
```

## Renk Paleti

### Ana Renkler (Navy Blue Tema)
| Değişken | Hex Kodu | Kullanım |
|----------|----------|----------|
| `--color-primary` | `#1a2634` | Ana marka rengi (Navy) |
| `--color-primary-light` | `#2a3a4d` | Açık navy |
| `--color-primary-dark` | `#0f1820` | Koyu navy |
| `--color-paper` | `#FAF8F4` | Ana arka plan |
| `--color-cream` | `#f4f1ea` | İkincil arka plan |

## Fontlar

| Değişken | Font Ailesi | Kullanım |
|----------|-------------|----------|
| `--font-serif` | Playfair Display | Başlıklar |
| `--font-body` | Merriweather | Gövde metni |
| `--font-sans` | DM Sans | UI elementleri |

---

## ÖNEMLİ NOTLAR

### Çoklu Dil Sistemi
- **Polylang KULLANILMIYOR** - Kendi sistemimiz var
- `simple-language.php` ile yönetiliyor
- `language` taxonomy kullanılıyor
- Diller: English (en), Arabic (ar), French (fr)
- URL formatı: `?lang=en`, `?lang=ar`, `?lang=fr`
- Arapça RTL destekli

### Cache Temizleme (Production)
```bash
ssh siteground
cd www/humanitarianblog.org/public_html
wp sg purge && wp cache flush
```

### Database Prefix
- Production: `yiz_`
- Local: `wp_`

### Devre Dışı Özellikler
```php
// auto-translate.php - sonsuz döngü sorunu
// deepl-translation.php - güncelleme gerekiyor
```

---

## URLs

- **Production:** https://humanitarianblog.org
- **Admin:** https://humanitarianblog.org/wp-admin/
- **Local:** humanitarian-blog.local
