<?php
/**
 * Plugin Name: Redirect on Registration
 * Description: Redirect new users to a selected course archive page after registration.
 * Version: 1.0
 * Author: Your Name
 */

add_action('user_register', 'redirect_on_registration', 10, 1);
function redirect_on_registration($user_id) {
    $enrolled_course_id = isset($_POST['enrol_course_id']) ? intval($_POST['enrol_course_id']) : 0;
    if ($enrolled_course_id > 0) {
        $course_permalink = get_permalink($enrolled_course_id);
        wp_redirect($course_permalink);
        exit;
    }
    wp_redirect(home_url());
    exit;
}
