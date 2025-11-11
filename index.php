<?php get_header(); ?>
<section class="py-5">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="row g-4">
                <?php while (have_posts()) : the_post(); ?>
                    <div class="col-md-6">
                        <article class="glassy-card h-100">
                            <h2><a href="<?php the_permalink(); ?>" class="text-white"><?php the_title(); ?></a></h2>
                            <p class="text-white-50"><?php echo wp_trim_words(get_the_content(), 30); ?></p>
                            <a class="btn btn-link text-uppercase text-white" href="<?php the_permalink(); ?>"><?php esc_html_e('Devamını Oku', 'pogostudios'); ?></a>
                        </article>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="mt-5">
                <?php
                the_posts_pagination([
                    'mid_size' => 2,
                    'prev_text' => __('Önceki', 'pogostudios'),
                    'next_text' => __('Sonraki', 'pogostudios'),
                ]);
                ?>
            </div>
        <?php else : ?>
            <p class="text-white-50"><?php esc_html_e('Henüz içerik eklenmedi.', 'pogostudios'); ?></p>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
