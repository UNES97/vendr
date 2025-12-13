<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('roles');
        $this->load->model('User_model');

        // Require login for all profile methods
        require_login();
    }

    /**
     * View user profile
     */
    public function index()
    {
        $user_id = user_id();
        $data['page_title'] = 'My Profile';
        $data['user'] = $this->User_model->get_by_id($user_id);

        $data['user_name'] = user_name();
        $data['user_email'] = user_email();
        $data['user_initial'] = get_user_initials();

        $this->load->view('layouts/base', [
            'content' => $this->load->view('profile/index', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'user_initial' => $data['user_initial'],
        ]);
    }

    /**
     * Update user profile
     */
    public function update()
    {
        $user_id = user_id();

        if ($this->input->post()) {
            $name = $this->input->post('name');
            $phone = $this->input->post('phone');

            if (empty($name)) {
                $this->session->set_flashdata('error', 'Name is required');
                redirect('profile');
                return;
            }

            $update_data = [
                'name' => $name,
                'phone' => $phone
            ];

            if ($this->User_model->update($user_id, $update_data)) {
                // Update session name
                $this->session->set_userdata('name', $name);
                $this->session->set_flashdata('success', 'Profile updated successfully!');
                redirect('profile');
            } else {
                $this->session->set_flashdata('error', 'Failed to update profile');
                redirect('profile');
            }
        }

        redirect('profile');
    }

    /**
     * Change password
     */
    public function change_password()
    {
        $user_id = user_id();

        if (!$this->input->post()) {
            redirect('profile');
            return;
        }

        $current_password = $this->input->post('current_password');
        $new_password = $this->input->post('new_password');
        $confirm_password = $this->input->post('confirm_password');

        // Validate inputs
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $this->session->set_flashdata('error', 'All password fields are required');
            redirect('profile');
            return;
        }

        if ($new_password !== $confirm_password) {
            $this->session->set_flashdata('error', 'New passwords do not match');
            redirect('profile');
            return;
        }

        if (strlen($new_password) < 6) {
            $this->session->set_flashdata('error', 'Password must be at least 6 characters');
            redirect('profile');
            return;
        }

        // Get current user
        $user = $this->User_model->get_by_id($user_id);

        // Verify current password
        if (!password_verify($current_password, $user['password'])) {
            $this->session->set_flashdata('error', 'Current password is incorrect');
            redirect('profile');
            return;
        }

        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        if ($this->User_model->update($user_id, ['password' => $hashed_password])) {
            $this->session->set_flashdata('success', 'Password changed successfully!');
            redirect('profile');
        } else {
            $this->session->set_flashdata('error', 'Failed to change password');
            redirect('profile');
        }
    }
}
