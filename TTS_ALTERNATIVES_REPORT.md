# TTS Alternatifleri Araştırma Raporu
**GÖREV 16: Voice Article için Text-to-Speech Alternatifleri**

## Özet

Humanitarian Blog'un "Voice Article" özelliği için en uygun TTS API'lerinin karşılaştırmalı analizi.

---

## 1. Amazon Polly

### Fiyatlandırma
| Ses Tipi | Fiyat (1M karakter) | Free Tier |
|----------|---------------------|-----------|
| Standard | $4.80 | 5M karakter/ay (12 ay) |
| Neural | $19.20 | 1M karakter/ay (12 ay) |
| Long-Form | $100.00 | 500K karakter/ay |
| Generative | $30.00 | 100K karakter/ay |

### Avantajlar
- AWS ekosistemi ile kolay entegrasyon
- 29 dilde 60+ ses
- SSML desteği
- Real-time streaming
- CloudWatch monitoring

### Dezavantajlar
- Free tier sadece 12 ay
- Neural sesler pahalı

### Tavsiye Edilen Kullanım
- AWS altyapısı kullanan projeler
- Enterprise ölçekli uygulamalar
- IVR ve telefon sistemleri

---

## 2. Google Cloud Text-to-Speech

### Fiyatlandırma
| Ses Tipi | Fiyat (1M karakter) |
|----------|---------------------|
| Standard | $4.00 |
| WaveNet | $16.00 |
| Neural2 | $16.00 |

**Free Tier:** Aylık 4M standart, 1M WaveNet karakter (süresiz)

### Avantajlar
- 380+ ses, 50+ dil
- Süresiz free tier
- Yüksek ses kalitesi (MOS skorları)
- DeepMind teknolojisi
- SSML desteği

### Dezavantajlar
- Google Cloud hesabı gerekli
- Kompleks kurulum

### Tavsiye Edilen Kullanım
- Çok dilli içerik (Arapça, Fransızca desteği)
- Kalite odaklı projeler
- Uzun vadeli kullanım (süresiz free tier)

---

## 3. ElevenLabs

### Fiyatlandırma
| Plan | Fiyat | Karakterler |
|------|-------|-------------|
| Free | $0 | 10K/ay |
| Starter | $5/ay | 30K/ay |
| Creator | $22/ay | 100K/ay |
| Pro | $99/ay | 500K/ay |
| Scale | $330/ay | 2M/ay |

### Avantajlar
- En gerçekçi ve duygusal sesler
- Ses klonlama özelliği
- Kolay API
- Hızlı gelişim

### Dezavantajlar
- Subscription model (esnek değil)
- 3x daha pahalı
- Sınırlı SSML desteği

### Tavsiye Edilen Kullanım
- Premium içerik
- Podcast/audiobook üretimi
- Ses klonlama gerektiğinde

---

## 4. Microsoft Azure Speech

### Fiyatlandırma
| Ses Tipi | Fiyat (1M karakter) |
|----------|---------------------|
| Neural | $15.00 |
| Neural HD | $24.00 |

**Free Tier:** 500K karakter/ay

### Avantajlar
- Geniş SSML desteği
- Azure entegrasyonu
- Custom Neural Voice
- 130+ dil

### Dezavantajlar
- Azure hesabı gerekli
- Kurulum kompleks

---

## 5. Ücretsiz/Açık Kaynak Alternatifler

### Web Speech API (Browser)
- **Fiyat:** Ücretsiz
- **Dezavantaj:** Tarayıcıya bağlı, sınırlı kalite
- **Mevcut Kullanım:** Şu anda tema bunu kullanıyor

### Mozilla TTS / Coqui TTS
- **Fiyat:** Ücretsiz (self-hosted)
- **Dezavantaj:** Sunucu kaynağı gerekli
- **Avantaj:** Tamamen kontrol sizde

### Piper TTS
- **Fiyat:** Ücretsiz (self-hosted)
- **Avantaj:** Hızlı, hafif, offline çalışır

---

## Öneriler

### Humanitarian Blog için En Uygun Seçenekler:

#### 1. Mevcut Çözümü Koruma (Ücretsiz)
Browser'ın Web Speech API'sini kullanmaya devam edin. Mevcut implementasyon çalışıyor.

#### 2. Google Cloud TTS (Tavsiye - Kalite/Fiyat Dengesi)
- Süresiz free tier
- Arapça ve Fransızca desteği mükemmel
- Yüksek ses kalitesi

**Maliyet Örneği:**
- Ortalama makale: ~5000 karakter
- Free tier: ~800 makale/ay (standart) veya ~200 makale/ay (WaveNet)

#### 3. Amazon Polly (AWS Kullanıyorsanız)
- AWS altyapısıyla uyumlu
- Standard sesler ucuz

#### 4. ElevenLabs (Premium İçerik)
- En iyi ses kalitesi
- Ancak pahalı, sadece özel içerikler için

---

## Implementasyon Notları

### Google Cloud TTS Entegrasyonu

```php
// functions.php'ye eklenecek
function humanitarian_generate_tts($content, $lang = 'en-US') {
    $api_key = get_option('humanitarian_google_tts_key');

    // API çağrısı
    $response = wp_remote_post('https://texttospeech.googleapis.com/v1/text:synthesize', [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode([
            'input' => ['text' => $content],
            'voice' => ['languageCode' => $lang],
            'audioConfig' => ['audioEncoding' => 'MP3'],
        ]),
    ]);

    return json_decode(wp_remote_retrieve_body($response));
}
```

---

## Sonuç

| Kriter | En İyi Seçenek |
|--------|----------------|
| Ücretsiz Kullanım | Web Speech API (mevcut) |
| Kalite/Fiyat Dengesi | Google Cloud TTS |
| Enterprise | Amazon Polly |
| Premium Kalite | ElevenLabs |
| Çok Dilli | Google Cloud TTS |

**Tavsiyem:** Mevcut Web Speech API'yi koruyun, ileride kalite artışı istenirse Google Cloud TTS'e geçiş yapın.

---

## Kaynaklar

- [Best TTS APIs in 2025 - Speechmatics](https://www.speechmatics.com/company/articles-and-news/best-tts-apis-in-2025-top-12-text-to-speech-services-for-developers)
- [AI Text To Speech Cost Comparison - DAISY](https://daisy.org/news-events/articles/ai-text-to-speech-cost-comparison/)
- [Amazon Polly vs Google Cloud TTS - PeerSpot](https://www.peerspot.com/products/comparisons/amazon-polly_vs_google-cloud-text-to-speech)
- [Amazon Polly Pricing](https://aws.amazon.com/polly/pricing/)
- [Best TTS APIs - Eden AI](https://www.edenai.co/post/best-text-to-speech-apis)
