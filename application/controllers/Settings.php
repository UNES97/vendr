<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('roles');
        $this->load->model('User_model');
        $this->load->model('Settings_model');

        // Require login for all settings methods
        require_login();
    }

    /**
     * Check if user is admin
     */
    private function check_admin_access()
    {
        // Only admins can access settings
        if (!is_admin()) {
            $this->session->set_flashdata('error', 'You do not have permission to access this page');
            redirect('dashboard');
        }
    }

    /**
     * Settings Dashboard (Hub)
     */
    public function index()
    {
        $this->check_admin_access();

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }

        $this->load->helper('settings');

        $data['page_title'] = 'Application Settings';
        $data['user_name'] = $this->session->userdata('name');
        $data['user_email'] = $this->session->userdata('email');
        $data['user_initial'] = substr($this->session->userdata('name'), 0, 1);

        // Get application settings from database
        $data['app_name'] = get_app_name();
        $data['app_version'] = '1.0.0';
        $data['currency'] = get_currency();
        $data['timezone'] = get_timezone();
        $data['language'] = get_app_language();
        $data['date_format'] = get_date_format();
        $data['time_format'] = get_time_format();

        $this->load->view('layouts/base', [
            'content' => $this->load->view('settings/index', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'user_initial' => $data['user_initial'],
        ]);
    }

    /**
     * General Settings Page
     */
    public function general()
    {
        $this->check_admin_access();

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }

        $data['page_title'] = 'General Settings';
        $data['user_name'] = $this->session->userdata('name');
        $data['user_email'] = $this->session->userdata('email');
        $data['user_initial'] = substr($this->session->userdata('name'), 0, 1);

        // Get application settings from database with defaults
        $data['app_name'] = $this->_get_setting('app_name', 'Restaurant POS System');
        $data['currency'] = $this->_get_setting('currency', 'PKR');
        $data['timezone'] = $this->_get_setting('timezone', 'Asia/Karachi');
        $data['language'] = $this->_get_setting('language', 'en');
        $data['date_format'] = $this->_get_setting('date_format', 'Y-m-d');
        $data['time_format'] = $this->_get_setting('time_format', 'H:i:s');

        $this->load->view('layouts/base', [
            'content' => $this->load->view('settings/general', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'user_initial' => $data['user_initial'],
        ]);
    }

    /**
     * Update General Settings
     */
    public function update_general()
    {
        $this->check_admin_access();

        $app_name = $this->input->post('app_name');
        $currency = $this->input->post('currency');
        $timezone = $this->input->post('timezone');
        $language = $this->input->post('language');
        $date_format = $this->input->post('date_format');
        $time_format = $this->input->post('time_format');

        // Save settings to database
        $this->Settings_model->save_multiple([
            'app_name' => $app_name,
            'currency' => $currency,
            'timezone' => $timezone,
            'language' => $language,
            'date_format' => $date_format,
            'time_format' => $time_format
        ]);

        $this->session->set_flashdata('success', 'General settings updated successfully!');
        redirect('settings/general');
    }

    /**
     * Business Settings Page
     */
    public function business()
    {
        $this->check_admin_access();

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }

        $data['page_title'] = 'Business Settings';
        $data['user_name'] = $this->session->userdata('name');
        $data['user_email'] = $this->session->userdata('email');
        $data['user_initial'] = substr($this->session->userdata('name'), 0, 1);

        // Business configuration from database
        $data['restaurant_name'] = $this->_get_setting('restaurant_name', 'My Restaurant');
        $data['phone'] = $this->_get_setting('phone', '+92-000-0000000');
        $data['email'] = $this->_get_setting('email', 'info@restaurant.local');
        $data['address'] = $this->_get_setting('address', '123 Restaurant Street, City');
        $data['tax_rate'] = $this->_get_setting('tax_rate', '17');
        $data['service_charge'] = $this->_get_setting('service_charge', '0');

        $this->load->view('layouts/base', [
            'content' => $this->load->view('settings/business', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'user_initial' => $data['user_initial'],
        ]);
    }

    /**
     * Update Business Settings
     */
    public function update_business()
    {
        $this->check_admin_access();

        $restaurant_name = $this->input->post('restaurant_name');
        $phone = $this->input->post('phone');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $tax_rate = $this->input->post('tax_rate');
        $service_charge = $this->input->post('service_charge');

        // Save settings to database
        $this->Settings_model->save_multiple([
            'restaurant_name' => $restaurant_name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'tax_rate' => $tax_rate,
            'service_charge' => $service_charge
        ]);

        $this->session->set_flashdata('success', 'Business settings updated successfully!');
        redirect('settings/business');
    }


    /**
     * Helper method to get a setting value or return default
     */
    private function _get_setting($key, $default = null)
    {
        $setting = $this->Settings_model->get($key);
        return $setting ? $setting->value : $default;
    }
}
