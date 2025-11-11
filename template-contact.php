<?php
/* Template Name: Contact */
get_header();
$contact = pogostudios_get_contact_info();
$status = sanitize_text_field($_GET['status'] ?? '');
?>
<section class="py-5 section-dark">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <h1 class="section-title">Bizimle İletişime Geç</h1>
                <p class="text-white-50 mb-4">Formu doldurarak prodüksiyon, rezervasyon veya kurumsal teklifler için bize ulaş.</p>
                <?php if ($status === 'success') : ?>
                    <div class="alert alert-success">Mesajınız alındı. En kısa sürede dönüş yapacağız.</div>
                <?php elseif ($status === 'error') : ?>
                    <div class="alert alert-danger">Lütfen tüm alanları doldurun.</div>
                <?php endif; ?>
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="contact-card">
                    <input type="hidden" name="action" value="pogostudios_contact">
                    <?php wp_nonce_field('pogostudios_contact', 'pogostudios_contact_nonce'); ?>
                    <div class="mb-3">
                        <label class="form-label">İsim</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-posta</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mesaj</label>
                        <textarea name="message" rows="4" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-pogo w-100">Gönder</button>
                </form>
            </div>
            <div class="col-lg-6">
                <div class="map-embed mb-4">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12041.464969287924!2d28.97953!3d41.015137!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0:0x0!2zNDHCsDAwJzU0LjUiTiAyOMKwNTgnNDkuMCJF!5e0!3m2!1str!2str!4v1700000000000" allowfullscreen loading="lazy"></iframe>
                </div>
                <div class="glassy-card">
                    <h5>Stüdyo Bilgileri</h5>
                    <p class="mb-1 text-white-50"><strong>Telefon:</strong> <?php echo esc_html($contact['phone']); ?></p>
                    <p class="mb-1 text-white-50"><strong>E-posta:</strong> <?php echo esc_html($contact['email']); ?></p>
                    <p class="text-white-50"><strong>Adres:</strong> <?php echo esc_html($contact['address']); ?></p>
                    <div class="d-flex gap-3 flex-wrap">
                        <?php foreach ($contact['social'] as $url) : ?>
                            <a class="text-white-50" href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener">Sosyal</a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
