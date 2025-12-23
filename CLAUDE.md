# HumanitarianBlog - Proje Dokümantasyonu

## Tech Stack

| Teknoloji | Versiyon/Detay |
|-----------|----------------|
| WordPress | 6.x |
| PHP | 7.4+ |
| Tema | humanitarian-theme-new (custom) |
| Database | MySQL (Local by Flywheel) |
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
│       │   ├── email-verification.php
│       │   ├── pdf-generator.php
│       │   ├── qr-generator.php
│       │   ├── post-notifications.php
│       │   ├── translations-panel.php
│       │   └── admin-simplify.php
│       ├── template-parts/         # Tekrar kullanılan parçalar
│       │   ├── cards/              # Kart bileşenleri
│       │   ├── hero/               # Hero bölümleri
│       │   ├── sections/           # Sayfa bölümleri
│       │   └── content/            # İçerik şablonları
│       ├── languages/              # .po/.mo çeviri dosyaları
│       ├── translations/           # Manuel çeviri markdown'ları
│       ├── functions.php           # Ana tema fonksiyonları
│       └── style.css               # Ana stil dosyası
├── plugins/
│   └── wordpress-importer/         # Tek aktif plugin
└── uploads/                        # Medya dosyaları
```

## Tema Lokasyonu

```
wp-content/themes/humanitarian-theme-new/
```

## Renk Paleti

### Ana Renkler (Navy Blue Tema)
| Değişken | Hex Kodu | Kullanım |
|----------|----------|----------|
| `--color-primary` | `#1a2634` | Ana marka rengi (Navy) |
| `--color-primary-light` | `#2a3a4d` | Açık navy (vurgular) |
| `--color-primary-dark` | `#0f1820` | Koyu navy (hover) |
| `--color-dark` | `#1a1919` | Metin rengi |
| `--color-navy` | `#00203f` | Üst şerit |

### Arka Plan Renkleri
| Değişken | Hex Kodu | Kullanım |
|----------|----------|----------|
| `--color-paper` | `#FAF8F4` | Ana arka plan |
| `--color-cream` | `#f4f1ea` | İkincil arka plan |
| `--color-bone` | `#F9F6F1` | Header arka planı |

### Kategori Badge Renkleri
| Değişken | Hex Kodu |
|----------|----------|
| `--badge-teal` | `#1a2634` |
| `--badge-blue` | `#1A73E8` |
| `--badge-green` | `#188038` |
| `--badge-orange` | `#E37400` |
| `--badge-red` | `#D93025` |

## Fontlar

| Değişken | Font Ailesi | Kullanım |
|----------|-------------|----------|
| `--font-serif` | Playfair Display | Başlıklar (h1-h6) |
| `--font-body` | Merriweather | Paragraf/gövde metni |
| `--font-sans` | DM Sans | UI elementleri, menüler |

**Google Fonts URL:**
```
https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,900;1,400&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400&display=swap
```

## Aktif Pluginler

| Plugin | Açıklama |
|--------|----------|
| WordPress Importer | İçerik import/export |

---

## ÖNEMLİ NOTLAR

### Çoklu Dil Sistemi
- **Polylang KULLANILMIYOR**
- Kendi `simple-language.php` sistemimiz var
- `language` taxonomy ile post'lara dil atanıyor
- Desteklenen diller: English (en), Arabic (ar), French (fr)
- Arapça RTL (sağdan sola) destekli

### Custom Fields
- **ACF Pro YOK**
- Kendi meta box sistemimiz var (`functions.php`)
- Post meta'lar:
  - `_humanitarian_analysis` - Derinlemesine analiz mi?
  - `_humanitarian_editors_pick` - Editörün seçimi mi?
  - `_translation_group` - Çeviri grubu ID
  - `_original_post_id` - Orijinal yazı ID

### Kullanıcı Rolleri
| Rol | Yetkiler |
|-----|----------|
| Admin | Tam yetki |
| Author | Yazı yazma/düzenleme |

### Devre Dışı Bırakılan Özellikler
```php
// DISABLED: Sonsuz post oluşturma döngüsüne neden oluyor
// require_once HUMANITARIAN_DIR . '/inc/auto-translate.php';

// DISABLED: simple-language sistemi için güncelleme gerekiyor
// require_once HUMANITARIAN_DIR . '/inc/deepl-translation.php';
```

### Görsel Boyut Önerileri
| Kullanım | Boyut |
|----------|-------|
| Hero | 1200x750px |
| Cards | 800x600px |
| Square | 400x400px |

### Önemli Dosyalar
- `inc/simple-language.php` - Dil sistemi
- `inc/ajax-handlers.php` - AJAX endpoint'leri
- `inc/translations-panel.php` - Admin çeviri paneli
- `inc/admin-simplify.php` - Admin arayüz sadeleştirme

### Development Ortamı
- **Local by Flywheel** kullanılıyor
- Site URL: `humanitarian-blog.local` (muhtemelen)

---

## Hızlı Referans

### Yeni Yazı Meta Box'ları
- "In-Depth Analysis" checkbox
- "Editor's Pick" checkbox

### Dil Değiştirme
Header'da language switcher mevcut, `?lang=XX` parametresi ile çalışır.

### RTL Desteği
Arapça seçildiğinde otomatik RTL class'ları eklenir (`rtl.css` yüklenir).
