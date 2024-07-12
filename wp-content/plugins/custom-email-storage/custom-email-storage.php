`<?php
/**
 * Plugin Name: Custom Email Storage
 * Description: Store email data in a custom table.
 * Version: 1.0
 * Author: Your Name
 */

// Activation hook to create the custom table
register_activation_hook( __FILE__, 'custom_plugin_create_table' );
function custom_plugin_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_email_data';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(100) NOT NULL,
        course_id bigint(20) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/// Modify your course template to add an email field
function custom_add_email_field_to_course_form() {
    global $post;
    $course_id = $post->ID;
    ?>
    <style>
        .lp-button.button.button-purchase-course {
            display: none !important;
        }

        .custom-buy-now-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        .custom-buy-now-button:hover {
            background-color: #0056b3;
        }

        .email-input {
            margin-top: 10px;
            display: inline-block;
        }

        .email-input input[type="email"] {
            width: 200px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
            vertical-align: top;
        }

        .email-input button[type="submit"] {
            padding: 7px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .email-input button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
    <div class="lp-button button button-purchase-course custom-buy-now-button">Buy Now</div>
    <div class="email-input">
        <form action="" method="post">
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
    <?php
}
add_action( 'learn-press/single-course-summary', 'custom_add_email_field_to_course_form' );


// Handle form submission to store email data in custom table
function custom_store_email_data() {
    global $wpdb;

    if ( isset( $_POST['submit'] ) && isset( $_POST['email'] ) && isset( $_POST['course_id'] ) ) {
        $table_name = $wpdb->prefix . 'custom_email_data';

        $email = sanitize_email( $_POST['email'] );
        $course_id = absint( $_POST['course_id'] );

        $data = array(
            'email' => $email,
            'course_id' => $course_id,
        );

        $wpdb->insert( $table_name, $data );
    }
}
add_action( 'init', 'custom_store_email_data' );
