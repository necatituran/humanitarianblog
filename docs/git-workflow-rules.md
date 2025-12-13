# Git Workflow Kuralları

> **Her promptta bu kuralları hatırlat!**

---

## 📋 Branch Stratejisi

### Ana Branch
- `main` - Production-ready kod (her phase tamamlandıkında)

### Özellik Branch'leri
```bash
feature/phase-1-setup
feature/phase-2-design-system
feature/phase-3-templates
feature/phase-4-components
feature/phase-5-javascript
feature/phase-6-offline
feature/phase-7-admin
feature/phase-8-testing
```

---

## 🔄 Workflow Adımları

### 1. Yeni Phase Başlangıcı
```bash
git checkout main
git checkout -b feature/phase-X-name
```

### 2. Geliştirme Sırasında
```bash
# Her önemli özellik sonrası commit
git add .
git commit -m "feat(phase-X): açıklama"
```

### 3. Phase Tamamlandığında
```bash
# Main'e geç
git checkout main

# Merge et
git merge feature/phase-X-name

# Push et
git push origin main

# Branch'i sil (opsiyonel)
git branch -d feature/phase-X-name
```

---

## 💬 Commit Mesaj Formatı

### Format
```
<type>(phase-X): <açıklama>

[Opsiyonel detaylı açıklama]

[Opsiyonel footer]
```

### Type Önekleri
- `feat` - Yeni özellik
- `fix` - Bug düzeltmesi
- `style` - CSS/tasarım değişikliği
- `refactor` - Kod iyileştirme
- `docs` - Dokümantasyon
- `test` - Test ekleme

### Örnekler
```bash
# İyi örnekler ✅
git commit -m "feat(phase-2): Add CSS design system variables"
git commit -m "style(phase-2): Implement responsive typography"
git commit -m "feat(phase-3): Create homepage hero section"
git commit -m "fix(phase-5): Fix search modal keyboard navigation"

# Kötü örnekler ❌
git commit -m "update files"
git commit -m "css changes"
git commit -m "WIP"
```

---

## 📦 Her Phase Sonunda

### Kontrol Listesi
- [ ] Tüm dosyalar commit edildi mi?
- [ ] Branch main'e merge edildi mi?
- [ ] GitHub'a push edildi mi?
- [ ] docs/phaseX-name.md dokümantasyonu oluşturuldu mu?
- [ ] README.md güncellendi mi (Phase status)?

### Komut Dizisi
```bash
# Phase sonunda mutlaka yap
git add .
git commit -m "feat(phase-X): Phase X Complete - [özet]"
git checkout main
git merge feature/phase-X-name
git push origin main
```

---

## 🚫 Commitlenmemesi Gerekenler

`.gitignore` zaten bunları hariç tutuyor:
- ❌ `REACT_HUMANITARIAN/`
- ❌ `wp-config.php`
- ❌ `wp-content/uploads/`
- ❌ `wp-content/plugins/`
- ❌ `node_modules/`
- ❌ `.env` dosyaları

✅ **Sadece custom theme ve docs commitlenir!**

---

## 📝 Dokümantasyon Kuralları

### Her Phase İçin Oluştur
```
docs/
├── phase1-temel-kurulum.md          ✅
├── phase2-design-system.md          (Phase 2 sonunda)
├── phase3-templates.md              (Phase 3 sonunda)
├── phase4-components.md             (Phase 4 sonunda)
├── phase5-javascript.md             (Phase 5 sonunda)
├── phase6-offline-features.md       (Phase 6 sonunda)
├── phase7-admin-dashboard.md        (Phase 7 sonunda)
└── phase8-testing.md                (Phase 8 sonunda)
```

### Dokümantasyon İçeriği
Her phase dokümanında olmalı:
- ✅ Yapılanların listesi
- ✅ Oluşturulan dosyalar ve amaçları
- ✅ Kod örnekleri
- ✅ Test senaryoları
- ✅ Bilinen sınırlamalar

---

## 🎯 Quick Reference

### Phase Başlat
```bash
git checkout -b feature/phase-X-name
```

### Geliştirme Ara Commit
```bash
git add .
git commit -m "feat(phase-X): specific feature"
```

### Phase Bitir
```bash
git add .
git commit -m "feat(phase-X): Phase X Complete"
git checkout main
git merge feature/phase-X-name
git push origin main
```

### Durum Kontrol
```bash
git status
git log --oneline -5
git branch
```

---

## ⚠️ Önemli Hatırlatmalar

1. **Her phase ayrı branch** - Karışıklığı önler
2. **Sık commit** - Küçük, anlamlı commitler
3. **Açıklayıcı mesajlar** - Ne yaptığını yaz
4. **Main branch temiz** - Sadece tamamlanmış phase'ler
5. **Push sonrası kontrol** - GitHub'da görünüyor mu?

---

**Son Güncelleme:** 2025-12-14 (Phase 1)
**Versiyon:** 1.0.0
