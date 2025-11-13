<?php
/**
 * PogoStudios theme functions and definitions
 */

if (!defined('POGOSTUDIOS_VERSION')) {
    define('POGOSTUDIOS_VERSION', '1.0.0');
}

if (is_admin()) {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * Theme supports & menus
 */
function pogostudios_setup()
{
    load_theme_textdomain('pogostudios', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('custom-logo');

    register_nav_menus([
        'primary' => __('Primary Menu', 'pogostudios'),
        'footer' => __('Footer Menu', 'pogostudios'),
    ]);
}
add_action('after_setup_theme', 'pogostudios_setup');

/**
 * Enqueue assets
 */
function pogostudios_enqueue_assets()
{
    wp_enqueue_style('pogostudios-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', [], '5.3.2');
    wp_enqueue_style('pogostudios-style', get_stylesheet_uri(), ['pogostudios-bootstrap'], POGOSTUDIOS_VERSION);

    wp_enqueue_script('pogostudios-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', [], '5.3.2', true);
    wp_enqueue_script('pogostudios-main', get_template_directory_uri() . '/assets/js/main.js', ['jquery'], POGOSTUDIOS_VERSION, true);

    wp_localize_script('pogostudios-main', 'PogoStudiosData', [
        'restUrl' => esc_url_raw(rest_url('pogostudios/v1/bookings')),
        'nonce' => wp_create_nonce('wp_rest'),
        'availability' => [
            'timeSlots' => pogostudios_get_available_time_slots(),
            'blackoutDates' => pogostudios_get_blackout_dates(),
        ],
        'strings' => [
            'slotTaken' => __('This slot is already booked. Please choose another time.', 'pogostudios'),
            'bookingSuccess' => __('Your session is confirmed! We will reach out shortly.', 'pogostudios'),
            'bookingError' => __('Something went wrong. Please try again or contact the studio.', 'pogostudios'),
        ],
    ]);
}
add_action('wp_enqueue_scripts', 'pogostudios_enqueue_assets');

function pogo_enqueue_fonts()
{
    wp_enqueue_style(
        'pogo-fonts',
        'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&family=Inter:wght@400;500;600&display=swap',
        [],
        null
    );
}
add_action('wp_enqueue_scripts', 'pogo_enqueue_fonts');

function pogo_load_fonts()
{
    wp_enqueue_style(
        'pogo-google-fonts',
        'https://fonts.googleapis.com/css2?family=Literata:opsz,wght@7..72,300;7..72,400;7..72,500&display=swap',
        [],
        null
    );
}
add_action('wp_enqueue_scripts', 'pogo_load_fonts');

function pogostudios_enqueue_agenda_assets()
{
    if (is_page_template('template-booking-agenda.php')) {
        $agenda_js = get_template_directory() . '/assets/js/agenda.js';
        $agenda_version = POGOSTUDIOS_VERSION;
        if (file_exists($agenda_js)) {
            $agenda_version .= '-' . filemtime($agenda_js);
        }
        wp_enqueue_script(
            'pogostudios-agenda',
            get_template_directory_uri() . '/assets/js/agenda.js',
            ['jquery'],
            $agenda_version,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'pogostudios_enqueue_agenda_assets');

/**
 * Register Booking CPT
 */
function pogostudios_register_booking_cpt()
{
    $labels = [
        'name' => __('Bookings', 'pogostudios'),
        'singular_name' => __('Booking', 'pogostudios'),
        'add_new_item' => __('Add New Booking', 'pogostudios'),
        'view_items' => __('View Bookings', 'pogostudios'),
    ];

    $args = [
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'supports' => ['title'],
        'menu_icon' => 'dashicons-calendar-alt',
    ];

    register_post_type('pogostudios_booking', $args);
}
add_action('init', 'pogostudios_register_booking_cpt');

function pogostudios_add_booking_metaboxes()
{
    add_meta_box(
        'pogostudios-booking-details',
        __('Rezervasyon Detayları', 'pogostudios'),
        'pogostudios_render_booking_metabox',
        'pogostudios_booking',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pogostudios_add_booking_metaboxes');

function pogostudios_render_booking_metabox($post)
{
    wp_nonce_field('pogostudios_save_booking', 'pogostudios_booking_nonce');
    $fields = [
        'booking_name' => get_post_meta($post->ID, 'booking_name', true),
        'booking_email' => get_post_meta($post->ID, 'booking_email', true),
        'booking_phone' => get_post_meta($post->ID, 'booking_phone', true),
        'booking_date' => get_post_meta($post->ID, 'booking_date', true),
        'booking_time' => get_post_meta($post->ID, 'booking_time', true),
        'booking_notes' => get_post_meta($post->ID, 'booking_notes', true),
    ];
    $status = get_post_meta($post->ID, 'booking_status', true);
    if (!$status) {
        $status = 'pending';
    }
    $statuses = pogostudios_get_booking_statuses();
    ?>
    <table class="form-table">
        <tr>
            <th><?php esc_html_e('İsim', 'pogostudios'); ?></th>
            <td><input type="text" name="booking_name" class="regular-text" value="<?php echo esc_attr($fields['booking_name']); ?>"></td>
        </tr>
        <tr>
            <th><?php esc_html_e('E-posta', 'pogostudios'); ?></th>
            <td><input type="email" name="booking_email" class="regular-text" value="<?php echo esc_attr($fields['booking_email']); ?>"></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Telefon', 'pogostudios'); ?></th>
            <td><input type="text" name="booking_phone" class="regular-text" value="<?php echo esc_attr($fields['booking_phone']); ?>"></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Tarih', 'pogostudios'); ?></th>
            <td><input type="date" name="booking_date" value="<?php echo esc_attr($fields['booking_date']); ?>"></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Saat', 'pogostudios'); ?></th>
            <td><input type="text" name="booking_time" value="<?php echo esc_attr($fields['booking_time']); ?>"></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Notlar', 'pogostudios'); ?></th>
            <td><textarea name="booking_notes" rows="3" class="large-text"><?php echo esc_textarea($fields['booking_notes']); ?></textarea></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Durum', 'pogostudios'); ?></th>
            <td>
                <select name="booking_status">
                    <?php foreach ($statuses as $key => $label) : ?>
                        <option value="<?php echo esc_attr($key); ?>" <?php selected($status, $key); ?>>
                            <?php echo esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description"><?php esc_html_e('İptal edilen rezervasyonlar otomatik olarak slot listesinden düşer.', 'pogostudios'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

function pogostudios_save_booking_metabox($post_id)
{
    if (!isset($_POST['pogostudios_booking_nonce']) || !wp_verify_nonce($_POST['pogostudios_booking_nonce'], 'pogostudios_save_booking')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $raw_email = sanitize_email($_POST['booking_email'] ?? '');
    $sanitized_phone = pogostudios_sanitize_phone($_POST['booking_phone'] ?? '');
    if ($sanitized_phone && !pogostudios_is_valid_phone($sanitized_phone)) {
        $sanitized_phone = '';
    }

    $booking_date = sanitize_text_field($_POST['booking_date'] ?? '');
    if ($booking_date && !pogostudios_is_valid_booking_date($booking_date)) {
        $booking_date = '';
    }

    $fields = [
        'booking_name' => sanitize_text_field($_POST['booking_name'] ?? ''),
        'booking_email' => $raw_email && filter_var($raw_email, FILTER_VALIDATE_EMAIL) ? $raw_email : '',
        'booking_phone' => $sanitized_phone,
        'booking_date' => $booking_date,
        'booking_time' => sanitize_text_field($_POST['booking_time'] ?? ''),
        'booking_notes' => sanitize_textarea_field($_POST['booking_notes'] ?? ''),
        'booking_status' => sanitize_text_field($_POST['booking_status'] ?? 'pending'),
    ];

    if (!in_array($fields['booking_status'], array_keys(pogostudios_get_booking_statuses()), true)) {
        $fields['booking_status'] = 'pending';
    }

    foreach ($fields as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }
}
add_action('save_post_pogostudios_booking', 'pogostudios_save_booking_metabox');

function pogostudios_booking_columns($columns)
{
    $new_columns = [];
    foreach ($columns as $key => $label) {
        $new_columns[$key] = $label;
        if ($key === 'title') {
            $new_columns['booking_date'] = __('Tarih', 'pogostudios');
            $new_columns['booking_time'] = __('Saat', 'pogostudios');
            $new_columns['booking_status'] = __('Durum', 'pogostudios');
        }
    }
    return $new_columns;
}
add_filter('manage_pogostudios_booking_posts_columns', 'pogostudios_booking_columns');

function pogostudios_booking_custom_column($column, $post_id)
{
    switch ($column) {
        case 'booking_date':
            echo esc_html(get_post_meta($post_id, 'booking_date', true));
            break;
        case 'booking_time':
            echo esc_html(get_post_meta($post_id, 'booking_time', true));
            break;
        case 'booking_status':
            $status = get_post_meta($post_id, 'booking_status', true) ?: 'pending';
            $statuses = pogostudios_get_booking_statuses();
            echo esc_html($statuses[$status] ?? ucfirst($status));
            break;
    }
}
add_action('manage_pogostudios_booking_posts_custom_column', 'pogostudios_booking_custom_column', 10, 2);

/**
 * Admin settings (Theme options & booking availability)
 */
function pogostudios_register_settings()
{
    register_setting('pogostudios_contact_group', 'pogostudios_contact_phone');
    register_setting('pogostudios_contact_group', 'pogostudios_contact_email');
    register_setting('pogostudios_contact_group', 'pogostudios_contact_address');
    register_setting('pogostudios_contact_group', 'pogostudios_contact_social');

    register_setting('pogostudios_booking_group', 'pogostudios_available_time_slots');
    register_setting('pogostudios_booking_group', 'pogostudios_blackout_dates');
}
add_action('admin_init', 'pogostudios_register_settings');

function pogostudios_admin_menu()
{
    add_menu_page(
        __('PogoStudios Settings', 'pogostudios'),
        __('PogoStudios', 'pogostudios'),
        'manage_options',
        'pogostudios-settings',
        'pogostudios_render_settings_page',
        'dashicons-format-audio',
        60
    );

    add_submenu_page(
        'pogostudios-settings',
        __('Booking Availability', 'pogostudios'),
        __('Booking Availability', 'pogostudios'),
        'manage_options',
        'pogostudios-booking',
        'pogostudios_render_booking_page'
    );
}
add_action('admin_menu', 'pogostudios_admin_menu');

function pogostudios_render_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('PogoStudios Contact Settings', 'pogostudios'); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('pogostudios_contact_group'); ?>
            <?php do_settings_sections('pogostudios_contact_group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Phone', 'pogostudios'); ?></th>
                    <td><input type="text" name="pogostudios_contact_phone" value="<?php echo esc_attr(get_option('pogostudios_contact_phone')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Email', 'pogostudios'); ?></th>
                    <td><input type="email" name="pogostudios_contact_email" value="<?php echo esc_attr(get_option('pogostudios_contact_email')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Address', 'pogostudios'); ?></th>
                    <td><textarea name="pogostudios_contact_address" rows="3" class="large-text"><?php echo esc_textarea(get_option('pogostudios_contact_address')); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Social Profiles (comma separated URLs)', 'pogostudios'); ?></th>
                    <td><input type="text" name="pogostudios_contact_social" value="<?php echo esc_attr(get_option('pogostudios_contact_social')); ?>" class="regular-text"></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function pogostudios_render_booking_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Booking Availability', 'pogostudios'); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('pogostudios_booking_group'); ?>
            <?php do_settings_sections('pogostudios_booking_group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Available Time Slots', 'pogostudios'); ?></th>
                    <td>
                        <textarea name="pogostudios_available_time_slots" rows="4" class="large-text" placeholder="09:00,10:00,11:00,12:00,13:00,14:00,15:00,16:00,17:00"><?php echo esc_textarea(get_option('pogostudios_available_time_slots')); ?></textarea>
                        <p class="description"><?php esc_html_e('Enter comma separated hourly slots (24h format).', 'pogostudios'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Blackout Dates', 'pogostudios'); ?></th>
                    <td>
                        <textarea name="pogostudios_blackout_dates" rows="3" class="large-text" placeholder="2024-01-01,2024-01-02"><?php echo esc_textarea(get_option('pogostudios_blackout_dates')); ?></textarea>
                        <p class="description"><?php esc_html_e('Enter comma separated dates (YYYY-MM-DD) that should be disabled.', 'pogostudios'); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Save Availability', 'pogostudios')); ?>
        </form>
    </div>
    <?php
}

/**
 * Helpers
 */
function pogostudios_get_available_time_slots()
{
    $slots = get_option('pogostudios_available_time_slots');
    if (!$slots) {
        $slots = '09:00,10:00,11:00,12:00,13:00,14:00,15:00,16:00,17:00';
    }
    $slots_array = array_filter(array_map('trim', explode(',', $slots)));
    return array_values($slots_array);
}

function pogostudios_get_blackout_dates()
{
    $dates = get_option('pogostudios_blackout_dates');
    if (!$dates) {
        return [];
    }
    $dates_array = array_filter(array_map('trim', explode(',', $dates)));
    return array_values($dates_array);
}

/**
 * REST endpoints
 */
function pogostudios_register_rest_routes()
{
    register_rest_route('pogostudios/v1', '/bookings', [
        [
            'methods' => WP_REST_Server::READABLE,
            'callback' => 'pogostudios_rest_get_bookings',
            'permission_callback' => '__return_true',
            'args' => [
                'date' => [
                    'required' => true,
                    'sanitize_callback' => 'sanitize_text_field',
                ],
            ],
        ],
        [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => 'pogostudios_rest_create_booking',
            'permission_callback' => '__return_true',
            'args' => [
                'name' => ['required' => true, 'sanitize_callback' => 'sanitize_text_field'],
                'email' => ['required' => true, 'sanitize_callback' => 'sanitize_email'],
                'phone' => ['required' => true, 'sanitize_callback' => 'sanitize_text_field'],
                'date' => ['required' => true, 'sanitize_callback' => 'sanitize_text_field'],
                'time' => ['required' => true, 'sanitize_callback' => 'sanitize_text_field'],
                'notes' => ['required' => false, 'sanitize_callback' => 'sanitize_textarea_field'],
            ],
        ],
    ]);
}
add_action('rest_api_init', 'pogostudios_register_rest_routes');

function pogostudios_rest_get_bookings(WP_REST_Request $request)
{
    $date = $request->get_param('date');
    if (!pogostudios_is_valid_booking_date($date)) {
        return new WP_Error('invalid_date', __('Geçersiz tarih formatı.', 'pogostudios'), ['status' => 400]);
    }

    $bookings = get_posts([
        'post_type' => 'pogostudios_booking',
        'numberposts' => -1,
        'meta_key' => 'booking_date',
        'meta_value' => $date,
        'fields' => 'ids',
    ]);

    $taken = [];
    $blocking_statuses = pogostudios_get_blocking_statuses();
    foreach ($bookings as $booking_id) {
        $status = get_post_meta($booking_id, 'booking_status', true);
        if (!in_array($status ?: 'pending', $blocking_statuses, true)) {
            continue;
        }
        $taken[] = get_post_meta($booking_id, 'booking_time', true);
    }

    return rest_ensure_response([
        'date' => $date,
        'taken' => array_values(array_filter($taken)),
    ]);
}

function pogostudios_rest_create_booking(WP_REST_Request $request)
{
    $nonce = $request->get_header('x_wp_nonce');
    if (!$nonce || !wp_verify_nonce($nonce, 'wp_rest')) {
        return new WP_Error('invalid_nonce', __('Geçersiz istek.', 'pogostudios'), ['status' => 403]);
    }

    $date = $request->get_param('date');
    $time = $request->get_param('time');
    if (!pogostudios_is_valid_booking_date($date)) {
        return new WP_Error('invalid_date', __('Geçersiz tarih formatı.', 'pogostudios'), ['status' => 400]);
    }
    $raw_email = sanitize_email($request->get_param('email'));
    $email = $raw_email && filter_var($raw_email, FILTER_VALIDATE_EMAIL) ? $raw_email : '';
    if (!$email) {
        return new WP_Error('invalid_email', __('Lütfen geçerli bir e-posta adresi girin.', 'pogostudios'), ['status' => 400]);
    }

    $phone = pogostudios_sanitize_phone($request->get_param('phone'));
    if (!$phone || !pogostudios_is_valid_phone($phone)) {
        return new WP_Error('invalid_phone', __('Telefon numarası +90 sonrası en fazla 11 haneli olmalıdır.', 'pogostudios'), ['status' => 400]);
    }

    if (in_array($date, pogostudios_get_blackout_dates(), true)) {
        return new WP_Error('slot_unavailable', __('Selected date is unavailable.', 'pogostudios'), ['status' => 400]);
    }

    $available_slots = pogostudios_get_available_time_slots();
    if (!in_array($time, $available_slots, true)) {
        return new WP_Error('invalid_slot', __('Selected time is invalid.', 'pogostudios'), ['status' => 400]);
    }

    $existing = get_posts([
        'post_type' => 'pogostudios_booking',
        'numberposts' => 1,
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'booking_date',
                'value' => $date,
            ],
            [
                'key' => 'booking_time',
                'value' => $time,
            ],
        ],
        'fields' => 'ids',
    ]);

    $blocking_statuses = pogostudios_get_blocking_statuses();
    $active_existing = [];
    foreach ($existing as $existing_id) {
        $status = get_post_meta($existing_id, 'booking_status', true) ?: 'pending';
        if (in_array($status, $blocking_statuses, true)) {
            $active_existing[] = $existing_id;
        }
    }

    if (!empty($active_existing)) {
        return new WP_Error('slot_taken', __('This slot has already been booked.', 'pogostudios'), ['status' => 409]);
    }

    $booking_id = wp_insert_post([
        'post_type' => 'pogostudios_booking',
        'post_title' => sprintf('%s - %s', sanitize_text_field($request->get_param('name')), $date),
        'post_status' => 'publish',
        'meta_input' => [
            'booking_name' => sanitize_text_field($request->get_param('name')),
            'booking_email' => $email,
            'booking_phone' => $phone,
            'booking_date' => $date,
            'booking_time' => $time,
            'booking_notes' => sanitize_textarea_field($request->get_param('notes')),
            'booking_status' => 'pending',
        ],
    ]);

    if (is_wp_error($booking_id)) {
        return $booking_id;
    }

    $payload = [
        'name' => sanitize_text_field($request->get_param('name')),
        'email' => $email,
        'phone' => $phone,
        'date' => $date,
        'time' => $time,
        'notes' => sanitize_textarea_field($request->get_param('notes')),
    ];

    pogostudios_send_booking_email($payload);

    return rest_ensure_response([
        'success' => true,
        'message' => __('Booking confirmed.', 'pogostudios'),
    ]);
}

/**
 * Contact form handler
 */
function pogostudios_handle_contact_form()
{
    if (empty($_POST['pogostudios_contact_nonce']) || !wp_verify_nonce($_POST['pogostudios_contact_nonce'], 'pogostudios_contact')) {
        wp_die(__('Invalid request', 'pogostudios'));
    }

    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        wp_redirect(add_query_arg('status', 'error', wp_get_referer()));
        exit;
    }

    $body = sprintf("İsim: %s\nEmail: %s\nMesaj: %s", $name, $email, $message);
    wp_mail(get_option('admin_email'), __('Yeni İletişim Mesajı', 'pogostudios'), $body);

    wp_redirect(add_query_arg('status', 'success', wp_get_referer()));
    exit;
}
add_action('admin_post_nopriv_pogostudios_contact', 'pogostudios_handle_contact_form');
add_action('admin_post_pogostudios_contact', 'pogostudios_handle_contact_form');

/**
 * Utility: fetch contact info safely
 */
function pogostudios_get_contact_info()
{
    return [
        'phone' => get_option('pogostudios_contact_phone', '+90 555 555 55 55'),
        'email' => get_option('pogostudios_contact_email', 'info@pogostudios.com'),
        'address' => get_option('pogostudios_contact_address', 'Istanbul, Turkey'),
        'social' => array_filter(array_map('trim', explode(',', (string) get_option('pogostudios_contact_social', 'https://instagram.com/pogostudios,https://youtube.com/pogostudios')))),
    ];
}

function pogostudios_get_notification_email()
{
    $contact_email = get_option('pogostudios_contact_email');
    if ($contact_email && is_email($contact_email)) {
        return $contact_email;
    }
    return get_option('admin_email');
}

function pogostudios_get_booking_statuses()
{
    return [
        'pending' => __('Onay Bekliyor', 'pogostudios'),
        'confirmed' => __('Onaylandı', 'pogostudios'),
        'cancelled' => __('İptal Edildi', 'pogostudios'),
        'active' => __('Aktif', 'pogostudios'),
    ];
}

function pogostudios_get_blocking_statuses()
{
    return ['pending', 'confirmed', 'active'];
}

function pogostudios_sanitize_phone($value)
{
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }
    $digits = preg_replace('/\D+/', '', $value);
    if ($digits === '') {
        return '';
    }
    $maxLength = 12;
    if (strlen($digits) > $maxLength) {
        $digits = substr($digits, 0, $maxLength);
    }
    $has_plus = strpos($value, '+') === 0;
    return $has_plus ? '+' . $digits : $digits;
}

function pogostudios_is_valid_phone($value)
{
    if ($value === '') {
        return false;
    }
    $digits = preg_replace('/\D+/', '', $value);
    if ($digits === '') {
        return false;
    }
    $localDigits = strlen($digits) > 2 ? substr($digits, -10) : $digits;
    return strlen($localDigits) === 10;
}

function pogostudios_is_valid_booking_date($value)
{
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
        return false;
    }
    [$year, $month, $day] = array_map('intval', explode('-', $value));
    return checkdate($month, $day, $year);
}

function pogostudios_send_booking_email($payload)
{
    $admin_email = pogostudios_get_notification_email();
    if (!$admin_email) {
        return;
    }

    $subject = sprintf(__('Yeni Rezervasyon: %s %s', 'pogostudios'), $payload['date'], $payload['time']);
    $message = sprintf(
        "İsim: %s\nE-posta: %s\nTelefon: %s\nTarih: %s\nSaat: %s\nNotlar: %s",
        $payload['name'],
        $payload['email'],
        $payload['phone'],
        $payload['date'],
        $payload['time'],
        $payload['notes']
    );

    $headers = ['Content-Type: text/plain; charset=UTF-8'];
    if (!empty($payload['email']) && is_email($payload['email'])) {
        $headers[] = 'Reply-To: ' . $payload['name'] . ' <' . $payload['email'] . '>';
    }

    wp_mail($admin_email, $subject, $message, $headers);
}

function pogostudios_get_page_url($slug, $fallback = '/')
{
    $page = get_page_by_path($slug);
    if ($page) {
        return get_permalink($page);
    }
    return esc_url(home_url($fallback));
}

/**
 * Ensure menu links get Bootstrap classes
 */
function pogostudios_nav_link_class($atts, $item, $args, $depth)
{
    if (isset($args->theme_location) && $args->theme_location === 'primary') {
        $atts['class'] = trim(('nav-link fw-semibold ' . ($atts['class'] ?? '')));
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'pogostudios_nav_link_class', 10, 4);

/**
 * Recommend SEO & security plugins
 */
function pogostudios_recommended_plugins_notice()
{
    if (!current_user_can('activate_plugins')) {
        return;
    }

    $missing = [];
    if (!is_plugin_active('wordpress-seo/wp-seo.php')) {
        $missing[] = 'Yoast SEO';
    }
    if (!is_plugin_active('wordfence/wordfence.php')) {
        $missing[] = 'Wordfence Security';
    }
    if (!is_plugin_active('litespeed-cache/litespeed-cache.php') && !is_plugin_active('wp-rocket/wp-rocket.php')) {
        $missing[] = 'LiteSpeed Cache veya WP Rocket';
    }

    if (!$missing) {
        return;
    }

    echo '<div class=\"notice notice-info\"><p><strong>PogoStudios:</strong> Lütfen şu eklentileri kurun: ' . esc_html(implode(', ', $missing)) . '.</p></div>';
}
add_action('admin_notices', 'pogostudios_recommended_plugins_notice');
