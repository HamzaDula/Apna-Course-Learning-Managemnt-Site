<?php
/*
Plugin Name: Custom LearnPress
Version: 1.1
*/

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

// Meta box callback function
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
    if (is_singular('lp_course')) {
        $availability_date = get_post_meta($post->ID, '_lp_course_availability_date', true);
        if ($availability_date && strtotime($availability_date) > time()) {
            $availability_date_formatted = date_i18n(get_option('date_format'), strtotime($availability_date));
            echo '<div class="lp-course-availability-message" style="color: red; font-weight: bold;">';
            echo sprintf(__('This course will be available for purchase on %s.', 'textdomain'), $availability_date_formatted);
            echo '</div>';
            echo '<style>.lp-button.button.button-purchase-course { display: none !important; }</style>';
        } else {
            echo '<style>.lp-button.button.button-purchase-course { display: inline-block !important; }</style>';
        }
    }
}
add_action('wp_head', 'hide_lp_buy_now_button_and_message');

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

        // Uncomment the line below to send the email
        // wp_mail($to, $subject, $message);
    }
}
add_action('send_lp_course_availability_email', 'send_lp_course_availability_email');

// Activation Hook
function activate_custom_learnpress_plugin() {
    // Activation tasks if any
}
register_activation_hook(__FILE__, 'activate_custom_learnpress_plugin');

// Deactivation Hook
function deactivate_custom_learnpress_plugin() {
    // Clear scheduled events
    $courses = get_posts(array('post_type' => 'lp_course', 'numberposts' => -1));
    foreach ($courses as $course) {
        $timestamp = wp_next_scheduled('send_lp_course_availability_email', array($course->ID));
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'send_lp_course_availability_email', array($course->ID));
        }
    }
}
register_deactivation_hook(__FILE__, 'deactivate_custom_learnpress_plugin');
?>
