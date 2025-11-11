# PogoStudios WordPress Theme
Modern, mobile-first müzik stüdyosu teması. Bootstrap 5, Poppins/Space Grotesk tipografi ve koyu/kontrastlı renk paletiyle gelir. Tüm sayfalar (Anasayfa, Hakkımızda, Vizyonumuz, Rezervasyon, İletişim) için hazır şablonlar ve yönetilebilir rezervasyon sistemi içerir.

## Ana Özellikler
- Özel sayfa şablonları (`template-home`, `template-about`, `template-vision`, `template-booking`, `template-contact`)
- REST tabanlı rezervasyon sistemi (admin panelinden slot yönetimi, gri/dolu saat gösterimi)
- Tema ayarları paneli (iletişim bilgileri, sosyal linkler, slot ve blackout yönetimi)
- Bootstrap 5 + Google Fonts (Poppins + Space Grotesk) entegrasyonu, responsive layout
- Neon/urban rap estetiği için cam efektleri, gradient CTA’lar ve koyu arka plan temasına sahip yepyeni UI
- SEO (Yoast) ve güvenlik (Wordfence) eklentisi öneri bildirimi
- İletişim formu (admin-post + e-posta), Google Maps gömülü konum

## Kurulum
1. Klasörü `wp-content/themes/pogostudios` dizinine kopyalayın.
2. WordPress admin > Görünüm > Temalar > **PogoStudios** temasını etkinleştirin.
3. Sayfaları oluşturun ve şablonlarını seçin:
   - Anasayfa → `Homepage`
   - Hakkımızda → `About & Vision`
   - Vizyonumuz → `Vision`
   - Rezervasyon → `Booking`
   - İletişim → `Contact`
4. Görünüm > Menü bölümünden `Primary` ve `Footer` menülerini atayın.

## Rezervasyon Sistemi
- `Rezervasyon > Booking Availability` menüsünden saat slotları (ör. `09:00,10:00,...`) ve blackout tarihlerini girin.
- Kullanıcılar Rezervasyon sayfasında tarih seçtiğinde alınan slotlar gri (dolu) görünür.
- Form gönderimleri `pogostudios_booking` özel yazı tipinde saklanır ve admin e-postasına bildirim gönderilir.
- REST uç noktaları:
  - `GET /wp-json/pogostudios/v1/bookings?date=YYYY-MM-DD`
  - `POST /wp-json/pogostudios/v1/bookings`

## Rezervasyon Yönetimi
- WordPress admin sol menüsünde **Bookings** (Rezervasyonlar) listesinde tüm kayıtları görebilirsiniz.
- Rezervasyonu açtığınızda detaylar ve `Durum` alanı (Onay Bekliyor / Onaylandı / İptal Edildi) bulunur.
- Durumu “İptal Edildi” yaptığınızda slot otomatik olarak tekrar müsait hale gelir.
- Tema ayarlarındaki e-posta adresi (veya WP admin e-postası) bildirim almak için kullanılır; gerekirse buradan güncelleyin.

## Tema Ayarları
- `PogoStudios > PogoStudios Settings`: telefon, e-posta, adres ve sosyal profilleri güncelleyin.
- Bu bilgiler header CTA, footer ve iletişim sayfasında dinamik olarak kullanılır.

## Önerilen Eklentiler
Tema admin ekranında öneri bildirimi görünür. Manuel kurulum için:
1. **Yoast SEO** – meta title/description, site haritası
2. **Wordfence Security** – güvenlik taramaları
3. **LiteSpeed Cache** veya **WP Rocket** – performans optimizasyonu (lazy-load, minify)

## SEO & Performans İpuçları
- Görsel yüklemelerinde WebP kullanın, 200KB altına indirin.
- Lighthouse’da 90+ hedefi için cache eklentisindeki CSS/JS optimizasyonlarını açın.
- Open Graph ve favicon bilgileri `header.php` içinde hazır.

## Test Listesi
- [ ] Rezervasyon formu farklı tarih/saat kombinasyonlarıyla veri kaydediyor
- [ ] Blackout tarihleri seçilemiyor, gri slotlar doğrulanıyor
- [ ] İletişim formu gönderimi admin e-postasına ulaşıyor
- [ ] Tüm sayfalar mobil kırılımda (≤768px) düzgün çalışıyor
- [ ] İlk kurulum sonrası Yoast + Wordfence + cache eklentisi etkin

## Paketleme
Tema klasörünü `.zip` arşivleyip WordPress admin > Temalar > Yeni ekle üzerinden yükleyebilirsiniz.
