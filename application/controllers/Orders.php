<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('pagination');
        $this->load->library('roles');
        $this->load->model('Order_model');
        $this->load->model('Meal_model');
        $this->check_access();
    }

    /**
     * List all orders
     */
    public function index()
    {
        $data['page_title'] = 'Orders';

        // Get filter parameters
        $search = $this->input->get('search', true);
        $status = $this->input->get('status', true);
        $date = $this->input->get('date', true);
        $page = (int)$this->input->get('page', true) ?: 1;

        // Store filters in data for view
        $data['filters'] = [
            'search' => $search,
            'status' => $status,
            'date' => $date,
        ];

        // Get all orders with filters
        $all_orders = $this->Order_model->get_all(9999, 0);

        // Apply filters
        $filtered_orders = [];
        foreach ($all_orders as $order) {
            $matches = true;

            // Search filter (by order number or customer name)
            if (!empty($search)) {
                $search_lower = strtolower($search);
                if (strpos(strtolower($order['order_number']), $search_lower) === false &&
                    strpos(strtolower($order['customer_name'] ?? ''), $search_lower) === false) {
                    $matches = false;
                }
            }

            // Status filter
            if (!empty($status) && $status != 'all' && $order['order_status'] != $status) {
                $matches = false;
            }

            // Date filter
            if (!empty($date)) {
                $order_date = date('Y-m-d', strtotime($order['created_at']));
                if ($order_date != $date) {
                    $matches = false;
                }
            }

            if ($matches) {
                $filtered_orders[] = $order;
            }
        }

        // Pagination
        $per_page = 15;
        $total_rows = count($filtered_orders);
        $offset = ($page - 1) * $per_page;

        $config['base_url'] = base_url('orders');
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

        // Get paginated orders
        $data['orders'] = array_slice($filtered_orders, $offset, $per_page);

        // Get order statistics
        $data['total_orders'] = count($all_orders);
        $data['total_revenue'] = array_sum(array_column($all_orders, 'total_amount'));
        $data['pending_orders'] = count(array_filter($all_orders, function($o) { return $o['order_status'] === 'pending'; }));
        $data['completed_orders'] = count(array_filter($all_orders, function($o) { return $o['order_status'] === 'completed'; }));

        $this->load->view('layouts/base', [
            'content' => $this->load->view('orders/index', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    /**
     * View order details
     */
    public function view($id)
    {
        $data['page_title'] = 'Order Details';
        $data['order'] = $this->Order_model->get_by_id($id);

        if (!$data['order']) {
            $this->session->set_flashdata('error', 'Order not found');
            redirect('orders');
        }

        // Get order items
        $data['items'] = $this->Order_model->get_items($id);

        // Get meal details for each item
        foreach ($data['items'] as &$item) {
            $meal = $this->Meal_model->get_by_id($item['meal_id']);
            $item['meal_name'] = $meal ? $meal['name'] : 'Unknown Meal';
        }

        $this->load->view('layouts/base', [
            'content' => $this->load->view('orders/view', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    /**
     * Update order status
     */
    public function update_status($id)
    {
        $status = $this->input->post('order_status');
        $payment_status = $this->input->post('payment_status');

        $update_data = [];
        if (!empty($status)) {
            $update_data['order_status'] = $status;
        }
        if (!empty($payment_status)) {
            $update_data['payment_status'] = $payment_status;
        }

        if (!empty($update_data) && $this->Order_model->update($id, $update_data)) {
            $this->session->set_flashdata('success', 'Order updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to update order');
        }

        redirect('orders/view/' . $id);
    }

    /**
     * Delete order
     */
    public function delete($id)
    {
        if ($this->Order_model->delete($id)) {
            $this->session->set_flashdata('success', 'Order deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete order');
        }

        redirect('orders');
    }

    /**
     * Check user access
     */
    private function check_access()
    {
        require_login();

        // Admins and managers can access orders
        if (!has_any_role(array('admin', 'manager'))) {
            $this->session->set_flashdata('error', 'You do not have permission to access orders');
            redirect('dashboard');
        }
    }
}
