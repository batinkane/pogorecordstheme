<?php
/* Template Name: Homepage */
get_header();
$contact = pogostudios_get_contact_info();
$booking_url = function_exists('pogostudios_get_page_url') ? pogostudios_get_page_url('rezervasyon', '/booking') : home_url('/booking');
$about_url = function_exists('pogostudios_get_page_url') ? pogostudios_get_page_url('hakkimizda', '/about') : home_url('/about');
$vision_url = function_exists('pogostudios_get_page_url') ? pogostudios_get_page_url('vizyonumuz', '/vision') : home_url('/vision');
?>
<section class="hero text-center text-md-start position-relative">
    <div class="container py-5 position-relative">
        <span class="floating-badge">Dolby Atmos • Analog Heat</span>
        <div class="row align-items-center">
            <div class="col-lg-6 fade-up" style="animation-delay: 0.1s;">
                <div class="tag-pill mb-3">
                    <span>Rap & Urban Sessions</span>
                </div>
                <h1 class="display-4 fw-bold mb-3" style="font-family: var(--font-display);">
                    Punchline’larını <br> bizim sahnemizde kayda al
                </h1>
                <p class="lead text-white-50 mb-4">808’ler, analog sıcaklık ve modüler synth duvarı aynı odada. PogoStudios, drill’den trap’e tüm ses evrenini tek çatı altında toplar.</p>
                <div class="d-flex gap-3 flex-column flex-md-row">
                    <a href="<?php echo esc_url($booking_url); ?>" class="btn btn-pogo">Hemen Rezervasyon Yap</a>
                    <a href="<?php echo esc_url($about_url); ?>" class="btn btn-outline-light border-0">Stüdyoyu Keşfet</a>
                </div>
                <div class="d-flex gap-4 flex-wrap mt-4">
                    <div class="stat-chip">
                        <strong>+140</strong>
                        <span>Mix & Mastering</span>
                    </div>
                    <div class="stat-chip">
                        <strong>24/7</strong>
                        <span>Açık Stüdyo</span>
                    </div>
                    <div class="stat-chip">
                        <strong>4</strong>
                        <span>Vokal Booth</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 ms-auto fade-up" style="animation-delay: 0.3s;">
                <div class="glassy-card position-relative overflow-hidden">
                    <span class="tag-pill mb-3">Ses Paketi</span>
                    <h4 class="mb-3">“Night Session” Bundle</h4>
                    <p class="text-white-50 mb-4">4 saat kayıt + hızlı edit + referans mix. Beat import, lyric monitor ve creative coach dahil.</p>
                    <ul class="list-unstyled text-start small text-white-50 mb-4">
                        <li class="mb-2">• UAD + Avalon vokal zinciri</li>
                        <li class="mb-2">• Analog saturation (Heat)</li>
                        <li class="mb-2">• İsteğe bağlı canlı video kaydı</li>
                    </ul>
                    <a href="<?php echo esc_url($booking_url); ?>" class="btn btn-pogo w-100">Slotları Gör</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="grid-line position-relative">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 fade-up">
                    <span class="text-uppercase text-white-50">PogoStudios</span>
                    <h2 class="section-title">Modern Ses Üretim Deneyimi</h2>
                    <p class="section-subtitle">Trap, drill, R&B ve alternatif hiphop prodüksiyonları için İstanbul’un en hızlı workflow’una sahip stüdyosuyuz.</p>
                    <div class="d-flex flex-column gap-2 mt-4">
                        <div class="d-flex align-items-center gap-3">
                            <span class="tag-pill">SSL + Neve Chain</span>
                            <span class="text-white-50">Analog + dijital hybrid miks</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="tag-pill">Creative Coach</span>
                            <span class="text-white-50">Writing camp & topluluk desteği</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="tag-pill">Drop Ready</span>
                            <span class="text-white-50">Spotify & YouTube optimizasyonu</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 fade-up">
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow">
                        <iframe src="https://www.youtube.com/embed/m6UOo2YGbIE" title="PogoStudios" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4 fade-up">
                <div class="glassy-card h-100">
                    <h4>Vocal Gym</h4>
                    <p class="text-white-50">Isınma, katmanlama ve ad-lib oturumlarını hızlandıran özel preset bankası.</p>
                    <span class="tag-pill">12k+ chain</span>
                </div>
            </div>
            <div class="col-md-4 fade-up" style="animation-delay: 0.2s;">
                <div class="glassy-card h-100">
                    <h4>Beat Lab</h4>
                    <p class="text-white-50">Ableton + MPC Live 2 + analog synth duvarı ile modüler beat üretim alanı.</p>
                    <span class="tag-pill">Producer ready</span>
                </div>
            </div>
            <div class="col-md-4 fade-up" style="animation-delay: 0.3s;">
                <div class="glassy-card h-100">
                    <h4>Live Visual</h4>
                    <p class="text-white-50">Kayıt sırasında eş zamanlı kamera set-up’ıyla sosyal medya klipleri.</p>
                    <span class="tag-pill">Content pack</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col">
                <span class="text-uppercase text-white-50">Hakkımızda</span>
                <h2 class="section-title">Kulaklar Şahit, Sanatçılar Onaylı</h2>
            </div>
        </div>
        <div class="row g-4">
            <?php
            $testimonials = [
                ['name' => 'Deniz Yılmaz', 'role' => 'Rock Vocal', 'quote' => 'PogoStudios ekibi, soundumu olması gereken yere taşıdı. Analog ekipman bağımlılık yapıyor.'],
                ['name' => 'Maya K.', 'role' => 'Podcast Creator', 'quote' => 'Booking sistemi sayesinde haftalık kayıtlarımı dakikalar içinde planlıyorum.'],
                ['name' => 'Kara Kolektif', 'role' => 'Band', 'quote' => 'Odaların akustiği kusursuz, mühendisler yaratıcı sürece ortak oluyor.'],
            ];
            foreach ($testimonials as $index => $testimonial) :
            ?>
                <div class="col-md-4">
                    <div class="testimonial fade-up" style="animation-delay: <?php echo esc_attr($index * 0.1); ?>s;">
                        <p class="mb-4">“<?php echo esc_html($testimonial['quote']); ?>”</p>
                        <h6 class="mb-0"><?php echo esc_html($testimonial['name']); ?></h6>
                        <small class="text-white-50"><?php echo esc_html($testimonial['role']); ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-5 section-dark">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6 fade-up">
                <h2 class="section-title">Vizyonumuz</h2>
                <p>PogoStudios; bağımsız sanatçılar, markalar ve içerik üreticileri için İstanbul’un en erişilebilir prodüksiyon merkezi olmayı hedefliyor.</p>
                <ul class="list-unstyled mt-4">
                    <li class="mb-2">• Hibrit kayıt zinciri (Analog + Digital)</li>
                    <li class="mb-2">• Modüler booking ve esnek paketler</li>
                    <li class="mb-2">• Sürdürülebilir ve kapsayıcı stüdyo kültürü</li>
                </ul>
                <a href="<?php echo esc_url($about_url); ?>" class="btn btn-pogo mt-3">Hikayemizi Oku</a>
                <a href="<?php echo esc_url($vision_url); ?>" class="btn btn-outline-light border-0 mt-3 ms-md-3 mt-md-0">Vizyonumuz</a>
            </div>
            <div class="col-lg-6 fade-up">
                <div class="bg-dark text-white rounded-4 p-4">
                    <h5>Misyonumuz</h5>
                    <p class="text-white-50">Her sanatçının hikayesini özgün tutarken global ses standartlarını yakalamasına rehberlik etmek.</p>
                    <div class="d-flex gap-4 mt-4">
                        <div>
                            <span class="d-block text-white-50">Topluluk</span>
                            <strong>Creative Hub</strong>
                        </div>
                        <div>
                            <span class="d-block text-white-50">Teknoloji</span>
                            <strong>Dolby Atmos</strong>
                        </div>
                        <div>
                            <span class="d-block text-white-50">Destek</span>
                            <strong>Expert Crew</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-3">Slotları kontrol et, 30 saniyede rezervasyon yap.</h2>
                <p class="text-white-50 mb-0">Telefon: <?php echo esc_html($contact['phone']); ?> · E-posta: <?php echo esc_html($contact['email']); ?></p>
            </div>
            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                <a href="<?php echo esc_url($booking_url); ?>" class="btn btn-pogo">Rezervasyon Sayfasına Git</a>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
