<?php
/* Template Name: Vision */
get_header();
$booking_url = function_exists('pogostudios_get_page_url') ? pogostudios_get_page_url('rezervasyon', '/booking') : home_url('/booking');
?>
<section class="py-5 section-dark">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-uppercase text-white-50">Vizyonumuz</span>
            <h1 class="section-title">Müziğin Geleceğini Şekillendirmek</h1>
            <p class="mx-auto" style="max-width: 720px;">PogoStudios, bölgedeki tüm bağımsız sanatçılar için bütünsel bir üretim deneyimi sunmayı hedefler. Teknolojiyi yaratıcılıkla aynı potada eritiyor, sürdürülebilir ve kapsayıcı bir stüdyo kültürü oluşturuyoruz.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="glassy-card h-100">
                    <h4>Topluluk</h4>
                    <p class="text-white-50">Yerel sahneyi destekleyen, işbirlikçi bir creative hub.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glassy-card h-100">
                    <h4>Teknoloji</h4>
                    <p class="text-white-50">Dolby Atmos, analog mastering ve uzaktan kayıt altyapısı.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glassy-card h-100">
                    <h4>Sürdürülebilirlik</h4>
                    <p class="text-white-50">Enerji tasarruflu ekipman, karbon ayak izi raporlama, kapsayıcı programlar.</p>
                </div>
            </div>
        </div>
        <div class="mt-5 p-5 bg-dark text-white rounded-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h2>2025 Yol Haritası</h2>
                    <ul class="text-white-50">
                        <li>Yaratıcı burs programı ile 12 genç prodüktörün desteklenmesi</li>
                        <li>Live streaming stüdyosunun genişletilmesi</li>
                        <li>Ses mühendisliği eğitimleri için açık kaynak içerikler</li>
                    </ul>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="<?php echo esc_url($booking_url); ?>" class="btn btn-pogo">Vizyonun Parçası Ol</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
