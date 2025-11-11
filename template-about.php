<?php
/* Template Name: About & Vision */
get_header();
$booking_url = function_exists('pogostudios_get_page_url') ? pogostudios_get_page_url('rezervasyon', '/booking') : home_url('/booking');
?>
<section class="py-5 section-dark">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="tag-pill mb-3">Biz Kimiz?</span>
                <h1 class="section-title">PogoStudios Hikayesi</h1>
                <p>2012 yılında İstanbul’da kurulan PogoStudios, genç prodüktörlerin ve bağımsız müzisyenlerin ihtiyaç duyduğu yaratıcı alanı sunmak için doğdu. Bugün, Dolby Atmos sertifikalı miks odaları, vintage mikrofon koleksiyonu ve analog mastering zinciriyle küresel ses standartlarını yakalıyoruz.</p>
                <p>Stüdyomuz; kayıt, mixing, mastering, podcast, ses tasarımı ve etkinlik yayınları için özelleşmiş 8 farklı odaya sahip. Her projede sanatçıyla birlikte büyüyen butik bir yaklaşım benimsiyoruz.</p>
                <a href="<?php echo esc_url($booking_url); ?>" class="btn btn-pogo mt-3">Rezervasyon Talebi Oluştur</a>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="glassy-card h-100">
                            <h4>Vizyon</h4>
                            <p class="text-white-50">Yerel sahneleri uluslararası üretim kalitesiyle buluşturmak.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="glassy-card h-100">
                            <h4>Misyon</h4>
                            <p class="text-white-50">Sanatçıların özgün seslerini güvenle kaydedebileceği kapsayıcı alanlar yaratmak.</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="glassy-card">
                            <h4>Değerlerimiz</h4>
                            <ul class="mb-0 text-white-50">
                                <li class="mb-2">Şeffaf iş akışı & anlaşılır fiyatlama</li>
                                <li class="mb-2">Sürdürülebilirlik ve topluluk odaklılık</li>
                                <li class="mb-2">Teknolojik merak ve sürekli gelişim</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-uppercase text-white-50">Ekibimiz</span>
            <h2 class="section-title">Ses Mühendisleri & Prodüktörler</h2>
        </div>
        <div class="row g-4">
            <?php
            $team = [
                ['name' => 'Selim Aksoy', 'role' => 'Head Producer', 'bio' => 'Büyük sahne prodüksiyonlarında 15+ yıl deneyim, analog miks uzmanı.'],
                ['name' => 'Maya Karaca', 'role' => 'Sound Designer', 'bio' => 'Podcast ve film ses tasarımında ödüllü projeler, Dolby Atmos kalibrasyonları.'],
                ['name' => 'Kerem Oğuz', 'role' => 'Booking Lead', 'bio' => 'Rezervasyon & müşteri deneyimi lideri, esnek paket danışmanı.'],
            ];
            foreach ($team as $member) : ?>
                <div class="col-md-4">
                    <div class="glassy-card h-100">
                        <h4 class="mb-1"><?php echo esc_html($member['name']); ?></h4>
                        <p class="text-white-50"><?php echo esc_html($member['role']); ?></p>
                        <p class="text-white-50"><?php echo esc_html($member['bio']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <h3 class="section-title">Galeri</h3>
                <p>Analog rack’lerimizden lounge alanlarımıza kadar stüdyoyu keşfet.</p>
                <ul class="list-unstyled">
                    <li class="mb-2">• Live Room A - Drum ready</li>
                    <li class="mb-2">• Control Room - SSL Matrix</li>
                    <li class="mb-2">• Creative Lab - Modüler synth duvarı</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6"><div class="ratio ratio-1x1 rounded-4" style="background: url('https://images.unsplash.com/photo-1487215078519-e21cc028cb29?auto=format&fit=crop&w=800&q=80') center/cover;"></div></div>
                    <div class="col-6"><div class="ratio ratio-1x1 rounded-4" style="background: url('https://images.unsplash.com/photo-1485579149621-3123dd979885?auto=format&fit=crop&w=800&q=80') center/cover;"></div></div>
                    <div class="col-6"><div class="ratio ratio-1x1 rounded-4" style="background: url('https://images.unsplash.com/photo-1487180144351-b8472da7d491?auto=format&fit=crop&w=800&q=80') center/cover;"></div></div>
                    <div class="col-6"><div class="ratio ratio-1x1 rounded-4" style="background: url('https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=800&q=80') center/cover;"></div></div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
