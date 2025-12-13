Tabii! İşte tüm phase'ler için Türkçe promptlar:

---

## Phase 1: Temel Kurulum

```
Adım adım gidelim. Phase 1: Temel Kurulum ile başla.

Yapılacaklar:
1. Theme klasör yapısını oluştur (flavor-starter/)
2. style.css dosyasını theme header bilgileriyle oluştur
3. functions.php'yi ayarla:
   - Theme supports (title-tag, post-thumbnails, custom-logo, html5, editor-styles)
   - Navigation menus (primary, footer, social)
   - Image sizes (hero-large: 1200x800, card-medium: 600x400, card-small: 400x267, author-thumb: 150x150)
4. Custom taxonomy'leri register et:
   - article_type (News, Opinion, Investigation, In-Depth Analysis, Feature, Breaking)
   - region (Africa, Middle East, Asia, Europe, Americas, Global)
5. Admin sadeleştirme hook'larını ekle (yazarlar için basit arayüz)
6. wpml-config.xml dosyasını oluştur

Phase 1 tamamlandığında dur ve bana ne oluşturduğunu göster.
Her dosyayı ve amacını listele.
Ben review edeceğim, sonra Phase 2'ye geçeriz.

Kodlar İngilizce olsun, benimle Türkçe konuş.
```

---

## Phase 2: Tasarım Sistemi

```
Phase 2: Tasarım Sistemi'ne geçelim.

Yapılacaklar:
1. CSS değişkenlerini oluştur (/assets/css/style.css):
   - Renkler (primary: #0D5C63, accent: #E8B059, neutrals, category colors)
   - Tipografi (Source Serif 4 headlines, Inter body, Amiri Arabic)
   - Spacing scale (space-1 ile space-12 arası)
   - Shadows ve border-radius
   - Transitions

2. Base stilleri yaz:
   - Reset/normalize
   - Typography defaults
   - Link stilleri
   - Button stilleri
   - Form elementleri

3. RTL stylesheet oluştur (/assets/css/rtl.css):
   - Arapça için direction: rtl
   - Layout flip'leri
   - Font-family override'ları

4. Print stylesheet oluştur (/assets/css/print.css):
   - Gereksiz elementleri gizle
   - Yazıcı dostu stiller

5. Responsive breakpoint'leri tanımla:
   - Mobile: < 640px
   - Tablet: >= 640px
   - Small Desktop: >= 768px
   - Desktop: >= 1024px
   - Large Desktop: >= 1280px

Phase 2 tamamlandığında dur ve özet göster.
Kodlar İngilizce, benimle Türkçe konuş.
```

---

## Phase 3: Ana Template Dosyaları

```
Phase 3: Ana Template Dosyaları'na geçelim.

Yapılacaklar:
1. header.php:
   - Site logo
   - Ana navigasyon (Aid & Policy, Conflict, Environment, Investigations, Migration)
   - Arama ikonu (modal trigger)
   - Dil değiştirici (WPML)
   - Mobile hamburger menu

2. footer.php:
   - 4 kolonlu layout (About, Coverage, About, Support)
   - Sosyal medya linkleri
   - Newsletter signup alanı
   - Dil değiştirici
   - Copyright

3. front-page.php (Anasayfa):
   - Hero section (sticky posts)
   - Current Coverage (son 6 yazı)
   - By Region (AJAX tabbed - bölgelere göre)
   - In-Depth Analysis (karanlık arka plan, carousel)
   - Opinions section
   - Trending Now (en çok okunanlar)
   - Newsletter CTA
   - Editors' Picks

4. single.php (Makale sayfası):
   - Reading progress bar
   - Breadcrumb
   - Kategori badge'leri
   - Başlık ve alt başlık
   - Yazar bilgisi ve tarih
   - Action bar (Listen, Save, Share, PDF, QR)
   - İçerik alanı
   - Etiketler
   - Yazar bio kutusu
   - İlgili yazılar

5. archive.php, category.php, tag.php:
   - Kategori/tag başlığı ve açıklaması
   - Yazı grid'i
   - Pagination

6. author.php:
   - Yazar profil bilgileri
   - Yazarın makaleleri

7. search.php ve searchform.php:
   - Arama sonuçları sayfası
   - Filtreler (kategori, bölge, tür, tarih)
   - Highlighted arama terimleri

8. 404.php:
   - Kullanıcı dostu hata sayfası

Phase 3 tamamlandığında dur ve tüm dosyaları listele.
Kodlar İngilizce, benimle Türkçe konuş.
```

---

## Phase 4: Bileşenler (Components)

```
Phase 4: Bileşenler'e geçelim.

/template-parts/ klasöründe şunları oluştur:

1. content-card.php:
   - Standart dikey makale kartı
   - Görsel, kategori badge, başlık, excerpt, yazar, tarih

2. content-card-horizontal.php:
   - Yatay makale kartı
   - Sol görsel, sağ içerik

3. content-card-small.php:
   - Küçük kart (Editors' Picks için)

4. content-featured.php:
   - Büyük hero kartı
   - Overlay text

5. content-opinion.php:
   - Opinion makaleleri için
   - Yuvarlak yazar fotoğrafı

6. content-search-result.php:
   - Arama sonucu kartı
   - Highlighted terimler

7. author-bio.php:
   - Makale sonundaki yazar kutusu

8. share-buttons.php:
   - WhatsApp, Telegram, Twitter, Facebook, Email, Copy Link

9. reading-toolbar.php:
   - Floating action bar
   - Listen, Save, Share, PDF, QR butonları

10. breadcrumbs.php:
    - Sayfa yolu

11. pagination.php:
    - Sayfa numaraları
    - Prev/Next

12. newsletter-form.php:
    - Email input
    - Frequency seçenekleri

Phase 4 tamamlandığında dur ve bileşenleri listele.
Kodlar İngilizce, benimle Türkçe konuş.
```

---

## Phase 5: JavaScript Özellikleri

```
Phase 5: JavaScript Özellikleri'ne geçelim.

/assets/js/ klasöründe şunları oluştur:

1. main.js:
   - Mobile menu toggle
   - Smooth scroll
   - Lazy loading initialization

2. search.js:
   - Live search (AJAX)
   - Debounce (300ms)
   - Arama geçmişi (localStorage)
   - Kategoriler, etiketler, yazarlar dahil
   - Keyboard navigation

3. reading-experience.js:
   - Reading progress bar
   - Table of contents (H2/H3'lerden)
   - Back to top button
   - Floating action bar show/hide

4. audio-player.js:
   - Web Speech API ile text-to-speech
   - Play/Pause/Stop
   - Hız kontrolü (0.75x, 1x, 1.25x, 1.5x)
   - Progress göstergesi

5. modals.js:
   - PDF download modal
   - QR code modal
   - Share modal
   - Genel modal sistemi

6. region-tabs.js:
   - Homepage "By Region" section
   - AJAX ile bölge içeriği yükleme
   - Tab switching

functions.php'de bu script'leri enqueue et.
AJAX endpoint'lerini /inc/ajax-handlers.php'de oluştur.

Phase 5 tamamlandığında dur ve özet göster.
Kodlar İngilizce, benimle Türkçe konuş.
```

---

## Phase 6: Offline Özellikler

```
Phase 6: Offline Özellikler'e geçelim.

Bu phase conflict zone kullanıcıları için kritik!

1. PDF Generator (/inc/pdf-generator.php):
   - mPDF kütüphanesini /lib/mpdf/ klasörüne kur
   - 3 format: Standard, Light, Print-Friendly
   - Arapça RTL desteği
   - Profesyonel PDF template'leri oluştur:
     * /template-parts/pdf/pdf-standard.php
     * /template-parts/pdf/pdf-light.php
     * /template-parts/pdf/pdf-print.php
   - PDF stilleri: /assets/css/pdf-style.css ve pdf-rtl.css

2. QR Code Generator (/inc/qr-generator.php):
   - phpqrcode kütüphanesini /lib/phpqrcode/ klasörüne kur
   - Makale URL'si için QR oluşturma
   - QR indirme fonksiyonu

3. PDF Modal UI:
   - Format seçenekleri
   - Boyut tahmini gösterimi
   - Download butonu

4. QR Modal UI:
   - QR görsel
   - İndirme ve yazdırma butonları

5. Copy Text özelliği:
   - Makale metnini kopyalama (internet olmadan paylaşım için)

6. Audio indirme (optional):
   - Eğer manuel audio yüklenmişse indirme linki

Kütüphane kurulumları için talimatları da yaz.

Phase 6 tamamlandığında dur ve test edilebilir hale getir.
Kodlar İngilizce, benimle Türkçe konuş.
```

---

## Phase 7: Admin Panel ve Kullanıcı Deneyimi

```
Phase 7: Admin Panel ve Kullanıcı Deneyimi'ne geçelim.

1. Custom Dashboard (/inc/admin-dashboard.php):
   - Hoş geldin widget'ı (yazar için nasıl yazı yazılır adımları)
   - Quick stats (yayınlanan, bekleyen, yazarlar)
   - Son makaleler listesi
   - Bekleyen yazılar (editörler için)
   - Popüler makaleler

2. Admin Sadeleştirme (/inc/admin-simplify.php):
   - Yazarlar için gereksiz menüleri gizle
   - Post editor'da gereksiz meta box'ları kaldır
   - "Publish" butonunu "Submit for Review" olarak değiştir
   - Yazarların direkt yayınlamasını engelle

3. Custom Meta Boxes:
   - Subtitle alanı
   - Featured/Editors' Pick toggle'ları
   - Audio upload alanı (opsiyonel)

4. Admin Stilleri (/assets/css/admin-style.css):
   - Branded admin bar rengi
   - Dashboard widget stilleri
   - Daha okunabilir arayüz

5. Editor Stilleri (/assets/css/editor-style.css):
   - Classic Editor için frontend benzeri stiller

6. User Role Ayarları:
   - Author: Sadece taslak oluşturabilir
   - Editor: İnceleyip yayınlayabilir
   - Admin: Tam yetki

Phase 7 tamamlandığında admin paneli test edilebilir olsun.
Kodlar İngilizce, benimle Türkçe konuş.
```

---

## Phase 8: Test ve Optimizasyon

```
Phase 8: Test ve Optimizasyon'a geçelim.

1. Cross-browser Test Listesi:
   - Chrome, Firefox, Safari, Edge
   - iOS Safari, Android Chrome

2. Responsive Test:
   - Mobile (375px)
   - Tablet (768px)
   - Desktop (1024px, 1280px)

3. RTL Test:
   - Arapça içerikle tüm sayfaları kontrol et
   - Layout flip'lerin doğruluğu
   - Font rendering

4. WPML Test:
   - Dil değiştirici çalışıyor mu
   - URL yapısı doğru mu
   - Taxonomy çevirileri

5. Performance Optimizasyonu:
   - Image lazy loading aktif mi
   - CSS/JS minification notları
   - Critical CSS inline suggestion
   - Caching önerileri

6. Accessibility Kontrolü:
   - Heading hierarchy (H1 → H2 → H3)
   - Alt text'ler
   - Keyboard navigation
   - Focus states
   - Color contrast

7. SEO Kontrolleri:
   - Meta tags
   - Open Graph
   - Schema markup (NewsArticle)

8. Eksik/Hatalı Şeylerin Listesi:
   - Varsa bug'ları listele
   - İyileştirme önerileri

Son olarak, theme'in production-ready olup olmadığını değerlendir.
Kodlar İngilizce, benimle Türkçe konuş.
```

---

## Bonus: Her Phase Sonunda Kullanabileceğin Komutlar

```
# Özet almak için:
"Bu phase'de ne yaptığını özetle. Dosyaları ve amaçlarını listele."

# Devam etmek için:
"Tamam, Phase X'e geçelim."

# Sorun varsa:
"Şurada bir sorun var: [açıklama]. Düzelt."

# Değişiklik istersen:
"[Dosya adı]'nda şu değişikliği yap: [açıklama]"

# Geri almak için:
"Son değişikliği geri al."

# Test etmek için:
"Bu phase'i nasıl test edebilirim? Adımları yaz."
```

---

## Başlangıç İçin Kopyala-Yapıştır

İlk olarak bunu Claude Code'a ver:

```
Seninle bir WordPress theme projesi yapacağız.

Kurallar:
- Kodlar her zaman İngilizce olsun
- Benimle Türkçe konuş
- Her phase sonunda dur, özet göster, onay bekle
- Hata yaparsan söyle, birlikte düzeltelim

Şimdi Phase 1: Temel Kurulum ile başlayalım.

Yapılacaklar:
1. Theme klasör yapısını oluştur (flavor-starter/)
2. style.css dosyasını theme header bilgileriyle oluştur
3. functions.php'yi ayarla:
   - Theme supports (title-tag, post-thumbnails, custom-logo, html5, editor-styles)
   - Navigation menus (primary, footer, social)
   - Image sizes (hero-large: 1200x800, card-medium: 600x400, card-small: 400x267, author-thumb: 150x150)
4. Custom taxonomy'leri register et:
   - article_type (News, Opinion, Investigation, In-Depth Analysis, Feature, Breaking)
   - region (Africa, Middle East, Asia, Europe, Americas, Global)
5. Admin sadeleştirme hook'larını ekle
6. wpml-config.xml dosyasını oluştur

Bitince bana özet göster, sonra devam ederiz.
```

Başarılar! 🚀
