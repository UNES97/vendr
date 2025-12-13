<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meals extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('pagination');
        $this->load->library('roles');
        $this->load->model('Meal_model');
        $this->load->model('Category_model');

        // Require login for all meal methods
        require_login();
    }

    public function index()
    {
        $this->check_access();

        $data['page_title'] = 'Meals Menu';

        // Get filter parameters
        $search = $this->input->get('search', true);
        $category = $this->input->get('category', true);
        $status = $this->input->get('status', true);
        $sort_by = $this->input->get('sort_by', true) ?: 'created_at';
        $sort_order = $this->input->get('sort_order', true) ?: 'DESC';
        $page = (int)$this->input->get('page', true) ?: 1;

        // Store filters in data for view
        $data['filters'] = [
            'search' => $search,
            'category' => $category,
            'status' => $status,
            'sort_by' => $sort_by,
            'sort_order' => $sort_order,
        ];

        // Build query parameters
        $filters = [];
        if (!empty($search)) {
            $filters['search'] = $search;
        }
        if (!empty($category) && $category != 'all') {
            $filters['category_id'] = $category;
        }
        if (!empty($status) && $status != 'all') {
            $filters['status'] = $status;
        }

        // Pagination configuration
        $per_page = 15;
        $total_rows = $this->Meal_model->count_meals($filters);
        $offset = ($page - 1) * $per_page;

        $config['base_url'] = base_url('meals');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;

        // Pagination styling
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="flex items-center justify-center space-x-2 mt-6">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><span class="bg-red-600 text-white px-3 py-2 rounded-lg">';
        $config['cur_tag_close'] = '</span></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['num_links'] = 5;

        // Apply pagination
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // Get meals with filters and pagination
        $data['meals'] = $this->Meal_model->get_filtered(
            $filters,
            $per_page,
            $offset,
            $sort_by,
            $sort_order
        );

        // Get categories for filter dropdown (only meal categories)
        $data['categories'] = $this->Category_model->get_all('meal');

        $this->load->view('layouts/base', [
            'content' => $this->load->view('inventory/meals/index', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    public function add()
    {
        $this->check_access();

        $data['page_title'] = 'Add Meal';
        $data['categories'] = $this->Category_model->get_all('meal');

        $this->load->view('layouts/base', [
            'content' => $this->load->view('inventory/meals/add', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    public function create()
    {
        $this->check_access();

        $meal_data = [
            'category_id' => $this->input->post('category_id'),
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'sku' => $this->input->post('sku'),
            'cost_price' => $this->input->post('cost_price'),
            'selling_price' => $this->input->post('selling_price'),
            'status' => 'active'
        ];

        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $image_name = $this->upload_meal_image();
            if ($image_name) {
                $meal_data['image'] = $image_name;
            }
        }

        if ($this->Meal_model->create($meal_data)) {
            $this->session->set_flashdata('success', 'Meal created successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to create meal');
        }

        redirect('meals');
    }

    public function edit($id)
    {
        $this->check_access();

        $data['page_title'] = 'Edit Meal';
        $data['meal'] = $this->Meal_model->get_by_id($id);
        $data['categories'] = $this->Category_model->get_all('meal');

        if (!$data['meal']) {
            $this->session->set_flashdata('error', 'Meal not found');
            redirect('meals');
        }

        $this->load->view('layouts/base', [
            'content' => $this->load->view('inventory/meals/edit', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    public function update($id)
    {
        $this->check_access();

        $meal_data = [
            'category_id' => $this->input->post('category_id'),
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'sku' => $this->input->post('sku'),
            'cost_price' => $this->input->post('cost_price'),
            'selling_price' => $this->input->post('selling_price'),
            'status' => $this->input->post('status')
        ];

        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $image_name = $this->upload_meal_image();
            if ($image_name) {
                // Delete old image if exists
                $old_meal = $this->Meal_model->get_by_id($id);
                if ($old_meal && !empty($old_meal['image'])) {
                    $old_path = FCPATH . 'upload/meals/' . $old_meal['image'];
                    if (file_exists($old_path)) {
                        unlink($old_path);
                    }
                }
                $meal_data['image'] = $image_name;
            }
        }

        if ($this->Meal_model->update($id, $meal_data)) {
            $this->session->set_flashdata('success', 'Meal updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to update meal');
        }

        redirect('meals');
    }

    public function delete($id)
    {
        $this->check_access();

        // Delete image if exists
        $meal = $this->Meal_model->get_by_id($id);
        if ($meal && !empty($meal['image'])) {
            $image_path = FCPATH . 'upload/meals/' . $meal['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        // Delete recipe
        $this->Meal_model->delete_recipe($id);

        if ($this->Meal_model->delete($id)) {
            $this->session->set_flashdata('success', 'Meal deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete meal');
        }

        redirect('meals');
    }

    private function upload_meal_image()
    {
        $config['upload_path'] = FCPATH . 'upload/meals/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size'] = 5120; // 5MB
        $config['file_name'] = 'meal_' . uniqid() . '_' . time();

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('image')) {
            return $this->upload->data('file_name');
        } else {
            $this->session->set_flashdata('warning', 'Image upload failed: ' . $this->upload->display_errors('', ''));
            return false;
        }
    }

    private function check_access()
    {
        // Admins and managers can access meals management
        if (!has_any_role(array('admin', 'manager'))) {
            $this->session->set_flashdata('error', 'You do not have permission to manage meals');
            redirect('dashboard');
        }
    }
}

