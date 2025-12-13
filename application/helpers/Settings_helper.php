<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Settings Helper
 *
 * Provides convenient functions to access application settings
 * throughout the application
 */

// Global settings cache
$_settings_cache = null;

/**
 * Get all settings from database and cache them
 */
function get_all_settings()
{
    global $_settings_cache;

    if ($_settings_cache === null) {
        $CI = &get_instance();
        $CI->load->model('Settings_model');

        $settings = $CI->Settings_model->get_as_array();
        $_settings_cache = $settings;
    }

    return $_settings_cache;
}

/**
 * Get a specific setting value
 *
 * @param string $key Setting key
 * @param mixed $default Default value if setting not found
 * @return mixed Setting value or default
 */
function setting($key, $default = null)
{
    $settings = get_all_settings();
    return isset($settings[$key]) ? $settings[$key] : $default;
}

/**
 * Get currency symbol or code
 */
function get_currency()
{
    return setting('currency', 'PKR');
}

/**
 * Get timezone
 */
function get_timezone()
{
    return setting('timezone', 'Asia/Karachi');
}

/**
 * Get tax rate as percentage
 */
function get_tax_rate()
{
    return floatval(setting('tax_rate', '0'));
}

/**
 * Get service charge as percentage
 */
function get_service_charge()
{
    return floatval(setting('service_charge', '0'));
}

/**
 * Get date format
 */
function get_date_format()
{
    return setting('date_format', 'Y-m-d');
}

/**
 * Get time format
 */
function get_time_format()
{
    return setting('time_format', 'H:i:s');
}

/**
 * Get date and time combined format
 */
function get_datetime_format()
{
    return get_date_format() . ' ' . get_time_format();
}

/**
 * Format a timestamp using app settings
 *
 * @param string|int $timestamp Unix timestamp or date string
 * @param string $format Optional format override
 * @return string Formatted date/time
 */
function format_date($timestamp, $format = null)
{
    if ($format === null) {
        $format = get_date_format();
    }

    if (is_string($timestamp)) {
        $timestamp = strtotime($timestamp);
    }

    return date($format, $timestamp);
}

/**
 * Format a timestamp using app date+time settings
 *
 * @param string|int $timestamp Unix timestamp or date string
 * @return string Formatted date and time
 */
function format_datetime($timestamp)
{
    return format_date($timestamp, get_datetime_format());
}

/**
 * Format time using app settings
 *
 * @param string|int $timestamp Unix timestamp or time string
 * @return string Formatted time
 */
function format_time($timestamp)
{
    if (is_string($timestamp)) {
        $timestamp = strtotime($timestamp);
    }

    return date(get_time_format(), $timestamp);
}

/**
 * Format a price with currency
 *
 * @param float $amount Amount to format
 * @param bool $show_code Show currency code instead of symbol
 * @return string Formatted price
 */
function format_price($amount, $show_code = true)
{
    $currency = get_currency();
    $formatted = number_format($amount, 2, '.', ',');

    if ($show_code) {
        return $formatted . ' ' . $currency;
    }

    // You could add currency symbols here if needed
    $symbols = [
        'PKR' => '₨',
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'INR' => '₹',
    ];

    $symbol = isset($symbols[$currency]) ? $symbols[$currency] : $currency;
    return $symbol . ' ' . $formatted;
}

/**
 * Calculate amount with tax
 *
 * @param float $amount Base amount
 * @return float Amount including tax
 */
function add_tax($amount)
{
    $tax_rate = get_tax_rate();
    return $amount + ($amount * $tax_rate / 100);
}

/**
 * Calculate amount with service charge
 *
 * @param float $amount Base amount
 * @return float Amount including service charge
 */
function add_service_charge($amount)
{
    $service_charge = get_service_charge();
    return $amount + ($amount * $service_charge / 100);
}

/**
 * Calculate tax amount
 *
 * @param float $amount Base amount
 * @return float Tax amount
 */
function calculate_tax($amount)
{
    $tax_rate = get_tax_rate();
    return $amount * $tax_rate / 100;
}

/**
 * Calculate service charge amount
 *
 * @param float $amount Base amount
 * @return float Service charge amount
 */
function calculate_service_charge($amount)
{
    $service_charge = get_service_charge();
    return $amount * $service_charge / 100;
}

/**
 * Get restaurant name
 */
function get_restaurant_name()
{
    return setting('restaurant_name', 'Restaurant POS System');
}

/**
 * Get application name
 */
function get_app_name()
{
    return setting('app_name', 'Restaurant POS System');
}

/**
 * Get application language
 */
function get_app_language()
{
    return setting('language', 'en');
}
