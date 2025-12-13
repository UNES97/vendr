<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('roles');
        $this->load->model('User_model');

        // Require login for all staff methods
        require_login();
    }

    /**
     * Check if user is admin
     */
    private function check_admin_access()
    {
        // Only admins can access staff management
        if (!is_admin()) {
            $this->session->set_flashdata('error', 'You do not have permission to access this page');
            redirect('dashboard');
        }
    }

    /**
     * List all staff members
     */
    public function index()
    {
        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }

        $data['page_title'] = 'Staff Management';
        $data['user_name'] = $this->session->userdata('name');
        $data['user_email'] = $this->session->userdata('email');
        $data['user_initial'] = substr($this->session->userdata('name'), 0, 1);

        // Get all staff members
        $data['staff'] = $this->User_model->get_all();

        $this->load->view('layouts/base', [
            'content' => $this->load->view('staff/index', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'user_initial' => $data['user_initial'],
        ]);
    }

    /**
     * Show form to add new user (Admin only)
     */
    public function add()
    {
        $this->check_admin_access();

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }

        $data['page_title'] = 'Add New User';
        $data['user_name'] = $this->session->userdata('name');
        $data['user_email'] = $this->session->userdata('email');
        $data['user_initial'] = substr($this->session->userdata('name'), 0, 1);

        $this->load->view('layouts/base', [
            'content' => $this->load->view('staff/add', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'user_initial' => $data['user_initial'],
        ]);
    }

    /**
     * Create new user (Admin only)
     */
    public function create()
    {
        $this->check_admin_access();

        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $role = $this->input->post('role');
        $phone = $this->input->post('phone');

        // Validation
        if (!$name || !$email || !$password || !$role) {
            $this->session->set_flashdata('error', 'All required fields must be filled');
            redirect('staff/add');
            return;
        }

        // Check if email already exists
        if ($this->User_model->get_by_email($email)) {
            $this->session->set_flashdata('error', 'Email already exists');
            redirect('staff/add');
            return;
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashed_password,
            'role' => $role,
            'phone' => $phone,
            'status' => 'active'
        ];

        if ($this->User_model->create($data)) {
            $this->session->set_flashdata('success', 'User created successfully!');
            redirect('staff');
        } else {
            $this->session->set_flashdata('error', 'Error creating user');
            redirect('staff/add');
        }
    }

    /**
     * Show form to edit user (Admin only)
     */
    public function edit($id)
    {
        $this->check_admin_access();

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }

        $data['page_title'] = 'Edit User';
        $data['user_name'] = $this->session->userdata('name');
        $data['user_email'] = $this->session->userdata('email');
        $data['user_initial'] = substr($this->session->userdata('name'), 0, 1);

        $data['staff'] = $this->User_model->get_by_id($id);

        if (!$data['staff']) {
            $this->session->set_flashdata('error', 'User not found');
            redirect('staff');
            return;
        }

        $this->load->view('layouts/base', [
            'content' => $this->load->view('staff/edit', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'user_initial' => $data['user_initial'],
        ]);
    }

    /**
     * Update user (Admin only)
     */
    public function update($id)
    {
        $this->check_admin_access();

        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $role = $this->input->post('role');
        $phone = $this->input->post('phone');
        $status = $this->input->post('status');

        // Validation
        if (!$name || !$email || !$role) {
            $this->session->set_flashdata('error', 'All required fields must be filled');
            redirect('staff/edit/' . $id);
            return;
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'phone' => $phone,
            'status' => $status
        ];

        if ($this->User_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'User updated successfully!');
            redirect('staff');
        } else {
            $this->session->set_flashdata('error', 'Error updating user');
            redirect('staff/edit/' . $id);
        }
    }

    /**
     * Toggle user status (Admin only)
     * Prevent admin/staff from disabling themselves
     */
    public function toggle_status($id)
    {
        $this->check_admin_access();

        $current_user_id = user_id();

        // Prevent user from disabling themselves
        if ($id == $current_user_id) {
            $this->session->set_flashdata('error', 'You cannot disable your own account');
            redirect('staff');
            return;
        }

        $staff = $this->User_model->get_by_id($id);

        if (!$staff) {
            $this->session->set_flashdata('error', 'User not found');
            redirect('staff');
            return;
        }

        // Toggle between active and inactive
        $new_status = ($staff['status'] === 'active') ? 'inactive' : 'active';

        if ($this->User_model->update($id, ['status' => $new_status])) {
            $action = ($new_status === 'active') ? 'activated' : 'deactivated';
            $this->session->set_flashdata('success', 'User ' . $action . ' successfully!');
        } else {
            $this->session->set_flashdata('error', 'Error updating user status');
        }

        redirect('staff');
    }

    /**
     * Change user password (Admin only)
     */
    public function change_password($id)
    {
        $this->check_admin_access();

        $staff = $this->User_model->get_by_id($id);

        if (!$staff) {
            $this->session->set_flashdata('error', 'User not found');
            redirect('staff');
            return;
        }

        if (!$this->input->post()) {
            redirect('staff/edit/' . $id);
            return;
        }

        $new_password = $this->input->post('new_password');
        $confirm_password = $this->input->post('confirm_password');

        // Validation
        if (empty($new_password) || empty($confirm_password)) {
            $this->session->set_flashdata('error', 'Password fields are required');
            redirect('staff/edit/' . $id);
            return;
        }

        if ($new_password !== $confirm_password) {
            $this->session->set_flashdata('error', 'Passwords do not match');
            redirect('staff/edit/' . $id);
            return;
        }

        if (strlen($new_password) < 6) {
            $this->session->set_flashdata('error', 'Password must be at least 6 characters');
            redirect('staff/edit/' . $id);
            return;
        }

        // Hash and update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        if ($this->User_model->update($id, ['password' => $hashed_password])) {
            $this->session->set_flashdata('success', 'Password changed successfully!');
            redirect('staff');
        } else {
            $this->session->set_flashdata('error', 'Error changing password');
            redirect('staff/edit/' . $id);
        }
    }

    /**
     * Delete user (Admin only)
     * Prevent user from deleting themselves
     */
    public function delete($id)
    {
        $this->check_admin_access();

        $current_user_id = user_id();

        // Prevent user from deleting themselves
        if ($id == $current_user_id) {
            $this->session->set_flashdata('error', 'You cannot delete your own account');
            redirect('staff');
            return;
        }

        if ($this->User_model->delete($id)) {
            $this->session->set_flashdata('success', 'User deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Error deleting user');
        }

        redirect('staff');
    }
}
