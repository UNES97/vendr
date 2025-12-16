<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Set MySQL and PHP Timezone Hook
 *
 * This hook reads the timezone from the settings table and ensures that
 * both PHP's timezone and MySQL's timezone match the configured value.
 * This prevents timestamp inconsistencies.
 *
 * Called after the controller is constructed.
 */
function set_mysql_timezone()
{
    // Get CodeIgniter instance
    $CI =& get_instance();

    // Make sure database is loaded
    if (!isset($CI->db)) {
        $CI->load->database();
    }

    // Load settings helper
    $CI->load->helper('settings');

    // Get timezone from settings table (with fallback to Africa/Casablanca)
    $app_timezone = get_timezone();

    // Set PHP timezone to match app settings
    date_default_timezone_set($app_timezone);

    // Convert timezone to MySQL timezone format
    // Using offset is more reliable than timezone names
    $now = new DateTime('now', new DateTimeZone($app_timezone));
    $offset = $now->format('P'); // e.g., +01:00

    // Set MySQL session timezone
    $CI->db->query("SET time_zone = '{$offset}'");

    // Log for debugging (only in development)
    if (ENVIRONMENT === 'development') {
        log_message('debug', "Timezone set to {$app_timezone} (offset: {$offset}) from settings table");
    }
}
