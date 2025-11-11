<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PogoStudios - Modern music studio with professional recording, production and rehearsal spaces.">
    <meta property="og:title" content="PogoStudios">
    <meta property="og:description" content="Book world-class music sessions at PogoStudios.">
    <meta property="og:type" content="website">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="sticky-top site-header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark py-3">
            <a class="navbar-brand fw-bold text-uppercase text-white" href="<?php echo esc_url(home_url('/')); ?>">PogoStudios</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#pogoNav" aria-controls="pogoNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="pogoNav">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => 'navbar-nav ms-auto mb-2 mb-lg-0 gap-3 align-items-lg-center text-uppercase fw-semibold',
                    'fallback_cb' => '__return_empty_string',
                    'depth' => 1,
                ]);
                ?>
                <a href="<?php echo esc_url(function_exists('pogostudios_get_page_url') ? pogostudios_get_page_url('rezervasyon', '/booking') : home_url('/booking')); ?>" class="btn btn-pogo ms-lg-3 mt-3 mt-lg-0">Rezervasyon</a>
            </div>
        </nav>
    </div>
</header>
<main>
