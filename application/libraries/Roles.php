<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Roles Library
 * Handles role-based access control and permissions
 */
class Roles {

    private $CI;
    private $role_matrix;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->role_matrix = $this->_define_role_matrix();
    }

    /**
     * Define the role-based access control matrix
     * Maps roles to accessible pages/controllers
     */
    private function _define_role_matrix()
    {
        return array(
            'admin' => array(
                'full_access' => true,
                'pages' => array(
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
            ),
            'manager' => array(
                'full_access' => false,
                'pages' => array(
                    'dashboard',
                    'products',
                    'meals',
                    'orders',
                    'reports',
                    'kitchen',
                    'waiter',
                ),
                'actions' => array(
                    'view' => true,
                    'create' => false,
                    'edit' => false,
                    'delete' => false,
                ),
            ),
            'cashier' => array(
                'full_access' => false,
                'pages' => array('pos'),
                'actions' => array(
                    'create_order' => true,
                    'view_order' => true,
                    'complete_order' => true,
                    'cancel_order' => false,
                ),
            ),
            'chef' => array(
                'full_access' => false,
                'pages' => array('kitchen'),
                'actions' => array(
                    'view_orders' => true,
                    'update_order_status' => true,
                    'view_order_details' => true,
                ),
            ),
            'waitress' => array(
                'full_access' => false,
                'pages' => array('waiter'),
                'actions' => array(
                    'manage_tables' => true,
                    'view_orders' => true,
                    'take_order' => true,
                ),
            ),
            'staff' => array(
                'full_access' => false,
                'pages' => array('dashboard'),
                'actions' => array(
                    'view_dashboard' => true,
                ),
            ),
        );
    }

    /**
     * Check if current user can access a page
     */
    public function can_access_page($page)
    {
        $role = $this->_get_user_role();

        if (!isset($this->role_matrix[$role])) {
            return false;
        }

        $matrix = $this->role_matrix[$role];

        if ($matrix['full_access']) {
            return true;
        }

        return in_array($page, $matrix['pages']);
    }

    /**
     * Check if current user can perform an action
     */
    public function can_perform_action($action)
    {
        $role = $this->_get_user_role();

        if (!isset($this->role_matrix[$role])) {
            return false;
        }

        $matrix = $this->role_matrix[$role];

        if ($matrix['full_access']) {
            return true;
        }

        if (!isset($matrix['actions'])) {
            return false;
        }

        return isset($matrix['actions'][$action]) && $matrix['actions'][$action];
    }

    /**
     * Check if user has specific role
     */
    public function has_role($role)
    {
        return $this->_get_user_role() === $role;
    }

    /**
     * Check if user has any of the specified roles
     */
    public function has_any_role($roles)
    {
        $user_role = $this->_get_user_role();
        return in_array($user_role, (array)$roles);
    }

    /**
     * Require specific page access (redirects if denied)
     */
    public function require_page_access($page)
    {
        if (!$this->can_access_page($page)) {
            $this->CI->session->set_flashdata('error', 'You do not have permission to access this page');
            redirect('dashboard');
        }
    }

    /**
     * Require specific action (redirects if denied)
     */
    public function require_action($action)
    {
        if (!$this->can_perform_action($action)) {
            $this->CI->session->set_flashdata('error', 'You do not have permission to perform this action');
            redirect('dashboard');
        }
    }

    /**
     * Get all pages accessible by current user
     */
    public function get_accessible_pages()
    {
        $role = $this->_get_user_role();

        if (!isset($this->role_matrix[$role])) {
            return array();
        }

        $matrix = $this->role_matrix[$role];

        if ($matrix['full_access']) {
            return $this->_flatten_all_pages();
        }

        return $matrix['pages'];
    }

    /**
     * Get all pages accessible by a specific role
     */
    public function get_role_pages($role)
    {
        if (!isset($this->role_matrix[$role])) {
            return array();
        }

        $matrix = $this->role_matrix[$role];

        if ($matrix['full_access']) {
            return $this->_flatten_all_pages();
        }

        return $matrix['pages'];
    }

    /**
     * Get all available roles
     */
    public function get_all_roles()
    {
        return array_keys($this->role_matrix);
    }

    /**
     * Get role details
     */
    public function get_role_details($role)
    {
        return isset($this->role_matrix[$role]) ? $this->role_matrix[$role] : null;
    }

    /**
     * Private: Get current user role from session
     */
    private function _get_user_role()
    {
        $role = $this->CI->session->userdata('role');
        return $role ? $role : 'guest';
    }

    /**
     * Private: Flatten all pages from matrix
     */
    private function _flatten_all_pages()
    {
        $all_pages = array();
        foreach ($this->role_matrix as $matrix) {
            if (isset($matrix['pages'])) {
                $all_pages = array_merge($all_pages, $matrix['pages']);
            }
        }
        return array_unique($all_pages);
    }
}
