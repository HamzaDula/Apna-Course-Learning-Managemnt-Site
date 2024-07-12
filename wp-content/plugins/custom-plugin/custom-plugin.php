<?php
/**
 * Plugin Name: Custom CRUD Plugin
 * Author : Hamza
 * Version : 1.0
 * Description: This plugin is used to perform the CRUD operations
 */

 define('EMS_PLUGIN_PATH',plugin_dir_path(__FILE__));

 define("EMS_PLUGIN_URL",plugin_dir_url(__FILE__));


add_action('admin_menu','custom_admin_menu');

function custom_admin_menu(){
    add_menu_page('Emplyeee System | Employee Management System' ,'Emplyeee System','manage_options','employee-system','employee_system_callback','dashicons-media-document
    ','6');
    //sub-menu
    add_submenu_page('employee-system','Add Employee','Add Employee','manage_options','employee-system','employee_system_callback');

    add_submenu_page('employee-system','Employee Lists','Employee lists','manage_options','employee-lists','employee_lists_cb');
}

function employee_system_callback(){
    include_once(EMS_PLUGIN_PATH."page/add-employee.php");
}

//sub-menu callback
function employee_lists_cb(){
    include_once(EMS_PLUGIN_PATH."page/list-employee.php");
   
}
register_activation_hook(__FILE__, 'ems_create_table');
function ems_create_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'emp_detail';

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(180) DEFAULT NULL,
        email VARCHAR(100) DEFAULT NULL,
        phone VARCHAR(30) DEFAULT NULL,
        gender ENUM('male','female','other') DEFAULT NULL,
        desg VARCHAR(30) DEFAULT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_deactivation_hook(__FILE__,'emp_drop_table');

function emp_drop_table(){
    global $wpdb;

    $table_name = $wpdb->prefix . 'emp_detail';

    $sql = "DROP TABLE IF EXITS $table_name";

    $wpdb->query($sql);
}
