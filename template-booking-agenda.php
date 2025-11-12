<?php
/*
Template Name: Booking Agenda
Template Post Type: page
*/
get_header();
?>
<section class="py-5">
    <div class="container">
        <div class="agenda-wrapper">
            <div class="agenda-calendar" id="agenda-calendar">
                <div class="agenda-calendar__header">
                    <button class="agenda-calendar__nav" id="agenda-prev-month" aria-label="<?php esc_attr_e('Önceki Ay', 'pogostudios'); ?>">&larr;</button>
                    <div class="agenda-calendar__selects">
                        <select id="agenda-month-select" aria-label="<?php esc_attr_e('Ay seç', 'pogostudios'); ?>"></select>
                        <select id="agenda-year-select" aria-label="<?php esc_attr_e('Yıl seç', 'pogostudios'); ?>"></select>
                    </div>
                    <button class="agenda-calendar__nav" id="agenda-next-month" aria-label="<?php esc_attr_e('Sonraki Ay', 'pogostudios'); ?>">&rarr;</button>
                </div>
                <div class="agenda-calendar__weekdays">
                    <?php
                    $weekdays = ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'];
                    foreach ($weekdays as $weekday) {
                        echo '<div class="agenda-calendar__weekday">' . esc_html($weekday) . '</div>';
                    }
                    ?>
                </div>
                <div class="agenda-calendar__grid" id="agenda-calendar-grid" aria-live="polite"></div>
                <div class="agenda-calendar__legend">
                    <span><span class="legend-dot available"></span> <?php esc_html_e('Müsait', 'pogostudios'); ?></span>
                    <span><span class="legend-dot selected"></span> <?php esc_html_e('Seçili', 'pogostudios'); ?></span>
                    <span><span class="legend-dot disabled"></span> <?php esc_html_e('Dolu/Kapalı', 'pogostudios'); ?></span>
                </div>
                <div class="agenda-tooltip" id="agenda-tooltip" role="status"></div>
            </div>

            <div class="agenda-panel">
                <div id="agenda-day-info" class="agenda-day-info">
                    <h3><?php esc_html_e('Gün Seçin', 'pogostudios'); ?></h3>
                    <p class="text-white-50"><?php esc_html_e('Takvimden bir güne dokunun veya tıklayın, slotları inceleyin.', 'pogostudios'); ?></p>
                </div>

                <div id="agenda-slots" class="agenda-slots d-none">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0" id="agenda-slots-title"></h4>
                        <button class="btn btn-link text-uppercase text-white-50" id="agenda-reset-selection"><?php esc_html_e('Seçimi Sıfırla', 'pogostudios'); ?></button>
                    </div>
                    <div class="agenda-slots__list" id="agenda-slots-list"></div>
                </div>

                <div id="agenda-form-wrapper" class="agenda-form d-none">
                    <h5 class="mb-3"><?php esc_html_e('Rezervasyon Formu', 'pogostudios'); ?></h5>
                    <form id="agenda-booking-form" class="booking-card">
                        <input type="hidden" name="date" id="agenda-form-date">
                        <input type="hidden" name="time" id="agenda-form-time">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><?php esc_html_e('İsim Soyisim', 'pogostudios'); ?></label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php esc_html_e('E-posta', 'pogostudios'); ?></label>
                                <input type="email" name="email" class="form-control" required pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$" inputmode="email" autocomplete="email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php esc_html_e('Telefon', 'pogostudios'); ?></label>
                                <input type="tel" name="phone" class="form-control" required inputmode="tel" autocomplete="tel" data-phone-mask placeholder="+90 (5__) ___ __ __">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php esc_html_e('Tarih', 'pogostudios'); ?></label>
                                <input type="text" id="agenda-date-display" class="form-control" placeholder="<?php esc_attr_e('Takvimden tarih seçin', 'pogostudios'); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php esc_html_e('Saat', 'pogostudios'); ?></label>
                                <input type="text" id="agenda-time-display" class="form-control" placeholder="<?php esc_attr_e('Slot seçildiğinde görünür', 'pogostudios'); ?>" readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label"><?php esc_html_e('Notlar', 'pogostudios'); ?></label>
                                <textarea name="notes" rows="3" class="form-control" placeholder="<?php esc_attr_e('Proje detayını paylaşın', 'pogostudios'); ?>"></textarea>
                            </div>
                        </div>
                        <div class="booking-status mt-3" id="agenda-booking-status"></div>
                        <button type="submit" class="btn btn-pogo w-100 mt-3"><?php esc_html_e('Rezervasyonu Onayla', 'pogostudios'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
