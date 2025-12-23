# Humanitarian Blog - Sprint Görevleri

## UI/Tasarım

### GÖREV 1: Header Düzenlemesi
- Logo'yu header'ın sol üstünden header'ın altına taşı (ortalanmış)
- Menüyü biraz sola kaydır
- Menü başlıklarını bold ve daha büyük yap

### GÖREV 2: Privacy Dropdown
Top bar'da (navy bar) Contact Us'ın yanına Privacy dropdown ekle:
- Privacy Policy → /privacy-policy
- Child Safeguard Policy → /child-safeguard
- Content Usage Policy → /content-usage
- User Agreement → /user-agreement
Hover'da açılsın.

### GÖREV 3: Editors' Picks
Editors' Picks section'ında 4 yerine 8 makale göster.

### GÖREV 4: Cookie Banner
Cookie policy banner'ını kaldır.

### GÖREV 5: Single Post - Butonlar
Single article'daki PDF, QR ve Voice butonlarını:
- Merkeze al
- Her satırda 1 buton (dikey diz)
- Boyutunu 2 kat büyüt

### GÖREV 6: Single Post - Yazar Profili
Yazar bilgisini LinkedIn formatına çevir:
- Profil resmi
- İsim
- Meslek/Ünvan
- Deneyim yılı + Alan
Users tablosuna custom field ekle veya CPT oluştur (hangisi uygunsa).

### GÖREV 7: Social Media Butonları
Renkleri site renk paletiyle uyumlu yap (navy/primary).

### GÖREV 8: Newsletter Section
Newsletter section'ı Editors' Picks'in altına taşı.

### GÖREV 9: Featured Articles
Homepage header altı için Featured Articles sistemi:
- Posts'a "featured" meta field ekle VEYA CPT oluştur
- Admin/Author dashboard'dan seçebilsin
- Homepage'de göster

## Admin/Dashboard

### GÖREV 10: Author Role Kısıtlama
Author role için dashboard'da sadece Articles ve Media görünsün.

### GÖREV 11: Users Tablosu
Users'a yeni kolonlar ekle: profession, experience_years, experience_field

## Fonksiyonel

### GÖREV 12: Cross-Linking
Makale içine diğer makalelerin linklenebileceği sistem (shortcode veya Gutenberg block).

### GÖREV 13: Comment + Login Sistemi
- Sayfalarda comment alanını aktif et
- Basit frontend login (email + password)
- Login olan kullanıcı: yorum atabilsin, bookmark yapabilsin, mesaj atabilsin
- Mesajlar dashboard'da Submissions'da görünsün

### GÖREV 14: Contact Form → Submissions
Contact formlarından gelen veriler Submissions'da listelensin.

### GÖREV 15: DeepSeek Çeviri
DeepSeek API ile tüm posts/pages'i Arapça ve Fransızca'ya çeviren Python scripti.

## Araştırma

### GÖREV 16: TTS Alternatifleri
Voice için ücretsiz/ücretli TTS alternatifleri araştır (Google, Amazon Polly, ElevenLabs vs).
