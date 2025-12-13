<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model');
        $this->load->library('pagination');
        $this->load->library('roles');

        // Check authentication
        require_login();

        // Only admins and managers can access inventory
        if (!has_any_role(array('admin', 'manager'))) {
            $this->session->set_flashdata('error', 'You do not have permission to access inventory management');
            redirect('dashboard');
        }
    }

    // ===== CATEGORIES =====
    public function categories()
    {
        $page_title = 'Categories Management';

        $data['page_title'] = $page_title;
        $data['categories'] = $this->Category_model->get_all();

        $this->load->view('layouts/base', [
            'page_title' => $page_title,
            'content' => $this->load->view('inventory/categories/index', $data, true)
        ]);
    }

    public function add_category()
    {
        $page_title = 'Add Category';

        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'type' => $this->input->post('type'),
                'description' => $this->input->post('description'),
                'status' => 'active'
            ];

            if ($this->Category_model->create($data)) {
                $this->session->set_flashdata('success', 'Category created successfully!');
                redirect('inventory/categories');
            } else {
                $this->session->set_flashdata('error', 'Failed to create category');
            }
        }

        $data['page_title'] = $page_title;
        $this->load->view('layouts/base', [
            'page_title' => $page_title,
            'content' => $this->load->view('inventory/categories/add', $data, true)
        ]);
    }

    public function edit_category($id)
    {
        $page_title = 'Edit Category';

        $data['category'] = $this->Category_model->get_by_id($id);

        if (!$data['category']) {
            $this->session->set_flashdata('error', 'Category not found');
            redirect('inventory/categories');
        }

        if ($this->input->post()) {
            $update_data = [
                'name' => $this->input->post('name'),
                'type' => $this->input->post('type'),
                'description' => $this->input->post('description')
            ];

            if ($this->Category_model->update($id, $update_data)) {
                $this->session->set_flashdata('success', 'Category updated successfully!');
                redirect('inventory/categories');
            } else {
                $this->session->set_flashdata('error', 'Failed to update category');
            }
        }

        $data['page_title'] = $page_title;
        $this->load->view('layouts/base', [
            'page_title' => $page_title,
            'content' => $this->load->view('inventory/categories/edit', $data, true)
        ]);
    }

    public function delete_category($id)
    {
        $category = $this->Category_model->get_by_id($id);

        if (!$category) {
            $this->session->set_flashdata('error', 'Category not found');
            redirect('inventory/categories');
        }

        if ($this->Category_model->delete($id)) {
            $this->session->set_flashdata('success', 'Category deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete category');
        }

        redirect('inventory/categories');
    }
}
?>
