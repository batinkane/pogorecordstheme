</main>
<?php $contact = pogostudios_get_contact_info(); ?>
<footer class="pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="text-white">PogoStudios</h5>
                <p class="text-white-50">Profesyonel kayıt, prodüksiyon ve miksaj hizmetleri. Enerjinizi sahneye taşıyın.</p>
            </div>
            <div class="col-md-4">
                <h6 class="text-uppercase text-white">İletişim</h6>
                <p class="mb-1 text-white-50"><?php echo esc_html($contact['phone']); ?></p>
                <p class="mb-1 text-white-50"><?php echo esc_html($contact['email']); ?></p>
                <p class="mb-0 text-white-50"><?php echo esc_html($contact['address']); ?></p>
            </div>
            <div class="col-md-4">
                <h6 class="text-uppercase text-white">Bizi Takip Et</h6>
                <div class="d-flex gap-3">
                    <?php foreach ($contact['social'] as $url) : ?>
                        <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener" class="text-white-50">@pogostudios</a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="border-top border-secondary mt-4 pt-3 text-center text-white-50">
            &copy; <?php echo esc_html(date('Y')); ?> PogoStudios. All rights reserved.
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
