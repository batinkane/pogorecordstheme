<?php get_header(); ?>
<section class="py-5">
    <div class="container">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article class="mx-auto" style="max-width: 840px;">
                    <h1 class="section-title mb-4"><?php the_title(); ?></h1>
                    <div class="content"><?php the_content(); ?></div>
                </article>
            <?php endwhile; endif; ?>
    </div>
</section>
<?php get_footer(); ?>
