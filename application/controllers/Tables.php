<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tables extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Table_model');
        $this->load->model('Table_usage_session_model');

        // Require login and admin access
        require_login();
        $this->check_admin_access();
    }

    /**
     * Check if user is admin
     */
    private function check_admin_access()
    {
        if (!is_admin()) {
            $this->session->set_flashdata('error', 'You do not have permission to access this page');
            redirect('dashboard');
        }
    }

    /**
     * List all tables
     */
    public function index()
    {
        $data['page_title'] = 'Restaurant Tables Management';
        $data['user_name'] = user_name();
        $data['user_email'] = user_email();
        $data['user_initial'] = get_user_initials();

        $tables = $this->Table_model->get_all();

        // Add usage info for each table
        foreach ($tables as &$table) {
            if ($table['status'] == 'occupied') {
                $session = $this->Table_usage_session_model->get_active_session($table['id']);
                $table['session_start'] = $session ? $session->session_start : null;
            } else {
                $table['session_start'] = null;
            }
            // Set last_available_at for idle calculation
            $table['last_available_at'] = isset($table['last_available_at']) ? $table['last_available_at'] : null;
        }

        $data['tables'] = $tables;
        $data['total_tables'] = count($data['tables']);
        $data['available_tables'] = count($this->Table_model->get_available());
        $data['occupied_tables'] = count($this->Table_model->get_occupied());

        $this->load->view('layouts/base', [
            'content' => $this->load->view('tables/index', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'user_initial' => $data['user_initial'],
        ]);
    }

    /**
     * Add new table form
     */
    public function add()
    {
        $data['page_title'] = 'Add New Table';
        $data['user_name'] = user_name();
        $data['user_email'] = user_email();
        $data['user_initial'] = get_user_initials();

        $this->load->view('layouts/base', [
            'content' => $this->load->view('tables/add', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'user_initial' => $data['user_initial'],
        ]);
    }

    /**
     * Create new table
     */
    public function create()
    {
        $table_number = $this->input->post('table_number');
        $capacity = $this->input->post('capacity');
        $location = $this->input->post('location');

        // Validation
        if (!$table_number || !$capacity) {
            $this->session->set_flashdata('error', 'Table number and capacity are required');
            redirect('tables/add');
            return;
        }

        // Check if table number already exists
        $existing = $this->db->where('table_number', $table_number)
            ->where('deleted_at', NULL)
            ->get('tables')
            ->row();

        if ($existing) {
            $this->session->set_flashdata('error', 'Table number already exists');
            redirect('tables/add');
            return;
        }

        $data = [
            'table_number' => $table_number,
            'capacity' => intval($capacity),
            'location' => $location,
            'status' => 'available'
        ];

        if ($this->Table_model->create($data)) {
            $this->session->set_flashdata('success', 'Table created successfully!');
            redirect('tables');
        } else {
            $this->session->set_flashdata('error', 'Error creating table');
            redirect('tables/add');
        }
    }

    /**
     * Edit table form
     */
    public function edit($id)
    {
        $data['page_title'] = 'Edit Table';
        $data['user_name'] = user_name();
        $data['user_email'] = user_email();
        $data['user_initial'] = get_user_initials();

        $data['table'] = $this->Table_model->get_by_id($id);

        if (!$data['table']) {
            $this->session->set_flashdata('error', 'Table not found');
            redirect('tables');
            return;
        }

        $this->load->view('layouts/base', [
            'content' => $this->load->view('tables/edit', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'user_initial' => $data['user_initial'],
        ]);
    }

    /**
     * Update table
     */
    public function update($id)
    {
        $table_number = $this->input->post('table_number');
        $capacity = $this->input->post('capacity');
        $location = $this->input->post('location');
        $status = $this->input->post('status');

        // Validation
        if (!$table_number || !$capacity) {
            $this->session->set_flashdata('error', 'Table number and capacity are required');
            redirect('tables/edit/' . $id);
            return;
        }

        // Check if table number already exists (for other tables)
        $existing = $this->db->where('table_number', $table_number)
            ->where('id !=', $id)
            ->where('deleted_at', NULL)
            ->get('tables')
            ->row();

        if ($existing) {
            $this->session->set_flashdata('error', 'Table number already exists');
            redirect('tables/edit/' . $id);
            return;
        }

        $data = [
            'table_number' => $table_number,
            'capacity' => intval($capacity),
            'location' => $location,
            'status' => $status
        ];

        if ($this->Table_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Table updated successfully!');
            redirect('tables');
        } else {
            $this->session->set_flashdata('error', 'Error updating table');
            redirect('tables/edit/' . $id);
        }
    }

    /**
     * Delete table
     */
    public function delete($id)
    {
        if ($this->Table_model->delete($id)) {
            $this->session->set_flashdata('success', 'Table deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Error deleting table');
        }

        redirect('tables');
    }
}
