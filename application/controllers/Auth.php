<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('User_model');
    }

    public function login()
    {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        $data['page_title'] = 'Login';
        $this->load->view('auth/login', $data);
    }

    public function do_login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $remember = $this->input->post('remember');

        if (empty($email) || empty($password)) {
            $this->session->set_flashdata('error', 'Email and password are required');
            redirect('auth/login');
            return;
        }

        // Authenticate user using User_model
        $user = $this->User_model->authenticate($email, $password);

        if (!$user) {
            $this->session->set_flashdata('error', 'Invalid email or password');
            redirect('auth/login');
            return;
        }

        // Check if user account is active
        if ($user['status'] !== 'active') {
            $this->session->set_flashdata('error', 'Your account is inactive. Contact administrator.');
            redirect('auth/login');
            return;
        }

        // Set session data
        $user_data = array(
            'user_id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'role' => $user['role'],
            'logged_in' => TRUE
        );

        $this->session->set_userdata($user_data);

        // Set remember me cookie (30 days)
        if ($remember) {
            $this->input->set_cookie(array(
                'name' => 'remember_me',
                'value' => base64_encode($user['email'] . ':' . $user['password']),
                'expire' => 2592000  // 30 days
            ));
        }

        $this->session->set_flashdata('success', 'Logged in successfully!');

        // Role-based redirect
        redirect($this->_get_redirect_by_role($user['role']));
    }

    /**
     * Get redirect URL based on user role
     */
    private function _get_redirect_by_role($role)
    {
        switch ($role) {
            case 'admin':
                return 'dashboard';
            case 'manager':
                return 'dashboard';
            case 'cashier':
                return 'pos';
            case 'chef':
                return 'kitchen';
            case 'waitress':
                return 'waiter';
            case 'staff':
                return 'dashboard';
            default:
                return 'dashboard';
        }
    }

    public function logout()
    {
        // Delete remember me cookie
        $this->input->set_cookie('remember_me', '', -3600);
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'Logged out successfully');
        redirect('auth/login');
    }
}
