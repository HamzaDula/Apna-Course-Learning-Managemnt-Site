<?php
/**
 * Handles loading all the necessary files
 *
 * @package Tutor_Starter
 */

defined('ABSPATH') || exit;

// Content width.
if (!isset($content_width)) {
    $content_width = apply_filters('tutorstarter_content_width', get_theme_mod('content_width_value', 1140));
}

// Theme GLOBALS.
$theme = wp_get_theme();
define('TUTOR_STARTER_VERSION', $theme->get('Version'));

// Load autoloader.
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) :
    require_once dirname(__FILE__) . '/vendor/autoload.php';
endif;

// Include TGMPA class.
if (file_exists(dirname(__FILE__) . '/inc/Custom/class-tgm-plugin-activation.php')) :
    require_once dirname(__FILE__) . '/inc/Custom/class-tgm-plugin-activation.php';
endif;

// Register services.
if (class_exists('Tutor_Starter\\Init')) :
    Tutor_Starter\Init::register_services();
endif;

// Add a meta box to the LearnPress course edit screen
function add_lp_course_availability_date_meta_box() {
    add_meta_box(
        'lp_course_availability_date',
        __('Course Availability Date', 'textdomain'),
        'lp_course_availability_date_meta_box_callback',
        'lp_course', // LearnPress course post type
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_lp_course_availability_date_meta_box');

function lp_course_availability_date_meta_box_callback($post) {
    $availability_date = get_post_meta($post->ID, '_lp_course_availability_date', true);
    echo '<label for="lp_course_availability_date">' . __('Set Course Availability Date', 'textdomain') . '</label>';
    echo '<input type="date" id="lp_course_availability_date" name="lp_course_availability_date" value="' . esc_attr($availability_date) . '" />';
}

// Save the meta box data
function save_lp_course_availability_date_meta_box_data($post_id) {
    if (isset($_POST['lp_course_availability_date'])) {
        update_post_meta($post_id, '_lp_course_availability_date', sanitize_text_field($_POST['lp_course_availability_date']));
    }
}
add_action('save_post', 'save_lp_course_availability_date_meta_box_data');

// Restrict course purchase based on availability date
function restrict_lp_course_purchase($can_purchase, $course_id) {
    $availability_date = get_post_meta($course_id, '_lp_course_availability_date', true);
    if ($availability_date && strtotime($availability_date) > time()) {
        // Course is not available yet, so return false to restrict purchase
        return false;
    }
    return $can_purchase;
}
add_filter('learn-press/user-can-purchase-course', 'restrict_lp_course_purchase', 10, 2);

// Hide the Buy Now button if the course cannot be purchased yet and display a message
function hide_lp_buy_now_button_and_message() {
    global $post;
    if ('lp_course' === get_post_type($post)) {
        $availability_date = get_post_meta($post->ID, '_lp_course_availability_date', true);
        if ($availability_date && strtotime($availability_date) > time()) {
            $availability_date_formatted = date_i18n(get_option('date_format'), strtotime($availability_date));
            echo '<div class="lp-course-availability-message" style="color: red; font-weight: bold;">';
            echo sprintf(__('This course will be available for purchase on %s.', 'textdomain'), $availability_date_formatted);
            echo '</div>';
            // Add a custom class to the button for easier targeting
            echo '<script>document.addEventListener("DOMContentLoaded", function() { document.querySelector(".buy-now-button-class").classList.add("hide-buy-now-button"); });</script>';
        }
    }
}
add_action('learn-press/before-course-buttons', 'hide_lp_buy_now_button_and_message');

// Add custom CSS to hide the button
function hide_lp_buy_now_button_css() {
    echo '<style>.hide-buy-now-button { display: none !important; }</style>';
}
add_action('wp_head', 'hide_lp_buy_now_button_css');


// Schedule an event to send email notifications when the course becomes available
function schedule_lp_course_availability_email($post_id) {
    if (get_post_type($post_id) != 'lp_course') {
        return;
    }

    $availability_date = get_post_meta($post_id, '_lp_course_availability_date', true);
    if ($availability_date) {
        $timestamp = strtotime($availability_date);
        wp_schedule_single_event($timestamp, 'send_lp_course_availability_email', array($post_id));
    }
}
add_action('save_post', 'schedule_lp_course_availability_email');

// Send email notifications
function send_lp_course_availability_email($course_id) {
    $users = get_users();
    $course_title = get_the_title($course_id);
    $course_link = get_permalink($course_id);

    foreach ($users as $user) {
        $to = $user->user_email;
        $subject = __('Course Now Available', 'textdomain');
        $message = sprintf(__('The course "%s" is now available for purchase. You can view the course here: %s', 'textdomain'), $course_title, $course_link);

        // Log the email details
        error_log('Sending email to: ' . $to);
        error_log('Subject: ' . $subject);
        error_log('Message: ' . $message);

        // Uncomment the line below to send the email
        wp_mail($to, $subject, $message);
    }
}
add_action('send_lp_course_availability_email', 'send_lp_course_availability_email');

// Script to hide the Buy Now button
add_action( 'wp_footer', 'hide_lp_buy_now_button_script' );
function hide_lp_buy_now_button_script() {
    global $post;
    if ( 'lp_course' === get_post_type( $post ) ) {
        $availability_date = get_post_meta( $post->ID, '_lp_course_availability_date', true );
        if ( $availability_date && strtotime( $availability_date ) > time() ) {
            ?>
            <style>.lp-button.button.button-purchase-course { display: none !important; }</style>
            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    const buyNowButton = document.querySelector('.lp-button.button.button-purchase-course');
                    if (buyNowButton) {
                        buyNowButton.style.display = 'none';
                    }
                });
            </script>
            <?php
        }
    }
} 


