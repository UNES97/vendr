<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Helper
 * Provides authentication and authorization checking functions
 */

/**
 * Check if user is logged in
 * Redirects to login if not authenticated
 */
function require_login()
{
    $CI = &get_instance();
    if (!$CI->session->userdata('user_id')) {
        $CI->session->set_flashdata('error', 'You must be logged in to access this page');
        redirect('auth/login');
    }
}

/**
 * Check if user has specific role
 */
function has_role($role)
{
    $CI = &get_instance();
    $user_role = $CI->session->userdata('role');
    return $user_role === $role;
}

/**
 * Check if user has any of the specified roles
 */
function has_any_role($roles)
{
    $CI = &get_instance();
    $user_role = $CI->session->userdata('role');
    return in_array($user_role, (array)$roles);
}

/**
 * Check if user is admin
 */
function is_admin()
{
    return has_role('admin');
}

/**
 * Check if user is manager
 */
function is_manager()
{
    return has_role('manager');
}

/**
 * Check if user is cashier
 */
function is_cashier()
{
    return has_role('cashier');
}

/**
 * Check if user is chef
 */
function is_chef()
{
    return has_role('chef');
}

/**
 * Check if user is waitress
 */
function is_waitress()
{
    return has_role('waitress');
}

/**
 * Check if user is staff
 */
function is_staff()
{
    return has_role('staff');
}

/**
 * Get current logged in user ID
 */
function user_id()
{
    $CI = &get_instance();
    return $CI->session->userdata('user_id');
}

/**
 * Get current logged in user name
 */
function user_name()
{
    $CI = &get_instance();
    return $CI->session->userdata('name');
}

/**
 * Get current logged in user email
 */
function user_email()
{
    $CI = &get_instance();
    return $CI->session->userdata('email');
}

/**
 * Get current logged in user role
 */
function user_role()
{
    $CI = &get_instance();
    return $CI->session->userdata('role');
}

/**
 * Check if user can access a page/controller
 * Returns true if user has access, false otherwise
 */
function can_access($page)
{
    $role = user_role();
    $access_matrix = get_access_matrix();

    if (!isset($access_matrix[$role])) {
        return false;
    }

    return in_array($page, $access_matrix[$role]);
}

/**
 * Require specific role or redirect
 */
function require_role($required_roles)
{
    require_login();

    if (!has_any_role($required_roles)) {
        $CI = &get_instance();
        $CI->session->set_flashdata('error', 'You do not have permission to access this page');
        redirect('dashboard');
    }
}

/**
 * Get role-based access control matrix
 */
function get_access_matrix()
{
    return array(
        'admin' => array(
            'dashboard',
            'products',
            'meals',
            'categories',
            'inventory',
            'orders',
            'expense',
            'reports',
            'staff',
            'kitchen',
            'waiter',
            'settings',
            'pos',
        ),
        'manager' => array(
            'dashboard',
            'products',
            'meals',
            'orders',
            'reports',
            'kitchen',
            'waiter',
        ),
        'cashier' => array(
            'pos',
        ),
        'chef' => array(
            'kitchen',
        ),
        'waitress' => array(
            'waiter',
        ),
        'staff' => array(
            'dashboard',
        ),
    );
}

/**
 * Get list of all available pages for a role
 */
function get_role_pages($role)
{
    $matrix = get_access_matrix();
    return isset($matrix[$role]) ? $matrix[$role] : array();
}

/**
 * Check if user is logged in (returns boolean, doesn't redirect)
 */
function is_logged_in()
{
    $CI = &get_instance();
    return (bool)$CI->session->userdata('user_id');
}

/**
 * Get initials from user name for avatar
 */
function get_user_initials()
{
    $name = user_name();
    $parts = explode(' ', $name);
    $initials = '';

    foreach ($parts as $part) {
        if (!empty($part)) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
    }

    return substr($initials, 0, 2);
}
