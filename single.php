<?php get_header(); ?>
<section class="py-5">
    <div class="container">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article class="mx-auto" style="max-width: 760px;">
                    <h1 class="section-title mb-3"><?php the_title(); ?></h1>
                    <p class="text-white-50 mb-4"><?php echo get_the_date(); ?> · <?php esc_html_e('by', 'pogostudios'); ?> <?php the_author(); ?></p>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="mb-4"><?php the_post_thumbnail('large', ['class' => 'img-fluid rounded-4']); ?></div>
                    <?php endif; ?>
                    <div class="content"><?php the_content(); ?></div>
                </article>
            <?php endwhile; endif; ?>
    </div>
</section>
<?php get_footer(); ?>
