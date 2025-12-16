<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pos extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('roles');
        $this->load->model('Meal_model');
        $this->load->model('Order_model');
        $this->load->model('Category_model');
        $this->load->model('Table_model');
        $this->load->model('Table_usage_session_model');
        $this->check_access();
    }

    /**
     * Main POS page
     */
    public function index()
    {
        $data['page_title'] = 'Point of Sale';

        // Get all active meals
        $filters = ['status' => 'active'];
        $data['meals'] = $this->Meal_model->get_filtered($filters, 9999, 0, 'name', 'ASC');

        // Get meal categories
        $data['categories'] = $this->Category_model->get_all('meal');

        // Get tables for dine-in orders
        $data['tables'] = $this->db->where('status', 'available')
                                   ->or_where('status', 'occupied')
                                   ->get('tables')
                                   ->result_array();

        $this->load->view('layouts/pos_minimal', [
            'content' => $this->load->view('pos/index', $data, true),
            'page_title' => $data['page_title'],
        ]);
    }

    /**
     * Create new order
     */
    public function create_order()
    {
        $this->load->library('form_validation');

        $order_data = [
            'order_number' => $this->generate_order_number(),
            'customer_name' => $this->input->post('customer_name'),
            'customer_phone' => $this->input->post('customer_phone'),
            'table_id' => (int)$this->input->post('table_id') ?: null,
            'order_type' => $this->input->post('order_type') ?: 'dine-in',
            'subtotal' => 0,
            'tax_amount' => 0,
            'total_amount' => 0,
            'payment_method' => $this->input->post('payment_method') ?: 'cash',
            'order_status' => 'pending',
            'payment_status' => 'pending',
            'created_by' => $this->session->userdata('user_id'),
        ];

        if ($this->Order_model->create($order_data)) {
            $order_id = $this->db->insert_id();

            // Add order items
            $items = $this->input->post('items');
            if (!empty($items)) {
                foreach ($items as $item) {
                    $item_data = [
                        'order_id' => $order_id,
                        'meal_id' => $item['meal_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['quantity'] * $item['price'],
                    ];
                    $this->db->insert('order_items', $item_data);
                }
            }

            // Update order totals
            $this->update_order_totals($order_id);

            // Start table usage tracking for dine-in orders
            if ($order_data['order_type'] == 'dine-in' && !empty($order_data['table_id'])) {
                // Calculate idle time before this session
                $idle_minutes = $this->Table_usage_session_model->calculate_idle_time($order_data['table_id']);

                // Start new session
                $this->Table_usage_session_model->start_session($order_data['table_id'], $order_id, $idle_minutes);

                // Auto-update table status to occupied
                $this->Table_model->update($order_data['table_id'], ['status' => 'occupied']);
            }

            $this->session->set_flashdata('success', 'Order created successfully. Order #' . $order_data['order_number']);
        } else {
            $this->session->set_flashdata('error', 'Failed to create order');
        }

        echo json_encode(['status' => 'success', 'order_id' => $order_id ?? 0]);
    }

    /**
     * Get meals by category via AJAX
     */
    public function get_meals_by_category($category_id = 0)
    {
        $filters = [
            'status' => 'active',
            'category_id' => $category_id > 0 ? $category_id : null
        ];

        $meals = $this->Meal_model->get_filtered($filters, 9999, 0, 'name', 'ASC');

        header('Content-Type: application/json');
        echo json_encode($meals);
    }

    /**
     * Get meal details via AJAX
     */
    public function get_meal_details($meal_id)
    {
        $meal = $this->Meal_model->get_by_id($meal_id);

        if (!$meal) {
            http_response_code(404);
            echo json_encode(['error' => 'Meal not found']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($meal);
    }

    /**
     * Update order totals
     */
    private function update_order_totals($order_id)
    {
        $items = $this->db->where('order_id', $order_id)
                         ->get('order_items')
                         ->result_array();

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['total_price'];
        }

        $tax_amount = $subtotal * 0.17; // 17% tax (Pakistan VAT)
        $total_amount = $subtotal + $tax_amount;

        $this->Order_model->update($order_id, [
            'subtotal' => $subtotal,
            'tax_amount' => $tax_amount,
            'total_amount' => $total_amount,
        ]);
    }

    /**
     * Generate unique order number
     */
    private function generate_order_number()
    {
        $prefix = 'ORD-' . date('Ymd');
        $latest = $this->db->select('order_number')
                          ->from('orders')
                          ->like('order_number', $prefix)
                          ->order_by('id', 'DESC')
                          ->limit(1)
                          ->get()
                          ->row_array();

        if ($latest) {
            $number = (int)substr($latest['order_number'], -5) + 1;
        } else {
            $number = 1;
        }

        return $prefix . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Check user access
     */
    private function check_access()
    {
        require_login();

        // Only cashiers and admins can access POS
        if (!has_any_role(array('cashier', 'admin'))) {
            $this->session->set_flashdata('error', 'Only cashiers can access the POS system');
            redirect('dashboard');
        }
    }
}
