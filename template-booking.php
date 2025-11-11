<?php
/* Template Name: Booking */
get_header();
$time_slots = pogostudios_get_available_time_slots();
$blackout_dates = pogostudios_get_blackout_dates();
?>
<section class="py-5 section-dark">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                <h1 class="section-title">Rezervasyon</h1>
                <p class="text-white-50">Uygun saatleri seç, detaylarını bırak, ekibimiz onay mailini göndersin. Dolu saatler gri görünür, blackout günler otomatik kapanır.</p>
                <form id="pogo-booking-form" class="booking-card mt-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">İsim Soyisim</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-posta</label>
                            <input type="email" name="email" class="form-control" required pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$" inputmode="email" autocomplete="email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefon</label>
                            <input type="tel" name="phone" class="form-control" required inputmode="tel" autocomplete="tel" data-phone-mask placeholder="+90 (5__) ___ __ __">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tarih</label>
                            <input type="date" name="date" class="form-control" min="<?php echo esc_attr(date('Y-m-d')); ?>" required pattern="\d{4}-\d{2}-\d{2}" inputmode="numeric" placeholder="YYYY-AA-GG">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Saat</label>
                            <select name="time" class="form-select" required>
                                <option value="">Saat seçin</option>
                                <?php foreach ($time_slots as $slot) : ?>
                                    <option value="<?php echo esc_attr($slot); ?>"><?php echo esc_html($slot); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notlar</label>
                            <textarea name="notes" rows="3" class="form-control" placeholder="Proje detayını paylaş"></textarea>
                        </div>
                    </div>
                    <div class="booking-status" id="booking-status"></div>
                    <button type="submit" class="btn btn-pogo w-100 mt-3">Rezervasyonu Onayla</button>
                </form>
            </div>
            <div class="col-lg-5">
                <div class="glassy-card h-100">
                    <h4>Uygun Slotlar</h4>
                    <p class="text-white-50" id="booking-slot-date">Bir tarih seçtikten sonra saatler burada listelenir.</p>
                    <p class="text-white-50 small mb-2">Saat seçimi sağdaki listeye tıklayarak yapılabilir.</p>
                    <div id="booking-slots" class="mt-4">
                        <?php foreach ($time_slots as $slot) : ?>
                            <div class="booking-slot" data-slot="<?php echo esc_attr($slot); ?>">
                                <span><?php echo esc_html($slot); ?></span>
                                <span class="status text-success">Müsait</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-4">
                        <h6>İş Akışı</h6>
                        <ul class="text-white-50 small">
                            <li>Slot seçildikten sonra onay maili gelir.</li>
                            <li>24 saat içinde iptal ücretsizdir.</li>
                            <li>Tekrar eden rezervasyonlar için paket fiyatları sunulur.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
